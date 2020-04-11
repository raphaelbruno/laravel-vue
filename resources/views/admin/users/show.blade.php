@extends('admin.layouts.template-resource-show')

@section('title')
    <i class="fas fa-user mr-1"></i> @lang('admin.users')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin:users.index') }}"><i class="fas fa-user"></i> @lang('admin.users')</a></li>
    <li class="breadcrumb-item"><i class="fas fa-eye"></i> @lang('crud.show')</li>
@endsection

@section('fields')
    <div class="form-group row">
        <label class="col text-right">@lang('crud.name')</label>
        <div class="col">{{ $item->name }}</div>
    </div>
    <div class="form-group row">
        <label class="col text-right">@lang('crud.email')</label>
        <div class="col">{{ $item->email }}</div>
    </div>
    <h5 class="text-center">@lang('admin.profile')</h5>
    <hr>
    <div class="form-group row">
        <label class="col text-right">@lang('admin.identity')</label>
        <div class="col">{{ isset($item->profile) ? $item->profile->getMaskedIdentity() : '' }}</div>
    </div>
    <div class="form-group row">
        <label class="col text-right">@lang('admin.birthdate')</label>
        <div class="col">{{ isset($item->profile) && isset($item->profile->birthdate) ? $item->profile->birthdate->format('d/m/Y') : '' }}</div>
    </div>
@endsection

@section('col')
    <div class="subitem col col-12 col-lg-6">
        <div class="form-group">
            <label for="birthdate">@lang('admin.roles')</label>
            @foreach($item->roles as $role)
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <b>{{ $role->title }} (@lang('admin.level'): {{ $role->level }})</b>
                </div>
                <div class="card-body">
                    {{ !empty($role->permissionsToString()) ? $role->permissionsToString() : trans('admin.no-items') }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection
