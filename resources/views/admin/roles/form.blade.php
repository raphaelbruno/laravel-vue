<?php
    $fontAwesomeIcon = 'fas fa-' . $icon;
    $resource = App\Helpers\TemplateHelper::getCurrentResource();
?>
@extends('admin.layouts.template-resource-form')

@section('title')
    <i class="{{ $fontAwesomeIcon }} mr-1"></i> {{ $title }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin:'.$resource.'.index') }}"><i class="{{ $fontAwesomeIcon }}"></i> {{ $title }}</a></li>
    <li class="breadcrumb-item"><i class="fas fa-{{ isset($item) ? 'edit' : 'plus' }}"></i> {{ isset($item) ? trans('crud.edit') : trans('crud.new') }}</li>
@endsection

@section('fields')

    {!! \App\Helpers\FormHelper::input([
        'ref' => 'title',
        'label' => 'crud.title',
        'required' => true,
        'icon' => $icon,
        'value' => !empty(old('item.title')) ? old('item.title') : ( isset($item) ? $item->title : '' ),
    ]) !!}

    {!! \App\Helpers\FormHelper::input([
        'ref' => 'name',
        'label' => 'crud.name',
        'required' => true,
        'icon' => 'tag',
        'value' => !empty(old('item.name')) ? old('item.name') : ( isset($item) ? $item->name : '' ),
    ]) !!}

    {!! \App\Helpers\FormHelper::input([
        'ref' => 'level',
        'label' => 'admin.level',
        'icon' => 'sitemap',
        'value' => !empty(old('item.level')) ? old('item.level') : ( isset($item) ? $item->level : '' ),
        'attributes' => [
            'data-mask' => '00',
            'data-mask-reverse' => 'true'
        ],
    ]) !!}

    {!! \App\Helpers\FormHelper::switch([
        'ref' => 'default',
        'label' => 'admin.default',
        'item' => isset($item) ? $item : null,
        'checked' => (bool) (!empty(old('item.default')) ? old('item.default') : ( isset($item) ? $item->default : false ) ),
    ]) !!}

@endsection

@section('col')
    <div class="col col-md-6">
        <sub-items options="{{ json_encode($subitems->map(function($item){ return (object)['key' => $item->id, 'value' => $item->title]; })) }}"
            label="@lang('admin.permissions')"
            added-items="{{ json_encode(old('subitems') ? old('subitems') : (isset($item) ? $item->permissions->pluck('id') : [])) }}"
        >
        </sub-items>
    </div>
@endsection