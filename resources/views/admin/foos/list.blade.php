@extends('admin.layouts.template-resource-list')

@section('title')
    <i class="fas fa-copy mr-1"></i> Foos
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><i class="fas fa-copy"></i> Foos</li>
@endsection

@section('thead')
    <tr>
        <th>@lang('crud.id')</th>
        <th>Somethng</th>
        <th>@lang('crud.created-at')</th>
        <th>@lang('crud.updated-at')</th>
        <th>@lang('crud.author')</th>
        <th class="text-center">@lang('crud.actions')</th>
    </tr>
@endsection

@section('tbody')
    @foreach($items as $item)
        <tr>
            <td class="align-middle">{{ $item->id }}</td>
            <td class="align-middle"><a href="{{ route('admin:foos.edit', $item->id) }}" title="@lang('crud.edit')"><b>{{ $item->something }}</b></a></td>
            <td class="align-middle">{{ $item->created_at->format('d/m/Y H:i:s') }}</td>
            <td class="align-middle">{{ $item->updated_at->format('d/m/Y H:i:s') }}</td>
            <td class="align-middle">{{ $item->user->shortName() }}</td>
            <td class="align-middle text-center">
                @include('admin.layouts.partials.actions', [
                    'resource' => 'foos',
                    'id' => $item->id,
                    'name' => $item->something
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