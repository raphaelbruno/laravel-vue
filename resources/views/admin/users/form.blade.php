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
        'ref' => 'name',
        'label' => 'crud.name',
        'required' => true,
        'icon' => $icon,
        'value' => !empty(old('item.name')) ? old('item.name') : ( isset($item) ? $item->name : '' ),
    ]) !!}

    {!! \App\Helpers\FormHelper::input([
        'ref' => 'email',
        'label' => 'crud.email',
        'required' => true,
        'icon' => 'at',
        'value' => !empty(old('item.email')) ? old('item.email') : ( isset($item) ? $item->email : '' ),
    ]) !!}

    <div class="row">
        <div class="form-group col">
            {!! \App\Helpers\FormHelper::password([
                'ref' => 'password',
                'label' => 'crud.password',
                'icon' => 'key',
            ]) !!}
        </div>
        <div class="form-group col">
            {!! \App\Helpers\FormHelper::password([
                'ref' => 'confirm-password',
                'label' => 'crud.confirm-password',
                'icon' => 'key',
            ]) !!}
        </div>
    </div>
    
    <h5>@lang('admin.profile')</h5>
    <hr>
    
    {!! \App\Helpers\FormHelper::input([
        'ref' => 'identity',
        'name' => 'profile[identity]',
        'label' => 'admin.identity',
        'icon' => 'address-card',
        'class' => 'cpf',
        'value' => !empty(old('profile.identity')) ? old('profile.identity') : ( isset($item) && isset($item->profile) ? $item->profile->identity : '' ),
    ]) !!}
    
    {!! \App\Helpers\FormHelper::input([
        'ref' => 'birthdate',
        'name' => 'profile[birthdate]',
        'label' => 'admin.birthdate',
        'icon' => 'address-card',
        'class' => 'date date-picker',
        'value' => !empty(old('profile.birthdate')) ? old('profile.birthdate') : ( isset($item) && isset($item->profile) ? $item->profile->birthdate : '' ),
    ]) !!}

    {!! \App\Helpers\FormHelper::switch([
        'ref' => 'dark_mode',
        'name' => 'profile[dark_mode]',
        'label' => 'admin.dark-mode',
        'checked' => (bool) (!empty(old('profile.dark_mode')) ? old('profile.dark_mode') : ( isset($item) && isset($item->profile) ? $item->profile->dark_mode : false ) ),
    ]) !!}

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