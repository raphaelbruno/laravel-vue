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
                @lang('admin.you-are-logged-in', ['name' => Auth::user()->name])
                </p>
                @if(Auth::user()->profile)
                <hr>
                <p>
                    <h4>@lang('admin.your-profile')</h4>
                    <ul>
                        <li>@lang('admin.identity'): {{ Auth::user()->profile->getMaskedIdentity() }}</li>
                        <li>@lang('admin.birthdate'): {{ Auth::user()->profile->birthdate->format('d/m/Y') }}</li>
                    </ul>
                </p>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
