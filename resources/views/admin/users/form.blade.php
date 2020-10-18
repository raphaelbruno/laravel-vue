@extends('admin.layouts.template-resource-form')

@section('title')
    <i class="fas fa-user mr-1"></i> @lang('admin.users')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin:users.index') }}"><i class="fas fa-user"></i> @lang('admin.users')</a></li>
    <li class="breadcrumb-item"><i class="fas fa-{{ isset($item) ? 'edit' : 'plus' }}"></i> {{ isset($item) ? trans('crud.edit') : trans('crud.new') }}</li>
@endsection

@section('fields')
    <div class="form-group">
        <label for="name">@lang('crud.name')</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <input type="text" id="name" name="item[name]" class="form-control" value="{{ !empty(old('item.name')) ? old('item.name') : ( isset($item) ? $item->name : '' ) }}">
        </div>
    </div>
    <div class="form-group">
        <label for="email">@lang('crud.email')</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-at"></i></span>
            </div>
            <input type="text" id="email" name="item[email]" class="form-control" value="{{ !empty(old('item.email')) ? old('item.email') : ( isset($item) ? $item->email : '' ) }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col">
            <label for="password">@lang('crud.password')</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                </div>
                <input type="password" id="password" name="item[password]" class="form-control">
            </div>
        </div>
        <div class="form-group col">
            <label for="confirm-password">@lang('crud.confirm-password')</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                </div>
                <input type="password" id="confirm-password" name="item[confirm-password]" class="form-control">
            </div>
        </div>
    </div>
    
    <h5>@lang('admin.profile')</h5>
    <hr>
    
    <div class="form-group">
        <label for="identity">@lang('admin.identity')</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="far fa-address-card"></i></span>
            </div>
            <input type="text" id="identity" name="profile[identity]" class="form-control cpf" value="{{ !empty(old('profile.identity')) ? old('profile.identity') : ( isset($item->profile) ? $item->profile->getMaskedIdentity() : '') }}">
        </div>
    </div>
    <div class="form-group">
        <label for="birthdate">@lang('admin.birthdate')</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
            </div>
            <input type="text" id="birthdate" name="profile[birthdate]" class="form-control date date-picker" value="{{ !empty(old('profile.birthdate')) ? old('profile.birthdate') : ( isset($item->profile) && isset($item->profile->birthdate) ? $item->profile->birthdate->format('d/m/Y') : '' ) }}">
        </div>
    </div>
@endsection

@section('col')
    <div class="col col-md-6">
        <sub-items options="{{ json_encode($subitems->map(function($item){ return (object)['key' => $item->id, 'value' => $item->title]; })) }}"
            label="@lang('admin.roles')"
            added-items="{{ json_encode(old('subitems') ? old('subitems') : (isset($item) ? $item->roles->pluck('id') : [])) }}"
        >
        </sub-items>
    </div>
@endsection