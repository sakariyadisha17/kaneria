@extends('layouts.master')
@section('title')
    Patient
@endsection
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Patient</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL('/nursing/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ URL('nursing/patients') }}">Patient</a></li>
                            <li class="breadcrumb-item active">
                                <a href="#">{{ isset($patient) ? 'Edit' : 'Add' }}</a>
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
                                <h4 class="card-title" id="horz-layout-basic">Patient Details</h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    @include('notifications')

                                    <form method="POST" action="{{ url('nursing/patients/add') }}" id="patient-form">
                                        @csrf

                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="patient_id">Patient ID:</label>
                                            <div class="col-md-6 d-flex">
                                                <input type="text" class="form-control" name="patient_id" id="patient_id" placeholder="Enter Patient ID" required>
                                                <button type="button" class="btn btn-primary ml-2" id="search-patient">Search</button>
                                            </div>
                                        </div>

                                        <div id="patient-details" style="display: none;">
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="fullname">Full Name:</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="fullname" id="fullname" readonly>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="phone">Phone:</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="phone" id="phone" readonly>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="address">Address:</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="address" id="address" readonly>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="room_type">Select Room Type:</label>
                                                <div class="col-md-6">
                                                    <select class="form-control" name="room_type" id="room_type">
                                                        <option value="">Select Room</option>
                                                        @foreach($rooms as $room)
                                                            <option value="{{ $room->id }}">{{ $room->type }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="bed">Selected Bed:</label>
                                                <div class="col-md-6 d-flex">
                                                    <input type="text" class="form-control" id="bed_display" readonly>
                                                    <input type="hidden" name="bed" id="bed">
                                                    <button type="button" class="btn btn-info ml-2" data-toggle="modal" data-target="#bedModal">Select Bed</button>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="admit_datetime">Admit Date & Time:</label>
                                                <div class="col-md-6">
                                                    <input type="datetime-local" class="form-control" name="admit_datetime" id="admit_datetime" required>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="type"> Type:</label>
                                                <div class="col-md-6">
                                                    <select class="form-control" name="type" id="type" required>
                                                        <option value="select">Select Type</option>
                                                        <option value="indoor">Indoor</option>
                                                        <option value="day_care">Day care treatment</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="mlc">MLC:</label>
                                                <div class="col-md-6">
                                                    <input type="hidden" name="mlc" value="0">
                                                    <input type="checkbox" name="mlc" id="mlc" value="1">
                                                </div>
                                            </div>


                                        </div>

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                            <a href="{{ url('nursing/patients') }}" class="btn btn-warning mr-1">
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
        </div>
    </div>
</div>
<!-- END: Content-->

<!-- Bed Selection Modal -->
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

@section('additionaljs')
<script>
$(document).ready(function () {
    $('#search-patient').click(function () {
        var patient_id = $('#patient_id').val();
        if (patient_id !== '') {
            $.ajax({
                url: "{{ url('nursing/patients/search') }}",
                type: "GET",
                data: { patient_id: patient_id },
                success: function (response) {
                    if (response.success) {
                        $('#fullname').val(response.data.fullname);
                        $('#phone').val(response.data.phone);
                        $('#address').val(response.data.address);
                        $('#patient-details').show();
                    } else {
                        alert('Patient not found!');
                    }
                }
            });
        } else {
            alert('Please enter a Patient ID!');
        }
    });


    $("#room_type").change(function () {
    let room_id = $(this).val();
    
    if (room_id) {
        $.ajax({
            url: "{{ url('nursing/patients/getBeds') }}",
            type: "GET",
            data: { room_id: room_id },
            success: function (response) {
                let html = "";
                if (response.length > 0) {
                    response.forEach(bed => {
                        // Set color based on is_occupied status
                        let bedClass = bed.is_occupied === 0 ? 'bg-warning' : 'bg-success'; // Success for 0 (unoccupied) and Warning for 1 (occupied)
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
                $("#bedModal").modal("show"); // Show the modal after beds are loaded
            },
            error: function() {
                alert('Error fetching beds!');
            }
        });
    }
});

$(document).on("click", ".bed-box", function () {
    let bed_id = $(this).data("id");
    let bed_no = $(this).data("bed_no");
    
    $("#bed").val(bed_id);
    $("#bed_display").val(`Bed ${bed_no}`);
    
    $(`.bed-box[data-id="${bed_id}"]`).removeClass('bg-warning').addClass('bg-success');
    
    $("#bedModal").modal("hide");
});

});


document.addEventListener('DOMContentLoaded', function () {
    const now = new Date();

    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');

    // Construct the datetime-local value
    const datetimeLocalValue = `${year}-${month}-${day}T${hours}:${minutes}`;

    // Set the value to the input field
    document.getElementById('admit_datetime').value = datetimeLocalValue;
});

  
</script>
@endsection

