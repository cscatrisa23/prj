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
                        @if ($user->admin)
                            <td class="user-is-admin">{{$user->typeToStr()}}</td>
                        @else
                            <td>{{$user->typeToStr()}}</td>
                        @endif
                        @if ($user->blocked)
                            <td class="user-is-blocked">{{$user->blockedToStr()}}</td>
                        @else
                            <td>{{$user->blockedToStr()}}</td>
                        @endif
                        <td>
                            <div class="form-group row">
                                @if($user->id != Auth::id())
                                    @if($user->blocked==0)
                                        <form class="form" method="POST" action="{{route('users.block', $user)}}">
                                            {{csrf_field()}}
                                            {{method_field('PATCH')}}
                                            <button  type="submit" class="btn btn-xs btn-danger">Block</button>
                                        </form>
                                    @else

                                        <form class="form" method="POST" action="{{route('users.unblock', $user)}}">
                                            {{csrf_field()}}
                                            {{method_field('PATCH')}}
                                            <button  type="submit" class="btn btn-xs btn-primary">Unblock</button>
                                        </form>
                                    @endif
                                    @if($user->admin==0)
                                        <form class="form" method="POST" action="{{route('users.promote', $user)}}">
                                            {{csrf_field()}}
                                            {{method_field('PATCH')}}
                                            <button  type="submit" class="btn btn-xs btn-primary">Promote</button>
                                        </form>
                                    @else
                                        <form class="form" method="POST" action="{{route('users.demote', $user)}}">
                                            {{csrf_field()}}
                                            {{method_field('PATCH')}}
                                            <button  type="submit" class="btn btn-xs btn-danger">Demote</button>
                                        </form>
                                    @endif
                                @else
                                    <p>No actions available!</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            <h2>No users found</h2>
        @endif
    </div>
@endsection