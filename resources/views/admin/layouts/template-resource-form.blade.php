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

                <form class="needs-validation" novalidate method="POST" action="{{ $itemID ? route('admin:'.$currentResource.'.update', $itemID) : route('admin:'.$currentResource.'.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if($itemID)
                        @method('PATCH')
                    @endif
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                @yield('fields')

                                @section('instructions')
                                    <small>@lang('crud.instructions')</small>
                                @show
                            </div>
                            @yield('col')
                        </div>
                    </div>

                    <div class="card-footer">
                        @section('actions')
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-1"></i>
                            @lang('crud.save')
                        </button>
                        <a href="{{ route('admin:'.$currentResource.'.index') }}" class="btn btn-danger">
                            <i class="fas fa-times-circle mr-1"></i>
                            @lang('crud.cancel')
                        </a>
                        @show
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
