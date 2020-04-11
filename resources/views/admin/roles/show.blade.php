@extends('admin.layouts.template-resource-show')

@section('title')
    <i class="fas fa-id-card mr-1"></i> @lang('admin.roles')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin:roles.index') }}"><i class="fas fa-id-card"></i> @lang('admin.roles')</a></li>
    <li class="breadcrumb-item"><i class="fas fa-eye"></i> Show</li>
@endsection

@section('fields')
    <div class="form-group row">
        <label class="col text-right">@lang('crud.title')</label>
        <div class="col">{{ $item->title }}</div>
    </div>
    <div class="form-group row">
        <label class="col text-right">@lang('crud.name')</label>
        <div class="col">{{ $item->name }}</div>
    </div>
    <div class="form-group row">
        <label class="col text-right">@lang('admin.level')</label>
        <div class="col">{{ $item->level }}</div>
    </div>
    <div class="form-group row">
        <label class="col text-right">@lang('admin.default')</label>
        <div class="col">
            <i class="fas fa-{{ $item->default ? 'check' : 'times' }} text-{{ $item->default ? 'success' : 'danger' }}"></i>
        </div>
    </div>
@endsection

@section('col')
    <div class="subitem col col-12 col-lg-6">
        <div class="form-group">
            <label>@lang('admin.permissions')</label>
            <div class="card card-outline card-secondary">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover m-0 subitems">
                            <thead>
                                <tr>
                                    <th>@lang('crud.title')</th>
                                    <th>@lang('crud.name')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($item->permissions as $subitem)
                                    <tr>
                                        <td class="align-middle">{{ $subitem->title }}</td>
                                        <td class="align-middle">{{ $subitem->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="align-middle" colspan="2">@lang('admin.no-items')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
