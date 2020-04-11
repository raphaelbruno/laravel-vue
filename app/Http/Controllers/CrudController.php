<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class CrudController extends Controller
{
    protected $onlyMine = false;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
            
        $items = $this->search($request);
            
        return view('admin.'.$this->resource.'.list', compact('items', 'request'));
    }
    public function search(Request $request)
    {
        $q = $request->get('q');
        return $this->model::where('title', 'ilike', "%{$q}%")
            ->orderBy('title')
            ->paginate();
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

        return view('admin.'.$this->resource.'.form');
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

        $fields = $request->item;
        $validator = Validator::make($fields, $this->rules, [], $this->names);
        if ($validator->fails())
            return Redirect::back()->withErrors($validator)->withInput();

        try {
            $fields = $this->prepareFieldInsert($fields);
            $this->model::create($fields);

            return Redirect::route('admin:'.$this->resource.'.index')
                ->with(['success' => trans('crud.successfully-added', [trans($this->item)])]);
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors([trans('crud.error-occurred') . $e->getMessage()])
                ->withInput();
        }
    }
    public function prepareFieldInsert($fields)
    {
        return $fields;
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

        $item = $this->model::find($id);
        if(!isset($item))
            return Redirect::back()->withErrors([trans('crud.item-not-found')]);
        
        if($this->onlyMine && Gate::denies('mine', $item))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);
        
        return view('admin.'.$this->resource.'.show', compact('item'));
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

        $item = $this->model::find($id);
        if(!isset($item))
            return Redirect::back()->withErrors([trans('crud.item-not-found')]);

        if($this->onlyMine && Gate::denies('mine', $item))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);
        
        return view('admin.'.$this->resource.'.form', compact('item'));
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
            
        if($this->onlyMine && Gate::denies('mine', $item))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $fields = $request->item;
        $validator = Validator::make($fields, $this->rules, [], $this->names);
        if ($validator->fails())
            return Redirect::back()->withErrors($validator)->withInput();
        
        try {
            $fields = $this->prepareFieldUpdate($fields);
            $item->update($fields);

            return Redirect::route('admin:'.$this->resource.'.index')
                ->with(['success' => trans('crud.successfully-updated', [trans($this->item)])]);
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors([trans('crud.error-occurred') . $e->getMessage()])
                ->withInput();
        }
    }
    public function prepareFieldUpdate($fields)
    {
        return $fields;
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

        try {
            $item = $this->model::find($id);
            $item->delete();

            return Redirect::route('admin:'.$this->resource.'.index')
                ->with(['success' => trans('crud.successfully-deleted', [trans($this->item)])]);
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors([trans('crud.error-occurred') . $e->getMessage()])
                ->withInput();
        }
    }
}
