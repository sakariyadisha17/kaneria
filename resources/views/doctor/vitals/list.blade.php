@extends('layouts.master')
@section('title')
    Vitals
@endsection
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Vitals</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL('doctor/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Vitals</li>
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
                                <h4 class="card-title">Vitals List</h4>
                                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                                    </ul>
                                </div>
                           


                            <div class="card-content collapse show">
                                <div class="card-body card-dashboard">
     
                                    <table class="table table-striped table-bordered" id="vital-table">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Patient ID</th>
                                                <th>Name</th>
                                                <th>Bp</th>
                                                <th>Spo2</th>
                                                <th>Pulse</th>
                                                <th>Date</th>
                                                <th>Actions</th>
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
            </section>
            <!-- Column rendering table -->
        </div>
    </div>
</div>
<!-- END: Content-->

<!-- Delete Modal -->
<div class="modal fade text-left" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash"></i> Delete Patient</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Confirm Patient Deletion.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Cancel</button>
                <a type="button" class="btn btn-danger btn-ok">Delete</a>
            </div>
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
<script src="/assets/js/plugins/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#vital-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            aaSorting: [[0, 'desc']],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ajax: '{!! route('doctor.vitals.data') !!}',
            columns: [
                { 
                    data: null, 
                    name: 'serial_number', 
                    orderable: false, 
                    searchable: false, 
                    render: function (data, type, row, meta) {
                        var table = $('#vital-table').DataTable();
                        var pageInfo = table.page.info();
                        // Calculate the descending serial number
                        return pageInfo.recordsTotal - (pageInfo.start + meta.row);
                    }
                },
                { data: 'patient_id', name: 'patient_id' },
                { data: 'fullname', name: 'fullname' },
                { data: 'bp', name: 'bp' },
                { data: 'spo2', name: 'spo2' },
                { data: 'pulse', name: 'pulse' },
                { data: 'date', name: 'date' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }

            ],
            search: {
                caseInsensitive: true
            }
        });


        $('#confirm-delete').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });

       
    });
</script>

@endsection
