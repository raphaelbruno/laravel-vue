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
    protected $names;
    protected $label;
    protected $title;
    protected $icon;
    protected $resource = null;
    protected $variablesToView = [];

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        if(!$this->icon) $this->icon = 'file';
        if(!$this->label) $this->label = trans('crud.item');
        if(!$this->title) $this->title = trans('crud.items');
        $this->middleware('auth');
        if(!$this->resource) $this->resource = TemplateHelper::getCurrentResource();
    }

    /**
     * Add Variables to view
     *
     * @param array $variables
     */
    function addToView($variables)
    {
        $this->variablesToView = array_merge($this->variablesToView, $variables);
    }

    /**
     * Detach and Attach subitems from a relationship
     *
     * @param object $item
     * @param string $relationship
     * @param array $input
     */
    public static function subitems($item, $relationship, $input)
    {
        $subitems = isset($input) ? array_map('intval', $input) : [];
        $subitemsOnDatabase = $item->{$relationship}->map(function($user){ return $user->id; })->toArray();
        $subitemsToDelete = array_diff($subitemsOnDatabase, $subitems);
        $subitemsToInsert = array_unique(array_diff($subitems, $subitemsOnDatabase));

        foreach($subitemsToDelete as $id) $item->{$relationship}()->detach($id);
        foreach($subitemsToInsert as $id) $item->{$relationship}()->attach($id);
        return (bool) $item->save();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Gate::denies($this->resource.'-view'))
            return $this->backOrJson(request(), 'not_authorized', 'crud.not-authorized');

        $title = $this->title;
        $icon = $this->icon;
        $items = $this->incrementToSearch($this->filter($this->search($request), $request), $request)->paginate();

        return request()->wantsJson() 
            ? $items 
            : view('admin.'.$this->resource.'.list', array_merge($this->variablesToView, compact('items', 'request', 'title', 'icon')));
    }
    public function search(Request $request)
    {
        $queryBuilder = $this->model::query();

        if($this->onlyMine)
            $queryBuilder->where('user_id', Auth::user()->id);

        return $queryBuilder;
    }

    /**
     * Override this method if you want to increment the query build of search
     *
     * @param \Illuminate\Database\Eloquent\Builder $queryBuilder
     * @param \Illuminate\Http\Response $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filter($queryBuilder, Request $request){
        $q = $request->get('q');

        if(preg_match('/^#\d+$/', $q)){ // Search by ID (#1)
            $queryBuilder->where('id', str_replace('#', '', $q));
        }elseif(preg_match('/^\w+:.+/', $q)){ // Search by specific field (field:foo)
            list($field, $value) = explode(':', $q);
            $queryBuilder->where($field, 'ilike', "%{$value}%");
        }else{
            $queryBuilder->where(function($builder) use($q) {
                $fields = (new $this->model())->getFillable();

                foreach($fields as $field)
                    $builder->orWhere($field, 'ilike', "%{$q}%");
            });
        }

        return $queryBuilder;
    }

    /**
     * Override this method if you want to increment the query build of search
     *
     * @param \Illuminate\Database\Eloquent\Builder $queryBuilder
     * @param \Illuminate\Http\Response $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function incrementToSearch($queryBuilder, Request $request){
        return $queryBuilder;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies($this->resource.'-create'))
            return $this->backOrJson(request(), 'not_authorized', 'crud.not-authorized');

        $label = $this->label;
        $title = $this->title;
        $icon = $this->icon;
        return request()->wantsJson() 
            ? null
            : view('admin.'.$this->resource.'.form', array_merge($this->variablesToView, compact('label', 'title', 'icon')));
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
            return $this->backOrJson(request(), 'not_authorized', 'crud.not-authorized');

        $fields = $this->prepareValidationStore($request);
        $validator = Validator::make($fields, $this->rules, [], $this->names);
        if ($validator->fails())
            return $this->backOrJson(request(), 'validation', $validator);

        try {
            $fields = $this->prepareFieldStore($fields);

            DB::beginTransaction();
            $item = $this->model::create($fields);
            if(!$this->afterStore($item, $request)){
                DB::rollBack();
                throw new Exception();
            }

            DB::commit();
            return $this->routeOrJson(request(), 'admin:'.$this->resource.'.index', trans('crud.successfully-added', [trans($this->label)]));
        } catch (\Exception $e) {
            $this->errorStore($fields);
            return $this->backOrJson(request(), 'generic', trans('crud.error-occurred') . $e->getMessage());
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
    public function afterStore($item, $request)
    {
        return true;
    }
    public function errorStore($fields){}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Gate::denies($this->resource.'-view'))
            return $this->backOrJson(request(), 'not_authorized', 'crud.not-authorized');

        $title = $this->title;
        $icon = $this->icon;
        $item = $this->model::find($id);

        if(!isset($item))
            return $this->backOrJson(request(), 'item_not_found', 'crud.item-not-found');
    
        if($this->onlyMine && Gate::denies('mine', $item))
            return $this->backOrJson(request(), 'not_authorized', 'crud.not-authorized');

        return request()->wantsJson() 
            ? $item
            : view('admin.'.$this->resource.'.show', array_merge($this->variablesToView, compact('item', 'title', 'icon')));
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
            return $this->backOrJson(request(), 'not_authorized', 'crud.not-authorized');

        $label = $this->label;
        $title = $this->title;
        $icon = $this->icon;
        $item = $this->model::find($id);
        
        if(!isset($item))
            return $this->backOrJson(request(), 'item_not_found', 'crud.item-not-found');
    
        if($this->onlyMine && Gate::denies('mine', $item))
            return $this->backOrJson(request(), 'not_authorized', 'crud.not-authorized');

        return request()->wantsJson() 
            ? $item
            : view('admin.'.$this->resource.'.form', array_merge($this->variablesToView, compact('item', 'label', 'title', 'icon')));
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
            return $this->backOrJson(request(), 'not_authorized', 'crud.not-authorized');

        $item = $this->model::find($id);
        if(!isset($item))
            return $this->backOrJson(request(), 'item_not_found', 'crud.item-not-found');

        if($this->onlyMine && Gate::denies('mine', $item))
            return $this->backOrJson(request(), 'not_authorized', 'crud.not-authorized');

        $fields = $this->prepareValidationUpdate($request, $item);
        $validator = Validator::make($fields, $this->rules, [], $this->names);
        if ($validator->fails())
            return $this->backOrJson(request(), 'validation', $validator);

        try {
            $fields = $this->prepareFieldUpdate($fields, $item);

            DB::beginTransaction();
            $item->update($fields);
            if(!$this->afterUpdate($item, $request)){
                DB::rollBack();
                throw new Exception();
            }

            DB::commit();
            return $this->routeOrJson(request(), 'admin:'.$this->resource.'.index', trans('crud.successfully-updated', [trans($this->label)]));
        } catch (\Exception $e) {
            $this->errorUpdate($item, $fields);
            return $this->backOrJson(request(), 'generic', trans('crud.error-occurred') . $e->getMessage());
        }
    }
    public function prepareValidationUpdate(Request $request, $item)
    {
        return $request->item;
    }
    public function prepareFieldUpdate($fields, $item)
    {
        return $fields;
    }
    public function afterUpdate($item, $request)
    {
        return true;
    }
    public function errorUpdate($item, $fields){}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Gate::denies($this->resource.'-delete'))
            return $this->backOrJson(request(), 'not_authorized', 'crud.not-authorized');
    
        $item = $this->model::find($id);
        if(!isset($item))
            return $this->backOrJson(request(), 'item_not_found', 'crud.item-not-found');
    
        if($this->onlyMine && Gate::denies('mine', $item))
            return $this->backOrJson(request(), 'not_authorized', 'crud.not-authorized');
    
        try {
            DB::beginTransaction();
            $item->delete();
            if(!$this->afterDestroy($item)){
                DB::rollBack();
                throw new Exception();
            }

            DB::commit();
            return $this->routeOrJson(request(), 'admin:'.$this->resource.'.index', trans('crud.successfully-deleted', [trans($this->label)]));
        } catch (\Exception $e) {
            return $this->backOrJson(request(), 'generic', trans('crud.error-occurred') . $e->getMessage());
        }
    }

    public function afterDestroy($item)
    {
        return true;
    }

    protected function routeOrJson(Request $request, $route, $message){
        $isValidator = $message instanceof \Illuminate\Validation\Validator;
        return $request->wantsJson()
            ? response(['message' => ($isValidator ? $message->errors() : trans($message))])
            : Redirect::route($route)->with($isValidator ? $message : ['message' => trans($message)]);
    }
    
    protected function backOrJson(Request $request, $error, $message){
        $isValidator = $message instanceof \Illuminate\Validation\Validator;
        return $request->wantsJson()
            ? response(['error' => $error, 'message' => ($isValidator ? $message->errors() : trans($message))])
            : Redirect::back()->withErrors($isValidator ? $message : [trans($message)])->withInput();
    }
}