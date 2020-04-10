@extends('admin.layouts.template-resource-show')

@section('title')
    <i class="fas fa-id-card mr-1"></i> Roles
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin:roles.index') }}"><i class="fas fa-id-card"></i> Roles</a></li>
    <li class="breadcrumb-item"><i class="fas fa-eye"></i> Show</li>
@endsection

@section('fields')
    <div class="form-group row">
        <label class="col-2 text-right">@lang('crud.title')</label>
        <div class="col">{{ $item->title }}</div>
    </div>
    <div class="form-group row">
        <label class="col-2 text-right">@lang('crud.name')</label>
        <div class="col">{{ $item->name }}</div>
    </div>
    <div class="form-group row">
        <label class="col-2 text-right">@lang('admin.level')</label>
        <div class="col">{{ $item->level }}</div>
    </div>
    <div class="form-group row">
        <label class="col-2 text-right">@lang('admin.default')</label>
        <div class="col">
            <i class="fas fa-{{ $item->default ? 'check' : 'times' }} text-{{ $item->default ? 'success' : 'danger' }}"></i>
        </div>
    </div>
@endsection
