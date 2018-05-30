@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Add new movement') }}</div>

                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" action="">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Movement Category') }}</label>
                                <div class="col-md-6">
                                        <select name="movement_category_id" id="inputType" class="form-control">
                                            <option disabled selected> Select an option </option>
                                            @foreach ($movementCategories as $movementCategory):
                                            <option value="{{$movementCategory->id}}">{{$movementCategory->name}}</option>
                                            @endforeach
                                        </select>
                                    @if ($errors->has('movement_category_id'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('movement_category_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Date') }}</label>
                                <div class="col-md-6">
                                    <input type="date" name="date" class="form-control" id="date" placeholder="Date" value="{{ old('date') }}">
                                    @if ($errors->has('date'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('date') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="value" class="col-md-4 col-form-label text-md-right">{{ __('Value') }}</label>
                                <div class="col-md-6">
                                    <input name="value" id="value" type="number" step="0.01" class="form-control{{ $errors->has('Value') ? ' is-invalid' : '' }}" name="value">
                                    @if ($errors->has('value'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('value') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="value" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>
                                <div class="col-md-6">
                                    <textarea name="description" id="description" class="form-control{{ $errors->has('Description') ? ' is-invalid' : '' }}" name="description"></textarea>
                                    @if ($errors->has('description'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="document_id" class="col-md-4 col-form-label text-md-right">{{ __('Document') }}</label>

                                <div class="col-md-6">
                                    <input name="document_id" id="document_id" type="file" class="form-control{{ $errors->has('Document') ? ' is-invalid' : '' }}" name="document_id" value="{{ old('document_id') }}">

                                    @if ($errors->has('document_id'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('document_id') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Save') }}
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