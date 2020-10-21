@extends('admin.layouts.template-resource-form')

@section('fields')

    {!! \App\Helpers\FormHelper::input([
        'ref' => 'title',
        'label' => 'crud.title',
        'required' => true,
        'icon' => $icon,
        'value' => !empty(old('item.title')) ? old('item.title') : ( isset($item) ? $item->title : '' ),
    ]) !!}

    {!! \App\Helpers\FormHelper::input([
        'ref' => 'name',
        'label' => 'crud.name',
        'required' => true,
        'icon' => 'tag',
        'value' => !empty(old('item.name')) ? old('item.name') : ( isset($item) ? $item->name : '' ),
    ]) !!}

@endsection
