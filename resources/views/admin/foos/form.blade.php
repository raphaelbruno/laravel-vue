<?php
    $icon = 'fas fa-copy';
    $resource = App\Helpers\TemplateHelper::getCurrentResource();
?>
@extends('admin.layouts.template-resource-form')

@section('title')
    <i class="{{ $icon }} mr-1"></i> {{ $title }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin:'.$resource.'.index') }}"><i class="{{ $icon }}"></i> {{ $title }}</a></li>
    <li class="breadcrumb-item"><i class="fas fa-{{ isset($item) ? 'edit' : 'plus' }}"></i> {{ isset($item) ? trans('crud.edit') : trans('crud.new') }}</li>
@endsection

@section('fields')
    <div class="form-group">
        <label for="title">@lang('crud.title') *</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-copy"></i></span>
            </div>
            <input type="text" id="title" name="item[title]" required class="form-control" value="{{ isset($item) ? $item->title : old('item.title') }}">
            <div class="invalid-feedback">@lang('crud.invalid-field', [trans('crud.title')])</div>
        </div>
    </div>
@endsection