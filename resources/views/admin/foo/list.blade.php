@extends('admin.layouts.template-list')

@section('title')
    <i class="fas fa-copy mr-1"></i> Foos
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><i class="fas fa-copy"></i> Foos</li>
@endsection

@section('content')
<div class="row">
    <section class="col connectedSortable">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list mr-1"></i> List</h3>
                <div class="card-tools">
                    <form method="GET">
                        <div class="input-group input-group-sm">
                            <input type="text" name="q" class="form-control float-right" placeholder="Search" value="{{ $request->get('q') }}">
                            <div class="input-group-append">
                                <a href="{{ route('admin::foos.index') }}" class="btn btn-default"><i class="fas fa-eraser"></i></a>
                            </div>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover m-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Somethng</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->something }}</td>
                                <td>{{ $item->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $item->updated_at->format('d/m/Y H:i:s') }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin::foos.show', $item->id) }}" title="Show" class="btn btn-sm btn-warning">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin::foos.edit', $item->id) }}" title="Edit" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0);" onclick="openDeleteComfirmation('{{ route('admin::foos.destroy', $item->id) }}', '{{ $item->something }}');" title="Delete" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>                
            </div>

            <div class="card-footer">
                <a href="{{ route('admin::foos.create') }}" title="New" class="btn btn-sm btn-success float-left">
                    <i class="fas fa-plus"></i> New
                </a>
                {{ $items->links('admin.layouts.partials.pagination') }}
                <div class="float-right mr-3 mt-1">
                    Total: {{ $items->total() }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
