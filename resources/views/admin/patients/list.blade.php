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
                <h3 class="content-header-title mb-0">Patients</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL('admin/dashboard') }}">Dashboard</a></li>
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
                                    <div class="card-header text-right">
                                        <div class="btn-group">
                                            <input type="date" id="export-date" class="form-control d-inline-block" style="width: 150px; margin-right: 10px;">
                                            <button onclick="downloadPDF()" class="btn btn-primary">
                                                <i class="fa fa-download"></i> PDF
                                            </button>
                                        </div>
                                    </div>
                                    @include('notifications')
                                    <table class="table table-striped table-bordered" id="patient-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Patient Id</th>                                                
                                                <th>Name</th>
                                                <th>Age</th>
                                                <th>Phone</th>
                                                <th>Address</th>
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

<!-- Modal for Soft Delete -->
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
                <p>Are you sure you want to soft delete this patient?</p>
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
    <script type="text/javascript">
    $(document).ready(function() {
        var table = $('#patient-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            aaSorting: [0, 'desc'],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ajax: '{!! route('admin.patients.data') !!}',
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'patient_id', name: 'patient_id' },
                { data: 'fullname', name: 'fullname' },
                { data: 'age', name: 'age' },
                { data: 'phone', name: 'phone' },
                { data: 'address', name: 'address' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

       
    });
</script>
<script>
    $(document).on('click', '.btn-trash', function () {
        let recordId = $(this).data('id'); 

        Swal.fire({
            title: 'Are you sure?',
            text: "This will soft delete the record.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, soft delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.patients.soft-delete', ':id') }}".replace(':id', recordId),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Patient Soft Deleted!',
                                text: response.message,
                                icon: 'success',
                                toast: true,
                                position: 'top-end',
                                timer: 3000,
                                showConfirmButton: false,
                            });

                            $('#patient-table').DataTable().ajax.reload(); // Reload the DataTable
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.error || 'An error occurred while soft deleting.',
                                icon: 'error',
                                toast: true,
                                position: 'top-end',
                                timer: 3000,
                                showConfirmButton: false,
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to soft delete the record. Please try again.',
                            icon: 'error',
                            toast: true,
                            position: 'top-end',
                            timer: 3000,
                            showConfirmButton: false,
                        });

                        // Log error for debugging
                        console.error(xhr.responseJSON);
                    }
                });
            }
        });
    });

    function downloadPDF() {
        var selectedDate = document.getElementById('export-date').value;
        
        if (!selectedDate) {
            Swal.fire({
                title: 'Error!',
                text: 'Please select a date before downloading.',
                icon: 'error',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
            });
            return;
        }

        // Redirect to the export route with the selected date
        window.location.href = `/admin/patients/export-pdf?date=${selectedDate}`;
    }

</script>
@endsection
