@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Add new Document') }}</div>

                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" action="{{route('document.store', $movement)}}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group row">
                                <label for="document_file" class="col-md-4 col-form-label text-md-right">{{ __('Document') }}</label>

                                <div class="col-md-6">
                                    <input name="document_file" id="document_file" type="file" class="form-control{{ $errors->has('document_file') ? ' is-invalid' : '' }}" name="document_file" value="{{ old('document_file') }}" required>

                                    @if ($errors->has('document_file'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('document_file') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="document_description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>
                                <div class="col-md-6">
                                    <textarea name="document_description" id="document_description" class="form-control{{ $errors->has('document_description') ? ' is-invalid' : '' }}" name="document_description"></textarea>
                                    @if ($errors->has('document_description'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('document_description') }}</strong>
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