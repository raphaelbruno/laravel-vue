@extends('admin.layouts.template')

@section('title')
    <i class="fas fa-user mr-1"></i> @lang('admin.profile')
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><i class="fas fa-user"></i> @lang('admin.profile')</li>
@endsection

@section('main')
<div class="row">
    <section class="col connectedSortable">
        <div class="card card-outline card-{{ isset($item) ? 'primary' : 'success' }}">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-edit mr-1"></i>  @lang('crud.edit')</h3>
            </div>

            <form method="POST" action="{{ route('admin::profile.update') }}">
                @csrf
                @method('PATCH')
                
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="name">@lang('crud.name')</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" id="name" name="user[name]" class="form-control" value="{{ $user->name }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">@lang('crud.email')</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-at"></i></span>
                                    </div>
                                    <input disabled type="text" id="email" name="user[email]" class="form-control" value="{{ $user->email }}">
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
                                    <input type="text" id="identity" name="profile[identity]" class="form-control cpf" value="{{ isset($user->profile) ? $user->profile->getMaskedIdentity() : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="birthdate">@lang('admin.birthdate')</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" id="birthdate" name="profile[birthdate]" class="form-control date date-picker" value="{{ isset($user->profile) && isset($user->profile->birthdate) ? $user->profile->birthdate->format('d/m/Y') : '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">@lang('crud.save')</button>
                    <a href="{{ route('admin::dashboard') }}" class="btn btn-danger">@lang('crud.cancel')</a>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
