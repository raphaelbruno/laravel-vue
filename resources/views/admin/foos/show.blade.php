@extends('admin.layouts.template-resource-show')

@section('fields')
    <div class="form-group row">
        <label class="col text-right">@lang('crud.title')</label>
        <div class="col">{{ $item->title }}</div>
    </div>
    <div class="form-group row">
        <label class="col text-right">@lang('crud.author')</label>
        <div class="col">{{ $item->user->short_name }}</div>
    </div>
    <div class="form-group row">
        <label class="col text-right">@lang('crud.created-at')</label>
        <div class="col">{{ isset($item->created_at) ? $item->created_at->format('d/m/Y H:i:s') : '' }}</div>
    </div>
    <div class="form-group row">
        <label class="col text-right">@lang('crud.updated-at')</label>
        <div class="col">{{ isset($item->updated_at) ? $item->updated_at->format('d/m/Y H:i:s') : '' }}</div>
    </div>
@endsection
