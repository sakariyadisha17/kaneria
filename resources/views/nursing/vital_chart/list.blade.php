@extends('layouts.master')
@section('title')
    Patients
@endsection
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Vital chart</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL('nursing/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Patients</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section id="column">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Patients List</h4>
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
                                <div class="table-responsive">
                                    @include('notifications')
                                    <table class="table table-striped table-bordered" id="patient-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Patient ID</th>                                                
                                                <th>Name</th>
                                                <th>Room</th>
                                                <th>Bed</th>
                                                <th>Action</th>
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
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-trash"></i> Delete patient</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Confirm patient Deletion.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">Delete</button>

            </div>
        </div>
    </div>
</div>


@endsection

@section('additionalcss')
<link rel="stylesheet" href="{{ url('plugins/datatables/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@endsection
@section('additionaljs')
    <script type="text/javascript" src="{{ url('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ url('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="/assets/js/plugins/flatpickr.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('#patient-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                order: [[0, 'desc']], 
                aaSorting: [0, 'desc'],
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                ajax: '{!! route('nursing.vital_chart.data') !!}',
                columns: [
                   
                    { data: null, name: 'id', render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }},
                    { data: 'patient_id', name: 'patient_id' },
                    { data: 'fullname', name: 'fullname' },
                    { data: 'room_type', name: 'room_type' },
                    { data: 'bed.bed_no', name: 'bed_no' }, 
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            $('#confirm-delete').on('show.bs.modal', function(e) {
                $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            });
        });
    </script>

<script>
    // Store the form element when delete button is clicked
    let deleteForm;

    $(document).on('click', '.text-danger', function (e) {
        e.preventDefault();
        deleteForm = $(this).closest('form'); // Get the form inside the button
        $('#confirm-delete').modal('show');
    });

    // Trigger form submission after confirming delete
    $('#confirm-delete-btn').on('click', function () {
        deleteForm.submit(); // Submit the form
        $('#confirm-delete').modal('hide'); // Close the modal
    });
</script>



@endsection
