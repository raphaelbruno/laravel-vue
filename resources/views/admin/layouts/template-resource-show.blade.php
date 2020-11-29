<?php
    $currentResource = getCurrentResource();
    $itemID = getItemID();
    $fontAwesomeIcon = 'fas fa-' . $icon;
?>

@extends('admin.layouts.template')

@section('title')
    <i class="{{ $fontAwesomeIcon }} mr-1"></i> {{ $title }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin:'.$currentResource.'.index') }}"><i class="{{ $icon }}"></i> {{ $title }}</a></li>
    <li class="breadcrumb-item"><i class="fas fa-eye"></i> @lang('crud.show')</li>
@endsection

@section('main')
    <div class="row">
        <section class="col connectedSortable">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-eye mr-1"></i> {{ trans('crud.show') }} ({{ trans('crud.id') }}: {{ $itemID }})</h3>
                </div>

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
                        <a href="{{ route('admin:'.$currentResource.'.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo-alt mr-1"></i>
                            {{ trans('crud.back') }}
                        </a>
                        @can($currentResource.'-update')
                            <a href="{{ route('admin:'.$currentResource.'.edit', $itemID) }}" class="btn btn-primary">
                                <i class="fas fa-edit mr-1"></i>
                                {{ trans('crud.edit') }}
                            </a>
                        @endcan
                    @show
                </div>
            </div>
        </section>
    </div>
@endsection
