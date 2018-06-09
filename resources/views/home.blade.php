@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                @if (isset($error))
                    <p class="alert alert-danger">{{ $error }}</p>
                @endif
                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                <div class="card">
                    <div class="card-header">
                        <span style="float: left;">Home</span>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-3 col-sm-3">
                                @if (empty(Auth::user()->profile_photo))
                                    <img href="#" width="200" height="200"  src="{{ asset('storage/profiles/default.jpeg') }}">
                                @else
                                    <img href="#" width="200" height="200"  src="{{ asset('storage/profiles/' . Auth::user()->profile_photo) }}">
                                @endif
                            </div>
                            <div class="col-md-8 col-sm-8">
                                <h2><b>{{Auth::user()->name}}</b></h2>
                                <p><b>Email:</b> {{Auth::user()->email}}</p>
                                @if(Auth::user()->phone!=null)
                                    <p><b>Phone Number: </b>{{Auth::user()->phone}}</p>
                                @else
                                    <p><b>Phone Number: </b><i>Not available</i></p>
                                @endif
                                <p><b>Created at:</b> {{Auth::user()->created_at}}</p>
                                <p><b>Number of associates:</b> {{count(DB::table('associate_members')->where('main_user_id', Auth::user()->id)->get())}}</p>
                                <p><b>Number of users that have me as an associate: </b>{{count(DB::table('associate_members')->where('associated_user_id', Auth::user()->id)->get())}}</p>
                            </div>
                        </div>
                            <a href="{{route('account.create')}}" class="btn btn-primary" style="float: left;">Create Account</a>
                            <a href="{{route('users.showEditProfile')}}" class="btn btn-primary" style="float: right;">Edit my Profile</a>
                    </div>
                </div>
            </div>
        </div>
@endsection
