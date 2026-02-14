@extends('layouts.master')
@section('title')
    Print
@endsection
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        @include('notifications')
        <div class="content-body">
            <section id="column">
                <div class="row">
                    <div class="col-12">
                        <div class="text-right mb-2">
                            <div class="btn-group">
                                <a href="{{ url('receptionist/appointments') }}">
                                    <button class="btn btn-primary">
                                        <i class="fas fa-arrow-left"></i> Back
                                    </button>
                                </a>
                                <button id="printBtn" class="btn btn-info">
                                    <i class="fas fa-print"></i> Print
                                </button>
                            </div>
                        </div>
                        <div class="card" id="printableArea">
                            <div class="letterhead container p-4">
                                <!-- Header Section -->
                                <div class="header row mb-4">
                                    <div class="logo col-md-2">
                                        <img src="{{ url('logos/logo.jpg') }}" 
                                            alt="Kaneria Hospital Logo" 
                                            class="logo-image">
                                    </div>
                                    <div class="details col-md-10 text-right">
                                        <h3><strong>Dr. Maulik V. Kaneria</strong></h3>
                                        <p style="font-weight: 650;font-size: 16px;">Consultant Physician</p>
                                        <p style="font-weight: 650;font-size: 16px;">M.D. Medicine</p>
                                        <p style="font-weight: 650;font-size: 16px;">Phone: +91 99133 40805</p>
                                    </div>
                                </div>

                                <!-- Patient Info -->
                                <div class="rx-date row mb-4">
                                    <div class="col-md-6">
                                        <h4 style="font-weight: 650;"><b>Name :</b> {{ @$patient->fullname }} (#{{ @$patient->patient_id }})</h4>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <h4 style="font-weight: 650;"><b>Date :</b> {{ \Carbon\Carbon::parse(@$patient->created_at)->format('d-m-Y') }}</h4>
                                    </div>
                                </div>

                                <!-- Table Section -->
                                <h4><b><center>OPD Bill</center></b></h4><br>
                                <table class="table table-bordered" style="font-weight: 650; font-size: 16px;">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $calculatedTotal = 0; @endphp

                                        @if (!empty($chargeDetails))
                                            @foreach ($chargeDetails as $charge)
                                                <tr>
                                                    <td>{{ $charge['type'] }}</td>
                                                    <td>{{ number_format($charge['amount'], 2) }}</td>
                                                </tr>
                                                @php $calculatedTotal += $charge['amount']; @endphp
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="2" class="text-center">No charges available</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                    <tfoot >
                                        <tr>
                                            <th style="text-align : right;">Amount</th>
                                            <th style="font-weight:700;">{{ number_format($amount, 2) }}</th>
                                        </tr>
                                        @if ($returnAmount > 0)
                                            <tr>
                                                <th style="text-align: right;">Return Amount</th>
                                                <th style="font-weight: 700;">{{ number_format($returnAmount, 2) }}</th>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th style="text-align : right;">Total Amount</th>
                                            <th style="font-weight:700;">{{ number_format($totalAmount, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>



                                <!-- Signature Section -->
                                <div class="row mt-5">
                                    <div class="col-md-12 text-right">
                                        <p style="font-weight: 650;font-size: 16px;"> Signature </p>
                                        <p style="font-weight: 650;font-size: 16px;">______________</p>

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
<style>
.logo-image {
    width: 400px; 
    max-width: 300px; 
    height: auto;
}

@media print {
    .logo-image {
        width: 300px; 
        max-width: 400px; 
        height: auto; 
    }

    .btn, .btn-group {
        display: none; 
    }

    body {
        color : black;
    }

}
</style>
@endsection

@section('additionaljs')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Print only the specific card box
    document.getElementById('printBtn').addEventListener('click', function() {
        const printContents = document.getElementById('printableArea').innerHTML;
        const originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;

        // Reload the page to restore JavaScript functionality
        location.reload();
    });
</script>
@endsection
