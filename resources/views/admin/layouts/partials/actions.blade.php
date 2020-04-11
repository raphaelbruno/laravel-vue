@can((isset($permission) ? $permission : $resource).'-view')
    @if(!isset($hide) || !in_array('view', $hide))
        <a href="{{ route('admin:'.$resource.'.show', $id) }}" title="@lang('crud.show')" class="btn btn-sm btn-warning">
            <i class="fas fa-eye"></i>
        </a>
    @endif
@endcan

@can((isset($permission) ? $permission : $resource).'-update')
    @if(!isset($hide) || !in_array('update', $hide))
        <a href="{{ route('admin:'.$resource.'.edit', $id) }}" title="@lang('crud.edit')" class="btn btn-sm btn-primary">
            <i class="fas fa-edit"></i>
        </a>
    @endif
@endcan

@can((isset($permission) ? $permission : $resource).'-delete')
    @if(!isset($hide) || !in_array('delete', $hide))
        <a href="javascript:void(0);" onclick="openDeleteComfirmation('{{ route('admin:'.$resource.'.destroy', $id) }}', '{{ $name }}');" title="@lang('crud.delete')" class="btn btn-sm btn-danger">
            <i class="fas fa-trash"></i>
        </a>
    @endif
@endcan