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

        <div class="container">
            <h1>Movements of account: {{$account->code}} ({{$account->user->name}})</h1>
            <form style="padding-right: 2px; margin-bottom: 20px" class="form" method="GET" action="{{route('movement.create', $account)}}">
                <button  type="submit" class="btn btn-xs btn-primary">Create Movement</button>
            </form>
            @if(count($movements))
                {{$movements->links()}}
                @foreach ($movements as $movement)
                    <div class="card card-block bg-faded">
                        <div class="col-md-8 col-sm-8" style="margin-top: 20px; ">
                            <p><b>ID: </b>{{$movement->id}}</p>
                            <p><b>Date: </b>{{$movement->date}}</p>
                            <p><b>Category: </b>{{$movement->category->name}}</p>
                            <p><b>Type: </b>{{$movement->type}}</p>
                            <p><b>Value: </b>{{$movement->value}}</p>
                            <p><b>Start Balance: </b>{{$movement->start_balance}}</p>
                            <p><b>End Balance: </b>{{$movement->end_balance}}</p>
                            @if($movement->document_id)
                                <p><b>Documents: </b><a href="{{route('document.view', $movement->document)}}">{{$movement->document->original_name}}</a></p>
                            @else
                                <p><b>Documents: </b><i>Not available</i></p>
                            @endif
                            @if($movement->description)
                                <p><b>Description: </b>{{$movement->description}}</p>
                            @else
                                <p><b>Description: </b><i>No document available</i></p>
                            @endif
                            <div class="form-group row" style="margin-top: 30px">
                                <form style="padding-right: 2px; margin-left: 15px;" class="form" method="GET" action="{{route('movement.edit', $movement)}}">
                                    {{csrf_field()}}
                                    <button  type="submit" class="btn btn-xs btn-primary">Edit</button>
                                </form>
                                <form style="padding-right: 2px;" class="form" method="GET" action="{{route('document.add', $movement)}}">
                                    <button  type="submit" class="btn btn-xs btn-primary">Add/Replace Document</button>
                                </form>
                                @if($movement->document)
                                <form style="padding-right: 2px" class="form" method="POST" action="{{route('document.delete', $movement->document)}}">
                                    {{csrf_field()}}
                                    {{method_field('DELETE')}}
                                    <button  type="submit" class="btn btn-xs btn-danger">Delete Document</button>
                                </form>
                                    @endif
                                <form  style="padding-right: 2px" class="form" method="POST" action="{{route('movement.destroy', $movement)}}">
                                    {{csrf_field()}}
                                    {{method_field('DELETE')}}
                                    <button type="submit" class="btn btn-xs btn-danger">Delete Movement</button>
                                </form>
                            </div>
                        </div>
                    </div>

                @endforeach
                {{$movements->links()}}
            @else
                <h2>No movements found</h2>
            @endif
        </div>
    </div>
@endsection