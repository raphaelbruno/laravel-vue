@extends('auth.layouts.template')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">@lang('auth.verify-email')</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            @lang('auth.fresh-verification-email')
                        </div>
                    @endif

                    @lang('auth.before-proceeding')
                    @lang('auth.dont-receive-email'),
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">@lang('auth.request-another') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
