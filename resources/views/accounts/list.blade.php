@extends('layouts.app')
@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        <h2>{{DB::table('users')->where('id', $user->id)->value('name')}}'s accounts</h2>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Status
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                    <a class="dropdown-item" href="{{route('accountsClose.users', $user)}}">Closed Accounts</a>
                    <a class="dropdown-item" href="{{route('accountsOpen.users', $user)}}">Open Accounts</a>
                    <a class="dropdown-item" href="{{route('accounts.users', $user)}}">All Accounts</a>
                </div>
                <a href="{{route('account.create')}}" class="btn btn-primary" style="float: right;">Create Account</a>
            </div>

        @if(count($accounts))
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Accounts code</th>
                    <th>Account Type</th>
                    <th>Start Balance</th>
                    <th>Current Balance</th>
                    <th>Status</th>
                    <th>Number of Movements</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($accounts as $account)
                    <tr>
                        <td>{{$account->code}}</td>
                        <td>{{$account->type->name}}</td>
                        <td>{{$account->start_balance}}</td>
                        <td>{{$account->current_balance}}</td>
                        <td>
                            @if($account->isOpen())
                                <span>Open</span>
                            @else
                                <span>Closed</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{route('movement.list', $account)}}">
                                {{count($account->movements)}}
                            </a>
                        </td>
                        <td>
                            @if (Auth::user()->can('deleteCloseOrReopen', $account))
                                <div class="form-group row">
                                    <form style="padding-right: 2px" class="form" method="GET" action="{{route('account.showEdit', $account)}}">
                                        {{csrf_field()}}
                                        <button  type="submit" class="btn btn-xs btn-primary">Edit</button>
                                    </form>
                                     <form style="padding-right: 2px" class="form" method="POST" action="{{route('account.delete', $account)}}">
                                         {{csrf_field()}}
                                         {{method_field('DELETE')}}
                                        <button  type="submit" class="btn btn-xs btn-danger">Delete</button>
                                    </form>
                                    @if($account->isOpen())
                                        <form class="form" method="POST" action="{{route('account.close', $account)}}">
                                            {{csrf_field()}}
                                            {{method_field('PATCH')}}
                                            <button  type="submit" class="btn btn-xs btn-danger">Close</button>
                                        </form>
                                    @else
                                        <form class="form" method="POST" action="{{route('account.reopen', $account)}}">
                                            {{csrf_field()}}
                                            {{method_field('PATCH')}}
                                            <button  type="submit" class="btn btn-xs btn-primary">Open</button>
                                        </form>
                                    @endif
                                </div>
                            @else
                                <span>Not allowed</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            <h2>No accounts found</h2>
        @endif
    </div>
@endsection