@extends('layouts.master')
@section('title')
    Diagnosis
@endsection
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Diagnosis</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL('/admin/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ URL('admin/diagnosis') }}">Diagnosis</a></li>
                            <li class="breadcrumb-item active">
                                <a href="#">{{ isset($diagnosis) ? 'Edit' : 'Add' }}</a>
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
                                <h4 class="card-title" id="horz-layout-basic">Diagnosis Details</h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    @include('notifications')

                                    <form method="POST" action="{{ isset($diagnosis) ? url('admin/diagnosis/' . $diagnosis->id . '/edit') : url('admin/diagnosis/add') }}" id="diagnosis-form">
                                        @csrf
                                        @if(isset($diagnosis))
                                            @method('PUT')
                                        @endif

                                        <input type="hidden" name="id" value="{{ $diagnosis->id ?? 0 }}">

                                        <div class="form-body">
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="name"> Name:</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="name" id="name" 
                                                        placeholder="name" 
                                                        value="{{ old('name', $diagnosis->name ?? '') }}" 
                                                        required>
                                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="diat">Diat Plan:</label>
                                                <div class="col-md-6">
                                                    <textarea 
                                                        class="form-control" 
                                                        name="diat" 
                                                        id="diat" 
                                                        placeholder="Enter Diat Plan in Gujarati" 
                                                        rows="5" 
                                                        >{{ old('diat', $diagnosis->diat ?? '') }}</textarea>
                                                    <span class="text-danger">{{ $errors->first('diat') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="diat">Note:</label>
                                                <div class="col-md-6">
                                                    <textarea 
                                                        class="form-control" 
                                                        name="note" 
                                                        id="note" 
                                                        placeholder="Enter Note" 
                                                        rows="5" 
                                                        >{{ old('note', $diagnosis->note ?? '') }}</textarea>
                                                    <span class="text-danger">{{ $errors->first('note') }}</span>
                                                </div>
                                            </div>


                                             <!-- Medicine Selection Field -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="medicine">Select Medicine:</label>
                                                <div class="col-md-6">
                                                <select id="medicine" class="form-control select2">
                                                    <option value="" disabled selected>Select Medicine</option>
                                                    @foreach($medicines as $medicine)
                                                        <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                                    @endforeach
                                                </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="button" id="add-medicine-btn" class="btn btn-primary">Add Medicine</button>
                                                </div>
                                            </div>

                                            <!-- Medicine List Table -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control">Selected Medicines:</label>
                                                <div class="col-md-9">
                                                    <table class="table table-bordered" id="medicine-table">
                                                        <thead>
                                                            <tr>
                                                                <th>Medicine Name</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if(isset($selectedMedicines))
                                                                @foreach($selectedMedicines as $medicine)
                                                                    <tr><td>
                                                                    <input type="hidden" name="medicines[]" value="{{ $medicine->id }}">
                                                                    {{ $medicine->name }}</td>
                                                                        <td>
                                                                            <button type="button" class="btn btn-danger btn-sm remove-medicine-btn">Remove</button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-actions">
                                            <a href="{{ url('admin/diagnosis') }}" class="btn btn-warning mr-1">
                                                <i class="feather icon-x"></i> Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i> Save
                                            </button>
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

@endsection

@section('additionalcss')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />

@endsection

@section('additionaljs')
<!-- Include jQuery and Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>

<!-- Initialize Select2 -->
<script>
    $(document).ready(function () {
        $('#medicine').select2({
            placeholder: "Select Medicine",
            allowClear: true
        });
    });
</script>
<script>
    document.getElementById('add-medicine-btn').addEventListener('click', function() {
    const medicineSelect = document.getElementById('medicine');
    const selectedMedicineId = medicineSelect.value;
    const selectedMedicineName = medicineSelect.options[medicineSelect.selectedIndex].text;

    if (selectedMedicineId && selectedMedicineName) {
        const medicineTable = document.getElementById('medicine-table').querySelector('tbody');
        const row = document.createElement('tr');

        row.innerHTML = `
            <td>
                <input type="hidden" name="medicines[]" value="${selectedMedicineId}">
                ${selectedMedicineName}
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-medicine-btn">Remove</button>
            </td>
        `;

        medicineTable.appendChild(row);
    }
});

// Remove medicine row
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('remove-medicine-btn')) {
        event.target.closest('tr').remove();
    }
});

</script>
@endsection
