@extends('admin.layouts.template-form')

@section('title')
    <i class="fas fa-copy mr-1"></i> Foos
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin::foos.index') }}"><i class="fas fa-copy"></i> Foos</a></li>
<li class="breadcrumb-item"><i class="fas fa-{{ isset($item) ? 'edit' : 'plus' }}"></i> {{ isset($item) ? __('crud.edit') : __('crud.new') }}</li>
@endsection

@section('content')
<div class="row">
    <section class="col connectedSortable">
        <div class="card card-outline card-{{ isset($item) ? 'primary' : 'success' }}">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-{{ isset($item) ? 'edit' : 'plus' }} mr-1"></i> {{ isset($item) ? __('crud.edit') : __('crud.new') }}</h3>
            </div>

            <form method="POST" action="{{ isset($item) ? route('admin::foos.update', $item->id) : route('admin::foos.store') }}">
                @csrf
                @if(isset($item))
                    @method('PATCH')
                @endif
                
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="something">Something</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-copy"></i></span>
                                    </div>
                                    <input type="text" id="something" name="item[something]" class="form-control" placeholder="Type Something" value="{{ isset($item) ? $item->something : old('item.something') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">@lang('crud.save')</button>
                    <a href="{{ route('admin::foos.index') }}" class="btn btn-danger">@lang('crud.cancel')</a>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
