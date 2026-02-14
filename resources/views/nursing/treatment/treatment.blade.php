@extends('layouts.master')

@section('title', 'Treatment')

@section('content')
<div class="app-content content">
  <div class="content-overlay"></div>
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Treatment Sheet</h3>
        <div class="breadcrumbs-top">
          <div class="breadcrumb-wrapper">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="{{ URL('nursing/dashboard') }}">Dashboard</a>
              </li>
              <li class="breadcrumb-item active">Treatment Sheet</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    @include('notifications')
    <div class="content-body">
      <section class="section">
        <div class="row justify-content-center">
          <div class="col-lg-10 col-md-12">
            <div class="text-right mb-3">
              <button id="printBtn" class="btn btn-info">
                <i class="fas fa-print"></i> Print
              </button>
            </div>
            <div class="card shadow-lg">
              <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">Add Patient Treatment</h4>
              </div>
              <div class="card-body">
                <div class="row justify-content-center">
                  <div class="col-md-4">
                    <label for="select_date">Select Date:</label>
                    <input type="date" id="select_date" class="form-control" value="{{ date('Y-m-d') }}">
                  </div>
                </div>
                <div class="table-responsive mt-4">
                  <div id="medicineTables"></div>
                </div>
                <form id="treatmentForm">
                  <input type="hidden" id="patient_id" name="patient_id" value="{{ $patient->id }}">
                  <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success">
                      <i class="fa fa-save"></i> Save Treatment
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>
@endsection

@section('additionalcss')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('additionaljs')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
$(document).ready(function () {
    let patientId = $('#patient_id').val();
    let tableContainer = $('#medicineTables');

    function loadMedicines(patientId, date) {
        tableContainer.empty();
        if (patientId) {
            $.ajax({
                url: "{{ url('/nursing/treatment_sheet/get-patient-medicines') }}/" + patientId + "?date=" + date,
                type: "GET",
                success: function (response) {
                    if (date) {
                        response.medicines = response.medicines.filter(function(medicine) {
                            if (!medicine.datetime) return false;
                            let medDate = new Date(medicine.datetime).toISOString().split('T')[0];
                            return medDate === date;
                        });
                    }
                    if (response.medicines && response.medicines.length > 0) {
                        let tableHtml = `<div class="mt-4">
                            <table class="table table-bordered treatmentTable">
                                <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Medicine Name</th>
                                        <th>Quantity</th>
                                        <th>Frequency</th>
                                        <th>Note</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                        response.medicines.forEach(function(medicine) {
                            let formattedDateTime = medicine.datetime 
                                ? new Date(medicine.datetime).toISOString().slice(0, 16) 
                                : '';
                            let frequencyCount = getFrequencyCount(medicine.frequency);
                            let actionCheckboxes = '';
                            let statusArr = medicine.status ? medicine.status.split(',') : [];
                            for (let i = 0; i < frequencyCount; i++) {
                                let isChecked = (statusArr[i] && statusArr[i].trim() === "1") ? 'checked' : '';
                                let storedTime = medicine["time" + (i + 1)] ? medicine["time" + (i + 1)] : '';
                                actionCheckboxes += `<div class="d-flex align-items-center mb-2">
                                    <input type="checkbox" class="dose-checkbox me-2" data-id="${medicine.id}" data-index="${i}" name="dose_checkbox_${medicine.id}[]" value="1" ${isChecked}>
                                    <span class="dose-time-text">${storedTime ? storedTime : ' '}</span>
                                </div>`;
                            }
                            tableHtml += `<tr>
                                <td>${formattedDateTime}</td>
                                <td>${medicine.name}</td>
                                <td>${medicine.quantity ?? ''}</td>
                                <td>${medicine.frequency ?? ''}</td>
                                <td>${medicine.note ?? ''}</td>
                                <td>${actionCheckboxes}</td>
                            </tr>`;
                        });
                        tableHtml += `</tbody></table></div>`;
                        tableContainer.append(tableHtml);
                    } else {
                        tableContainer.append('<div class="text-center mt-3 text-danger">No medicines found for this date.</div>');
                    }
                },
                error: function (xhr) {
                    console.error("Error fetching medicines:", xhr.responseText);
                    tableContainer.append('<div class="text-center text-danger">Failed to load medicines.</div>');
                }
            });
        }
    }

    let today = new Date().toISOString().split('T')[0];
    $('#select_date').val(today);
    loadMedicines(patientId, today);

    $('#select_date').on('change', function () {
        let selectedDate = $(this).val();
        loadMedicines(patientId, selectedDate);
    });

    $(document).on('change', '.dose-checkbox', function () {
        let checkbox = $(this);
        let doseTimeText = checkbox.closest('div').find('.dose-time-text');
        if (checkbox.is(':checked')) {
            let currentTime = new Date().toLocaleTimeString('en-GB', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            doseTimeText.text(currentTime);
        } else {
            doseTimeText.text('');
        }
    });

    $('#treatmentForm').submit(function (event) {
        event.preventDefault();
        let checkedDoses = [];
        $('.dose-checkbox').each(function () {
            let medicineId = $(this).data('id');
            let index = $(this).data('index');
            let doseTime = $(this).closest('div').find('.dose-time-text').text();
            checkedDoses.push({
                medicine_id: medicineId,
                index: index,
                checked: $(this).is(':checked') ? 1 : 0,
                time: doseTime
            });
        });
        $.ajax({
            url: "{{ url('/nursing/treatment_sheet/update-medicine-status') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                checkedDoses: checkedDoses
            },
            success: function () {
                Swal.fire({
                    title: 'Success!',
                    text: 'Treatment saved successfully.',
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false,
                });
                let selectedDate = $('#select_date').val();
                loadMedicines(patientId, selectedDate);
            },
            error: function (xhr) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to save treatment. Please try again.',
                    icon: 'error',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false,
                });
                console.log(xhr.responseText);
            }
        });
    });

    $('#printBtn').click(function () {
        let printContents = document.getElementById("medicineTables").innerHTML;
        let selectedDate = $('#select_date').val();
        let printWindow = window.open('', '', 'width=900,height=600');
        printWindow.document.write(`
            <html>
            <head>
                <title>Treatment Sheet - ${selectedDate}</title>
                <style>
                    body { font-family: Arial, sans-serif; background: #fff; padding: 20px; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .header h1 { font-size: 24px; margin: 0; }
                    .header p { font-size: 16px; margin: 0; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #333; padding: 10px; text-align: center; }
                    th { border: 1px solid #333; padding: 10px; text-align: center; font-size: 16px; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>Treatment Sheet</h1><br>
                    <p>Date: ${selectedDate}</p>
                </div>
                ${printContents}
                <script>
                    window.onload = function() {
                        window.print();
                        window.onafterprint = function() { window.close(); }
                    };
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    });

    function getFrequencyCount(frequency) {
        const frequencies = {
            "OD": 1, "BD": 2, "TDS": 3, "QID": 4,
            "HS": 1, "SOS": 1, "Before Food": 1,
            "After Food": 1, "PRN": 1
        };
        return frequencies[frequency] || 0;
    }
});
</script>
@endsection
