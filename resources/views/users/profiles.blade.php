@extends('layouts.app')
@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="container">
        @if(count($users))
            <form action="{{action('UserController@getProfiles')}}" method="GET">
                <input name="name" placeholder="Name">
                <input type="submit" class="btn-primary" value="Search" >
            </form>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Profile Picture</th>
                    <th>Fullname</th>
                    <th>Associate</th>
                    <th>Associate Of</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>
                            @if (empty($user->profile_photo))
                                <img href="#" width="125" height="125"  src="{{ asset('storage/profiles/default.jpeg') }}">
                            @else
                                <img href="#" width="125" height="125"  src="{{ asset('storage/profiles/' . $user->profile_photo) }}">
                            @endif
                        </td>
                        <td>{{$user->name}}</td>
                        <td>
                            @if(count($associates))
                                @php
                                    $encontrou=false;
                                @endphp
                                @foreach($associates as $associate)
                                    @if ($associate->associated_user_id == $user->id)
                                        @php
                                            $encontrou=true;
                                        @endphp
                                    @endif
                                @endforeach
                            @endif
                            @if($encontrou)
                                <span>associate</span>
                            @else
                                <span>-----</span>
                            @endif
                        </td>
                        <td>
                            @if(count($associate_of))
                                @php
                                    $encontrou=false;
                                @endphp
                                @foreach($associate_of as $associate)
                                    @if ($associate->main_user_id == $user->id)
                                        @php
                                            $encontrou=true;
                                        @endphp
                                    @endif
                                @endforeach
                            @endif
                            @if($encontrou)
                                <span>associate of</span>
                            @else
                                <span>-----</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            <h2>No users found</h2>
        @endif
    </div>
@endsection