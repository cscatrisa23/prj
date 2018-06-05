@extends('layouts.app')
@section('content')

    <div class="container">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
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
                    <th>Actions</th>
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
                            @foreach($associates as $associate)
                                @if ($associate->associated_user_id == $user->id)
                                    <span>associate</span>
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach($associate_of as $associate)
                                @if ($associate->main_user_id == $user->id)
                                    <span>associate-of</span>
                                @endif
                            @endforeach
                        </td>
                        <td>

                            <form class="form" method="POST" action="{{route('associate.add')}}">
                                {{csrf_field()}}
                                <input type="hidden" name="associated_user" value="{{$user->id}}">
                                @if ($errors->has('associated_user'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('associated_user') }}</strong>
                                    </span>
                                @endif
                                <button  type="submit" class="btn btn-xs btn-primary">Add as an Associate</button>
                            </form>
                            <form class="form" method="POST" action="{{route('associate.remove', $user->id)}}">
                                {{csrf_field()}}
                                {{method_field('DELETE')}}
                                <button  type="submit" class="btn btn-xs btn-danger">Remove Associate</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            <h2>No users found</h2>
        @endif
    </div>
@endsection