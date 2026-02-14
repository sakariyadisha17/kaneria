@extends('layouts.master')

@section('title', 'Discharge')

@section('content')
<div class="app-content content">
  <div class="content-overlay"></div>
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Discharge Summary</h3>
        <div class="breadcrumbs-top">
          <div class="breadcrumb-wrapper">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="{{ URL('medical_officer/dashboard') }}">Dashboard</a>
              </li>
              <li class="breadcrumb-item active">Discharge Summary</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    @include('notifications')
    <div class="content-body">
      <section class="section">
        <div class="row justify-content-center">
          <div class="col-lg-12 col-md-12">
            <div class="text-right">
              <a href="{{ url('medical_officer/discharge_summary') }}" class="btn btn-info">
                <i class="fas fa-arrow-left"></i> Back
              </a>
              {{-- <button class="btn btn-primary" id="printBtn">
                <i class="fas fa-print"></i> Print
              </button> --}}
            </div>
            <div class="card shadow-lg">
              <div class="card-body">
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs" id="dischargeTabs" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="tab1" data-toggle="tab" href="#discharge-summary" role="tab">Discharge Summary</a>
                  </li>
                </ul>
                <!-- Tab Content -->
                <div class="tab-content mt-3">
                  <div class="tab-pane fade show active" id="discharge-summary" role="tabpanel">
                    <form id="summaryForm" data-patient-id="{{ $patient->id }}">
                      <div class="form-group col-6">
                          <label for="complaints">Complaints and Investigation</label>
                          <textarea class="form-control" id="complaints" name="complaints" rows="4" required></textarea>
                      </div>
                      <div class="form-group col-6">
                        <label for="relative_name">Relative Name</label>
                        <textarea class="form-control" id="relative_name" name="relative_name" rows="4" required></textarea>
                    </div>
                    <div class="form-group col-6">
                      <label for="relative_num">Relative Number</label>
                      <textarea class="form-control" id="relative_num" name="relative_num" rows="4" required></textarea>
                  </div>
                      <div class="form-group col-6">
                          <label for="discharge_medication">Discharge Medication</label>
                          <div id="medicine-container">
                              <div class="input-group mb-2">
                                  <input type="text" name="discharge_medication[]" class="form-control medicine-input" placeholder="Enter Medicine">
                                  <button type="button" class="btn btn-success add-medicine">+</button>
                              </div>
                          </div>
                      </div>
                  
                      <button type="submit" class="btn btn-success">Save Summary</button>
                      <button id="printSummary" class="btn btn-primary" style="display: none;">Print Summary</button>
                  </form>
                  
                  </div>
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
@endsection

@section('additionaljs')
<script>
$(document).ready(function() {
    // Add new medicine input
    $(document).on('click', '.add-medicine', function() {
        let newInput = `
            <div class="input-group mb-2">
                <input type="text" name="discharge_medication[]" class="form-control medicine-input" placeholder="Enter Medicine">
                <button type="button" class="btn btn-danger remove-medicine">-</button>
            </div>`;
        $('#medicine-container').append(newInput);
    });

    // Remove medicine input
    $(document).on('click', '.remove-medicine', function() {
        $(this).closest('.input-group').remove();
    });

    // Submit form with JSON data
    $('#summaryForm').submit(function(e) {
        e.preventDefault();

        var patientId = $(this).data('patient-id');

        // Collect medicine values as an array
        let medicines = [];
        $('.medicine-input').each(function() {
            let medicine = $(this).val().trim();
            if (medicine) {
                medicines.push(medicine);
            }
        });

        var formData = {
            complaints: $('#complaints').val(),
            relative_name: $('#relative_name').val(),
            relative_num: $('#relative_num').val(),
            discharge_medication: medicines, // Store medicines as array
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        $.ajax({
            url: "/medical_officer/discharge_summary/patients/" + patientId + "/save-discharge-summary",
            type: "POST",
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $('#printSummary').show();
                } else {
                    alert('Failed to save discharge summary.');
                }
            },
            error: function() {
                alert('Something went wrong!');
            }
        });
    });
    
    $('#printSummary').click(function() {
          var patientId = $('#summaryForm').data('patient-id');
          window.open("/medical_officer/discharge_summary/patients/" + patientId + "/print-discharge-summary", "_blank");
      });
});


</script>
@endsection
