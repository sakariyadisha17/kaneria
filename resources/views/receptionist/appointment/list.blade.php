@extends('layouts.master')
@section('title')
    Appointment
@endsection
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Appointment</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL('receptionist/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Appointment</li>
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
                                <h4 class="card-title"> Appointment List</h4>
                                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                                    </ul>
                                </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 col-6 mb-2 text-center">
                                        <span class="badge badge-secondary d-inline-block" style="background-color: #DCDBD8; width: 30px;">&nbsp;</span><br>
                                        <small>Special</small><br>
                                        <span id="special-count" class="badge badge-pill badge-primary">{{ $statusCounts['Special'] ?? 0 }}</span>
                                    </div>
                                    <div class="col-md-2 col-6 mb-2 text-center">
                                        <span class="badge badge-secondary d-inline-block" style="background-color: #f7e4ee; width: 30px;">&nbsp;</span><br>
                                        <small>Pending</small><br>
                                        <span id="pending-count" class="badge badge-pill badge-primary">{{ $statusCounts['Pending'] ?? 0 }}</span>
                                    </div>
                                    <div class="col-md-2 col-6 mb-2 text-center">
                                        <span class="badge badge-secondary d-inline-block" style="background-color: #D4F1F4; width: 30px;">&nbsp;</span><br>
                                        <small>Arrived</small><br>
                                        <span id="arrived-count" class="badge badge-pill badge-primary">{{ $statusCounts['Arrived'] ?? 0 }}</span>
                                    </div>
                                    <div class="col-md-2 col-6 mb-2 text-center">
                                        <span class="badge badge-secondary d-inline-block" style="background-color: #EBF0FF; width: 30px;">&nbsp;</span><br>
                                        <small>Report</small><br>
                                        <span id="report-count" class="badge badge-pill badge-primary">{{ $statusCounts['Report'] ?? 0 }}</span>
                                    </div>
                                    <div class="col-md-2 col-6 mb-2 text-center">
                                        <span class="badge badge-secondary d-inline-block" style="background-color: #ccffcc; width: 30px;">&nbsp;</span><br>
                                        <small>Complete</small><br>
                                        <span id="completed-count" class="badge badge-pill badge-primary">{{ $statusCounts['Completed'] ?? 0 }}</span>
                                    </div>
                                </div>

                                <br>

                                <div class="row justify-content-center">
                                    <div class="col-md-4 col-10">
                                        <label for="select_date">Select Date:</label>
                                        <input type="date" id="select_date" class="form-control" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>

                           
                            <div class="card-content collapse show">
                                <div class="card-body card-dashboard">
                                    <div class="card-header text-right">
                                    <div class="btn-group">
                                        <a href="javascript:void(0)" onclick="exportFile('pdf')" class="btn btn-primary"><i class="fa fa-plus"></i> PDF</a> 
                                        <a href="javascript:void(0)" onclick="exportFile('csv')" class="btn btn-info"><i class="fa fa-plus"></i> CSV</a>
                                    </div>
                                    </div>
                                    <table class="table table-striped table-bordered" id="patient-table">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Patient ID</th>
                                                <th>Time</th>
                                                <th>Name</th>
                                                <th>Address</th>
                                                <th>Phone</th>
                                                <th>Amount</th>
                                                <th>Status</th>
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    .custom-select {
        background-color: #f0f8ff;
        border: 1px solid #007bff;
        border-radius: 5px;
        color: #333;
        font-weight: 500;
        transition: all 0.3s;
    }

    .custom-select:focus {
        outline: none;
        box-shadow: 0 0 5px 2px #007bff50;
        border-color: #007bff;
    }

    .custom-select option {
        font-size: 14px;
        font-weight: 400;
    }
</style>

@endsection
@section('additionaljs')
<script type="text/javascript" src="{{ url('plugins/datatables/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ url('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>

<script src="/assets/js/plugins/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#select_date", {dateFormat: "Y-m-d"});
</script>

<script>
    function exportFile(type) {
        const date = document.getElementById('select_date').value;
        const base = type === 'pdf' 
            ? "{{ route('appointments.exportPDF') }}" 
            : "{{ route('appointments.exportCSV') }}";
        const url = `${base}?date=${date}`;
        window.location.href = url;
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
        var table = $('#patient-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            aaSorting: [[0, 'desc']],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ajax: {
                url: "{{ route('appointments.data') }}",
                data: function (d) {
                    d.date = $('#select_date').val(); // Pass selected date
                },
            },
            columns: [
                {
                    data: null,
                    name: 'serial_number',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row, meta) {
                        var table = $('#patient-table').DataTable();
                        var pageInfo = table.page.info();
                        return pageInfo.recordsTotal - (pageInfo.start + meta.row);
                    }
                },
                { data: 'patient_id', name: 'patient_id' },
                { data: 'time', name: 'time' },
                { data: 'fullname', name: 'fullname' },
                { data: 'address', name: 'address' },
                { data: 'phone', name: 'phone' },
                { data: 'amount', name: 'amount' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            createdRow: function (row, data, dataIndex) {
                $(row).css('background-color', data.background_color);
            }
        });

        $('#confirm-delete').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });

        function fetchStatusCounts(date) {
            $.ajax({
                url: '{{ route("appointments.statusCounts") }}',
                method: 'GET',
                data: { date: date },
                success: function (response) {
                    if (response.success) {
                        // Update UI with status counts
                        $('#pending-count').text(response.counts.Pending || 0);
                        $('#arrived-count').text(response.counts.Arrived || 0);
                        $('#report-count').text(response.counts.Report || 0);
                        $('#completed-count').text(response.counts.Completed || 0);
                        $('#special-count').text(response.counts.Special || 0);
                    }
                },
                error: function () {
                    console.error('Failed to fetch status counts.');
                }
            });
        }

        var defaultDate = $('#select_date').val();
        fetchStatusCounts(defaultDate);

        $('#select_date').on('change', function () {
            var selectedDate = $(this).val();
            fetchStatusCounts(selectedDate);
            table.ajax.reload(); 
        });

        $('#patient-table').on('change', '.change-status', function () {
            var id = $(this).data('id');
            var status = $(this).val();

            $.ajax({
                url: '{{ route("appointments.updateStatus") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    status: status
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            toast: true,
                            position: 'top-end',
                            timer: 3000,
                            showConfirmButton: false,
                        });
                        table.ajax.reload();
                        fetchStatusCounts($('#select_date').val()); // Update counts
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            toast: true,
                            position: 'top-end',
                            timer: 3000,
                            showConfirmButton: false,
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'An error occurred while updating the status.',
                        toast: true,
                        position: 'top-end',
                        timer: 3000,
                        showConfirmButton: false,
                    });
                }
            });
        });
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
    url: '{{ route("appointments.uploadFiles") }}',
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
