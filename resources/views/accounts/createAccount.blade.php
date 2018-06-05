@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Create Account') }}</div>

                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" action="{{ route('account.store') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group row">
                                    <label for="account_type_id" class="col-md-4 col-form-label text-md-right">{{ __('Account Type') }}</label>
                                    <div class="col-md-6">
                                        <select name="account_type_id" id="inputType" class="form-control">
                                            <option disabled selected> Select an option </option>
                                            @foreach ($accountTypes as $accountType):
                                            <option value="{{$accountType->id}}">{{$accountType->name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('account_type_id'))
                                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('account_type_id') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('Account Code') }}</label>

                                    <div class="col-md-6">
                                        <input id="code" type="text" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" value="{{ old('code') }}" required>
                                        @if ($errors->has('code'))
                                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
                                        @endif

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="date" class="col-md-4 col-form-label text-md-right">{{ __('Creation Date') }}</label>

                                    <div class="col-md-6">
                                        <input id="date" type="date" class="form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" name="date" value="{{ old('date') }}">

                                        @if ($errors->has('date'))
                                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('date') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="start_balance" class="col-md-4 col-form-label text-md-right">{{ __('Start Balance') }}</label>

                                    <div class="col-md-6">
                                        <input id="start_balance" type="text" class="form-control{{ $errors->has('start_balance') ? ' is-invalid' : '' }}" name="start_balance" value="{{ old('start_balance') }}"required>
                                        @if ($errors->has('start_balance'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('start_balance') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description   (Optional)') }}</label>

                                    <div class="col-md-6">
                                        <textarea id="description" type="text" class="form-control" name="description" value="{{ old('description') }}"></textarea>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Create Account') }}
                                        </button>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
