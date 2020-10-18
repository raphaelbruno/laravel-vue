@extends('admin.layouts.template-resource-list')

@section('title')
    <i class="fas fa-id-card mr-1"></i> @lang('admin.roles')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><i class="fas fa-id-card"></i> @lang('admin.roles')</li>
@endsection

@section('thead')
    <tr>
        <th>@lang('crud.title')</th>
        <th>@lang('crud.name')</th>
        <th class="text-center">@lang('admin.level')</th>
        <th class="text-center">@lang('admin.default')</th>
        <th class="text-center w-25">@lang('admin.permissions')</th>
        <th class="text-center">@lang('crud.actions')</th>
    </tr>
@endsection

@section('tbody')
    @foreach($items as $item)
        <tr>
            <td class="align-middle"><a href="{{ route('admin:roles.edit', $item->id) }}" title="@lang('crud.edit')"><b>{{ $item->title }}</b></a></td>
            <td class="align-middle">{{ $item->name }}</td>
            <td class="align-middle text-center">{{ $item->level }}</td>
            <td class="align-middle text-center">
                <i class="fas fa-{{ $item->default ? 'check' : 'times' }} text-{{ $item->default ? 'success' : 'danger' }}"></i>
            </td>
            <td class="align-middle text-center">
                {{ $item->permissionsToString() }}
            </td>
            <td class="align-middle text-center text-nowrap">
                @include('admin.layouts.partials.actions', [
                    'resource' => 'roles',
                    'id' => $item->id,
                    'name' => $item->title,
                    'hide' => ['view']
                ])
            </td>
        </tr>
    @endforeach
@endsection

@section('pagination')
    @include('admin.layouts.partials.navegation', ['pagination' => $items])
@endsection