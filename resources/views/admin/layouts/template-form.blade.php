<?php $method = strtoupper($method ?? 'GET'); ?>

@extends('admin.layouts.template')

@section('main')
    <div class="row">
        <section class="col">
            <div class="card card-outline card-@yield('color', 'secondary')">
                <div class="card-header">
                    <h3 class="card-title">@yield('label')</h3>
                </div>

                <form class="needs-validation" {!! $method != 'GET' ? 'method="POST"' : '' !!} novalidate action="@yield('action')" enctype="multipart/form-data">
                    @csrf
                    @if(!in_array($method, ['GET', 'POST']))
                        @method($method)
                    @endif

                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                @yield('fields')
                            </div>

                            @yield('col')

                            @section('instructions')
                            <div class="col col-12">
                                <small>@lang('crud.instructions')</small>
                            </div>
                            @show
                        </div>
                    </div>

                    <div class="card-footer">
                        @section('actions')
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-1"></i>
                            @lang('crud.save')
                        </button>
                        <a href="{{ route('admin:dashboard') }}" class="btn btn-danger">
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
