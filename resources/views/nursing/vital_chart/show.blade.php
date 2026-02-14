@extends('layouts.master')
@section('title', 'Vital chart')

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
  <div class="content-overlay"></div>
  <div class="content-wrapper">
    <!-- Header with Breadcrumbs -->
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">Vital Chart</h3>
        <div class="row breadcrumbs-top">
          <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ URL('receptionist/dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Vital Chart</li>
            </ol>
          </div>
        </div>
      </div>
      
    </div>

    <div class="content-body">
      <section id="column">
        <div class="row">
          <div class="col-12">
            <!-- Top Button -->
            <div class="text-right">
            <a href="{{ route('nursing.vital_chart.print', ['id' => $patient->id]) }}">
                <button id="printBtn" class="btn btn-info">
                    <i class="fas fa-print"></i> Print
                </button>
            </a>

            </div>

            <!-- Vital Chart Card -->
            <div class="card">
              <div class="card-header">
                 <h4 class="card-title">Vital Chart</h4>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <label for="select_date">Select Date:</label>
                            <input type="date" id="select_date" class="form-control" value="{{ date('Y-m-d') }}">
                            <input type="hidden" id="patient_id" name="patient_id" value="{{ $patient->id }}">                        </div>
                    </div>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                        </ul>
                    </div>
              </div>
              <div class="card-content collapse show">
                <div class="card-body card-dashboard">
                    <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table table-striped table-bordered" id="vital-table" style="min-width: 1200px;">
                    <colgroup>
                        <col style="width: 9%;">
                        <col style="width: 9%;">
                        <col style="width: 9%;">
                        <col style="width: 9%;">
                        <col style="width: 9%;">
                        <col style="width: 9%;">
                        <col style="width: 9%;">
                        <col style="width: 9%;">
                        <col style="width: 9%;">
                        <col style="width: 9%;">
                        <col style="width: 10%;">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>DateTime</th>
                            <th>Temp</th>
                            <th>Pulse/Min</th>
                            <th>BP/mmHg</th>
                            <th>Spo2</th>
                            <th>Input</th>
                            <th>Output</th>
                            <th>RBS</th>
                            <th>RT</th>
                            <th>Remark</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    </div>
                </div>
                </div>

            </div><!-- /.card -->
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Keeps table readable and scrollable on small devices */
.table-responsive {
  width: 100%;
  overflow-x: auto;
}

#vital-table {
  min-width: 1200px; /* or more based on how wide each column needs to be */
  table-layout: auto;
}

#vital-table th,
#vital-table td {
  white-space: nowrap;
}

  .dataTables_empty {
      display: none !important;
  }
  /* #vital-table td,
  #vital-table th {
    padding: 6px !important;
  }
  #vital-table td input.form-control {
    padding: 6px !important;
    font-size: 14px;
  } */
</style>
@endsection

@section('additionaljs')
<script src="{{ url('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ url('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    $('#printBtn').click(function() {
    let tableClone = $('#vital-table').clone(); // Clone the table
    tableClone.find('.no-print').remove(); // Remove action column

    let printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
        <html>
        <head>
            <title>Print Vital Chart</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th, td { border: 1px solid #000; padding: 10px; text-align: left; }
                th { background-color: #28a745; color: white; }
                h2 { text-align: center; }
            </style>
        </head>
        <body>
            <h2>Vital Chart Report</h2>
            ${tableClone.prop('outerHTML')}
            <script>
                window.onload = function() {
                    window.print();
                    window.onafterprint = function() { window.close(); };
                };
            </script>
        </body>
        </html>
    `);
});

</script>
<script>
$(document).ready(function () {
    flatpickr("#select_date", {
        dateFormat: "Y-m-d",
        defaultDate: new Date(),
        onChange: function(selectedDates, dateStr, instance) {
            table.ajax.reload();
        }
    });

    var table = $('#vital-table').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 35,
        language: {
            emptyTable: "",
            zeroRecords: ""
        },
        ajax: {
            url: "{{ route('nursing.vital_chart') }}",
            type: 'GET',
            data: function(d) {
                d.select_date = $('#select_date').val();
            }
        },
        createdRow: function(row, data, dataIndex) {
            $(row).attr('data-id', data.id);
        },
        columns: [
            {
                data: 'datetime',
                name: 'datetime',
                render: function(data, type, row, meta) {
                    return data ? data : '';
                }
            },
            {
                data: 'temp',
                name: 'temp',
                render: function(data, type, row, meta) {
                    if (!row.datetime) {
                        return `<input type="text" class="form-control temp" placeholder="Temp" value="${data ? data : ''}">`;
                    } else {
                        return `<span class="temp">${data ? data : ''}</span>`;
                    }
                }
            },
            {
                data: 'pulse',
                name: 'pulse',
                render: function(data, type, row, meta) {
                    if (!row.datetime) {
                        return `<input type="text" class="form-control pulse" placeholder="Pulse/Min" value="${data ? data : ''}">`;
                    } else {
                        return `<span class="pulse">${data ? data : ''}</span>`;
                    }
                }
            },
            {
                data: 'bp',
                name: 'bp',
                render: function(data, type, row, meta) {
                    if (!row.datetime) {
                        return `<input type="text" class="form-control bp" placeholder="BP/mmHg" value="${data ? data : ''}">`;
                    } else {
                        return `<span class="bp">${data ? data : ''}</span>`;
                    }
                }
            },
            {
                data: 'spo2',
                name: 'spo2',
                render: function(data, type, row, meta) {
                    if (!row.datetime) {
                        return `<input type="text" class="form-control spo2" placeholder="Spo2" value="${data ? data : ''}">`;
                    } else {
                        return `<span class="spo2">${data ? data : ''}</span>`;
                    }
                }
            },
            {
                data: 'input',
                name: 'input',
                render: function(data, type, row, meta) {
                    if (!row.datetime) {
                        return `<input type="text" class="form-control input" placeholder="Input" value="${data ? data : ''}">`;
                    } else {
                        return `<span class="input">${data ? data : ''}</span>`;
                    }
                }
            },
            {
                data: 'output',
                name: 'output',
                render: function(data, type, row, meta) {
                    if (!row.datetime) {
                        return `<input type="text" class="form-control output" placeholder="Output" value="${data ? data : ''}">`;
                    } else {
                        return `<span class="output">${data ? data : ''}</span>`;
                    }
                }
            },
            {
                data: 'rbs',
                name: 'rbs',
                render: function(data, type, row, meta) {
                    if (!row.datetime) {
                        return `<input type="text" class="form-control rbs" placeholder="RBS" value="${data ? data : ''}">`;
                    } else {
                        return `<span class="rbs">${data ? data : ''}</span>`;
                    }
                }
            },
            {
                data: 'rt',
                name: 'rt',
                render: function(data, type, row, meta) {
                    if (!row.datetime) {
                        return `<input type="text" class="form-control rt" placeholder="RT" value="${data ? data : ''}">`;
                    } else {
                        return `<span class="rt">${data ? data : ''}</span>`;
                    }
                }
            },
            {
                data: 'remark',
                name: 'remark',
                render: function(data, type, row, meta) {
                    if (!row.datetime) {
                        return `<input type="text" class="form-control remark" placeholder="Remark" value="${data ? data : ''}">`;
                    } else {
                        return `<span class="remark">${data ? data : ''}</span>`;
                    }
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                render: function(data, type, row, meta) {
                    if (!row.datetime) {
                        return `<button class="btn btn-sm addBtn" data-id="${row.id ? row.id : ''}"><i class="fa fa-plus"></i></button>`;
                    } else {
                        return `<button class="btn btn-sm btn-info editBtn" data-id="${row.id}"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger deleteBtn" data-id="${row.id}"><i class="fa fa-trash"></i></button>`;
                    }
                }
            }
        ],
        drawCallback: function(settings) {
            var api = this.api();
            var rows = api.rows({page: 'current'}).count();
            if (rows < 101) {
                for (var i = rows; i < 101; i++) {
                    $(api.table().body()).append(
                        `<tr>
                          <td></td>
                          <td><input type="text" class="form-control temp" placeholder="Temp"></td>
                          <td><input type="text" class="form-control pulse" placeholder="Pulse/Min"></td>
                          <td><input type="text" class="form-control bp" placeholder="BP/mmHg"></td>
                          <td><input type="text" class="form-control spo2" placeholder="Spo2"></td>
                          <td><input type="text" class="form-control input" placeholder="Input"></td>
                          <td><input type="text" class="form-control output" placeholder="Output"></td>
                          <td><input type="text" class="form-control rbs" placeholder="RBS"></td>
                          <td><input type="text" class="form-control rt" placeholder="RT"></td>
                          <td><input type="text" class="form-control remark" placeholder="Remark"></td>
                          <td><button class="btn btn-sm btn-primary addBtn">Add</button></td>
                        </tr>`
                    );
                }
            }
        }
    });

    $('#vital-table').on('click', '.addBtn', function() {
        let tr = $(this).closest('tr');
        let rowId = $(this).data('id') || '';
        let temp   = tr.find('.temp').val().trim();
        let pulse  = tr.find('.pulse').val().trim();
        let bp     = tr.find('.bp').val().trim();
        let spo2   = tr.find('.spo2').val().trim();
        let input = tr.find('.input').val().trim();
        let output = tr.find('.output').val().trim();
        let rbs    = tr.find('.rbs').val().trim();
        let rt     = tr.find('.rt').val().trim();
        let remark = tr.find('.remark').val().trim();

        if (!temp || !pulse || !bp || !spo2 || !input || !output || !rbs || !rt) {
            Swal.fire('Validation Error', 'Please fill all required fields.', 'warning');
            return;
        }

        let payload = {
            _token: '{{ csrf_token() }}',
            id: rowId,
            patient_id: $('#patient_id').val(),
            select_date: $('#select_date').val(),
            temp: temp,
            pulse: pulse,
            bp: bp,
            spo2: spo2,
            input: input,
            output: output,
            rbs: rbs,
            rt: rt,
            remark: remark
        };

        $.ajax({
            url: "{{ route('nursing.vital_chart.store') }}",
            type: 'POST',
            data: payload,
           

            success: function (response) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Vital saved successfully.',
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false,
                });

                $('#vital-table').DataTable().ajax.reload(null, false);
            },
            error: function (xhr) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to save vital. Please try again.',
                    icon: 'error',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false,
                });
                console.log(xhr.responseJSON);
            }
        });
    });

    $('#vital-table').on('click', '.editBtn', function() {
        let tr = $(this).closest('tr');
        let id = $(this).data('id');
        let patientId = $('#patient_id').val(); 
        let selectDate = $('#select_date').val(); 

        let fields = ['temp', 'pulse', 'bp', 'spo2', 'input', 'output', 'rbs', 'rt', 'remark'];
        fields.forEach(field => {
            let cell = tr.find('.' + field);
            if (!cell.find('input').length) {
                let currentValue = cell.text().trim();
                cell.html(`<input type="text" class="form-control ${field}" value="${currentValue}">`);
            }
        });

        $(this).removeClass('btn-info editBtn')
            .addClass('btn-success updateBtn')
            .html('<i class="fa fa-save"></i>')
            .data('id', id)
            .data('patient', patientId) 
            .data('date', selectDate);   
    });

    $('#vital-table').on('click', '.updateBtn', function() {
        let tr = $(this).closest('tr');
        let id = $(this).data('id');
        let patientId = $(this).data('patient'); 
        let selectDate = $(this).data('date');  

        let data = {
            _token: "{{ csrf_token() }}",
            id: id,
            patient_id: patientId,  
            select_date: selectDate, 
            temp: tr.find('.temp input').val().trim(),
            pulse: tr.find('.pulse input').val().trim(),
            bp: tr.find('.bp input').val().trim(),
            spo2: tr.find('.spo2 input').val().trim(),
            input: tr.find('.input input').val().trim(),
            output: tr.find('.output input').val().trim(),
            rbs: tr.find('.rbs input').val().trim(),
            rt: tr.find('.rt input').val().trim(),
            remark: tr.find('.remark input').val().trim()
        };

        $.ajax({
            url: "{{ route('nursing.vital_chart.update') }}",
            type: "POST",
            data: data,
            success: function (response) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Vital update successfully.',
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false,
                });

                $('#vital-table').DataTable().ajax.reload(null, false);
            },
            error: function (xhr) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to save vital. Please try again.',
                    icon: 'error',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false,
                });
                console.log(xhr.responseJSON);
            }
        });
    });
   
   
    $('#vital-table').on('click', '.deleteBtn', function() {
        let rowId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: 'This record will be deleted permanently.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ url('/nursing/vitals_chart/delete') }}/" + rowId,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Vital delete successfully.',
                            icon: 'success',
                            toast: true,
                            position: 'top-end',
                            timer: 3000,
                            showConfirmButton: false,
                        });

                        $('#vital-table').DataTable().ajax.reload(null, false);
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to save vital. Please try again.',
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
        });
    });
});
</script>
@endsection

