@extends('layouts.app')

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="container">
        @if(count($associate_ofUsers))
            @foreach($associate_ofUsers as $user)
                <div class="card card-block bg-faded">
                    <div class="row">
                        <div class="col-md-2 col-sm-2">
                            @if (empty($user->profile_photo))
                                <img href="#" width="152" height="152"  src="{{ asset('storage/profiles/default.jpeg') }}">
                            @else
                                <img href="#" width="152" height="152"  src="{{ asset('storage/profiles/' . $user->profile_photo) }}">
                            @endif
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <h2><b>{{$user->name}}</b></h2>
                            <p><b>Email:</b> {{$user->email}}</p>
                            @if($user->phone_number)
                                <p><b>Phone Number: </b>{{$user->phone_number}}</p>
                            @else
                                <p><b>Phone Number: </b><i>Not available</i></p>
                            @endif
                            <p><b>List of Accounts: </b><a href="{{route('accounts.users', $user)}}"><i>click here</i></a></p>
                            <p><b>{{$user->name}}'s Dashboard: </b><a href="{{route('user.statistics', $user)}}"><i>click here</i></a></p>

                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <h2>No associates of</h2>
        @endif
    </div>
@endsection