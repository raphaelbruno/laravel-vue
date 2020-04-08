<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

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
        $query = $request->get('q');
        $items = Foo::where('something', 'ilike', "%{$query}%")
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
                ->withErrors([trans('crud.error-occurred') . ' ' . $e->getMessage()])
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
        $item = Foo::find($id);
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
        $item = Foo::find($id);
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
        $fields = $request->item;
        $validator = Validator::make($fields, $this->rules, [], $this->names);
        if ($validator->fails())
        {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $item = Foo::find($id);
            $item->something = $fields['something'];
            $item->save();

            return Redirect::route('admin::foos.index')
                ->with(['success' => trans('crud.successfully-updated', ['Foo'])]);
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors([trans('crud.error-occurred') . ' ' . $e->getMessage()])
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
        try {
            $item = Foo::find($id);
            $item->delete();

            return Redirect::route('admin::foos.index')
                ->with(['success' => trans('crud.successfully-deleted', ['Foo'])]);
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors([trans('crud.error-occurred') . ' ' . $e->getMessage()])
                ->withInput();
        }
    }
}