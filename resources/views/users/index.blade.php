@extends('layouts.app')
@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="container">
        @if(count($users))
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Email</th>
                    <th>Fullname</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{$user->email}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->typeToStr()}}</td>
                        <td>{{$user->blockedToStr()}}</td>
                        @if($user->blocked==0)
                            <td>
                                <form method="POST" action="{{route('users.block', $user)}}">
                                    {{crsf_field()}}
                                    {{method_field('PATCH')}}
                                    <button  type="submit" class="btn btn-xs btn-danger">Block</button>
                                </form>
                        @endif
                        @if($user->blocked==1)
                            <td> <form method="POST" action="{{route('users.unblock', $user)}}">
                                    {{method_field('PATCH')}}
                                    <button  type="submit" class="btn btn-xs btn-primary">Unblock</button>
                                </form>
                        @endif
                        @if($user->admin==0)
                            <a class="btn btn-xs btn-primary" href="{{route('users.promote', $user)}}">Promote</a></td>
                        @endif
                        @if($user->admin==1)
                            <a class="btn btn-xs btn-danger" href="{{route('users.demote', $user)}}">Demote</a></td>
                        @endif
                    </tr>
                @endforeach
            </table>
        @else
            <h2>No users found</h2>
        @endif
    </div>
@endsection