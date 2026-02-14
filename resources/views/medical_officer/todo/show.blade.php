@extends('layouts.master')
@section('title', 'To do Details')

@section('content')
<div class="app-content content">
  <div class="content-overlay"></div>
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">To do list</h3>
        <div class="row breadcrumbs-top">
          <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ URL('medical_officer/dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">To do list</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    
    <div class="content-body">
      <section id="column">
        <div class="row">
          <div class="col-12">
            <div class="text-right mb-2">
              <a href="{{ url('medical_officer/to_do_list') }}" class="btn btn-info">
                <i class="fas fa-arrow-left"></i> Back
              </a>
              <button class="btn btn-success" id="printBtn">
                <i class="fas fa-print"></i> Print
              </button>
            </div>
            
            <!-- Patient Details Card -->
            <div id="patientCard">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Patient To Do List Details</h4>
                </div>
                <div class="card-body">
                  <p><strong>Patient Name :</strong> {{ $patient->fullname }} ({{ $patient->patient_id }})</p><br>
                  <form action="{{ url('medical_officer/save_todo') }}" method="POST">
                        @csrf
                        
                        <!-- Laboratory Section -->
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Laboratory:</label>
                            <div class="col-md-4">
                            <textarea class="form-control" name="laboratory_notes" placeholder="Enter details..."></textarea>
                            </div>
                            <div class="col-md-3">
                            <input type="text" class="form-control timepicker" name="laboratory_time" placeholder="Select time">
                            </div>
                            <div class="col-md-2">
                            <input type="checkbox" name="status" value="1">
                            <!-- Completed -->
                            </div>
                        </div>
                        
                        
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Radio:</label>
                            <div class="col-md-4">
                            <textarea class="form-control" name="radio_details" placeholder="Enter radio details..."></textarea>
                            </div>
                            <div class="col-md-3">
                            <input type="text" class="form-control timepicker" name="radio_time" placeholder="Select time">
                            </div>
                            <div class="col-md-2">
                            <input type="checkbox" name="status" value="1"> 
                            <!-- Completed -->
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Task
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
@endsection

@section('additionalcss')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('additionaljs')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    flatpickr(".timepicker", {
      enableTime: true,
      noCalendar: true,
      dateFormat: "h:i K",
      time_12hr: true
    });
  });
</script>
@endsection