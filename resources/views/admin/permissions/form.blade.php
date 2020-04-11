@extends('admin.layouts.template-resource-form')

@section('title')
    <i class="fas fa-clipboard-list mr-1"></i> @lang('admin.permissions')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin:permissions.index') }}"><i class="fas fa-clipboard-list"></i> @lang('admin.permissions')</a></li>
    <li class="breadcrumb-item"><i class="fas fa-{{ isset($item) ? 'edit' : 'plus' }}"></i> {{ isset($item) ? trans('crud.edit') : trans('crud.new') }}</li>
@endsection

@section('fields')
    <div class="form-group">
        <label for="title">@lang('crud.title')</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-clipboard-list"></i></span>
            </div>
            <input type="text" id="title" name="item[title]" class="form-control" value="{{ !empty(old('item.title')) ? old('item.title') : ( isset($item) ? $item->title : '' ) }}">
        </div>
    </div>
    <div class="form-group">
        <label for="name">@lang('crud.name')</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-tag"></i></span>
            </div>
            <input type="text" id="name" name="item[name]" class="form-control" value="{{ !empty(old('item.name')) ? old('item.name') : ( isset($item) ? $item->name : '' ) }}">
        </div>
    </div>
@endsection