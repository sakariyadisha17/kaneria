@extends('layouts.master')
@section('title', 'Reports')

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Reports</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL('receptionist/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Reports</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section id="reports-section">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Download Reports</h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <!-- Single Date Selection -->
                                    <h5>Select Date</h5>
                                    <form action="{{ route('receptionist.appointments.exportCSV') }}" method="GET" class="mb-4">
                                        <div class="form-group row">
                                            <label for="single_date" class="col-md-2 col-form-label">Select Date:</label>
                                            <div class="col-md-4">
                                                <input type="date" name="selecteddate" id="single_date" class="form-control" required>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-info">
                                                    <i class="fa fa-download"></i> Download CSV
                                                </button>
                                            </div>
                                        </div>
                                    </form>


                                    <!-- Custom Date Range Selection -->
                                    <h5>Select a Date Range</h5>
                                    <form action="{{ route('receptionist.appointments.exportCustomCSV') }}" method="GET">
                                        <div class="form-group row">
                                            <label for="from_date" class="col-md-2 col-form-label">From Date:</label>
                                            <div class="col-md-4">
                                                <input type="date" name="from_date" id="from_date" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="to_date" class="col-md-2 col-form-label">To Date:</label>
                                            <div class="col-md-4">
                                                <input type="date" name="to_date" id="to_date" class="form-control" required>
                                            </div>
                                       
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fa fa-download"></i> Download CSV
                                                </button>
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.19/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@endsection

@section('additionaljs')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.19/dist/sweetalert2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#single_date", {dateFormat: "Y-m-d"});
    flatpickr("#from_date", {dateFormat: "Y-m-d"});
    flatpickr("#to_date", {dateFormat: "Y-m-d"});
</script>
@endsection
