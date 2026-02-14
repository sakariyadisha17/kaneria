@extends('layouts.master')
@section('title')
   Refer Doctor
@endsection
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Refer Doctor</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL('/admin/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ URL('admin/refer_doctor') }}"> Refer Doctors</a></li>
                            <li class="breadcrumb-item active">
                                <a href="#">{{ isset($refer_doctor) ? 'Edit' : 'Add' }}</a>
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
                                <h4 class="card-title" id="horz-layout-basic">ReferDoctor Details</h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    @include('notifications')

                                    <form method="POST" action="{{ isset($refer_doctor) ? url('admin/refer_doctor/' . $refer_doctor->id . '/edit') : url('admin/refer_doctor/add') }}" id="doctor-form">
                                        @csrf
                                        @if(isset($refer_doctor))
                                            @method('PUT')
                                        @endif

                                        <input type="hidden" name="id" value="{{ $refer_doctor->id ?? 0 }}">

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
                                                            value="{{ old('name', $refer_doctor->name ?? '') }}" 
                                                            required>
                                                    </div>
                                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                                </div>
                                            </div>



                                            <!-- phone -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="phone">Phone:</label>
                                                <div class="col-md-6">
                                                    <input type="phone" class="form-control" name="phone" id="phone" 
                                                        placeholder="phone Address" 
                                                        value="{{ old('phone', $refer_doctor->phone ?? '') }}" 
                                                        required>
                                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                                </div>
                                            </div>


                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="address">Address:</label>
                                                <div class="col-md-6">
                                                    <textarea 
                                                        name="address" 
                                                        class="form-control" 
                                                        placeholder="Enter Address" 
                                                        required>{{ old('address', $refer_doctor->address ?? '') }}</textarea>
                                                    <span class="text-danger">{{ $errors->first('address') }}</span>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="form-actions">
                                            <a href="{{ url('admin/refer_doctor') }}" class="btn btn-warning mr-1">
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
