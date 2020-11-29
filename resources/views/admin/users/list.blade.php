<?php $resource = getCurrentResource(); ?>

@extends('admin.layouts.template-resource-list')

@section('thead')
    <tr>
        <th class="id">@lang('crud.id')</th>
        <th>@lang('crud.name')</th>
        <th>@lang('crud.email')</th>
        <th class="text-center">@lang('admin.identity')</th>
        <th class="text-center">@lang('admin.birthdate')</th>
        <th class="text-center w-25">@lang('admin.roles')</th>
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
                @can('has-level', $item)
                    <a href="{{ route('admin:'.$resource.'.edit', $item->id) }}" title="@lang('crud.edit')"><b>{{ $item->name }}</b></a>
                @else
                    <b>{{ $item->name }}</b>
                @endcan
            </td>
            <td class="align-middle">
                {{ $item->email }}
            </td>
            <td class="align-middle text-center">
                {{ isset($item->profile) ? $item->profile->getMaskedIdentity() : '' }}
            </td>
            <td class="align-middle text-center">
                {{ isset($item->profile) && isset($item->profile->birthdate) ? $item->profile->birthdate->format('d/m/Y') : '' }}
            </td>
            <td class="align-middle text-center">
                {{ $item->rolesToString() }}
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
