@extends('admin.layouts.template-resource-form')

@section('fields')

    {{ Form::input([
        'ref' => 'name',
        'label' => 'crud.name',
        'required' => true,
        'icon' => $icon,
        'value' => !empty(old('item.name')) ? old('item.name') : ( isset($item) ? $item->name : '' ),
    ]) }}

    {{ Form::input([
        'ref' => 'email',
        'label' => 'crud.email',
        'required' => true,
        'icon' => 'at',
        'value' => !empty(old('item.email')) ? old('item.email') : ( isset($item) ? $item->email : '' ),
    ]) }}

    <div class="row">
        <div class="form-group col col-12 col-md-6">
            {{ Form::password([
                'ref' => 'password',
                'label' => 'crud.password',
                'icon' => 'key',
            ]) }}
        </div>
        <div class="form-group col col-12 col-md-6">
            {{ Form::password([
                'ref' => 'confirm-password',
                'label' => 'crud.confirm-password',
                'icon' => 'key',
            ]) }}
        </div>
    </div>

    <h5>@lang('admin.profile')</h5>
    <hr>

    {{ Form::input([
        'ref' => 'identity',
        'name' => 'profile[identity]',
        'label' => 'admin.identity',
        'icon' => 'address-card',
        'class' => 'cpf',
        'value' => !empty(old('profile.identity')) ? old('profile.identity') : ( isset($item) && isset($item->profile) ? $item->profile->identity : '' ),
    ]) }}

    {{ Form::input([
        'ref' => 'birthdate',
        'name' => 'profile[birthdate]',
        'label' => 'admin.birthdate',
        'icon' => 'address-card',
        'class' => 'date date-picker',
        'value' => !empty(old('profile.birthdate')) ? old('profile.birthdate') : ( isset($item) && isset($item->profile) ? $item->profile->birthdate : '' ),
    ]) }}

    <div class="row">
        <div class="form-group col">
            <label>@lang('admin.avatar') </label>
            <div class="form-field">
                <img class="img-thumbnail rounded img-fluid" src="{{ \App\Helpers\TemplateHelper::filePath(isset($item) && $item->profile ? $item->profile->avatar : null, true) }}" alt="" />
            </div>
        </div>
        <div class="form-group col">
            {{ Form::switch([
                'ref' => 'dark_mode',
                'name' => 'profile[dark_mode]',
                'label' => 'admin.dark-mode',
                'checked' => (bool) (!empty(old('profile.dark_mode')) ? old('profile.dark_mode') : ( isset($item) && isset($item->profile) ? $item->profile->dark_mode : false ) ),
            ]) }}
        </div>
    </div>

@endsection

@section('col')
    <div class="col col-12 col-md-6">
        <sub-items options="{{ json_encode($subitems->map(function($item){ return (object)['key' => $item->id, 'value' => $item->title]; })) }}"
            label="@lang('admin.roles')"
            added-items="{{ json_encode(old('subitems') ? old('subitems') : (isset($item) ? $item->roles->pluck('id') : [])) }}"
        >
        </sub-items>
    </div>
@endsection
