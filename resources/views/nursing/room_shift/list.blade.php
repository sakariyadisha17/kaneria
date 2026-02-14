@extends('layouts.master')
@section('title')
    Room shifting
@endsection
@section('content')

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Room Shifting</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL('/nursing/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ URL('nursing/room_shifting') }}">Patient</a></li>
                            <li class="breadcrumb-item active">
                                <a href="#">{{ isset($patient) ? 'Shift' : 'Shift' }}</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section id="horizontal-form-layouts">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" id="horz-layout-basic">Room Shifting</h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    @include('notifications')
                                    <form method="POST" action="{{ url('nursing/room_shifting/add') }}" id="patient-form">

                                        @csrf

                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="patient_id">Select Patient:</label>
                                            <div class="col-md-6">
                                                <select class="form-control" name="patient_id" id="patient_id" required>
                                                    <option value="">Select Patient</option>
                                                    @foreach($patients as $patient)
                                                        <option value="{{ $patient->id }}">{{ $patient->fullname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="room_type">Current Room Type:</label>
                                            <div class="col-md-6">
                                               <input type="text" class="form-control"  id="old_room" readonly>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="bed">Current Bed:</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control"  id="old_bed" readonly>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="new_room_type">Shift to Room:</label>
                                            <div class="col-md-6">
                                            <select class="form-control" name="new_room_id" id="new_room_id">
                                                <option value="">Select Room</option>
                                                @foreach($rooms as $room)
                                                    <option value="{{ $room->id }}">{{ $room->type }}</option>
                                                @endforeach
                                            </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="new_bed_id">Shift to Bed:</label>
                                            <div class="col-md-6 d-flex">
                                                <input type="text" class="form-control" id="new_bed_id_display" readonly>
                                                <input type="hidden" name="new_bed_id" id="new_bed_id">
                                                <button type="button" class="btn btn-info ml-2" data-toggle="modal" data-target="#bedModal">Select Bed</button>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="datetime"> Date & Time:</label>
                                            <div class="col-md-6">
                                                <input type="datetime-local" class="form-control" name="datetime" id="datetime" required>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                            <a href="{{ url('nursing/room_shifting') }}" class="btn btn-warning mr-1">
                                                <i class="feather icon-x"></i> Cancel
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Room Shifting Records</h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <table class="table table-striped table-bordered" id="roomShiftingTable">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Patient Name</th>
                                                <th>Old Room</th>
                                                <th>Old Bed</th>
                                                <th>Shift to Room</th>
                                                <th>Shift to Bed</th>
                                                <th>Datetime</th>
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
        </div>
    </div>
</div>

<div class="modal fade" id="bedModal" tabindex="-1" role="dialog" aria-labelledby="bedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bedModalLabel">Select Bed</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="bed-list" class="d-flex flex-wrap"></div>
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
        var table = $('#roomShiftingTable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            order: [[0, 'desc']], 
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ajax: '{!! route('nursing.room_shift.data') !!}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'patient_name', name: 'patient_name' },
                { data: 'old_room', name: 'old_room' },
                { data: 'old_bed', name: 'old_bed' },
                { data: 'new_room', name: 'new_room' },
                { data: 'new_bed', name: 'new_bed' },
                { data: 'shifted_at', name: 'shifted_at' }
            ]
        });

        $('#confirm-delete').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });
    });
</script>

<script>
$(document).ready(function () {
    $("#new_room_id").change(function () {
    let room_id = $(this).val();
    
    if (room_id) {
        $.ajax({
            url: "{{ url('nursing/room_shifting/getBeds') }}",
            type: "GET",
            data: { room_id: room_id },
            success: function (response) {
                let html = "";
                if (response.length > 0) {
                    response.forEach(bed => {
                        let bedClass = bed.is_occupied === 0 ? 'bg-warning' : 'bg-success'; 
                        html += `
                            <div class="bed-box ${bedClass} text-white p-3 m-2" data-id="${bed.id}" data-bed_no="${bed.bed_no}" style="cursor:pointer;">
                                Bed ${bed.bed_no}
                            </div>
                        `;
                    });
                } else {
                    html = '<p class="text-warning">No beds available in this room.</p>';
                }
                $("#bed-list").html(html);
                $("#bedModal").modal("show"); 
            },
            error: function() {
                alert('Error fetching beds!');
            }
        });
    }
});

$(document).ready(function () {
    $("#patient_id").change(function () {
        let patient_id = $(this).val();
        if (patient_id) {
            $.ajax({
                url: "{{ url('nursing/room_shifting/getPatientDetails') }}",
                type: "GET",
                data: { patient_id: patient_id },
                success: function (data) {
                    $("#old_room_bed").val(`Room: ${data.room_type}, Bed: ${data.bed}`);
                    $("#old_room").val(data.room_type);
                    $("#old_bed").val(data.bed);
                }
            });
        }
    });
});

$(document).on("click", ".bed-box", function () {
    let bed_id = $(this).data("id");
    let bed_no = $(this).data("bed_no");
    
    $("#new_bed_id").val(bed_id);
    $("#new_bed_id_display").val(`Bed ${bed_no}`);
    $(`.bed-box[data-id="${bed_id}"]`).removeClass('bg-warning').addClass('bg-success');
    $("#bedModal").modal("hide");
});
});

document.addEventListener('DOMContentLoaded', function () {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');

    const datetimeLocalValue = `${year}-${month}-${day}T${hours}:${minutes}`;

    document.getElementById('datetime').value = datetimeLocalValue;
});
</script>
@endsection

