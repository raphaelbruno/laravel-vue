@extends('admin.layouts.template-resource-list')

@section('title')
    <i class="fas fa-user mr-1"></i> @lang('admin.users')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><i class="fas fa-user"></i> @lang('admin.users')</li>
@endsection

@section('thead')
    <tr>
        <th>@lang('crud.name')</th>
        <th>@lang('crud.email')</th>
        <th class="text-center">@lang('admin.identity')</th>
        <th class="text-center">@lang('admin.birthdate')</th>
        <th class="text-center w-25">@lang('admin.roles')</th>
        <th class="text-center">@lang('crud.actions')</th>
    </tr>
@endsection

@section('tbody')
    @foreach($items as $item)
        <tr>
            <td class="align-middle">
                @can('has-level', $item)
                    <a href="{{ route('admin:users.edit', $item->id) }}" title="@lang('crud.edit')"><b>{{ $item->name }}</b></a>
                @else
                    <b>{{ $item->name }}</b>
                @endcan
            </td>
            <td class="align-middle">{{ $item->email }}</td>
            <td class="align-middle text-center">{{ isset($item->profile) ? $item->profile->getMaskedIdentity() : '' }}</td>
            <td class="align-middle text-center">{{ isset($item->profile) && isset($item->profile->birthdate) ? $item->profile->birthdate->format('d/m/Y') : '' }}</td>
            <td class="align-middle text-center">{{ $item->rolesToString() }}</td>
            <td class="align-middle text-center text-nowrap">
                @can('has-level', $item)
                    @include('admin.layouts.partials.actions', [
                        'resource' => 'users',
                        'id' => $item->id,
                        'name' => $item->name,
                        'hide' => ['view']
                    ])
                @endcan
            </td>
        </tr>
    @endforeach
@endsection

@section('pagination')
    @include('admin.layouts.partials.navegation', ['pagination' => $items])
@endsection