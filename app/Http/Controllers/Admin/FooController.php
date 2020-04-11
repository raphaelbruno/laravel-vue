<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FooController extends CrudController
{
    protected $model = \App\Foo::class;
    protected $resource = 'foos';
    protected $onlyMine = true;
    protected $names;
    protected $item;
    
    protected $rules = [
        'something' => 'required|min:3'
    ];
    
    function __construct()
    {
        $this->names = [
            'something' => trans('Something')
        ];

        $this->item = trans('Foo');

        parent::__construct();
    }

    public function search(Request $request)
    {
        $q = $request->get('q');
        return $this->model::where('something', 'ilike', "%{$q}%")
            ->where('user_id', Auth::user()->id)
            ->orderBy('something')
            ->paginate();
    }
    
    public function prepareFieldInsert($fields)
    {
        $fields['user_id'] = Auth::user()->id;
        return $fields;
    }
}