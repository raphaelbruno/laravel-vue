@extends('admin.layouts.template')

@section('title')
    <i class="fas fa-tachometer-alt mr-1"></i> @lang('admin.dashboard')
@endsection

@section('main')
<div class="row">
    <info-box value="150"
        description="Some Information"
        link-label="@lang('admin.more-information')"
        href="#"
        color="primary"
        icon="thumbs-up">
    </info-box>

    <info-box value="63%"
        description="Some Information"
        link-label="@lang('admin.more-information')"
        href="#"
        color="success"
        icon="thermometer-half">
    </info-box>

    <info-box value="44"
        description="Some Information"
        link-label="@lang('admin.more-information')"
        href="#"
        color="warning"
        icon="users">
    </info-box>

    <info-box value="65"
        description="Some Information"
        link-label="@lang('admin.more-information')"
        href="#"
        color="danger"
        icon="chart-pie">
    </info-box>
</div>

<div class="row">
    <section class="col connectedSortable">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="far fa-newspaper mr-1"></i> @lang('admin.informations')</h3>
            </div>
            
            <div class="card-body">
                <p>
                @lang('admin.you-are-logged-in', ['name' => Auth::user()->first_name])
                </p>
                <hr>
                <p>
                    <h5>@lang('admin.your-profile')</h5>
                    @if(Auth::user()->profile)
                    <ul>
                        @if(isset(Auth::user()->profile->identity))
                            <li>@lang('admin.identity'): {{ Auth::user()->profile->getMaskedIdentity() }}</li>
                        @endif
                        @if(isset(Auth::user()->profile->birthdate))
                            <li>@lang('admin.birthdate'): {{ Auth::user()->profile->birthdate->format('d/m/Y') }}</li>
                        @endif
                    </ul>
                    @else
                    <p>@lang('admin.update-profile')</p>
                    @endif
                    <a href="{{ route('admin:profile') }}" class="btn btn-primary">@lang('crud.update')</a>
                </p>
            </div>
        </div>
    </section>
</div>
@endsection
