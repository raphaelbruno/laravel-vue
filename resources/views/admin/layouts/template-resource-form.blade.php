<?php
    $currentResource = App\Helpers\TemplateHelper::getCurrentResource();
    $itemID = App\Helpers\TemplateHelper::getItemID();
?>

@extends('admin.layouts.template')

@section('main')
<div class="row">
    <section class="col">
        <div class="card card-outline card-{{ $itemID ? 'primary' : 'success' }}">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-{{ $itemID ? 'edit' : 'plus' }} mr-1"></i> {{ $itemID ? trans('crud.edit') : trans('crud.new') }}</h3>
            </div>

            <form method="POST" action="{{ $itemID ? route('admin::'.$currentResource.'.update', $itemID) : route('admin::'.$currentResource.'.store') }}">
                @csrf
                @if($itemID)
                    @method('PATCH')
                @endif
                
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            @yield('fields')
                        </div>
                        @yield('col')
                    </div>
                </div>

                <div class="card-footer">
                    @section('actions')
                    <button type="submit" class="btn btn-success">@lang('crud.save')</button>
                    <a href="{{ route('admin::'.$currentResource.'.index') }}" class="btn btn-danger">@lang('crud.cancel')</a>
                    @show
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
