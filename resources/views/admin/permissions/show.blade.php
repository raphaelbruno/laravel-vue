@extends('admin.layouts.template-resource-show')

@section('title')
    <i class="fas fa-clipboard-list mr-1"></i> @lang('admin.permissions')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin:permissions.index') }}"><i class="fas fa-clipboard-list"></i> @lang('admin.permissions')</a></li>
    <li class="breadcrumb-item"><i class="fas fa-eye"></i> @lang('crud.show')</li>
@endsection

@section('fields')
    <div class="form-group row">
        <label class="col text-right">@lang('crud.title')</label>
        <div class="col">{{ $item->title }}</div>
    </div>
    <div class="form-group row">
        <label class="col text-right">@lang('crud.name')</label>
        <div class="col">{{ $item->name }}</div>
    </div>
@endsection