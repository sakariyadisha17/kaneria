@extends('layouts.master')
@section('title', 'Patient Dose Details')

@section('content')
<div class="app-content content">
  <div class="content-overlay"></div>
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Dose Details</h3>
        <div class="row breadcrumbs-top">
          <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ URL('nursing/dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Dose Details</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    
    <div class="content-body">
      <section id="column">
        <div class="row">
          <div class="col-12">
            <div class="text-right mb-3">
              <a href="{{ url('nursing/patient_dose') }}" class="btn btn-info">
                <i class="fas fa-arrow-left"></i> Back
              </a>
              <button class="btn btn-success" id="printBtn">
                <i class="fas fa-print"></i> Print
              </button>
            </div>
            
            <!-- Patient Details Card (Header) -->
            <div id="patientCard">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Patient Details</h4>
                </div>
                <div class="card-body">
                  <p><strong>Patient Name :</strong> {{ $patient->fullname }}</p>
                  <p><strong>Patient ID :</strong> {{ $patient->patient_id }}</p>
                  <p><strong>Age :</strong> {{ $patient->age ?? 'N/A' }}</p>
                  <div class="d-flex justify-content-center align-items-center gap-6">
                    <h5 class="mb-0">Filter by Date :</h5>
                    <!-- Date input is initially blank -->
                    <input type="date" id="dateFilter" class="form-control col-md-4">
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Data Container for Tables -->
            <div id="dataContainer">
              <!-- Vital Table -->
              <div id="vitalTableContainer" class="mt-3">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Vital Signs</h4>
                  </div>
                  <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered" id="vitalTable">
                      <thead>
                        <tr>
                          <th>Date & Time</th>
                          <th>BP</th>
                          <th>Pulse</th>
                          <th>SPO2</th>
                          <th>Temp</th>
                          <th>ECG</th>
                          <th>RBS</th>
                          <th>Report</th>
                          <th>Complaint</th>
                          <th>Patient Note</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($vital as $v)
                          <tr data-date="{{ \Carbon\Carbon::parse($v->datetime)->format('Y-m-d') }}">
                            <td>{{ \Carbon\Carbon::parse($v->datetime)->format('d-m-Y H:i') }}</td>
                            <td>{{ $v->bp }}</td>
                            <td>{{ $v->pulse }}</td>
                            <td>{{ $v->spo2 }}</td>
                            <td>{{ $v->temp }}</td>
                            <td>{{ $v->ecg }}</td>
                            <td>{{ $v->rbs }}</td>
                            <td>{{ $v->report ?? '-' }}</td>
                            <td>{{ $v->complaint ?? '-' }}</td>
                            <td>{{ $v->patient_note ?? '-' }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  </div>
                </div>
              </div>
              
              <!-- Medicine Table -->
              <div id="medicineTableContainer" class="mt-3">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Medicines</h4>
                  </div>
                  <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered" id="medicineTable">
                      <thead>
                        <tr>
                          <th>Date & Time</th>
                          <th>Medicine Name</th>
                          <th>Quantity</th>
                          <th>Frequency</th>
                          <th>Note</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($medicines as $medicine)
                          <tr data-date="{{ \Carbon\Carbon::parse($medicine->datetime)->format('Y-m-d') }}">
                            <td>{{ \Carbon\Carbon::parse($medicine->datetime)->format('d-m-Y H:i') }}</td>
                            <td>{{ $medicine->name }}</td>
                            <td>{{ $medicine->quantity }}</td>
                            <td>{{ $medicine->frequency }}</td>
                            <td>{{ $medicine->note }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  </div>

                </div>
              </div>
            </div>
            <!-- End Data Container -->
            
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
  // Initialize flatpickr for the date input
  flatpickr("#dateFilter", { dateFormat: "Y-m-d" });
</script>
<script>
$(document).ready(function() {
  // Function to filter table rows in both tables based on the selected date
  function filterTable() {
    var selectedDate = $('#dateFilter').val();
    // For the vital table:
    $('#vitalTable tbody tr').each(function() {
      var rowDate = $(this).attr('data-date');
      if (selectedDate) {
        $(this).toggle(rowDate === selectedDate);
      } else {
        $(this).show();
      }
    });
    // For the medicine table:
    $('#medicineTable tbody tr').each(function() {
      var rowDate = $(this).attr('data-date');
      if (selectedDate) {
        $(this).toggle(rowDate === selectedDate);
      } else {
        $(this).show();
      }
    });
  }
  
  // Bind change event to the date input
  $('#dateFilter').on('change', function() {
    filterTable();
  });
  
  // Print functionality with updated attractive layout
  $('#printBtn').on('click', function() {
    var selectedDate = $('#dateFilter').val();
    
    // Prepare Vital Table for printing
    var vitalClone;
    if (selectedDate) {
      vitalClone = $('#vitalTable tbody tr:visible').closest('table').clone();
    } else {
      vitalClone = $('#vitalTable').clone();
    }
    
    // Prepare Medicine Table for printing
    var medicineClone;
    if (selectedDate) {
      medicineClone = $('#medicineTable tbody tr:visible').closest('table').clone();
    } else {
      medicineClone = $('#medicineTable').clone();
    }
    
    // Build custom header for print
    var patientName = '{{ $patient->fullname }}';
    var patientID = '{{ $patient->patient_id }}';
    var patientAge = '{{ $patient->age ?? 'N/A' }}';
    var printHeader = '<div class="header">' +
                        '<div class="patient-info">Patient: ' + patientName + ' (ID: ' + patientID + ') | Age: ' + patientAge + '</div>' +
                        '</div>';
    
    // Build table sections for print
    var vitalTableHtml = '<div class="table-section"><h4>Vital Signs & Reports</h4>' +
                           vitalClone.prop('outerHTML') + '</div>';
    
    var medicineTableHtml = '<div class="table-section"><h4>Medicines</h4>' +
                              medicineClone.prop('outerHTML') + '</div>';
    
    var printContents = printHeader + vitalTableHtml + medicineTableHtml;
    
    // Open new window for printing and include inline CSS for an attractive layout
    var printWindow = window.open('', '', 'height=800,width=1000');
    printWindow.document.write('<html><head><title>Print</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('body { font-family: Arial, sans-serif; margin: 20px; }');
    printWindow.document.write('.header { text-align: center; margin-bottom: 20px; }');
    printWindow.document.write('.patient-info { font-size: 20px; font-weight: bold; margin-bottom: 10px; }');
    printWindow.document.write('.table-section { margin-bottom: 30px; }');
    printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 10px; }');
    printWindow.document.write('table, th, td { border: 1px solid #ddd; }');
    printWindow.document.write('th, td { padding: 8px; text-align: left; }');
    printWindow.document.write('th { background-color: #f2f2f2; }');
    printWindow.document.write('h4 { margin-bottom: 10px; }');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(printContents);
    printWindow.document.write('</body></html>');
    
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
  });
});
</script>
@endsection
