<?php
    $currentResource = getCurrentResource();
    $itemID = getItemID();
    $fontAwesomeIcon = 'fas fa-' . $icon;
?>

@extends('admin.layouts.template-form', ['method' => $method ?? ($itemID ? 'PUT' : 'POST')])

@section('title')
    <i class="{{ $fontAwesomeIcon }} mr-1"></i> {{ $title }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin:'.$currentResource.'.index') }}"><i class="{{ $fontAwesomeIcon }}"></i> {{ $title }}</a></li>
    <li class="breadcrumb-item"><i class="fas fa-{{ isset($item) ? 'edit' : 'plus' }}"></i> {{ isset($item) ? trans('crud.edit') : trans('crud.new') }}</li>
@endsection

@section('label')
<h3 class="card-title"><i class="fas fa-{{ $itemID ? 'edit' : 'plus' }} mr-1"></i> {{ $itemID ? trans('crud.edit') : trans('crud.new') }}</h3>
@endsection

@section('name')
    <i class="fas fa-{{ $itemID ? 'edit' : 'plus' }} mr-1"></i> {{ $itemID ? trans('crud.edit') : trans('crud.new') }}
@endsection

@section('color', ($itemID ? 'primary' : 'success') )
@section('action', ($itemID ? route('admin:'.$currentResource.'.update', $itemID) : route('admin:'.$currentResource.'.store')) )

@section('actions')
    <button type="submit" class="btn btn-success">
        <i class="fas fa-save mr-1"></i>
        @lang('crud.save')
    </button>
    <a href="{{ route('admin:'.$currentResource.'.index') }}" class="btn btn-danger">
        <i class="fas fa-times-circle mr-1"></i>
        @lang('crud.cancel')
    </a>
@endsection
