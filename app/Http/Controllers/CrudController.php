<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Helpers\TemplateHelper;

use Exception;

class CrudController extends Controller
{
    protected $onlyMine = false;
    protected $title = null;
    protected $resource = null;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        if(!$this->resource) $this->resource = TemplateHelper::getCurrentResource();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Gate::denies($this->resource.'-view'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);
        
        $title = $this->title;
        $items = $this->search($request);
        
        return view('admin.'.$this->resource.'.list', compact('items', 'request', 'title'));
    }
    public function search(Request $request)
    {
        $q = $request->get('q');
        $queryBuilder = $this->model::where('title', 'ilike', "%{$q}%")->orderBy('title');
        if($this->onlyMine) $queryBuilder->where('user_id', Auth::user()->id);
        return $queryBuilder->paginate();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies($this->resource.'-create'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $title = $this->title;
        return view('admin.'.$this->resource.'.form', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Gate::denies($this->resource.'-create'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $fields = $this->prepareValidationStore($request);
        $validator = Validator::make($fields, $this->rules, [], $this->names);
        if ($validator->fails())
            return Redirect::back()->withErrors($validator)->withInput();

        try {
            $fields = $this->prepareFieldStore($fields);

            DB::beginTransaction();
            $item = $this->model::create($fields);
            if(!$this->afterStore($item)){
                DB::rollBack();
                throw new Exception();
            }

            DB::commit();
            return Redirect::route('admin:'.$this->resource.'.index')
                ->with(['success' => trans('crud.successfully-added', [trans($this->item)])]);
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors([trans('crud.error-occurred') . $e->getMessage()])
                ->withInput();
        }
    }
    public function prepareValidationStore(Request $request)
    {
        return $request->item;
    }
    public function prepareFieldStore($fields)
    {
        if($this->onlyMine) $fields['user_id'] = Auth::user()->id;
        return $fields;
    }
    public function afterStore($item)
    {
        return true;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Gate::denies($this->resource.'-view'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $title = $this->title;
        $item = $this->model::find($id);
        if(!isset($item))
            return Redirect::back()->withErrors([trans('crud.item-not-found')]);
        
        if($this->onlyMine && Gate::denies('mine', $item))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);
        
        return view('admin.'.$this->resource.'.show', compact('item', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Gate::denies($this->resource.'-update'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $title = $this->title;
        $item = $this->model::find($id);
        if(!isset($item))
            return Redirect::back()->withErrors([trans('crud.item-not-found')]);

        if($this->onlyMine && Gate::denies('mine', $item))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);
        
        return view('admin.'.$this->resource.'.form', compact('item', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Gate::denies($this->resource.'-update'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $item = $this->model::find($id);
        if(!isset($item))
            return Redirect::back()->withErrors([trans('crud.item-not-found')]);
            
        if($this->onlyMine && Gate::denies('mine', $item))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $fields = $this->prepareValidationUpdate($request);
        $validator = Validator::make($fields, $this->rules, [], $this->names);
        if ($validator->fails())
            return Redirect::back()->withErrors($validator)->withInput();
        
        try {
            $fields = $this->prepareFieldUpdate($fields);

            DB::beginTransaction();
            $item->update($fields);
            if(!$this->afterUpdate($item)){
                DB::rollBack();
                throw new Exception();
            }

            DB::commit();
            return Redirect::route('admin:'.$this->resource.'.index')
                ->with(['success' => trans('crud.successfully-updated', [trans($this->item)])]);
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors([trans('crud.error-occurred') . $e->getMessage()])
                ->withInput();
        }
    }
    public function prepareValidationUpdate(Request $request)
    {
        return $request->item;
    }
    public function prepareFieldUpdate($fields)
    {
        return $fields;
    }
    public function afterUpdate($item)
    {
        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Gate::denies($this->resource.'-delete'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $item = $this->model::find($id);
        if(!isset($item))
            return Redirect::back()->withErrors([trans('crud.item-not-found')]);

        if($this->onlyMine && Gate::denies('mine', $item))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        try {
            DB::beginTransaction();
            $item->delete();
            if(!$this->afterDestroy($item)){
                DB::rollBack();
                throw new Exception();
            }

            DB::commit();
            return Redirect::route('admin:'.$this->resource.'.index')
                ->with(['success' => trans('crud.successfully-deleted', [trans($this->item)])]);
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors([trans('crud.error-occurred') . $e->getMessage()])
                ->withInput();
        }
    }
    public function afterDestroy($item)
    {
        return true;
    }
}
