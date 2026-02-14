@extends('layouts.master')
@section('title')
    Doctor
@endsection
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Doctor</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL('/admin/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ URL('admin/doctors') }}">Doctors</a></li>
                            <li class="breadcrumb-item active">
                                <a href="#">{{ isset($doctor) ? 'Edit' : 'Add' }}</a>
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
                                <h4 class="card-title" id="horz-layout-basic">Doctor Details</h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    @include('notifications')
                                    <form method="POST" action="{{ isset($doctor) ? url('admin/doctors/' . $doctor->id . '/edit') : url('admin/doctors/add') }}" id="doctor-form">
                                        @csrf
                                        @if(isset($doctor))
                                            @method('PUT')
                                        @endif

                                        <input type="hidden" name="id" value="{{ $doctor->id ?? 0 }}">
                                        <div class="form-body">
                                            <!-- doctor name -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="name"> Name:</label>
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <!-- Small part for "Dr." -->
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Dr.</span>
                                                        </div>
                                                        <!-- Main input for name -->
                                                        <input type="text" class="form-control" name="name" id="name" 
                                                            placeholder="Enter name of doctor" 
                                                            value="{{ isset($doctor) ? str_replace('Dr.', '', $doctor->name) : '' }}">
                                                    </div>
                                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                                </div>
                                            </div>

                                            <!-- phone -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="phone">Phone:</label>
                                                <div class="col-md-6">
                                                    <input type="phone" class="form-control" name="phone" id="phone" 
                                                        placeholder="Phone Number" 
                                                        value="{{ old('phone', $doctor->phone ?? '') }}">
                                                </div>
                                            </div>

                                            <!-- address -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="address">Address:</label>
                                                <div class="col-md-6">
                                                    <textarea 
                                                        name="address" 
                                                        class="form-control" 
                                                        placeholder="Enter Address">{{ old('address', $doctor->address ?? '') }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-actions">
                                            <a href="{{ url('admin/doctors') }}" class="btn btn-warning mr-1">
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
