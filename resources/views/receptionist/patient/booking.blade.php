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
                <h3 class="content-header-title mb-0">Booking Appointment</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL('receptionist/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Booking Appointment</li>
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
                                <h4 class="card-title">Appointment List</h4>
                                <div class="row justify-content-center">
                                    <div class="col-md-4">
                                        <label for="select_date">Select Date:</label>
                                        <input type="date" id="select_date" class="form-control" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="card-content collapse show">
                                <div class="card-body card-dashboard">
                                    @include('notifications')
                                    <table class="table table-striped table-bordered" id="booking-table">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Time</th>
                                                <th>PatientId</th>
                                                <th>Full Name</th>
                                                <th>Phone</th>
                                                <th>Address</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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
<!-- END: Content-->

@endsection

@section('additionalcss')
<link rel="stylesheet" href="{{ url('plugins/datatables/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    table.dataTable tbody tr {
        background-color: transparent !important;
    }

  
    .edit-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: flex-start;
    }

    .edit-field {
        display: flex;
        flex-direction: column;
        width: calc(25% - 15px);
        min-width: 200px;
    }

    .edit-actions {
        display: flex;
        justify-content: flex-start;
        gap: 10px;
        width: 100%;
        margin-top: 15px;
    }

    .edit-field label {
        font-weight: bold;
        margin-bottom: 5px;
        font-size: 14px;
    }

    .edit-field input, .edit-field select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }

    .edit-actions .btn {
        padding: 5px 10px;
        font-size: 14px;
    }
 


/* Label styling */
.form-label {
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 8px;
    display: block;
}

.select2-container .select2-selection--multiple .select2-selection__choice {
        font-size: 12px; 
        padding: 2px 5px; 
        margin: 2px;
    }

.select2-container--default .select2-selection--single .select2-selection__rendered {
    font-size: 12px; 
}


</style>


@endsection

@section('additionaljs')
<script type="text/javascript" src="{{ url('plugins/datatables/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ url('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#select_date", {dateFormat: "Y-m-d"});
</script>
<script>
    $(document).ready(function() {
        $('#charges').select2({
            placeholder: "Select charges",
            allowClear: true
        });
        
        $('#edit_charges').select2({
            placeholder: "Select charges",
            allowClear: true
        });
    });

</script>

<script>
    $(document).ready(function () {
        $('#referred_by').select2({
            placeholder: "Select a Referred Doctor",
            allowClear: true
        });

        $('#edit_referred_by').select2({
            placeholder: "Select a Referred Doctor",
            allowClear: true
        });
    });
</script>

<script>
$(document).ready(function () {
    var table = $('#booking-table').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],

        ajax: {
            url: "{{ route('advance.booking') }}", 
            data: function (d) {
                d.date = $('#select_date').val(); 
            }
        },
        columns: [
            { 
                data: null, 
                name: 'serial_number', 
                orderable: false, 
                searchable: false, 
                render: function (data, type, row, meta) {
                    var pageInfo = $('#booking-table').DataTable().page.info();
                    return pageInfo.start + meta.row + 1;
                }
            },
            { data: 'time', name: 'time' },
            { data: 'patient_id', name: 'patient_id', visible: true, searchable: true },
            {
                data: 'fullname',
                name: 'fullname',
                render: function (data, type, row) {
                    return data 
                        ? `<span>${data}</span>` 
                        : `<input type="text" class="form-control fullname" placeholder="Enter Name">`;
                }
            },
            {
                data: 'phone',
                name: 'phone',
                render: function (data, type, row) {
                    return data 
                        ? `<span>${data}</span>` 
                        : `<div class="phone-wrapper">
                                <input type="text" class="form-control phone" placeholder="Enter Phone" maxlength="10" oninput="validatePhoneNumber(this)">
                                <span class="error-message" style="display:none; color:red; font-size: 12px;">Please enter a valid 10-digit phone number.</span>
                            </div>`;
                }
            },

            {
                data: 'address',
                name: 'address',
                render: function (data, type, row) {
                    return data 
                        ? `<span>${data}</span>` 
                        : `<input type="text" class="form-control address" placeholder="Enter Address">`;
                }
            },
            { 
                data: 'status', 
                name: 'status',
                render: function (data) {
                    let badgeClass = '';
                    if (data === 'Arrived') {
                        badgeClass = 'bg-blue'; 
                    } else if (data === 'Pending') {
                        badgeClass = 'bg-danger'; 
                    } else {
                        badgeClass = 'bg-success';
                    }

                    return `<span class="badge ${badgeClass}">${data}</span>`;
                }
            },
            {
                data: 'time_id',
                name: 'time_id',
                render: function (data, type, row) {
                    if (row.status === 'Pending' || row.status === 'Self Book') {
                        return ` 
                            <button class="btn btn-success btn-sm" onclick="addFields(${row.id}, this)">
                                <i class="fa fa-plus"></i>
                            </button>
                            <button class="btn btn-primary btn-sm" onclick="editRow(${row.id})">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteRow(${row.id})">
                                <i class="fa fa-trash"></i>
                            </button>`;
                    } 
                     else if (row.status === 'Arrived' || row.status === 'Report') {
                        return ` 
                            <button class="btn btn-primary btn-sm" onclick="editRow(${row.id})">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-secondary btn-sm" onclick="deletesoft(${row.id})">
                                <i class="fa fa-trash"></i>
                            </button>`;
                    } else if (row.status === 'Completed') {
                        return ` 
                            <button class="btn btn-secondary btn-sm" onclick="deletesoft(${row.id})">
                                <i class="fa fa-trash"></i>
                            </button>`;
                    } else {
                        return ` 
                            <button class="btn btn-pink btn-sm" onclick="saveBooking(${data})">
                                Save
                            </button>`;
                    }
                }
            }
        ],
        columnDefs: [
            { targets: [0, 6], className: "text-center" }, 
        ],
        createdRow: function (row, data, dataIndex) {
             if (data.status_class) {
                 $(row).attr('style', data.status_class);  
             }
        }
      
    });

    $('#select_date').change(function () {
        table.ajax.reload(null, false); 
    });
});



function validatePhoneNumber(input) {
    const phoneRegex = /^\d{10}$/; 
    const errorMessage = input.nextElementSibling;

    if (phoneRegex.test(input.value)) {
        input.setCustomValidity(''); 
        input.style.borderColor = ''; 
        errorMessage.style.display = 'none';
    } else {
        input.setCustomValidity('Please enter a valid 10-digit phone number.');
        input.style.borderColor = 'red'; 
        errorMessage.style.display = 'block'; 
    }
}

 // Function to save booking details
function saveBooking(time_id) {
    let row = $('tr').has('button[onclick="saveBooking(' + time_id + ')"]').closest('tr');
    let fullname = row.find('.fullname').val();
    let phone = row.find('.phone').val();
    let address = row.find('.address').val();
    let date = $('#select_date').val();

    if (!fullname || !phone || !address) {
        Swal.fire({
            title: 'Error!',
            text: 'Please fill in all fields before saving.',
            icon: 'error',
            toast: true,
            position: 'top-end',
            timer: 3000,
            showConfirmButton: false,
        });
        return;
    }

    $.ajax({
        url: "{{ route('booking.store') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            time_id: time_id, // Use 'time_id' to match the Laravel request
            fullname: fullname,
            phone: phone,
            address: address,
            date: date
        },
        success: function (response) {
            Swal.fire({
                title: 'Success!',
                text: 'Booking saved successfully.',
                icon: 'success',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
            });

            $('#booking-table').DataTable().ajax.reload(null, false);
        },
        error: function (xhr) {
            Swal.fire({
                title: 'Error!',
                text: 'Failed to save booking. Please try again.',
                icon: 'error',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
            });
            console.log(xhr.responseJSON);
        }
    });
}


// Function to generate patient ID for new patients
function generatePatientId() {
    $.ajax({
        url: "{{ route('patient.generatePatientIds') }}", 
        method: "GET",
        success: function (response) {
            if (response.success) {
                $('#generated_patient_id').val(response.patient_id); // Set the generated ID here
            } else {
                Swal.fire('Error', 'Failed to generate patient ID.', 'error');
            }
        },
        error: function () {
            Swal.fire('Error', 'Failed to generate patient ID.', 'error');
        }
    });
}

 // Add fields when clicking "Add" button for appointment
function addFields(appointmentId, buttonElement) {
    const newRow = `
    <tr class="edit-row">
        <td colspan="10">
            <div class="edit-container">
                <div class="edit-field">
                    <label for="patient_type">Patient Type</label>
                    <select class="form-control" id="patient_type">
                        <option value="new">New Patient</option>
                        <option value="old">Old Patient</option>
                    </select>
                </div>
                <div class="edit-field old-patient-details" style="display: none;">
                    <div class="form-row">
                        <div class="col">
                            <label for="patient_id">Patient ID</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="patient_id" placeholder="Enter Patient ID">
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-sm" id="search_patient_btn">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="edit-field new-patient-details" style="display: none;">
                    <div class="form-row">
                        <div class="col">
                            <label for="generated_patient_id">Generated Patient ID</label>
                            <input type="text" class="form-control" id="generated_patient_id" placeholder="Generated Patient ID" readonly>
                        </div>
                    </div>
                </div>
              
                <div class="edit-field">
                    <label for="age">Age</label>
                    <input type="text" class="form-control" placeholder="Age" id="age">
                </div>
                <div class="edit-field">
                    <label for="gender">Gender</label>
                    <select class="form-control" id="gender">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="edit-field">
                    <label for="referred_by">Referred By</label>
                    <select class="form-control" id="referred_by" name="referred_by"></select>
                </div>
                <div class="edit-field">
                    <label for="charges">Charges</label>
                    <select class="form-control" id="charges" name="charges[]" multiple="multiple"></select>
                </div>
                <div class="edit-field">
                    <label for="amount">Amount</label>
                    <input type="text" class="form-control" id="amount" readonly>
                </div>
                <div class="edit-field">
                    <label for="payment_type">Payment Type</label>
                    <select class="form-control" id="payment_type">
                        <option value="">Select Payment Type</option>
                        <option value="Cash">Cash</option>
                        <option value="Online">Online</option>
                        <option value="Debit">Debit</option>

                    </select>
                </div>   
              
               <div class="edit-field">
                    <div class="custom-checkbox d-flex">
                        <label for="is_relative" class="form-label">Is Special? </label> &ensp;&ensp;
                        <input type="checkbox" id="is_relative" style="width:20px;height:20px;">
                        <span class="checkmark"></span>
                    </div>
                </div>


                <div class="edit-actions">
                    <button class="btn btn-success btn-sm" onclick="saveData(${appointmentId})">Save</button>
                </div>
            </div>
        </td>
    </tr>`;

    $(buttonElement).closest('tr').after(newRow);

    // Fetch data for dropdowns
    $.ajax({
        url: "{{ route('patientformdata') }}",
        method: "GET",
        success: function (data) {
            let referredByOptions = '<option value="">Referred Doctor</option>';
            data.doctors.forEach(doctor => {
                referredByOptions += `<option value="${doctor.id}">${doctor.name}</option>`;
            });
            $('#referred_by').html(referredByOptions).select2();

            let chargesOptions = '';
            data.charges.forEach(charge => {
                chargesOptions += `<option value="${charge.id}" data-amount="${charge.amount}">${charge.type}</option>`;
            });
            $('#charges').html(chargesOptions).select2();
        }
    });

    $(document).on('change', '#charges', function () {
        let totalAmount = 0;
        $(this).find(':selected').each(function () {
            totalAmount += parseFloat($(this).data('amount'));
        });
        $('#amount').val(totalAmount);
    });

    // Show/hide patient ID field based on patient type
    $(document).on('change', '#patient_type', function () {
        if ($(this).val() === 'old') {
            $('.old-patient-details').show();
            $('.new-patient-details').hide();
            $('#generated_patient_id').val(''); // Clear generated ID if switching to old
        } else {
            $('.old-patient-details').hide();
            $('.new-patient-details').show();
            generatePatientId(); // Auto-generate patient ID for new patients
        }
    });

    // Trigger the change event to hide/show fields based on the initial selection
    $('#patient_type').trigger('change');
}

// Save data to server
// function saveData(appointmentId) {
//     const isRelativeChecked = document.getElementById('is_relative').checked ? 'yes' : 'no';

//     const age = $('#age').val().trim();
//     const gender = $('#gender').val();
//     const referredBy = $('#referred_by').val(); // This should fetch the selected value
//     const charges = $('#charges').val();
//     const amount = $('#amount').val().trim();
//     const paymentType = $('#payment_type').val();
//     const patientId = $('#patient_id').val().trim();
//     const generatedPatientId = $('#generated_patient_id').val().trim();


//     let errorMessage = '';

//     if (!age) errorMessage = 'Age is required.';
//     else if (!gender) errorMessage = 'Gender is required.';
//     else if (!charges || charges.length === 0) errorMessage = 'Please select at least one charge.';
//     else if (!amount || isNaN(amount)) errorMessage = 'Amount must be valid.';
//     else if (!paymentType) errorMessage = 'Payment Type is required.';

//     if (errorMessage) {
//         Swal.fire('Error', errorMessage, 'error');
//         return;
//     }

//     const requestData = {
//         _token: '{{ csrf_token() }}',
//         appointment_id: appointmentId,  
//         age: age,
//         gender: gender,
//         referred_by: referredBy,
//         charges: charges,
//         amount: amount,
//         payment_type: paymentType,
//         is_relative: isRelativeChecked, // Here you pass the value

//     };

//     if (patientId) {
//         requestData.patient_id = patientId;
//     } else if (generatedPatientId) {
//         requestData.generated_patient_id = generatedPatientId;
//     }

//     $.ajax({
//         url: "{{ route('patient.store') }}",
//         method: "POST",
//         data: requestData,
      
//             success: function(response) {
//             if (response.success) {
//                 Swal.fire({
//                     title: 'Success!',
//                     text: 'Data saved successfully.',   
//                     icon: 'success',          
//                     toast: true,               
//                     position: 'top-end',     
//                     timer: 3000,              
//                     showConfirmButton: false,  
//                 });

//                 setTimeout(() => {
//                     $('#booking-table').DataTable().ajax.reload();
//                     clearPatientFields();
//                 }, 2000);

//             } else {
//                 Swal.fire({
//                     icon: 'error',            
//                     title: 'Error',            
//                     text: response.message, 
//                     toast: true,              
//                     position: 'top-end',       
//                     timer: 3000,               
//                     showConfirmButton: false,  
//                 });
//             }
//         },
//         error: function(xhr) {
//             Swal.fire({
//                 icon: 'error',            
//                 title: 'Oops...',          
//                 text: 'An error occurred while saving the data. Please try again.', 
//                 toast: true,              
//                 position: 'top-end',       
//                 timer: 3000,               
//                 showConfirmButton: false,  
//             });
//             console.log(xhr.responseJSON);
//         }

//     });
// }

function saveData(appointmentId) {
    const saveButton = $('.btn-success'); // Select the save button

    // Disable the button to prevent multiple clicks
    saveButton.prop('disabled', true);

    const isRelativeChecked = document.getElementById('is_relative').checked ? 'yes' : 'no';

    const age = $('#age').val().trim();
    const gender = $('#gender').val();
    const referredBy = $('#referred_by').val();
    const charges = $('#charges').val();
    const amount = $('#amount').val().trim();
    const paymentType = $('#payment_type').val();
    const patientId = $('#patient_id').val().trim();
    const generatedPatientId = $('#generated_patient_id').val().trim();

    let errorMessage = '';

    if (!age) errorMessage = 'Age is required.';
    else if (!gender) errorMessage = 'Gender is required.';
    else if (!charges || charges.length === 0) errorMessage = 'Please select at least one charge.';
    else if (!amount || isNaN(amount)) errorMessage = 'Amount must be valid.';
    else if (!paymentType) errorMessage = 'Payment Type is required.';

    if (errorMessage) {
        Swal.fire('Error', errorMessage, 'error');
        saveButton.prop('disabled', false); // Re-enable button if validation fails
        return;
    }

    const requestData = {
        _token: '{{ csrf_token() }}',
        appointment_id: appointmentId,
        age: age,
        gender: gender,
        referred_by: referredBy,
        charges: charges,
        amount: amount,
        payment_type: paymentType,
        is_relative: isRelativeChecked,
    };

    if (patientId) {
        requestData.patient_id = patientId;
    } else if (generatedPatientId) {
        requestData.generated_patient_id = generatedPatientId;
    }

    $.ajax({
        url: "{{ route('patient.store') }}",
        method: "POST",
        data: requestData,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Data saved successfully.',
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false,
                });

                setTimeout(() => {
                    $('#booking-table').DataTable().ajax.reload(function() {
                        saveButton.prop('disabled', false); // Enable button after table reloads
                    });
                }, 2000);

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
                saveButton.prop('disabled', false); // Re-enable if server returns an error
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'An error occurred while saving the data. Please try again.',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
            });
            console.log(xhr.responseJSON);
            saveButton.prop('disabled', false); // Re-enable if AJAX error occurs
        }
    });
}

// Clear patient fields after saving
function clearPatientFields() {
    $('#age').val('');
    $('#gender').val('').change();
    $('#referred_by').val('').change();
    $('#charges').val('').change();
    $('#amount').val('');
    $('#payment_type').val('').change();
    $('#patient_id').val('');
    $('#generated_patient_id').val('');
    $('#is_relative').prop('checked', false); // Ensure checkbox is unchecked when clearing

}

// Adjust field updates after patient search
$(document).on('click', '#search_patient_btn', function () {
    const patientId = $('#patient_id').val().trim();
    if (!patientId) {
        Swal.fire('Error', 'Please enter a patient ID.', 'error');
        return;
    }

    $.ajax({
        url: "{{ route('patient.getDetails') }}",
        method: "GET",
        data: { patient_id: patientId },
        success: function (response) {
            if (response.success && response.patient) {
                const patient = response.patient;

                $('#age').val(patient.age || '');
                $('#gender').val(patient.gender || '').change();
                $('#referred_by').val(patient.referred_by || '').change();
                $('#charges').val(patient.charges || '').change();
                $('#amount').val(patient.amount || '');
                $('#payment_type').val(patient.payment_type || '').change();
                $('#is_relative').prop('checked', patient.is_relative === 'yes' ? true : false);
            } else {
                clearPatientFields();
                Swal.fire('Error', 'Patient not found.', 'error');
            }
        },
        error: function () {
            clearPatientFields();
            Swal.fire('Error', 'Failed to fetch patient details.', 'error');
        }
    });
});


// Edit function
function editRow(appointmentId) {
    $.ajax({
        url: "{{ route('patient.edit', ':id') }}".replace(':id', appointmentId),
        method: "GET",
        success: function (data) {
            if (!data) {
                Swal.fire('Error!', 'No data found for the selected record.', 'error');
                return;
            }

            const row = $(`button[onclick="editRow(${appointmentId})"]`).closest('tr');
            const editableRow = `
                <tr class="edit-row">
                    <td colspan="10">
                        <div class="edit-container">
                            <div class="edit-field">
                                <label for="edit_fullname">Full Name</label>
                                <input type="text" class="form-control" id="edit_fullname" value="${data.fullname || ''}">
                            </div>
                            <div class="edit-field">
                                <label for="edit_phone">Phone</label>
                                <input type="text" class="form-control" id="edit_phone" value="${data.phone || ''}">
                            </div>
                            <div class="edit-field">
                                <label for="edit_address">Address</label>
                                <input type="text" class="form-control" id="edit_address" value="${data.address || ''}">
                            </div>
                            <div class="edit-field">
                                <label for="edit_age">Age</label>
                                <input type="number" class="form-control" id="edit_age" value="${data.patient.age || ''}">
                            </div>
                            <div class="edit-field">
                                <label for="edit_gender">Gender</label>
                                <select class="form-control" id="edit_gender">
                                    <option value="Male" ${data.patient.gender === 'Male' ? 'selected' : ''}>Male</option>
                                    <option value="Female" ${data.patient.gender === 'Female' ? 'selected' : ''}>Female</option>
                                </select>
                            </div>
                            <div class="edit-field">
                                <label for="edit_referred_by">Referred By</label>
                                <select class="form-control" id="edit_referred_by" name="edit_referred_by"></select>
                            </div>
                            <div class="edit-field">
                                <label for="edit_charges">Charges</label>
                                <select class="form-control" id="edit_charges" name="charges[]" multiple="multiple"></select>
                            </div>
                            <div class="edit-field">
                                <label for="edit_amount">Amount</label>
                                <input type="text" class="form-control" id="edit_amount" value="${data.patient.amount || ''}" readonly>
                            </div>
                            <div class="edit-field">
                                <label for="edit_return_amount">Return Amount</label>
                                <input type="number" class="form-control" id="edit_return_amount" value="${data.patient.return_amount || 0}">
                            </div>
                            <div class="edit-field">
                                <label for="edit_total_amount">Total Amount</label>
                                <input type="text" class="form-control" id="edit_total_amount" value="${data.patient.total_amount || ''}" readonly>
                            </div>
                            <div class="edit-field">
                                <label for="edit_payment_type">Payment Type</label>
                                <select class="form-control" id="edit_payment_type">
                                    <option value="Cash" ${data.patient.payment_type === 'Cash' ? 'selected' : ''}>Cash</option>
                                    <option value="Online" ${data.patient.payment_type === 'Online' ? 'selected' : ''}>Online</option>
                                    <option value="Debit" ${data.patient.payment_type === 'Debit' ? 'selected' : ''}>Debit</option>
                                </select>
                            </div>
                          

                             <div class="edit-field">
                                <div class="custom-checkbox d-flex">
                                    <label for="edit_is_relative" class="form-label">Is Special? </label> &ensp;&ensp;
                                     <input type="checkbox" id="edit_is_relative" 
                                        ${data.patient.is_relative === 'yes' ? 'checked' : ''} style="width:20px;height:20px;">
                                    <span class="checkmark"></span>
                                </div>
                            </div>


                            <div class="edit-actions">
                                <button class="btn btn-success btn-sm" onclick="updateData(${appointmentId})">Save</button>
                                <button class="btn btn-secondary btn-sm" onclick="cancelEdit(${appointmentId})">Cancel</button>
                            </div>
                        </div>
                    </td>
                </tr>`;

            row.replaceWith(editableRow);

            $.ajax({
                url: "{{ route('patientformdata') }}",
                method: "GET",
                success: function (dropdownData) {
                    let referredByOptions = '<option value="">Referred Doctor</option>';
                    dropdownData.doctors.forEach(function (doctor) {
                        referredByOptions += `<option value="${doctor.id}" ${doctor.id == data.patient.referred_by ? 'selected' : ''}>${doctor.name}</option>`;
                    });
                    $('#edit_referred_by').html(referredByOptions).select2();

                    let chargesOptions = '';
                    dropdownData.charges.forEach(function (charge) {
                        const isSelected = data.patient.charges && data.patient.charges.includes(charge.id) ? 'selected' : '';
                        chargesOptions += `<option value="${charge.id}" data-amount="${charge.amount}" ${isSelected}>${charge.type}</option>`;
                    });
                    $('#edit_charges').html(chargesOptions).trigger('change');

                    $('#edit_charges').select2({
                        placeholder: "Select Charges",
                        allowClear: true
                    }).on('change', function () {
                        updateAmountAndTotal();
                    });

                    updateAmountAndTotal();
                },
                error: function () {
                    Swal.fire('Error!', 'Failed to fetch dropdown data.', 'error');
                }
            });

            function updateAmountAndTotal() {
                const selectedCharges = $('#edit_charges').val(); 
                let totalAmount = 0;

                selectedCharges.forEach(function (chargeId) {
                    const charge = $('#edit_charges option[value="' + chargeId + '"]');
                    totalAmount += parseFloat(charge.data('amount')) || 0; 
                });

                $('#edit_amount').val(totalAmount || '');

                const returnAmount = parseFloat($('#edit_return_amount').val()) || 0;
                const finalTotalAmount = totalAmount - returnAmount;
                $('#edit_total_amount').val(finalTotalAmount);
            }
            $('#edit_return_amount').on('input', function () {
                updateAmountAndTotal();
            });

            updateAmountAndTotal();
        },
        error: function () {
            Swal.fire('Error!', 'Failed to fetch appointment details.', 'error');
        }
    });
}


function updateData(appointmentId) {
    const fullname = $('#edit_fullname').val();
    const phone = $('#edit_phone').val();
    const address = $('#edit_address').val();
    const age = $('#edit_age').val();
    const gender = $('#edit_gender').val();
    const referredBy = $('#edit_referred_by').val();
    const charges = $('#edit_charges').val(); 
    const amount = $('#edit_amount').val();
    const returnAmount = $('#edit_return_amount').val();
    const totalAmount = $('#edit_total_amount').val();
    const paymentType = $('#edit_payment_type').val();
    const isRelative = document.getElementById('edit_is_relative').checked ? 'yes' : 'no'; // Corrected here

    if (!fullname || !phone || !address || !age || !gender || !charges || !amount || !paymentType || totalAmount === '') {
        Swal.fire('Error', 'Please fill in all fields.', 'error');
        return;
    }

    $.ajax({
        url: "{{ route('patient.edit', ':id') }}".replace(':id', appointmentId),
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            appointment_id: appointmentId,
            fullname: fullname,
            phone: phone,
            address: address,
            age: age,
            gender: gender,
            referred_by: referredBy,
            charges: charges, 
            amount: amount,
            return_amount: returnAmount,
            total_amount: totalAmount,
            payment_type: paymentType,
            is_relative: isRelative // Include is_relative here

        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Success',
                    text: 'Record updated successfully.',
                    icon: 'success',
                    toast: true,              
                    position: 'top-end',       
                    timer: 3000,               
                    showConfirmButton: false,  
                });

                $('#booking-table').DataTable().ajax.reload(null, false);
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to update record.',
                    icon: 'error',
                    toast: true,               
                    position: 'top-end',       
                    timer: 3000,               
                    showConfirmButton: false,  
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error',
                text: 'Failed to update record.',
                icon: 'error',
                toast: true,               
                position: 'top-end',      
                timer: 3000,               
                showConfirmButton: false,  
            });

            console.log(xhr.responseJSON);
        }

    });
}


function cancelEdit(appointmentId) {
    location.reload();
}

// permanent Delete function
function deleteRow(appointmentId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('appointment.delete', ':id') }}".replace(':id', appointmentId),
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}", 
                    appointment_id: appointmentId, 
                },
                success: function(response) {
                    console.log(response);

                    if (response.success) {
                        Swal.fire({
                            title: 'Deleted',
                            text: 'The record has been deleted.',
                            icon: 'success',
                            toast: true,              
                            position: 'top-end',       
                            timer: 3000,               
                            showConfirmButton: false,  
                        });

                        $('#booking-table').DataTable().ajax.reload(null, false); 
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Failed to delete record.',
                            icon: 'error',
                            toast: true,               
                            position: 'top-end',       
                            timer: 3000,               
                            showConfirmButton: false,  
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Failed to delete record.',
                        icon: 'error',
                        toast: true,               
                        position: 'top-end',      
                        timer: 3000,               
                        showConfirmButton: false,  
                    });

                    // Log the error for debugging purposes
                    console.log(xhr.responseJSON);
                }
            });
        }
    });
}


//soft delete patient and appointment
function deletesoft(appointmentId) {
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
                url: "{{ route('patient.soft.delete', ':id') }}".replace(':id', appointmentId),
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Patient Soft Deleted!',
                            text: response.success,
                            icon: 'success',
                            toast: true,
                            position: 'top-end',
                            timer: 3000,
                            showConfirmButton: false,
                        });

                        $('#booking-table').DataTable().ajax.reload(null, false); // Reload the table
                    } else if (response.error) {
                        Swal.fire({
                            title: 'Error!',
                            text: response.error,
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
}

</script>





@endsection
