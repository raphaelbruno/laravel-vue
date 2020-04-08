@extends('admin.layouts.template-resource-form')

@section('title')
    <i class="fas fa-copy mr-1"></i> Foos
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin::foos.index') }}"><i class="fas fa-copy"></i> Foos</a></li>
<li class="breadcrumb-item"><i class="fas fa-{{ isset($item) ? 'edit' : 'plus' }}"></i> {{ isset($item) ? __('crud.edit') : __('crud.new') }}</li>
@endsection

@section('fields')
<div class="form-group">
    <label for="something">Something</label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-copy"></i></span>
        </div>
        <input type="text" id="something" name="item[something]" class="form-control" placeholder="Type Something" value="{{ isset($item) ? $item->something : old('item.something') }}">
    </div>
</div>
@endsection