@extends('layouts.master')
@section('title')
    Vitals
@endsection
@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <section id="letterhead">
                <div class="card">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <div class="btn-group">
                                        <a href="{{ url('receptionist/vitals') }}" class="btn btn-info" style="border-radius: 50px;">
                                            <i class="fas fa-arrow-left"></i>
                                        </a>
                                    </div>
                                </div>
                                <div>
                                    <button id="printBtn" class="btn btn-success">Print Letterhead</button>
                                </div>
                            </div>

                            <div class="letterhead container p-4" id="letterheadContent">
                                <!-- Your letterhead content here -->
                                <div class="header row mb-4">
                                    <div class="logo col-md-2">
                                        <img src="{{ url('logos/logo.jpg') }}" alt="Kaneria Hospital Logo" class="img-fluid">
                                    </div>
                                    <div class="details col-md-10 text-right">
                                        <h4><strong>Dr. Maulik V. Kaneria</strong></h4>
                                        <p>Consultant Physician</p>
                                        <p>M.D. Medicine</p>
                                        <p>Phone: +91 99133 40805</p>
                                    </div>
                                </div><hr>

                                <!-- Patient Info -->
                                <div class="rx-date row mb-4">
                                    <div class="col-md-8">
                                        <h5 style="margin-left:90px;"> {{ @$vital->patient->fullname }} ({{ @$vital->patient->gender }}) - {{ @$vital->patient->address }}</h5>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <h5 style="margin-left:70px;"><strong>Date :</strong> {{ \Carbon\Carbon::parse(@$vital->created_at)->format('d-m-Y') }}</h5>
                                    </div>
                                </div>

                                <!-- Vitals -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <h5 style="margin-left:70px;"><strong>Vitals : </strong></h5>
                                        <p style="margin-left:70px;">BP : {{ @$vital->bp }}</p>
                                        <p style="margin-left:70px;">P : {{ @$vital->pulse }}</p>
                                        <p style="margin-left:70px;">SPO<sub>2</sub> : {{ @$vital->spo2 }}</p>
                                        <p style="margin-left:70px;">Temperature : {{ @$vital->temp }}</p>
                                        <p style="margin-left:70px;">RS : {{ @$vital->rs }}</p>
                                        <p style="margin-left:70px;">CVS : {{ @$vital->cvs }}</p>
                                        <p style="margin-left:70px;">ECG : {{ @$vital->ecg }}</p>
                                        <p style="margin-left:70px;">RBS : {{ @$vital->cbs }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h5><strong>Additional Info :</strong></h5>
                                        <p>Addition : {{ @$vital->addition }}</p>
                                        <p>C/O : {{ @$vital->complaint }}</p>
                                        <p>Past History : {{ @$vital->past_history }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h5><strong>Diagnisis :</strong></h5>
                                        <p>{{ @$vital->diagnosisName->name ?? 'N/A' }}</p>
                                        <h5><strong>Diet Chart :</strong> </h5>
                                        <p>{{ @$vital->diat }}</p>
                                        <h5><strong>Note : </strong></h5>
                                        <p>{{ @$vital->note }}</p>
                                    </div>
                                </div>

                                <!-- Medicines -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h5><strong style="margin-left:70px;">Medicines :</strong></h5>
                                        <table class="table table-bordered"  style="margin-right:50px;margin-left:70px;">
                                            <thead>
                                                <tr>
                                                    <th>Medicine</th>
                                                    <th>Quantity</th>
                                                    <th>Frequency</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($medicines as $medicine)
                                                    <tr>
                                                        <td>{{ $medicine->name }}</td>
                                                        <td>{{ $medicine->quantity }}</td>
                                                        <td>{{ $medicine->frequency }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Follow-Up -->
                                <div class="row mt-4">
                                    <div class="col-md-8">
                                        <h5 style="margin-left:70px;"> <strong>ફરી બતાવવા ની તારીખ :</strong></h5>
                                        <p style="margin-left:70px;">{{ \Carbon\Carbon::parse(@$vital->next_date)->format('d-m-Y') }}</p>
                                    </div>

                                    <div class="col-md-8">
                                        <h5 style="margin-left:70px;"> <strong>ફરી બતાવવા આવો ત્યારે નીચે બતાવેલ રીપોર્ટ કરાવીને આવવું.</strong></h5>
                                        <p style="margin-left:70px;">{{ @$vital->next_report ?? 'No' }}</p>
                                    </div>

                                    <div class="col-md-4 text-right">
                                        <h5><strong>DR. MAULIK KANERIA</strong></h5>
                                        <h5>M.D. MEDICINE</h5>
                                        <h5>G-47959</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="letterhead container p-4" id="letterheadContent">
                                <!-- Upload Reports Section -->
                                <div class="row mb-4">
                                    <div class="col-12" style="margin-left:70px;">
                                        <h5><strong>Uploaded Reports:</strong></h5>
                                        <div class="row">
                                            @foreach ($patientFiles as $file)
                                                <div class="col-md-3 mb-3">
                                                    <div class="card">
                                                        <div class="card-body text-center">
                                                            @if (in_array(pathinfo($file->file_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                                                <img src="{{ Storage::url($file->file_path) }}" class="img-fluid" alt="Patient File">
                                                            @else
                                                                <p>{{ $file->file_name }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<script>
    document.getElementById('printBtn').addEventListener('click', function () {
        var content = document.getElementById('letterheadContent');

        var printWindow = window.open('', '', 'height=650,width=800');

        printWindow.document.write('<html><head><title>Print Letterhead</title>');
        printWindow.document.write(`
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                }
              .container {
                    max-width: 100%;
                    padding: 20px;
                }

                h4, h5, p {
                    margin: 5px 0;
                    padding: 0;
                }
               table {
                    width: 80%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                    
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                .text-right {
                    text-align: right;
                }
                .row {
                    display: flex;
                    flex-wrap: wrap;
                    margin: 0 -15px;
                }
                .col-md-2, .col-md-4, .col-md-6, .col-md-8, .col-md-10 {
                    padding: 15px;
                    box-sizing: border-box;
                }
                .col-md-2 { flex: 0 0 16.6667%; }
                .col-md-4 { flex: 0 0 33.3333%; }
                .col-md-6 { flex: 0 0 50%; }
                .col-md-8 { flex: 0 0 66.6667%; }
                .col-md-10 { flex: 0 0 83.3333%; }

                /* Print-specific styles */
              @media print {
                /* Hide header and logo sections completely */
                .header, .logo, .btn-group, .btn-success, hr {
                    display: none !important;
                }

                /* Ensure only vital patient details and content are printed */
                #letterheadContent > .rx-date, 
                #letterheadContent > .row:not(.header), 
                #letterheadContent .table {
                    display: block;
                   
                }

                /* Add margin to the top side for spacing */
                body {
                    margin-top: 120px; /* Adjust the space on top */
                    font-size: 12px; /* Adjust font size for print */
                    line-height: 1.5; /* Adjust line height for better readability */
                    font-size: 14px;
                }

                /* Ensure content is displayed properly on a single page */
                .container {
                    padding: 10px;
                }

                .row {
                    margin: 0;
                }

                /* Adjust column widths for better fit */
                .col-md-2, .col-md-4, .col-md-6, .col-md-8, .col-md-10 {
                    flex: 1;
                    padding: 5px;
                }

                /* Ensure all table data fits on a single page */
                 .table {
                    width: 80%;
                    table-layout: fixed; /* Ensures content fits without overflowing */
                    font-size: 14px; /* Adjust table font size */
                    margin-left: 70px; /* Add left margin for the table */
                }

                .table th, .table td {
                    padding: 8px; /* Reduce padding to fit content */
                    text-align: left;
                    font-size: 14px; /* Adjust font size in the table */
                }

                /* Adjust margins and spacing for a clean print */
                .letterhead {
                    margin-top: 0;
                    padding-top: 0;
                }

                /* Ensure no content is cut off */
                @page {
                    size: A4;
                    margin: 0; /* Set page margins to zero to maximize the content space */
                }

                /* Prevent content overflow */
                .table tbody {
                    page-break-inside: avoid; /* Ensure tables don't break across pages */
                }
            }
            </style>
        `);
        printWindow.document.write('</head><body>');
        printWindow.document.write(content.innerHTML);  
        printWindow.document.write('</body></html>');

        printWindow.document.close(); 
        printWindow.print();  
    });
</script>
@endsection
