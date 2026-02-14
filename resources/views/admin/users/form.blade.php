@extends('layouts.master')
@section('title')
    User
@endsection
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">User</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ URL('/admin/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ URL('admin/users') }}">Users</a></li>
                            <li class="breadcrumb-item active">
                                <a href="#">{{ isset($user) ? 'Edit' : 'Add' }}</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section id="horizontal-form-layouts">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" id="horz-layout-basic">User Details</h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    @include('notifications')

                                    <form method="POST" action="{{ isset($user) ? url('admin/users/' . $user->id . '/edit') : url('admin/users/add') }}" id="user-form">
                                        @csrf
                                        @if(isset($user))
                                            @method('PUT')
                                        @endif

                                        <input type="hidden" name="id" value="{{ $user->id ?? 0 }}">

                                        <div class="form-body">
                                              <!-- Role Dropdown -->
                                              <div class="form-group row">
                                                <label class="col-md-3 label-control" for="role">Role:</label>
                                                <div class="col-md-6">
                                                    <select name="role" id="role" class="form-control select2" required>
                                                        <option value="">Select Role</option>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}" 
                                                                {{ isset($user) && $user->roles->pluck('id')->contains($role->id) ? 'selected' : '' }}>
                                                                {{ $role->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger">{{ $errors->first('role') }}</span>
                                                </div>
                                            </div>
                                            <!-- Username -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="name">User Name:</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="name" id="name" 
                                                        placeholder="User Name" 
                                                        value="{{ old('name', $user->name ?? '') }}" 
                                                        required>
                                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                                </div>
                                            </div>

                                            <!-- Email -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="email">Email:</label>
                                                <div class="col-md-6">
                                                    <input type="email" class="form-control" name="email" id="email" 
                                                        placeholder="Email Address" 
                                                        value="{{ old('email', $user->email ?? '') }}" 
                                                        required>
                                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                                </div>
                                            </div>

                                            <!-- Password -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="password">Password:</label>
                                                <div class="col-md-6">
                                                    <input type="password" class="form-control" name="password" id="password" 
                                                        placeholder="Password" 
                                                        {{ isset($user) ? '' : 'required' }}>
                                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                                </div>
                                            </div>

                                          
                                        </div>

                                        <div class="form-actions">
                                            <a href="{{ url('admin/users') }}" class="btn btn-warning mr-1">
                                                <i class="feather icon-x"></i> Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i> Save
                                            </button>
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
<!-- END: Content-->

@endsection

@section('additionalcss')
@endsection

@section('additionaljs')

@endsection
