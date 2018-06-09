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
                <p>Total balance of accounts: {{number_format($totalBalance, 2)}}€</p>
            </div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Account code</th>
                    <th>Account type</th>
                    <th>Current Balance</th>
                    <th>Status</th>
                    <th>Account Percentage</th>
                </tr>
                </thead>
                <tbody>
                @for($i = 0; $i < count($accounts); $i++)
                    <tr>
                        <td>
                            {{$accounts[$i]->code}}
                        </td>
                        <td>
                            {{$accounts[$i]->type->name}}
                        </td>
                        <td>
                            {{$summary[$i]}}
                        </td>
                        <td>
                            @if($accounts[$i]->isOpen())
                                <span>Open</span>
                            @else
                                <span>Closed</span>
                            @endif
                        </td>
                        <td>
                            {{number_format($percentage[$i], 2)}}%
                        </td>
                    </tr>
                @endfor
            </table>
        @else
            <h2>No accounts found</h2>
        @endif
    </div>
@endsection