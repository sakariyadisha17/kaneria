@extends('layouts.master')
@section('title')
    Booking Appointment
@endsection

<style>
    #patient-list {
        width: 94%;
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ddd;
}
.list-group-item {
    cursor: pointer;
}



</style>
@section('content')
     <div class="app-content content">

        <div class="content-overlay"></div>

        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title mb-0">Booking</h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ URL('/receptionist/dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Booking</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                @include('notifications')
                    <form id="booking-form">
                        @csrf

                        <div class="container-fluid ">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Book Appointment</h3>
                                        </div>

                                        <div class="card-body">

                                            <!-- Full Name & Phone -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Full Name</label>
                                                        <input type="text" name="fullname" id="name" class="form-control" placeholder="Enter your full name" autocomplete="off" required>
                                                         <div id="patient-list" class="list-group" style="position:absolute; z-index:1000;"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Phone</label>
                                                        <input type="text" id="phone" name="phone" class="form-control" placeholder="Enter your phone number" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Address -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Address</label>
                                                        <textarea name="address" id="address" class="form-control" rows="3" placeholder="Enter your address" required></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Date & Time -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Select Date</label>
                                                        <input type="date" id="select_date" name="date" class="form-control"  required>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Select Time</label>
                                                        <select class="form-control" id="time" name="time_id" required>
                                                            <option value="">Select time</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            
                                            <!-- Buttons -->
                                           
                                                <a href="{{ url()->previous() }}" class="btn btn-danger">
                                                    Cancel
                                                </a>
                                                <button type="submit" class="btn btn-primary ml-2">
                                                    Submit
                                                </button>
                                        </div>


                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>

            </div>

        </div>

    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
    $(document).ready(function() {
    $('#name').on('keyup', function() {
        let query = $(this).val();
        if(query.length > 1) {
            $.ajax({
                url: "{{ route('patients.search') }}",
                type: 'GET',
                data: { query: query },
                success: function(data) {
                    console.log(data);
                    $('#patient-list').empty();
                    data.forEach(function(patient) {
                        $('#patient-list').append(`
                            <a href="#" class="list-group-item list-group-item-action patient-item" 
                               data-id="${patient.id}" 
                               data-phone="${patient.phone}" 
                               data-address="${patient.address}">
                                ${patient.fullname}
                            </a>
                        `);
                    });
                }
            });
        } else {
            $('#patient-list').empty();
        }
    });

    // Click on patient to auto-fill
    $(document).on('click', '.patient-item', function(e) {
        e.preventDefault();
         let name = $(this).text().trim();
        let phone = $(this).data('phone');
        let address = $(this).data('address');

        $('#name').val(name).focus();;
        $('#phone').val(phone);
        $('#address').val(address);

        $('#patient-list').empty();
    });
});
</script>


@section('additionalcss')

<link rel="stylesheet" href="{{ url('plugins/datatables/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@endsection

@section('additionaljs')    
<script type="text/javascript" src="{{ url('plugins/datatables/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ url('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>

<script src="/assets/js/plugins/flatpickr.min.js"></script>



 <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        flatpickr("#select_date", {dateFormat: "Y-m-d"});
    </script>
    <script>
         $(document).ready(function () {
            $('#select_date').on('change', function () {
                const date = $(this).val();
                if (date) {
                    $.ajax({
                        url: "{{ url('/available-times') }}",
                        type: 'GET',
                        data: { date },
                        success: function (data) {
                            const timeSelect = $('#time');
                            timeSelect.empty(); // Clear the dropdown

                            // Populate the dropdown with available times
                            data.forEach(item => {
                                timeSelect.append(`<option value="${item.id}">${item.time}</option>`);
                            });
                        },
                        error: function () {
                            alert('Error fetching available times.');
                        }
                    });
                }
            });

            $('#booking-form').on('submit', function (e) {
                e.preventDefault(); // Prevent default form submission
                console.log('Form submit prevented successfully!');

                let formData = $(this).serialize();
                console.log('Form data:', formData);

                $.ajax({
                    url: "{{ url('/receptionist/save_booking') }}",
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        console.log('Server response:', response);

                        // Show success notification
                        Swal.fire({
                            title: 'Success!',
                            text: 'Booking saved successfully.',
                            icon: 'success',
                            toast: true,
                            position: 'top-end',
                            timer: 3000,
                            showConfirmButton: false,
                        });

                        // Reset the form
                        $('#booking-form')[0].reset();
                        console.log('Form reset successfully!');
                    },
                    error: function (xhr) {
                        console.error('Error response:', xhr.responseJSON);

                        // Show error notification
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to save booking. Please try again.',
                            icon: 'error',
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
   
@endsection

