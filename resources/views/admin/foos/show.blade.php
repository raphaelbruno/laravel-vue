<?php
    $icon = 'fas fa-copy';
    $resource = App\Helpers\TemplateHelper::getCurrentResource();
?>
@extends('admin.layouts.template-resource-show')

@section('title')
    <i class="{{ $icon }} mr-1"></i> {{ $title }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin:'.$resource.'.index') }}"><i class="{{ $icon }}"></i> {{ $title }}</a></li>
    <li class="breadcrumb-item"><i class="fas fa-eye"></i> @lang('crud.show')</li>
@endsection

@section('fields')
    <div class="form-group row">
        <label class="col-2 text-right">@lang('crud.title')</label>
        <div class="col">{{ $item->title }}</div>
    </div>
    <div class="form-group row">
        <label class="col-2 text-right">@lang('crud.author')</label>
        <div class="col">{{ $item->user->shortName() }}</div>
    </div>
    <div class="form-group row">
        <label class="col-2 text-right">@lang('crud.created-at')</label>
        <div class="col">{{ $item->created_at->format('d/m/Y H:i:s') }}</div>
    </div>
    <div class="form-group row">
        <label class="col-2 text-right">@lang('crud.updated-at')</label>
        <div class="col">{{ $item->updated_at->format('d/m/Y H:i:s') }}</div>
    </div>
@endsection
