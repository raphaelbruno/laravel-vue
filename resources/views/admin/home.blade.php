@extends('admin.layouts.template')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>
                        Hi {{ Auth::user()->name }}, You are logged in!
                    </p>
                    <hr>
                    <p>
                        <h4>Profile</h4>
                        <ul>
                            <li>Identity: {{ Auth::user()->profile->getBeautyIdentity() }}</li>
                            <li>Birthdate: {{ Auth::user()->profile->getBeautyBirthdate() }}</li>
                        </ul>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
