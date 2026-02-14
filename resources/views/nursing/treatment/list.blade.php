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
                <h3 class="content-header-title mb-0">Treatment sheet</h3>
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

<!-- Upload multiple file -->
<div class="modal fade" id="uploadFileModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="max-width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Report Files</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="uploadFileForm">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <div class="modal-body">
                    <input type="hidden" name="patient_id" id="modal_patient_id">
                    <div class="form-group">
                        <label for="file_input">Choose File</label>
                        <input type="file" id="file_input" class="form-control" accept="image/*" capture="camera">
                    </div>

                    <!-- Add File Button -->
                    <div class="form-group text-right">
                        <button type="button" class="btn btn-success" id="add_file_btn">Add File</button>
                    </div>

                    <!-- Table to Show Added Files -->
                    <div class="table-responsive">
                        <table class="table table-bordered" id="file_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>File Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
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
            ajax: '{!! route('nursing.treatment.data') !!}',
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
    let deleteForm;
    $(document).on('click', '.text-danger', function (e) {
        e.preventDefault();
        deleteForm = $(this).closest('form'); 
        $('#confirm-delete').modal('show');
    });

    $('#confirm-delete-btn').on('click', function () {
        deleteForm.submit();
        $('#confirm-delete').modal('hide');
    });
</script>

<script>
 $(document).ready(function () {
    $('#patient-table').on('click', '.upload-file-btn', function () {
        var patientId = $(this).data('id');
        $('#modal_patient_id').val(patientId);
    });

let fileCounter = 0; 
const fileData = []; 

$('#add_file_btn').on('click', function () {
    const fileInput = $('#file_input')[0];
    const fileTable = $('#file_table tbody');

    if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        fileData.push(file); 

        const newRow = `
            <tr>
                <td>${++fileCounter}</td>
                <td>${file.name}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-file-btn" data-index="${fileCounter - 1}">Remove</button></td>
            </tr>`;
        fileTable.append(newRow);

        fileInput.value = '';

        fileTable.find('.remove-file-btn').last().on('click', function () {
            const index = $(this).data('index');
            fileData.splice(index, 1); 
            $(this).closest('tr').remove(); 
            fileCounter--;
        });
    } else {
        alert('Please select a file to add.');
    }
});

$('#uploadFileForm').on('submit', function (e) {
    e.preventDefault(); 

    const patientId = $('#modal_patient_id').val();
    const formData = new FormData();

    if (fileData.length === 0) {
        alert('Please add at least one file before submitting.');
        return;
    }

    formData.append('patient_id', patientId);

    $.each(fileData, function (index, file) {
        formData.append(`files[${index}]`, file);
    });

$.ajax({
    url: '{{ route("nursing.uploadFiles") }}',
    type: 'POST',
    data: formData,
    contentType: false,
    processData: false,
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
    success: function(response) {
        if (response.success) {
            $('#uploadFileModal').modal('hide');

            Swal.fire({
                text: response.message,
                icon: 'success',
                toast: true,
                position: 'top-right',
                timer: 3000,
                showConfirmButton: false,
            });

            table.ajax.reload();
        } else {
            Swal.fire({
                text: response.message,
                icon: 'error',
                toast: true,
                position: 'top-right',
                timer: 3000,
                showConfirmButton: false,
            });
        }
    },
    error: function(xhr) {
        toastr.error('An error occurred while uploading the file.');
    }
});

});

});

</script>



@endsection
