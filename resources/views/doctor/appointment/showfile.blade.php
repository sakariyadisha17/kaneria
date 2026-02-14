@extends('layouts.master')
@section('title')
    Appointment
@endsection
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Patient File</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL('doctor/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Patient File</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right">
            <div class="btn-group">
                <a href="{{ url('doctor/appointments') }}" class="btn btn-info" style="border-radius: 50px;"><i class="fas fa-arrow-left"></i></a> 
            </div>
        </div>
         
        <div class="content-body">
            <section id="column">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Patient File</h4>
                                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="feather icon-minus"></i></a></li>
                                        <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card-content collapse show">
                                <div class="card-body card-dashboard">
                                <a href="{{ route('doctor.generate.pdf', $id) }}" class="btn btn-primary mb-3">Generate PDF</a>

                                    <table class="table table-striped table-bordered" id="doctor-table">
                                        <thead>
                                            <tr>
                                                <th>File</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($patientFiles as $patient)
                                                <tr>
                                                    <td>{{ $patient->file_name ?? 'No File' }}</td>
                                                    <td>
                                                        <a href="{{ asset('storage/' . $patient->file_path) }}" class="btn btn-custom" target="_blank">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    
                                   
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<style>
.btn-custom {
    color: #007bff;
    font-size: 16px;
    padding: 10px 20px;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.btn-custom i {
    margin-right: 8px;
}

.btn-back {
    display: inline-block;
    background-color: #f0ad4e;
    color: white;
    font-size: 16px;
    padding: 10px 20px;
    border-radius: 50px;
    margin-top: 20px;
    text-decoration: none;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn-back:hover {
    background-color: #ec971f;
    transform: translateY(-2px);
}

.btn-back i {
    margin-right: 8px;
}
</style>
@endsection

@section('additionaljs')

@endsection
