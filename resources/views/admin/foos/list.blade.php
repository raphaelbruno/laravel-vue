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
            <td>{{ $item->id }}</td>
            <td><a href="{{ route('admin:foos.edit', $item->id) }}" title="@lang('crud.edit')"><b>{{ $item->something }}</b></a></td>
            <td>{{ $item->created_at->format('d/m/Y H:i:s') }}</td>
            <td>{{ $item->updated_at->format('d/m/Y H:i:s') }}</td>
            <td>{{ $item->user->shortName() }}</td>
            <td class="text-center">
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