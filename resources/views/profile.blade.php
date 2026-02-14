@extends('layouts.master')
@section('title')
    Profile
@endsection
@section('content')
<!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row"></div>
            <div class="content-body">
                <!-- users edit start -->
                <section class="users-edit">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <ul class="nav nav-tabs mb-2" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center @if(!Session::has('tab') || Session::get('tab') != '2') active @endif" id="edit_profile-tab" data-toggle="tab"
                                            href="#edit_profile" aria-controls="edit_profile" role="tab" aria-selected="true">
                                        <i class="feather icon-user mr-25"></i><span class="d-none d-sm-block">Update Profile</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link d-flex align-items-center @if(Session::has('tab') && Session::get('tab') == '2') active @endif" id="change_password-tab" data-toggle="tab"
                                            href="#change_password" aria-controls="change_password" role="tab" aria-selected="false">
                                            <i class="feather icon-info mr-25"></i><span class="d-none d-sm-block">Change Password</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane @if(!Session::has('tab') || Session::get('tab') != '2') active @endif" id="edit_profile" aria-labelledby="edit_profile-tab" role="tabpanel">
                                        @include('notifications')
                                        <!-- users edit account form start -->
                                        <form novalidate method="post" action="{{ URL('update_profile') }}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Name</label>
                                                            <input type="text" name="name" class="form-control" placeholder="Enter Name" value="{{ Auth::user()->name }}" required data-validation-required-message="Name is required">
                                                            @if ($errors->has('name'))
                                                                <div class="text-danger">{{ $errors->first('name') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>E-mail</label>
                                                            <input type="email" name="email" class="form-control" placeholder="Enter Email" value="{{ Auth::user()->email }}" required data-validation-required-message="Email is required">
                                                            @if ($errors->has('email'))
                                                                <div class="text-danger">{{ $errors->first('email') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                                                    <button type="submit" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1">Save Changes</button>
                                                    <!-- <button type="reset" class="btn btn-light">Cancel</button> -->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane @if(Session::has('tab') && Session::get('tab') == '2') active @endif" id="change_password" aria-labelledby="change_password-tab" role="tabpanel">
                                        <form novalidate method="post" action="{{ URL('change_password') }}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Current Password</label>
                                                            <input type="password" name="current_password" class="form-control" placeholder="Enter Current Password" value="" required data-validation-required-message="Current Password is required">
                                                            @if ($errors->has('current_password'))
                                                                <div class="text-danger">{{ $errors->first('current_password') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>New Password</label>
                                                            <input type="password" name="password" class="form-control" placeholder="Enter New Password" value="" required data-validation-required-message="New Password is required">
                                                            @if ($errors->has('password'))
                                                                <div class="text-danger">{{ $errors->first('password') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label>Confirm New Password</label>
                                                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm New Password" value="" required data-validation-required-message="New Password is required">
                                                            @if ($errors->has('password_confirmation'))
                                                                <div class="text-danger">{{ $errors->first('password_confirmation') }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                                                    <button type="submit" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1">Update Password</button>
                                                    <!-- <button type="reset" class="btn btn-light">Cancel</button> -->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection

@section('additionalcss')
    
@endsection
@section('additionaljs')
    
@endsection