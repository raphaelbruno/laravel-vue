@extends('admin.layouts.template-resource-show')

@section('title')
    <i class="fas fa-copy mr-1"></i> Foos
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin::foos.index') }}"><i class="fas fa-copy"></i> Foos</a></li>
<li class="breadcrumb-item"><i class="fas fa-eye"></i> Show</li>
@endsection

@section('fields')
<div class="form-group row">
    <label class="col-2 text-right">Something</label>
    <div class="col">{{ $item->something }}</div>
</div>
<div class="form-group row">
    <label class="col-2 text-right">@lang('crud.author')</label>
    <div class="col">{{ $item->user->shortName() }}</div>
</div>
<div class="form-group row">
    <label class="col-2 text-right">@lang('crud.created-at')</label>
    <div class="col">{{ $item->created_at->format('d/m/Y H:i:s') }}</div>
</div>
<div class="form-group row">
    <label class="col-2 text-right">@lang('crud.updated-at')</label>
    <div class="col">{{ $item->updated_at->format('d/m/Y H:i:s') }}</div>
</div>
@endsection
