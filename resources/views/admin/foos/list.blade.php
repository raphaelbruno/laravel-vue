<?php
    $fontAwesomeIcon = 'fas fa-' . $icon;
    $resource = App\Helpers\TemplateHelper::getCurrentResource();
?>
@extends('admin.layouts.template-resource-list')

@section('title')
    <i class="{{ $fontAwesomeIcon }} mr-1"></i> {{ $title }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><i class="{{ $fontAwesomeIcon }}"></i> {{ $title }}</li>
@endsection

@section('thead')
    <tr>
        <th class="id">@lang('crud.id')</th>
        <th>@lang('crud.title')</th>
        <th>@lang('crud.author')</th>
        <th class="actions">@lang('crud.actions')</th>
    </tr>
@endsection

@section('tbody')
    @foreach($items as $item)
        <tr>
            <td class="align-middle text-center">
                {{ $item->id }}
            </td>
            <td class="align-middle">
                <a href="{{ route('admin:'.$resource.'.edit', $item->id) }}" title="@lang('crud.edit')">
                    <b>{{ $item->title }}</b>
                </a>
            </td>
            <td class="align-middle">
                {{ $item->user->shortName() }}
            </td>
            <td class="align-middle text-center text-nowrap">
                @include('admin.layouts.partials.actions', [
                    'resource' => $resource,
                    'id' => $item->id,
                    'name' => $item->title
                ])
            </td>
        </tr>
    @endforeach
@endsection

@section('actions')
    @parent
@endsection

@section('pagination')
    @include('admin.layouts.partials.navegation', ['pagination' => $items])
@endsection