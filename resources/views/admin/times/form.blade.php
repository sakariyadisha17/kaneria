@extends('layouts.master')
@section('title')
    Time
@endsection
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Time</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL('/admin/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ URL('admin/times') }}">Time</a></li>
                            <li class="breadcrumb-item active">
                                <a href="#">{{ isset($time) ? 'Edit' : 'Add' }}</a>
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
                                <h4 class="card-title" id="horz-layout-basic">Time Details</h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    @include('notifications')
                                    
                                    <!-- Form -->
                                    <form method="POST" 
                                        action="{{ isset($time) ? url('admin/times/' . $time->id . '/edit') : url('admin/times/add') }}" 
                                        id="time-form">
                                        
                                        @csrf
                                        @if(isset($time))
                                            @method('PUT')
                                        @endif

                                        <input type="hidden" name="id" value="{{ $time->id ?? 0 }}">

                                        <div class="form-body">
                                            <!-- Time Input -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="time">Set Time:</label>
                                                <div class="col-md-6">
                                                    <input type="time" 
                                                        class="form-control" 
                                                        name="time" 
                                                        id="time" 
                                                        placeholder="Select Time" 
                                                        value="{{ old('time', isset($time) ? date('H:i', strtotime($time->time)) : '') }}" 
                                                        required>
                                                    <span class="text-danger">{{ $errors->first('time') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Timesheet Button (Only for Edit) -->
                                        @if(isset($time))
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#timeModal">
                                                Add Timesheet
                                            </button>
                                        @endif

                                        <!-- Form Actions -->
                                        <div class="form-actions">
                                            <a href="{{ url('admin/times') }}" class="btn btn-warning mr-1">
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
<!-- Add Time Modal -->
 @if(isset($time))
<div class="modal fade" id="timeModal" tabindex="-1" role="dialog" aria-labelledby="timeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ url('admin/times/' . $time->id . '/add-timesheets') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="timeModalLabel">Add Timesheets</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="timesheet_count">Number of Entries</label>
                        <input type="number" class="form-control" name="timesheet_count" id="timesheet_count" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Timesheets</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End Add Time Modal -->
@endif
@endsection

@section('additionalcss')

@endsection

@section('additionaljs')

@endsection
