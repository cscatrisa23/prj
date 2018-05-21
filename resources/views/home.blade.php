@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Dashboard</div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-3 col-sm-3">
                                @if (empty(Auth::user()->profile_photo))
                                    <img href="#" width="152" height="152"  src="{{ asset('storage/profiles/default.jpeg') }}">
                                @else
                                    <img href="#" width="152" height="152"  src="{{ asset('storage/profiles/' . Auth::user()->profile_photo) }}">
                                @endif
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <h2><b>{{Auth::user()->name}}</b></h2>
                                <p><b>Email:</b> {{Auth::user()->email}}</p>
                                @if($user->phone_number)
                                    <p><b>Phone Number: </b>{{Auth::user()->phone_number}}</p>
                                @else
                                    <p><b>Phone Number: </b><i>Not available</i></p>
                                @endif
                                <p><b>Created at:</b> {{Auth::user()->created_at}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
