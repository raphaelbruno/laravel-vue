@extends('admin.layouts.template')

@section('title')
    <i class="fas fa-user mr-1"></i> @lang('admin.profile')
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><i class="fas fa-user"></i> @lang('admin.profile')</li>
@endsection

@section('main')
<div class="row">
    <section class="col">
        <div class="card card-outline card-{{ isset($item) ? 'primary' : 'success' }}">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-edit mr-1"></i>  @lang('crud.edit')</h3>
            </div>

            <form method="POST" action="{{ route('admin:profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                
                <div class="card-body">
                    <div class="row">
                        <div class="col col-12 col-lg-6">
                            <div class="form-group">
                                <label for="name">@lang('crud.name')</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" id="name" name="user[name]" class="form-control" value="{{ !empty(old('user.name')) ? old('user.name') : $user->name }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">@lang('crud.email')</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-at"></i></span>
                                    </div>
                                    <input disabled type="text" id="email" name="user[email]" class="form-control" value="{{ !empty(old('user.email')) ? old('user.email') : $user->email }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">@lang('admin.avatar')</label>
                                <div class="row">
                                    @if(isset($user->profile) && isset($user->profile->avatar))
                                    <div class="col-3 col-md-2 col-lg-3">
                                        <img class="img-thumbnail img-circle img-fluid" src="{{ asset('admin/media/'.$user->profile->avatar) }}" alt="">
                                    </div>
                                    @endif
                                    <div class="col">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-image"></i></span>
                                            </div>
                                            <input type="text" class="form-control selected-file" readonly placeholder="@lang('crud.choose-file')">
                                            <div class="input-group-append">
                                                <label class="btn btn-primary m-0" for="avatar">
                                                    <input id="avatar" name="profile[avatar]" type="file" class="d-none" onchange="top.a = this.files;  $(this).closest('.input-group').find('.selected-file').val( Object.values(this.files).map(function(item){ return item.name; }).join(', ') )">
                                                    <i class="fas fa-search mr-1"></i>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col">
                                    <label for="password">@lang('crud.password')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        </div>
                                        <input type="password" id="password" name="user[password]" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group col">
                                    <label for="confirm-password">@lang('crud.confirm-password')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        </div>
                                        <input type="password" id="confirm-password" name="user[confirm-password]" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="identity">@lang('admin.identity')</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-address-card"></i></span>
                                    </div>
                                    <input type="text" id="identity" name="profile[identity]" class="form-control cpf" value="{{ !empty(old('profile.identity')) ? old('profile.identity') : ( isset($user->profile) ? $user->profile->getMaskedIdentity() : '' ) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="birthdate">@lang('admin.birthdate')</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" id="birthdate" name="profile[birthdate]" class="form-control date date-picker" value="{{ !empty(old('profile.birthdate')) ? old('profile.birthdate') : ( isset($user->profile) && isset($user->profile->birthdate) ? $user->profile->birthdate->format('d/m/Y') : '' ) }}">
                                </div>
                            </div>
                        </div>

                        <div class="col col-12 col-lg-6">
                            <div class="form-group">
                                <label for="birthdate">@lang('admin.roles')</label>
                                @foreach($user->roles as $role)
                                <div class="card card-outline card-warning">
                                    <div class="card-header">
                                        <b>{{ $role->title }} (@lang('admin.level'): {{ $role->level }})</b>
                                    </div>
                                    <div class="card-body">
                                        {{ !empty($role->permissionsToString()) ? $role->permissionsToString() : trans('admin.no-items') }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">@lang('crud.save')</button>
                    <a href="{{ route('admin:dashboard') }}" class="btn btn-danger">@lang('crud.cancel')</a>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
