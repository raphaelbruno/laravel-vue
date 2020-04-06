@extends('admin.layouts.template')

@section('title')
    <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('/admin') }}">Dashboard</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>150</h3>
                <p>Some Information</p>
            </div>
            <div class="icon"><i class="fas fa-thumbs-up"></i></div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>53<sup style="font-size: 20px">%</sup></h3>
                <p>Some Information</p>
            </div>
            <div class="icon"><i class="fas fa-thermometer-half"></i></div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>44</h3>
                <p>Some Information</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>65</h3>
                <p>Some Information</p>
            </div>
            <div class="icon"><i class="fas fa-chart-pie"></i></div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <section class="col connectedSortable">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="far fa-newspaper mr-1"></i> Informations</h3>
            </div>
            
            <div class="card-body">
                <p>
                    Hi {{ Auth::user()->name }}, You are logged in!
                </p>
                @if(Auth::user()->profile)
                <hr>
                <p>
                    <h4>Your Profile</h4>
                    <ul>
                        <li>Identity: {{ Auth::user()->profile->getMaskedIdentity() }}</li>
                        <li>Birthdate: {{ Auth::user()->profile->birthdate->format('d/m/Y') }}</li>
                    </ul>
                </p>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
