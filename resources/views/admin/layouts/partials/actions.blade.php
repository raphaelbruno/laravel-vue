@can($resource.'-view')
    <a href="{{ route('admin::'.$resource.'.show', $id) }}" title="@lang('crud.show')" class="btn btn-sm btn-warning">
        <i class="fas fa-eye"></i>
    </a>
@endcan

@can($resource.'-update')
    <a href="{{ route('admin::'.$resource.'.edit', $id) }}" title="@lang('crud.edit')" class="btn btn-sm btn-primary">
        <i class="fas fa-edit"></i>
    </a>
@endcan

@can($resource.'-delete')
    <a href="javascript:void(0);" onclick="openDeleteComfirmation('{{ route('admin::'.$resource.'.destroy', $id) }}', '{{ $name }}');" title="@lang('crud.delete')" class="btn btn-sm btn-danger">
        <i class="fas fa-trash"></i>
    </a>
@endcan