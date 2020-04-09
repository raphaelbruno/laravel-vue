<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Gate;

use App\Foo;

class FooController extends Controller
{
    protected $rules = [
        'something' => 'required|min:3'
    ];
    protected $names = [
        'something' => 'Something'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Gate::denies('foos-view'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);
            
        $query = $request->get('q');
        $items = Foo::where('something', 'ilike', "%{$query}%")
                    ->where('user_id', Auth::user()->id)
                    ->paginate();
        return view('admin.foo.list', compact('items', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies('foos-create'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        return view('admin.foo.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Gate::denies('foos-create'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $fields = $request->item;
        $validator = Validator::make($fields, $this->rules, [], $this->names);
        if ($validator->fails())
        {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $fields['user_id'] = Auth::user()->id;
            Foo::create($fields);

            return Redirect::route('admin::foos.index')
                ->with(['success' => trans('crud.successfully-added', ['Foo'])]);
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors([trans('crud.error-occurred') . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Gate::denies('foos-view'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $item = Foo::find($id);
        if(!isset($item))
            return Redirect::back()->withErrors([trans('crud.item-not-found')]);
            
        if(Gate::denies('mine', $item))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);
        
        return view('admin.foo.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Gate::denies('foos-update'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $item = Foo::find($id);
        if(!isset($item))
            return Redirect::back()->withErrors([trans('crud.item-not-found')]);

        if(Gate::denies('mine', $item))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);
        
        return view('admin.foo.form', compact('item'));
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
        if(Gate::denies('foos-update'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $item = Foo::find($id);
            
        if(Gate::denies('mine', $item))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        $fields = $request->item;
        $validator = Validator::make($fields, $this->rules, [], $this->names);
        if ($validator->fails())
        {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $item->something = $fields['something'];
            $item->save();

            return Redirect::route('admin::foos.index')
                ->with(['success' => trans('crud.successfully-updated', ['Foo'])]);
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors([trans('crud.error-occurred') . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Gate::denies('foos-delete'))
            return Redirect::back()->withErrors([trans('crud.not-authorized')]);

        try {
            $item = Foo::find($id);
            $item->delete();

            return Redirect::route('admin::foos.index')
                ->with(['success' => trans('crud.successfully-deleted', ['Foo'])]);
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors([trans('crud.error-occurred') . $e->getMessage()])
                ->withInput();
        }
    }
}