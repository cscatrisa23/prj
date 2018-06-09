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
            {{$accounts->links()}}
        @if(count($accounts))
                @foreach ($accounts as $account)

                    <div class="card card-block bg-faded">
                        <div class="col-md-8 col-sm-8" style="margin-top: 20px; ">
                            <p><b>Account code: </b>{{$account->code}}</p>
                            <p><b>Account Type: </b>{{$account->type->name}}</p>
                            <p><b>Date: </b>{{$account->date}}</p>
                            <p><b>Start Balance: </b>{{$account->start_balance}}</p>
                            <p><b>Current Balance: </b>{{$account->current_balance}}</p>

                            <p><b>Status: </b>
                                @if($account->isOpen())
                                    <span>Open</span>
                                @else
                                    <span>Closed</span>
                                @endif
                            </p>
                            <p><b>Number of Movements: </b>
                                <a href="{{route('movement.list', $account)}}">
                                    {{count($account->movements)}}
                                </a>
                            </p>
                            @if($account->descritpion)
                                <p><b>Description: </b>{{$account->description}}</p>
                            @else
                                <p><b>Description: </b><i>Not available</i></p>
                            @endif
                            <div class="form-group row" style="margin-top: 30px">
                                <form style="padding-right: 2px; margin-left: 15px;" class="form" method="GET" action="{{route('account.showEdit', $account)}}">
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
                        </div>
                    </div>
                @endforeach
                    {{$accounts->links()}}
        @else
            <h2>No accounts found</h2>
        @endif
    </div>
@endsection