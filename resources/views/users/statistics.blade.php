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
        <h2>{{$username}}'s statistics</h2>

        @if(count($accounts))
            <div class="links">
                <p>Number of Accounts: {{$numberOfAccounts}}</p>
                <p>Total balance of accounts: {{$totalBalance}}€</p>
            </div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Account code</th>
                    <th>Account type</th>
                    <th>Status</th>
                    <th>Account Percentage</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($accounts as $account)
                    <tr>
                        <td>{{$account->code}}</td>
                        <td>{{DB::table('account_types')->where('id', $account->account_type_id)->value('name')}}
                        <td>
                            @if($account->isOpen())
                                <span>Open</span>
                            @else
                                <span>Closed</span>
                            @endif
                        </td>
                        <td>{{round($account->current_balance/$totalBalance*100, 2)}}%</td>
                    </tr>
                @endforeach
            </table>
        @else
            <h2>No accounts found</h2>
        @endif
    </div>
@endsection