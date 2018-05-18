@extends('layouts.app')

@section('content')
    <div class="container">
        <form id="form-change-password" method="POST" action="{{route('users.changePassword')}}">
                <label for="old-password" class="col-sm-4 control-label">Old Password</label>
                <div class="col-sm-8">
                    <div class="form-group">
                        <input type="password" name="old_password" class="form-control" id="oldPassword" placeholder="Current Password">
                    </div>
                </div>
                <label for="New password" class="col-sm-4 control-label">Password</label>
                <div class="col-sm-8">
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" id="newpassword" placeholder="New Password">
                    </div>
                </div>
                <label for="password_confirmation" class="col-sm-4 control-label">Re-enter Password</label>
                <div class="col-sm-8">
                    <div class="form-group">
                        <input type="password" name="password_confirmation" class="form-control" id="passwordConfirmation" placeholder="Password Coonfirmation">
                    </div>
                </div>
            <div class="form-group">
                <div class="col-sm-offset-5 col-sm-6">
                    <button type="submit" class="btn btn-danger">Submit</button>
                </div>
            </div>
        </form>
    </div>

@endsection