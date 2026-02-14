@extends('layouts.master')
@section('title')
    Dashboard
@endsection

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title mb-0">Patient Report</h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ URL('receptionist/dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Patient Report</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            @include('notifications')

            <div class="content-body">
                <section id="column">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Patient List</h4>
                                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                                        </ul>
                                    </div>
                                    
                                    <div class="card-content collapse show">
                                        <div class="card-body card-dashboard">
                                            {{-- <div class="card-header text-right">
                                                <div class="btn-group">
                                                    <a href="javascript:void(0)" onclick="exportFile('pdf')"
                                                        class="btn btn-primary"><i class="fa fa-plus"></i> PDF</a>
                                                    <a href="javascript:void(0)" onclick="exportFile('csv')"
                                                        class="btn btn-info"><i class="fa fa-plus"></i> CSV</a>
                                                </div>
                                            </div> --}}
                                            <table class="table table-striped table-bordered" id="patientTable">
                                                <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Patient ID</th>
                                                        <th>Time</th>
                                                        <th>Name</th>
                                                        <th>Address</th>
                                                        <th>Phone</th>
                                                        <th>Amount</th>
                                                        <th>payment_type</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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
<link rel="stylesheet" href="{{ url('plugins/datatables/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">


@endsection

@section('additionaljs')
<script type="text/javascript" src="{{ url('plugins/datatables/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ url('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script>
    const selectedDate  = @json(request('date'));
    const selectedMonth = @json(request('month'));
    const selectedType  = @json(request('type'));
    const selectedFinancialYear = @json(request('financial_year'));
</script>

<script type="text/javascript">
    $(document).ready(function () {
        var table = $('#patientTable').DataTable({
    
        processing: true,
        serverSide: true,
            
        ajax: {
            url: "{{ route('receptionist.patient.list.data') }}",
            data: function (d) {
                d.date  = selectedDate;
                d.month = selectedMonth;
                d.type  = selectedType;
                d.financial_year = selectedFinancialYear;
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'patient_id', name: 'patients.patient_id' },
            { data: 'time', name: 'advance_booking.time' },
            { data: 'fullname', name: 'patients.fullname' },
            { data: 'address', name: 'patients.address' },
            { data: 'phone', name: 'patients.phone' },
            { data: 'amount', name: 'patients.amount' },
            { data: 'payment_type', name: 'patients.payment_type' },
            { data: 'date', name: 'advance_booking.date' },
            
        ]
    });

});
</script>
@endsection


