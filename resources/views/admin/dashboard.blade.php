@extends('admin.layouts.template')

@section('title')
    <i class="fas fa-tachometer-alt mr-1"></i> @lang('admin.dashboard')
@endsection

@section('main')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>150</h3>
                <p>Some Information</p>
            </div>
            <div class="icon"><i class="fas fa-thumbs-up"></i></div>
            <a href="#" class="small-box-footer">@lang('admin.more-information') <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>53<sup style="font-size: 20px">%</sup></h3>
                <p>Some Information</p>
            </div>
            <div class="icon"><i class="fas fa-thermometer-half"></i></div>
            <a href="#" class="small-box-footer">@lang('admin.more-information') <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>44</h3>
                <p>Some Information</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="#" class="small-box-footer">@lang('admin.more-information') <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>65</h3>
                <p>Some Information</p>
            </div>
            <div class="icon"><i class="fas fa-chart-pie"></i></div>
            <a href="#" class="small-box-footer">@lang('admin.more-information') <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <section class="col connectedSortable">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="far fa-newspaper mr-1"></i> @lang('admin.informations')</h3>
            </div>
            
            <div class="card-body">
                <p>
                @lang('admin.you-are-logged-in', ['name' => Auth::user()->firstName()])
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
