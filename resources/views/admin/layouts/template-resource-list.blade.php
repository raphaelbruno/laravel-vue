<?php
    $currentResource = App\Helpers\TemplateHelper::getCurrentResource();
?>

@extends('admin.layouts.template')

@section('main')
    <div class="row">
        <section class="col connectedSortable">
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list mr-1"></i> @lang('crud.list')</h3>
                    <div class="card-tools">
                        <form method="GET" class="d-flex align-items-center">
                            @yield('filters')
                            <div>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="q" class="form-control float-right" placeholder="@lang('crud.search')" value="{{ $request->get('q') }}">
                                    <div class="input-group-append">
                                        <a href="{{ route('admin:'.$currentResource.'.index') }}" title="@lang('crud.clear')" class="btn btn-default"><i class="fas fa-eraser"></i></a>
                                    </div>
                                    <div class="input-group-append">
                                        <button type="submit" title="@lang('crud.search')" class="btn btn-default"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover m-0">
                            <thead>
                                @yield('thead')
                            </thead>
                            <tbody>
                                @yield('tbody')
                            </tbody>
                        </table>
                    </div>                
                </div>

                <div class="card-footer">
                    @section('actions')
                        @can($currentResource.'-create')
                        <a href="{{ route('admin:'.$currentResource.'.create') }}" title="@lang('crud.new')" class="btn btn-sm btn-success float-left">
                            <i class="fas fa-plus mr-1"></i> @lang('crud.new')
                        </a>
                        @endcan
                    @show
                    @yield('pagination')
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="deleteConfirm" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmLabel">@lang('crud.delete-confirmation')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @lang('crud.are-you-sure-delete')<span class="item"></span>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times-circle mr-1"></i>
                            @lang('crud.cancel')
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash mr-1"></i>
                            @lang('crud.delete')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
