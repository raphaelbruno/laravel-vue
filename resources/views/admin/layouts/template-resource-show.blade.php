<?php
    $currentResource = App\Helpers\TemplateHelper::getCurrentResource();
    $itemID = App\Helpers\TemplateHelper::getItemID();
?>

@extends('admin.layouts.template')

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
