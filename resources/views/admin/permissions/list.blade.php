@extends('admin.layouts.template-resource-list')

@section('title')
    <i class="fas fa-clipboard-list mr-1"></i> @lang('admin.permissions')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><i class="fas fa-clipboard-list"></i> @lang('admin.permissions')</li>
@endsection

@section('thead')
    <tr>
        <th>@lang('crud.title')</th>
        <th>@lang('crud.name')</th>
        <th class="text-center">@lang('crud.actions')</th>
    </tr>
@endsection

@section('tbody')
    @foreach($items as $item)
        <tr>
            <td class="align-middle"><a href="{{ route('admin:permissions.edit', $item->id) }}" title="@lang('crud.edit')"><b>{{ $item->title }}</b></a></td>
            <td class="align-middle">{{ $item->name }}</td>
            <td class="align-middle text-center text-nowrap">
                @include('admin.layouts.partials.actions', [
                    'resource' => 'permissions',
                    'permission' => 'roles',
                    'id' => $item->id,
                    'name' => $item->title,
                    'hide' => ['view']
                ])
            </td>
        </tr>
    @endforeach
@endsection

@section('actions')
    @can('roles-create')
        <a href="{{ route('admin:permissions.create') }}" title="@lang('crud.new')" class="btn btn-sm btn-success float-left">
            <i class="fas fa-plus"></i> @lang('crud.new')
        </a>
    @endcan
@endsection

@section('pagination')
    @include('admin.layouts.partials.navegation', ['pagination' => $items])
@endsection