@extends('layouts.master')
@section('title')
    Letterhead
@endsection
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Letterhead</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL('receptionist/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Letterhead</li>
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
                        <!-- <div class="card"> -->
                        
                            <div class="text-right">
                                <div class="btn-group">
                                    <a href="{{url('receptionist/appointments')}}">
                                    <button class="btn btn-primary">
                                        <i class="fas fa-arrow-left"></i> Back
                                    </button></a>
                                    <a href="{{ route('reception.letterhead.print', ['id' => $patient->id]) }}">
                              <button class="btn btn-info" onclick="window.location.href='{{ route('reception.letterhead.print', $patient->id) }}'">
                                        <i class="fas fa-print"></i> Print Letterhead
                                    </button>
                                    </a>                        
                                </div>
                            </div>
                        <form method="POST" action="{{ route('letterhead.save', ['id' => $patient->id]) }}">
                            @csrf
                                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                <input type="hidden" name="appointment_id" value="{{ $patient->appointment_id  }}">
                            <div class="letterhead container p-4">
                                <!-- Header Section -->
                                <div class="header row mb-4">
                                    <div class="logo col-md-2">
                                        <img src="{{ url('logos/logo.jpg') }}" alt="Kaneria Hospital Logo" class="img-fluid">
                                    </div>
                                    <div class="details col-md-10 text-right">
                                        <h4>Dr. Maulik V. Kaneria</h4>
                                        <p>Consultant Physician</p>
                                        <p>M.D. Medicine</p>
                                        <p>Phone: +91 99133 40805</p>
                                    </div>
                                </div>

                                <!-- Patient Info -->
                                <div class="rx-date row mb-4">
                                    <div class="col-md-6">
                                        <h5>Rx : {{ @$patient->fullname }} ({{ @$patient->gender }}) - {{@$patient->address}}</h5>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <h5>Date: {{ \Carbon\Carbon::parse(@$patient->created_at)->format('d-m-Y') }}</h5>
                                    </div>
                                </div>

                                <!-- Vitals and Diagnosis -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="vitals-box">
                                            <h5>Vitals:</h5>
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
                                        <div class="form-group mt-2">
                                            <label for="addition"><h5>Addition:</h5></label>
                                            <textarea id="addition" name="addition" class="form-control" rows="3" placeholder="Addition"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="co"><h5>C/O (Chief Complaint):</h5></label>
                                            <textarea class="form-control" name="complaint" rows="4" placeholder="Chief Complaint"></textarea>
                                        </div>
                                        <div class="form-group ">
                                            <label for="addition"><h5>Past History:</h5></label>
                                            <textarea class="form-control" name="history" rows="4" placeholder="Past History"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <h5>Diagnosis:</h5>
                                        <select id="diagnosis" name="diagnosis[]" class="form-control" onchange="fetchDiagnosisDetails()" multiple>
                                            <option value="">Select Diagnosis</option>
                                            @foreach($diagnosis as $diagno)
                                                <option value="{{ $diagno->id }}">{{ $diagno->name }}</option>
                                            @endforeach
                                        </select>
                                        
                                        <br><br>

                                        <div class="form-group">
                                            <label for="report"><h5>Add Report:</h5></label>
                                            <textarea id="report" name="report" class="form-control" rows="3" placeholder="Report"></textarea>
                                        </div>
                                    
                                        <div id="medicinesTable">
                                            <h5>Medicines:</h5>
                                            <div class="row mb-2">
                                                <div class="col-12 text-right">
                                                    <button class="btn btn-secondary btn-sm" type="button" id="addFieldBtn">Add Medicine</button>
                                                </div>
                                            </div>
                                            <table class="table table-bordered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Medicine</th>
                                                        <th>Quantity</th>
                                                        <th>Frequency</th>
                                                        <th>Note</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="medicinesList">
                                                    <!-- Medicines rows will be added dynamically -->
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                                
                                <!-- Diet Chart -->
                                <div class="row mt-2">
                                    <div class="col-md-9">
                                        <h5>Diet Chart:</h5>
                                        <textarea class="form-control" name="diat" rows="5" placeholder="Diet Chart"></textarea>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-9">
                                        <h6><b>સુચના:</b></h6>
                                        <textarea class="form-control" name="note" rows="6" placeholder="Enter Note"></textarea>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-9">
                                        <h6><b>ફરી બતાવવા આવો ત્યારે નીચે બતાવેલ રીપોર્ટ કરાવીને આવવું.</b></h6>
                                        <textarea id="next_report" name="next_report" class="form-control" rows="3" placeholder="Report"></textarea>

                                    </div>
                                </div> 

                                <div class="row mt-2">
                                    <div class="col-12 col-md-6 text-right mb-3">
                                        <h5>કેટલા દિવસ પછી : 
                                            <input type="number" id="daysInput" class="form-control d-inline-block w-auto"  min="1">
                                        </h5>
                                        <h5>ફરી બતાવવા ની તારીખ: 
                                            <input type="text" id="nextAppointmentDate" class="form-control d-inline-block w-auto" name="next_date" readonly>
                                        </h5>                                    
                                    </div>
                                    
                                    
                                    <div class="row mt-4">
                                        <div class="col-12 text-right">
                                            <button class="btn btn-success" id="saveDetailsBtn" type="submit">Save</button>
                                        </div>
                                    </div>

                                    <div class="col-12 text-right mb-2">
                                        <h5><b>DR. MAULIK KANERIA<b></h5>
                                        <h5>M/D MEDICINE</h5>
                                        <h5>G-47959</h5>
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
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f1f3f5;
    }
    h5{
        font-weight: 500;
    }

    .letterhead {
        background-color: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
    }

    .header img {
        max-height: 100px;
        margin-right: 20px;
    }

    .details h4 {
        font-size: 22px;
        margin: 0;
        font-weight: bold;
    }

    .details p {
        margin: 5px 0;
        font-size: 14px;
    }

    .rx-date h5 {
        font-size: 16px;
    }

    .vitals-box {
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
    }

    .vitals-box h5 {
        margin-bottom: 15px;
    }

    .vital-item {
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
    }

    .vital-item label {
        font-size: 14px;
        margin-right: 10px;
        flex: 1;
    }

    .vital-item input {
        width: 60%;
        padding: 8px;
        border-radius: 4px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    .table-bordered th, .table-bordered td {
        border: 1px solid #ddd;
        text-align: center;
    }

    textarea {
        resize: none;
        border-radius: 5px;
        border: 1px solid #ccc;
        padding: 10px;
    }

    .btn-group .btn {
        font-size: 16px;
        padding: 8px 15px;
        border-radius: 4px;
        border: none;
    }

    .form-control {
        border-radius: 4px;
        padding: 8px;
        border: 1px solid #ccc;
    }

    .form-control-sm {
        padding: 5px;
    }


    .select2-container .select2-selection--multiple .select2-selection__choice {
        font-size: 14px; 
        padding: 2px 5px; 
        margin: 2px;
        
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        font-size: 14px; 
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


@endsection

@section('additionaljs')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $('#diagnosis').select2({
            placeholder: "Select diagnosis",
            allowClear: true,
        });

    });
</script>


<script>
document.getElementById('daysInput').addEventListener('input', function () {
    var days = parseInt(this.value);

    if (!isNaN(days) && days > 0) {
        var currentDate = new Date();
        currentDate.setDate(currentDate.getDate() + days);

        var dd = String(currentDate.getDate()).padStart(2, '0');
        var mm = String(currentDate.getMonth() + 1).padStart(2, '0');
        var yyyy = currentDate.getFullYear();

        var formattedDate = dd + '-' + mm + '-' + yyyy;
        document.getElementById('nextAppointmentDate').value = formattedDate;

        updateMedicineQuantities(days);
    } else {
        document.getElementById('nextAppointmentDate').value = '';
        resetMedicineQuantities();
    }
});

function updateMedicineQuantities(days) {
    const medicineRows = document.querySelectorAll('#medicinesList tr');
    medicineRows.forEach(row => {
        const quantityCell = row.querySelector('td:nth-child(2)');
        const frequencyCell = row.querySelector('td:nth-child(3)');

        const originalQuantity = parseInt(quantityCell.dataset.originalQuantity || quantityCell.textContent.trim());
        const frequency = frequencyCell.textContent.trim();

        // Calculate multiplier dynamically based on frequency
        let multiplier = calculateMultiplier(frequency);

        const newQuantity = multiplier * days;

        quantityCell.dataset.originalQuantity = originalQuantity;
        quantityCell.textContent = newQuantity > 0 ? newQuantity : originalQuantity;
    });
}

function calculateMultiplier(frequency) {
    if (!frequency) return 1; // Default multiplier if frequency is undefined

    if (frequency === 'BD') {
        return 2; // Twice daily
    } else if (frequency === 'TDS') {
        return 3; // Three times a day
    } else if (frequency === 'OD') {
        return 1; // Once daily
    } else if (frequency === 'SOS') {
        return 0; // As needed
    } else if (frequency.includes('-')) {
        // For frequencies like 1-0-0, ½-0-½, etc.
        return frequency.split('-').reduce((sum, part) => {
            if (part === '½') return sum + 0.5; // Convert '½' to 0.5
            return sum + (isNaN(parseFloat(part)) ? 0 : parseFloat(part)); // Add numeric parts
        }, 0);
    } else if (frequency.toLowerCase().includes('week')) {
        return 1 / 7; // For "week wise" values
    }

    return 1; // Default to once daily for other cases
}

function resetMedicineQuantities() {
    const medicineRows = document.querySelectorAll('#medicinesList tr');
    medicineRows.forEach(row => {
        const quantityCell = row.querySelector('td:nth-child(2)');
        const originalQuantity = quantityCell.dataset.originalQuantity;

        if (originalQuantity) {
            quantityCell.textContent = originalQuantity;
        }
    });
}

</script>

<script>
$(document).ready(function () {
    function formatDateToYMD(date) {
        const [day, month, year] = date.split('-');
        return `${year}-${month}-${day}`;
    }

    function formatDateToDMY(date) {
        const [year, month, day] = date.split('-');
        return `${day}-${month}-${year}`;
    }
});
</script>

<script>
    // Fetch Diagnosis Details
    function fetchDiagnosisDetails() {
        let selectedDiagnoses = $('#diagnosis').val(); // Get selected values (array)

        console.log('Selected Diagnosis IDs:', selectedDiagnoses); // Debugging

        if (selectedDiagnoses.length > 0) {
            $.ajax({
                url: '{{ route("get.diagnosis.details") }}',
                method: 'POST',
                data: {
                    diagnosis_id: selectedDiagnoses, // Send array of IDs
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    console.log('Response:', response); // Debugging

                    if (response.success) {
                        $('textarea[name="diat"]').val(response.diet || '');
                        $('textarea[name="note"]').val(response.note || '');

                        // Update medicines list
                        let medicinesHtml = '';
                        response.medicines.forEach(medicine => {
                            medicinesHtml += `
                                <tr data-id="${medicine.id}">
                                    <td>${medicine.name}</td>
                                    <td>${medicine.quantity}</td>
                                    <td>${medicine.frequency}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary editMedicineBtn">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger deleteMedicineBtn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                        $('#medicinesList').html(medicinesHtml);
                    } else {
                        alert(response.message || 'Failed to fetch diagnosis details.');
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching details:', xhr.responseJSON);
                    alert('An error occurred while fetching diagnosis details.');
                },
            });
        } else {
            $('textarea[name="diat"]').val('');
            $('textarea[name="note"]').val('');
        }
    }


    $('#addFieldBtn').click(function () {
    const newRow = `
        <tr>
            <td>
                <select name="medicine[]" class="form-control medicine-select"></select>
            </td>
            <td><input type="number" name="quantity[]" class="form-control" min="1"></td>
            <td>
                <select name="frequency[]" class="form-control freq-select">
                    <option value="1-0-0">1-0-0</option>
                    <option value="1-0-½">1-0-½</option>
                    <option value="½-0-0">½-0-0</option>
                    <option value="TWICE A WEEK">TWICE A WEEK</option>
                    <option value="1-1-0">1-1-0</option>
                    <option value="1-0-1">1-0-1</option>
                    <option value="1-1-1">1-1-1</option>
                    <option value="0-1-0">0-1-0</option>
                    <option value="0-0-1">0-0-1</option>
                    <option value="0-1-1">0-1-1</option>
                    <option value="½-0-½">½-0-½</option>
                    <option value="1-½-0">1-½-0</option>
                    <option value="½-½-0">½-½-0</option>
                    <option value="½-½-½">½-½-½</option>
                    <option value="0-0-½">0-0-½</option>
                    <option value="0-½-0">0-½-0</option>
                    <option value="SOS">SOS</option>
                    <option value="week wise 1">week wise 1</option>
                </select>
            </td>
            <td>
                <select name="note[]" class="form-control note-select">
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

    // Initialize Select2 for frequency dropdown
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

    fetchMedicineOptions($('#medicinesList tr:last .medicine-select'));
    });

    function fetchMedicineOptions(selectElement, selectedId = null) {
        $.ajax({
            url: '{{ route("get.medicine.list") }}',
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
                <option value="1-0-0" ${frequency === '1-0-0' ? 'selected' : ''}>1-0-0</option>
                <option value="1-0-½" ${frequency === '1-0-½' ? 'selected' : ''}>1-0-½</option>
                <option value="½-0-0" ${frequency === '½-0-0' ? 'selected' : ''}>½-0-0</option>
                <option value="TWICE A WEEK" ${frequency === 'TWICE A WEEK' ? 'selected' : ''}>TWICE A WEEK</option>
                <option value="1-1-0" ${frequency === '1-1-0' ? 'selected' : ''}>1-1-0</option>
                <option value="1-0-1" ${frequency === '1-0-1' ? 'selected' : ''}>1-0-1</option>
                <option value="1-1-1" ${frequency === '1-1-1' ? 'selected' : ''}>1-1-1</option>
                <option value="0-1-0" ${frequency === '0-1-0' ? 'selected' : ''}>0-1-0</option>
                <option value="0-0-1" ${frequency === '0-0-1' ? 'selected' : ''}>0-0-1</option>
                <option value="0-1-1" ${frequency === '0-1-1' ? 'selected' : ''}>0-1-1</option>
                <option value="½-0-½" ${frequency === '½-0-½' ? 'selected' : ''}>½-0-½</option>
                <option value="1-½-0" ${frequency === '1-½-0' ? 'selected' : ''}>1-½-0</option>
                <option value="½-½-0" ${frequency === '½-½-0' ? 'selected' : ''}>½-½-0</option>
                <option value="½-½-½" ${frequency === '½-½-½' ? 'selected' : ''}>½-½-½</option>
                <option value="0-0-½" ${frequency === '0-0-½' ? 'selected' : ''}>0-0-½</option>
                <option value="0-½-0" ${frequency === '0-½-0' ? 'selected' : ''}>0-½-0</option>
                <option value="SOS" ${frequency === 'SOS' ? 'selected' : ''}>SOS</option>
                <option value="week wise 1" ${frequency === 'week wise 1' ? 'selected' : ''}>week wise 1</option>
            </select>
        </td>
        <td>
            <select name="note[]" class="form-control note-select">
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


// Submit Form
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
        addition: $('textarea[name="addition"]').val(),
        complaint: $('textarea[name="complaint"]').val(),
        past_history: $('textarea[name="history"]').val(),
        diagnosis: $('select[id="diagnosis"]').val() || [], 
        report: $('textarea[name="report"]').val(),
        next_report: $('textarea[name="next_report"]').val(),

        diat: $('textarea[name="diat"]').val(),
        note: $('textarea[name="note"]').val(),
        next_date: $('input[name="next_date"]').val(),
        medicines: medicines 
    };
    console.log($('input[name="diagnosis"]').val());
    $.ajax({
        url: '{{ route("letterhead.save", ":id") }}'.replace(':id', '{{ $patient->id }}'),
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
        }
    },

    error: function(xhr) {
        // Extract error message from the JSON response
        const response = xhr.responseJSON;
        const message = response?.error || 'Something went wrong.';

        // Redirect and optionally pass error via query string
        window.location.href = "/error-display-page?message=" + encodeURIComponent(error);
    }
    });
});

</script>

@endsection
