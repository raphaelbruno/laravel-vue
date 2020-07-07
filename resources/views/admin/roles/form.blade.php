@extends('admin.layouts.template-resource-form')

@section('title')
    <i class="fas fa-id-card mr-1"></i> @lang('admin.roles')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin:roles.index') }}"><i class="fas fa-id-card"></i> @lang('admin.roles')</a></li>
    <li class="breadcrumb-item"><i class="fas fa-{{ isset($item) ? 'edit' : 'plus' }}"></i> {{ isset($item) ? trans('crud.edit') : trans('crud.new') }}</li>
@endsection

@section('fields')
    <div class="form-group">
        <label for="title">@lang('crud.title')</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
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
    <div class="form-group">
        <label for="level">@lang('admin.level')</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-sitemap"></i></span>
            </div>
            <input type="text" id="level" name="item[level]" data-mask="00" data-mask-reverse="true" class="form-control mask" value="{{ !empty(old('item.level')) ? old('item.level') : ( isset($item) ? $item->level : '' ) }}">
        </div>
    </div>
    <div class="form-group">
        <label for="default">@lang('admin.default')</label>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="item[default]" class="custom-control-input" id="default" {{ (!empty(old('item.default')) ? old('item.default') : ( isset($item) ? $item->default : false ) ) ? 'checked' : '' }}>
            <label class="custom-control-label" for="default"></label>
        </div>
    </div>

@endsection

@section('col')
    @include('admin.layouts.partials.subitems', [
        'subitems' => $subitems,
        'label' => trans('admin.permissions'),
        'addedItems' => old('subitems') ? old('subitems') : (isset($item) ? $item->permissions : [])
    ])
@endsection