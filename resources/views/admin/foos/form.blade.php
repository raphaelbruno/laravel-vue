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
        'item' => isset($item) ? $item : null,
    ]) !!}

@endsection