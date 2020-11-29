<?php $resource = getCurrentResource(); ?>

@extends('admin.layouts.template-resource-list')

@section('thead')
    <tr>
        <th class="id">@lang('crud.id')</th>
        <th>@lang('crud.title')</th>
        <th>@lang('crud.name')</th>
        <th class="text-center">@lang('admin.level')</th>
        <th class="text-center">@lang('admin.default')</th>
        <th class="text-center w-25">@lang('admin.permissions')</th>
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
                {{ $item->name }}
            </td>
            <td class="align-middle text-center">
                {{ $item->level }}
            </td>
            <td class="align-middle text-center">
                <i class="fas fa-{{ $item->default ? 'check' : 'times' }} text-{{ $item->default ? 'success' : 'danger' }}"></i>
            </td>
            <td class="align-middle text-center">
                {{ $item->permissionsToString() }}
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
