@extends('layouts.master')
@section('title')
    Bed
@endsection
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Bed</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL('/admin/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ URL('admin/beds') }}">Bed</a></li>
                            <li class="breadcrumb-item active">
                                <a href="#">{{ isset($bed) ? 'Edit' : 'Add' }}</a>
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
                                <h4 class="card-title" id="horz-layout-basic">Bed Details</h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    @include('notifications')

                                    <form method="POST" action="{{ isset($bed) ? url('admin/beds/' . $bed->id . '/edit') : url('admin/beds/add') }}" id="charge-form">
                                        @csrf
                                        @if(isset($bed))
                                            @method('PUT')
                                        @endif

                                        <input type="hidden" name="id" value="{{ $bed->id ?? 0 }}">

                                        <div class="form-body">

                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="room_id"> Select Room:</label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="room_id" id="room_id" required>
                                                    <option value="">Select Room </option>
                                                    @foreach($rooms as $room)
                                                        <option value="{{ $room->id }}" {{ (old('room_id', $bed->room_id ?? '') == $room->id) ? 'selected' : '' }}>
                                                            {{ $room->type }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger">{{ $errors->first('room_id') }}</span>
                                            </div>
                                        </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="bed_no"> Bed Number:</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="bed_no" id="bed_no" 
                                                        placeholder="Enter bed Number" 
                                                        value="{{ old('bed_no', $bed->bed_no ?? '') }}" 
                                                        required>
                                                    <span class="text-danger">{{ $errors->first('bed_no') }}</span>
                                                </div>
                                            </div>
                                        
                                        </div>


                                        <div class="form-actions">
                                            <a href="{{ url('admin/beds') }}" class="btn btn-warning mr-1">
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
