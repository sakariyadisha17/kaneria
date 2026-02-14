@extends('layouts.master')
@section('title')
    Patient Dose
@endsection
@section('content')

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Patient Dose</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ URL('nursing/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Patient Dose</li>
                </ol>
            </div>
        </div>

        @include('notifications')

        <div class="content-body">
            <section id="column">
                <div class="row">
                    <div class="col-12">
                        <div class="text-right">
                            <a href="{{url('nursing/patient_dose')}}" class="btn btn-info">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                        <form method="POST" action="{{ route('nursing.letterhead.save', ['id' => $patient->id]) }}">
                            @csrf
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                            <input type="hidden" name="appointment_id" value="{{ $patient->appointment_id  }}">
                            <div class="letterhead container p-4">
                                <!-- Date and Time -->
                                <h5>Rx  : {{@$patient->fullname}}</h5>

                                <div class="row mt-4 justify-content-center">
                                    <div class="col-md-6 text-left">
                                        <h5>Date Time:</h5>
                                        <input type="datetime-local" name="datetime" id="datetime" class="form-control form-control" required>
                                    </div>
                                </div>
                                <!-- Vitals, Report, and Medicines -->
                                <div class="row mt-4">
                                    <!-- Vitals Box -->
                                    <div class="col-md-3">
                                        <div class="vitals-box">
                                            <h5>Vitals</h5>
                                            <div class="vital-item">
                                                <label>BP:</label>
                                                <input type="text" name="bp" class="form-control form-control-sm" placeholder="BP">
                                            </div>
                                            <div class="vital-item">
                                                <label>P:</label>
                                                <input type="text" name="pulse" class="form-control form-control-sm" placeholder="Pulse">
                                            </div>
                                            <div class="vital-item">
                                                <label>SPO<sub>2</sub>:</label>
                                                <input type="text" name="spo2" class="form-control form-control-sm" placeholder="SPO2">
                                            </div>
                                            <div class="vital-item">
                                                <label>T:</label>
                                                <input type="text" name="temp" class="form-control form-control-sm" placeholder="Temperature">
                                            </div>
                                            <div class="vital-item">
                                                <label>RS</label>
                                                <input type="text" name="rs" class="form-control form-control-sm" placeholder="RS">
                                            </div>
                                            <div class="vital-item">
                                                <label>CVS:</label>
                                                <input type="text" name="cvs" class="form-control form-control-sm" placeholder="CVS">
                                            </div>
                                            <div class="vital-item">
                                                <label>ECG:</label>
                                                <input type="text" name="ecg" class="form-control form-control-sm" placeholder="ECG">
                                            </div>
                                            <div class="vital-item">
                                                <label>RBS:</label>
                                                <input type="text" name="rbs" class="form-control form-control-sm" placeholder="RBS">
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><h5>Report</h5></label>
                                                    <textarea name="report" class="form-control" rows="3" placeholder="Enter report details..."></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                <label for="co"><h5>C/O (Chief Complaint):</h5></label>
                                                <textarea class="form-control" name="complaint" rows="3" placeholder="Chief Complaint"></textarea>
                                            </div>
                                        </div>
                                        </div>

                                    </div>

                                     <!-- Medicines Box -->
                                     <div class="col-md-9">
                                     <div id="medicinesTable">
                                     <h5>Medicines</h5>
                                            <div class="text-right mb-2">
                                                <button class="btn btn-secondary btn-sm" type="button" id="addFieldBtn">+ Add Medicine</button>
                                            </div>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Medicine</th>
                                                        <th>Quantity</th>
                                                        <th>Frequency</th>
                                                        <th>Note</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="medicinesList"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                
                                </div>
                               
                                   
                              
                                <!-- Save Button -->
                                <div class="row mt-4">
                                    <div class="col-12 text-right">
                                        <button class="btn btn-success" id="saveDetailsBtn" type="submit">Save</button>
                                    </div>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

@endsection

@section('additionalcss')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f1f3f5;
    }
    .letterhead {
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
    }
    .header img {
        max-height: 80px;
    }
    .header h4 {
        font-size: 22px;
        font-weight: bold;
    }
    .vitals-box, .form-group {
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #f9f9f9;
    }
    .vital-item {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }
    .vital-item label {
        font-size: 14px;
        margin-right: 10px;
        flex: 1;
    }
    .vital-item input {
        flex: 2;
    }
    textarea {
        resize: none;
        border-radius: 5px;
        padding: 10px;
    }
    .table-bordered th, .table-bordered td {
        border: 1px solid #ddd;
        text-align: center;
    }
    .btn {
        font-size: 14px;
    }
</style>
@endsection

@section('additionaljs')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
        let now = new Date();

        let year = now.getFullYear();
        let month = String(now.getMonth() + 1).padStart(2, '0');
        let day = String(now.getDate()).padStart(2, '0');
        let hours = String(now.getHours()).padStart(2, '0');
        let minutes = String(now.getMinutes()).padStart(2, '0');

        // Setting the initial value with date and time (24-hour format)
        let formattedDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;

        document.getElementById("datetime").value = formattedDateTime;

        // Initialize flatpickr for datetime picker
        flatpickr("#datetime", {
            enableTime: true,      // Enable time selection
            dateFormat: "Y-m-d\\TH:i", // Use the correct format (YYYY-MM-DDTHH:mm)
        });
    });
</script>
<script>
     $('#addFieldBtn').click(function () {
    const newRow = `
        <tr>
            <td>
                <select name="medicine[]" class="form-control medicine-select"></select>
            </td>
            <td><input type="number" name="quantity[]" class="form-control" min="1"></td>
            <td>
                <select name="frequency[]" class="form-control freq-select">
                    <option value="OD">OD</option>
                    <option value="BD">BD</option>
                    <option value="TDS">TDS</option>
                    <option value="SOS">SOS</option>
                    <option value="QID ">QID </option>
                    <option value="HS">HS</option>
                    <option value="Before Food">Before Food</option>
                    <option value="After Food">After Food</option>
                    <option value="IV">IV</option>
                </select>
            </td>
            <td>
                <select name="note[]" class="form-control note-select">
                    <option value="No">No</option>
                    <option value="જમ્યા પછી">જમ્યા પછી</option>
                    <option value="જમ્યા પહેલાં">જમ્યા પહેલાં</option>
                    <option value="જરુર પડે ત્યારે">જરુર પડે ત્યારે</option>
                </select>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-success saveMedicineBtn">
                    <i class="fas fa-check"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger cancelMedicineBtn">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        </tr>
    `;
    $('#medicinesList').append(newRow);

    $('#medicinesList tr:last .freq-select').select2({
        placeholder: "Select a frequency",
        allowClear: true,
        tags: true, // Allow new values
        createTag: function (params) {
            return {
                id: params.term,
                text: params.term,
                newTag: true
            };
        }
    });
    $('#medicinesList tr:last .note-select').select2({
        placeholder: "Select a note",
        allowClear: true,
        tags: true, // Allow new values
        createTag: function (params) {
            return {
                id: params.term,
                text: params.term,
                newTag: true
            };
        }
    });

    fetchMedicineOptions($('#medicinesList tr:last .medicine-select'));
    });

    function fetchMedicineOptions(selectElement, selectedId = null) {
        $.ajax({
            url: '{{ route("nursing.get.medicine.list") }}',
            method: 'GET',
            success: function (response) {
              
                let optionsHtml = '<option value="">Select Medicine</option>';
                response.forEach(medicine => {
                    optionsHtml += `
                        <option value="${medicine.id}" ${medicine.id == selectedId ? 'selected' : ''}>
                            ${medicine.name}
                        </option>
                    `;
                });
                selectElement.html(optionsHtml).select2({
                    tags: true,
                    placeholder: "Select or add a new medicine",
                });
            },
            error: function () {
                alert('Failed to fetch medicine list.');
            },
        });
    }


$(document).on('click', '.saveMedicineBtn', function () {
    const row = $(this).closest('tr');
    const medicineId = row.find('.medicine-select').val();
    const medicineName = row.find('.medicine-select option:selected').text();
    const quantity = row.find('input[name="quantity[]"]').val();
    const frequency = row.find('select[name="frequency[]"]').val();
    const note = row.find('select[name="note[]"]').val();


    if (!medicineId || !quantity || !frequency ) {
        alert('Please fill in all fields.');
        return;
    }

    row.data('medicineId', medicineId);

    row.html(`
        <td>${medicineName}</td>
        <td>${quantity}</td>
        <td>${frequency}</td>
        <td>${note}</td>
        <td>
            <button type="button" class="btn btn-sm btn-primary editMedicineBtn">
                <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn btn-sm btn-danger deleteMedicineBtn">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `);
});

$(document).on('click', '.editMedicineBtn', function () {
    const row = $(this).closest('tr');
    const medicineId = row.data('id');
    const quantity = row.find('td').eq(1).text();
    const frequency = row.find('td').eq(2).text().trim();
    const note = row.find('td').eq(3).text().trim();

    row.html(`
        <td>
            <select name="medicine[]" class="form-control medicine-select"></select>
        </td>
        <td><input type="number" name="quantity[]" class="form-control" value="${quantity}" min="1"></td>
        <td>
            <select name="frequency[]" class="form-control freq-select">
                <option value="OD" ${frequency === 'OD' ? 'selected' : ''}>OD</option>
                <option value="BD" ${frequency === 'BD' ? 'selected' : ''}>BD</option>
                <option value="TDS" ${frequency === 'TDS' ? 'selected' : ''}>TDS</option>
                <option value="SOS" ${frequency === 'SOS' ? 'selected' : ''}>SOS</option>
                <option value="QID " ${frequency === 'QID ' ? 'selected' : ''}>QID </option>
                <option value="HS" ${frequency === 'HS' ? 'selected' : ''}>HS</option>
                <option value="Before Food" ${frequency === 'Before Food' ? 'selected' : ''}>Before Food</option>
                <option value="After Food" ${frequency === 'After Food' ? 'selected' : ''}>After Food</option>
                <option value="IV" ${frequency === 'IV' ? 'selected' : ''}>IV</option>
            </select>
        </td>
        <td>
            <select name="note[]" class="form-control note-select">
                <option value="No" ${note === 'No' ? 'selected' : ''}>No</option>
                <option value="જમ્યા પછી" ${note === 'જમ્યા પછી' ? 'selected' : ''}>જમ્યા પછી</option>
                <option value="જમ્યા પહેલાં" ${note === 'જમ્યા પહેલાં' ? 'selected' : ''}>જમ્યા પહેલાં</option>
                <option value="જરુર પડે ત્યારે" ${note === 'જરુર પડે ત્યારે' ? 'selected' : ''}>જરુર પડે ત્યારે</option>
            </select>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-success saveMedicineBtn">
                <i class="fas fa-check"></i>
            </button>
            <button type="button" class="btn btn-sm btn-danger cancelMedicineBtn">
                <i class="fas fa-times"></i>
            </button>
        </td>
    `);

    row.find('.freq-select').select2({
        placeholder: "Select a frequency",
        allowClear: true,
        tags: true, // Allow new values
        createTag: function (params) {
            return {
                id: params.term,
                text: params.term,
                newTag: true
            };
        }
    });

    fetchMedicineOptions(row.find('.medicine-select'), medicineId);
});

$(document).on('click', '.cancelMedicineBtn', function () {
    $(this).closest('tr').remove();
});

$(document).on('click', '.deleteMedicineBtn', function () {
    $(this).closest('tr').remove();
});



$('#saveDetailsBtn').click(function (e) {
    e.preventDefault();

    var medicines = [];

    $('#medicinesList tr').each(function () {
        var medicineName = $(this).find('td').eq(0).text();
        var quantity = $(this).find('td').eq(1).text();
        var frequency = $(this).find('td').eq(2).text();
        var note = $(this).find('td').eq(3).text();


        console.log('Medicine Name:', medicineName);
        console.log('Quantity:', quantity);
        console.log('Frequency:', frequency);
        console.log('Note:', note);


        // Ensure all fields are filled before pushing to the array
        if (medicineName && quantity && frequency && note) {
            medicines.push({
                medicineName: medicineName,
                quantity: quantity,
                frequency: frequency,
                note: note

            });
        } else {
            console.log('Incomplete row detected, skipping.');
        }
    });

    
    // Prepare data to send to server
    var data = {
        _token: '{{ csrf_token() }}',
        patient_id: '{{ $patient->id }}',
        appointment_id: '{{ $patient->appointment_id }}',
        bp: $('input[name="bp"]').val(),
        pulse: $('input[name="pulse"]').val(),
        spo2: $('input[name="spo2"]').val(),
        temp: $('input[name="temp"]').val(),
        rs: $('input[name="rs"]').val(),
        cvs: $('input[name="cvs"]').val(),
        ecg: $('input[name="ecg"]').val(),
        rbs: $('input[name="rbs"]').val(),
        report: $('textarea[name="report"]').val(),
        complaint: $('textarea[name="complaint"]').val(),
       
        datetime: $('input[name="datetime"]').val(),
        medicines: medicines 
    };
    console.log($('input[name="diagnosis"]').val());
    $.ajax({
        url: '{{ route("nursing.letterhead.save", ":id") }}'.replace(':id', '{{ $patient->id }}'),
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(data), 
      
        success: function(response) {
        if (response.success) {

            Swal.fire({
                text: response.message,
                icon: 'success',
                toast: true,
                position: 'top-right',
                timer: 5000,
                showConfirmButton: false,
                willClose: () => {
                    location.reload();
                }
            });

        } else {
            Swal.fire({
                text: response.message,
                icon: 'error',
                toast: true,
                position: 'top-right',
                timer: 5000,
                showConfirmButton: false,
            });
        }
    },
    error: function(xhr) {
        toastr.error('An error occurred while uploading the file.');
    }
    });
});
</script>
@endsection
