<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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
    protected $uploads = [/* [ name, directory ] */];
    protected $multipleUploads = [/* [ name, directory ] */];
    protected $filesToDelete = [];

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
        if(!$this->resource) $this->resource = getCurrentResource();
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
     * Override this method if you want to add some lists to view on create and edit routes
     * 
     * @return Array
     */
    public function options($item = null)
    {
        return [];
    }
    
    /**
     * Create, Edit and Delete subitems from a One to Many Relationship
     *
     * @param object $item
     * @param string $relationship
     * @param array $input
     */
    public static function subitemOneToMany($item, $relationship, $input, $allowVoid = true)
    {
        if(!$allowVoid && (!isset($input) || !count($input)) )
            throw new Exception(trans('crud.relationship-cannot-void', [trans($relationship)]));
        
        $ids = collect($input)->whereNotNull('id')->pluck('id')->toArray();
        $subitemsToDelete = $item->{$relationship}->filter(function($item) use($ids) { return !in_array($item->id, $ids); });

        foreach($input as $subitem)
            $item->{$relationship}()->updateOrCreate(['id' => $subitem['id'] ?? null], $subitem);

        foreach($subitemsToDelete as $subitem)
            $subitem->delete();

        return (bool) $item->save();
    }

    /**
     * Detach and Attach subitems from a Many to Many Relationship
     *
     * @param object $item
     * @param string $relationship
     * @param array $input
     */
    public static function subitemManyToMany($item, $relationship, $input, $allowVoid = true)
    {
        if(!$allowVoid && (!isset($input) || !count($input)) )
            throw new Exception(trans('crud.relationship-cannot-void', [trans($relationship)]));

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
        $items = $this->incrementToSearch($this->filter($this->search($request), $request), $request)->paginate(env('PAGINATION', 10));

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
        
        $this->addToView($this->options());

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
            if(!$this->afterStore($item, $request) || !$this->saveBinaryFiles($fields, $item)){
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
        return $this->prepareUploads($fields);
    }
    public function afterStore($item, $request)
    {
        return true;
    }
    public function errorStore($fields)
    {
        $this->deleteUploadedFiles($fields);
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

        $this->addToView($this->options($item));
        
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
            if(!$this->afterUpdate($item, $request) || !$this->saveBinaryFiles($fields, $item)){
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
        return $this->prepareUploads($fields, $item);
    }
    public function afterUpdate($item, $request)
    {
        foreach($this->filesToDelete as $fileToDelete)
            if(isset($fileToDelete)) Storage::delete($fileToDelete);
        return true;
    }
    public function errorUpdate($item, $fields)
    {
        $this->deleteUploadedFiles($fields);
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

    /**
     * Upload
     */
    protected function prepareUploads($fields, $item = null)
    {
        foreach($this->uploads as $upload){
            $name = $upload['name'];
            $directory = $upload['directory'];
            if(isset($fields[$name])){
                $fields[$name] = $fields[$name]->store($directory);
                if(isset($item) && isset($item->{$name}) && $item->{$name} != $fields[$name])
                    $this->filesToDelete[] = $item->{$name};
            }
        }
        return $fields;
    }

    protected function deleteUploadedFiles($fields)
    {
        foreach($this->uploads as $upload)
            if(isset($fields[$upload['name']])) Storage::delete($fields[$upload['name']]);
    }

    function saveBinaryFiles($fields, $item)
    {
        foreach($this->multipleUploads as $multipleUpload){
            $name = $multipleUpload['name'];
            $directory = $multipleUpload['directory'];
            $class = get_class($item->{$name}()->getRelated());
            
            $files = $fields[$name] ?? [];
            $filesOnDatabase = $item->{$name}->map(function($file){ return $file->id; })->toArray();
            $filesToKeep = array_map(function($file){
                    return json_decode($file)->id;
                }, array_filter($files, function($file){
                    return json_decode($file)->id;
                }));
            $filesToDelete = array_diff($filesOnDatabase, $filesToKeep);
            $filesToUpload = array_filter($files, function($file){
                return !json_decode($file)->id;
            });
            
            // Delete files
            if(!empty($filesToDelete) && !$class::destroy($filesToDelete)) return false;

            // Upload files
            foreach($filesToUpload as $file){
                $source = json_decode($file)->src;
                $extension = explode('/', mime_content_type($source))[1];
                $binaryString = str_replace(' ', '+', preg_replace('/.*:.*,(.*)/', '$1', $source));
                $filename = $directory . sha1($binaryString . uniqid()) . '.' . $extension;
                
                if(Storage::disk('public')->put($filename, base64_decode($binaryString))){
                    if(!$item->{$name}()->create(['src' => $filename])) return false;
                }
            }

        }

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