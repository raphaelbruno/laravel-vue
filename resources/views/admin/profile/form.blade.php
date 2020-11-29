@extends('admin.layouts.template-form', ['method' => 'PATCH'])

@section('title')
    <i class="fas fa-user mr-1"></i> @lang('admin.profile')
@endsection

@section('label')
    <i class="fas fa-edit mr-1"></i> @lang('crud.edit')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><i class="fas fa-user"></i> @lang('admin.profile')</li>
@endsection

@section('action', route('admin:profile.update'))

@section('fields')

    {{ Form::input([
        'ref' => 'name',
        'name' => 'user[name]',
        'label' => 'crud.name',
        'required' => true,
        'icon' => 'user',
        'value' => !empty(old('user.name')) ? old('user.name') : $user->name,
    ]) }}

    {{ Form::input([
        'ref' => 'email',
        'name' => 'user[email]',
        'label' => 'crud.email',
        'icon' => 'at',
        'attributes' => ['disabled' => 'disabled'],
        'value' => !empty(old('user.email')) ? old('user.email') : $user->email,
    ]) }}

    <div class="form-group">
        <label for="email">@lang('admin.avatar')</label>
        <div class="row">
            <div class="col-3 col-md-2 col-lg-3">
                <img class="img-thumbnail rounded-circle img-fluid" src="{{ avatar(Auth::user()->profile ? Auth::user()->profile->avatar : null) }}" alt="">
            </div>

            <div class="col">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-image"></i></span>
                    </div>
                    <input type="text" class="form-control selected-file" readonly placeholder="@lang('crud.choose-file')">
                    <div class="input-group-append">
                        <label class="btn btn-primary m-0" for="avatar">
                            <input id="avatar" name="profile[avatar]" type="file" class="d-none file">
                            <i class="fas fa-search mr-1"></i>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group col">
            {{ Form::password([
                'ref' => 'password',
                'name' => 'user[password]',
                'label' => 'crud.password',
                'icon' => 'key',
            ]) }}
        </div>
        <div class="form-group col">
            {{ Form::password([
                'ref' => 'confirm-password',
                'name' => 'user[confirm-password]',
                'label' => 'crud.confirm-password',
                'icon' => 'key',
            ]) }}
        </div>
    </div>

    {{ Form::input([
        'ref' => 'identity',
        'name' => 'profile[identity]',
        'label' => 'admin.identity',
        'icon' => 'address-card',
        'class' => 'cpf',
        'value' => !empty(old('profile.identity')) ? old('profile.identity') : ( isset($user->profile) ? $user->profile->getMaskedIdentity() : '' ),
    ]) }}

    {{ Form::input([
        'ref' => 'birthdate',
        'name' => 'profile[birthdate]',
        'label' => 'admin.birthdate',
        'icon' => 'address-card',
        'class' => 'date date-picker',
        'value' => !empty(old('profile.birthdate')) ? old('profile.birthdate') : ( isset($user->profile) && isset($user->profile->birthdate) ? $user->profile->birthdate->format('d/m/Y') : '' ),
    ]) }}

    {{ Form::switch([
        'ref' => 'dark_mode',
        'name' => 'profile[dark_mode]',
        'label' => 'admin.dark-mode',
        'checked' => (bool) (!empty(old('profile.dark_mode')) ? old('profile.dark_mode') : isDarkMode($user) ),
    ]) }}

@endsection

@section('col')
    <div class="col col-12 col-lg-6">
        <div class="form-group">
            <label>@lang('admin.roles')</label>
            @foreach($user->roles as $role)
            <div class="card card-outline card-warning">
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
