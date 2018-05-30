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

        @if(count($movements))
            <table class="table table-striped">
                <thead>
                <tr>
                    <td>Account </td>
                    <td>Type</td>
                    <td>Category</td>
                    <td>Date</td>
                    <td>Value</td>
                    <td>Start balance</td>
                    <td>End balance</td>
                </tr>
                </thead>
                <tbody>
                @foreach ($movements as $movement)
                    <tr>
                        <td>{{$movement->id}}</td>
                        <td>{{$movement->type}}</td>
                        <td>{{$movement->category->name}}</td>
                        <td>{{$movement->date}}</td>
                        <td>{{$movement->value}}</td>
                        <td>{{$movement->start_balance}}</td>
                        <td>{{$movement->end_balance}}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <h2>No movements found</h2>
    @endif
@endsection