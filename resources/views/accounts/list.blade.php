@extends('layouts.app')
@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="container">
        <h2>{{DB::table('users')->where('id', $user->id)->value('name')}}'s accounts</h2>
        @if(count($accounts))
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Accounts code</th>
                    <th>Account Type</th>
                    <th>Current Balance</th>
                    <th>Delete Account</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($accounts as $account)
                    <tr>
                        <td>{{$account->code}}</td>
                        <td>{{DB::table('account_types')->where('id', $account->account_type_id)->value('name')}}</td>
                        <td>{{$account->current_balance}}</td>
                        <td>
                            <form class="form" method="POST" action="{{route('account.delete', $account)}}">
                                {{csrf_field()}}
                                {{method_field('DELETE')}}
                                <button  type="submit" class="btn btn-xs btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            <h2>No accounts found</h2>
        @endif
    </div>
@endsection