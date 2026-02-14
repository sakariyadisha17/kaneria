@extends('layouts.master')
@section('title', 'Patient Services')

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Services Details</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL('nursing/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Services Details</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="content-body">
            <section id="column">
                <div class="row">
                    <div class="col-12">
                        <div class="text-right mb-2">
                            <a href="{{url('nursing/patient_dose')}}" class="btn btn-info">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Select Service</h4>
                            </div>
                            <div class="card-body">
                                <h4>{{ $patient->name }}</h4>
                                <form id="serviceForm">
                                    @csrf
                                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                    <label for="service">Select Service:</label>
                                    <select id="service" name="service_id" class="form-control">
                                        <option value="">-- Select Service --</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" data-amount="{{ $service->amount }}">
                                                {{ $service->name }} (₹{{ $service->amount }})
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h4 class="card-title">Active Services</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Service Name</th>
                                            <th>Amount</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Calculate Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="activeServices">
                                        @foreach($patient->activeServices as $service)
                                        <tr data-id="{{ $service->id }}">
                                            <td>{{ $service->service->name }}</td>
                                            <td>{{ $service->service->amount }}</td>

                                            <td>{{ $service->start_datetime }}</td>
                                            <td class="end-time">{{ $service->end_datetime ?? 'Ongoing' }}</td>
                                            <td class="amount">₹{{ $service->calculate_amount ?? 0 }}</td>
                                            <td>
                                                <button class="btn toggleService {{ is_null($service->end_datetime) ? 'btn-danger' : 'btn-secondary' }}"
                                                        data-id="{{ $service->id }}"
                                                        data-service-id="{{ $service->service_id }}"
                                                        data-status="{{ is_null($service->end_datetime) ? 'stop' : 'closed' }}"
                                                        {{ !is_null($service->end_datetime) ? 'disabled' : '' }}>
                                                    {{ is_null($service->end_datetime) ? 'Stop' : 'Closed' }}
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                            </div>
                        </div>


                        <div class="card mt-3">
                            <div class="card-header">
                                <h4 class="card-title">Inactive Services</h4>
                            </div>
                            <div class="card-body">
                                <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Service Name</th>
                                            <th>Amount</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Calculate Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="inactiveServices">
                                        @foreach($patient->inactiveServices as $service)
                                        <tr data-id="{{ $service->id }}">
                                            <td>{{ $service->service->name }}</td>
                                            <td>{{ $service->service->amount }}</td>
                                            <td>{{ $service->start_datetime }}</td>
                                            <td>{{ $service->end_datetime }}</td>
                                            <td class="amount"> <input type="number" class="form-control edit-amount" 
                                                data-id="{{ $service->id }}" 
                                                value="{{ $service->calculate_amount }}" 
                                                min="0"></td>
                                            <td>
                                                <button class="btn btn-danger btn-sm deleteService" data-id="{{ $service->id }}"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        @endforeach
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
@endsection

@section('additionalcss')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
@endsection

@section('additionaljs')
<script>
$(document).ready(function() {
    // Start Service
    $("#service").change(function() {
        let serviceId = $(this).val();
        if (serviceId) {
            let patientId = $("input[name='patient_id']").val();
            startService(patientId, serviceId);
        }
    });

    function startService(patientId, serviceId) {
        $.post("{{ url('nursing/patient_dose/patient-service-start') }}", {
            patient_id: patientId,
            service_id: serviceId,
            _token: "{{ csrf_token() }}"
        }, function(response) {
            alert(response.message);
            location.reload();
        }).fail(function(xhr) {
            alert('Error: ' + xhr.responseJSON.message);
        });
    }

    // Stop Service
    $(document).on('click', '.toggleService', function() {
        let button = $(this);
        let serviceId = button.data('service-id');
        let recordId = button.data('id');
        let status = button.data('status');

        if (status === 'closed') return;

        $.post("{{ url('nursing/patient_dose/patient-service-stop') }}", {
            id: recordId,
            service_id: serviceId,
            _token: "{{ csrf_token() }}"
        }, function(response) {
            alert(response.message);

            let row = $("tr[data-id='" + recordId + "']");
            row.find(".end-time").text(response.end_datetime);
            row.find(".amount").text("₹" + response.amount);

            button.removeClass("btn-danger").addClass("btn-secondary").text("Closed").prop("disabled", true);
        }).fail(function(xhr) {
            alert('Error: ' + xhr.responseJSON.message);
        });
    });

});
</script>

<script>
$(document).ready(function() {
    $(document).on('click', '.deleteService', function() {
        let button = $(this);
        let recordId = button.data('id');
        
        if (confirm("Are you sure you want to delete this service?")) {
            $.post("{{ url('nursing/patient_dose/patient-service-delete') }}", {
                id: recordId,
                _token: "{{ csrf_token() }}"
            }, function(response) {
                alert(response.message);
                button.closest('tr').remove();
            }).fail(function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            });
        }
    });
});
</script>
<script>
$(document).on('change', '.edit-amount', function () {
    let serviceId = $(this).data('id');
    let newAmount = $(this).val();

    $.ajax({
        url: "{{ url('nursing/patient_dose/update-service-amount') }}",
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            id: serviceId,
            calculate_amount: newAmount
        },
        success: function (response) {
            if (response.success) {
                alert('Amount updated successfully!');
            } else {
                alert('Error updating amount.');
            }
        },
        error: function () {
            alert('Failed to update amount.');
        }
    });
});

</script>
@endsection