@extends('layouts.master')
@section('title')
    Medicine
@endsection
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Medicine</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL('/admin/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ URL('admin/medicine') }}">Medicine</a></li>
                            <li class="breadcrumb-item active">
                                <a href="#">{{ isset($medicine) ? 'Edit' : 'Add' }}</a>
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
                                <h4 class="card-title" id="horz-layout-basic">Medicine Details</h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    @include('notifications')

                                    <form method="POST" action="{{ isset($medicine) ? url('admin/medicine/' . $medicine->id . '/edit') : url('admin/medicine/add') }}" id="medicine-form">
                                        @csrf
                                        @if(isset($medicine))
                                            @method('PUT')
                                        @endif

                                        <input type="hidden" name="id" value="{{ $medicine->id ?? 0 }}">

                                        <div class="form-body">
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="name"> Name:</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="name" id="name" 
                                                        placeholder="name" 
                                                        value="{{ old('name', $medicine->name ?? '') }}" 
                                                        required>
                                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="Quantity"> Quantity:</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="quantity" id="quantity" 
                                                        placeholder="quantity" 
                                                        value="{{ old('quantity', $medicine->quantity ?? '') }}" 
                                                        required>
                                                    <span class="text-danger">{{ $errors->first('quantity') }}</span>
                                                </div>
                                            </div>
                                          
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="freq"> Frequency:</label>
                                                <div class="col-md-6">
                                                    <select class="form-control" name="frequency" id="frequency" required>
                                                        <option value="1-0-0" {{ old('frequency', $medicine->frequency ?? '') == '1-0-0' ? 'selected' : '' }}>1-0-0</option>
                                
                              <option value="1-0-½" {{ old('frequency', $medicine->frequency ?? '') == '1-0-½' ? 'selected' : '' }}>1-0-½</option>
                                                            <option value="½-0-0" {{ old('frequency', $medicine->frequency ?? '') == '½-0-0' ? 'selected' : '' }}>½-0-0</option>
                              
                              <option value="TWICE A WEEK" {{ old('frequency', $medicine->frequency ?? '') == 'TWICE A WEEK' ? 'selected' : '' }}>TWICE A WEEK</option>
                              
                              <option value="1-1-0" {{ old('frequency', $medicine->frequency ?? '') == '1-1-0' ? 'selected' : '' }}>1-1-0</option>                
                                                        <option value="1-0-1" {{ old('frequency', $medicine->frequency ?? '') == '1-0-1' ? 'selected' : '' }}>1-0-1</option>
                                                        <option value="1-1-1" {{ old('frequency', $medicine->frequency ?? '') == '1-1-1' ? 'selected' : '' }}>1-1-1</option>
                                                        <option value="0-1-0" {{ old('frequency', $medicine->frequency ?? '') == '0-1-0' ? 'selected' : '' }}>0-1-0</option>
                                                        <option value="0-0-1" {{ old('frequency', $medicine->frequency ?? '') == '0-0-1' ? 'selected' : '' }}>0-0-1</option>
                                                        <option value="0-1-1" {{ old('frequency', $medicine->frequency ?? '') == '0-1-1' ? 'selected' : '' }}>0-1-1</option>
                                                        <option value="½-0-½" {{ old('frequency', $medicine->frequency ?? '') == '½-0-½' ? 'selected' : '' }}>½-0-½</option>
                                                        <option value="1-½-0" {{ old('frequency', $medicine->frequency ?? '') == '1-½-0' ? 'selected' : '' }}>1-½-0</option>
                                                        <option value="½-½-0" {{ old('frequency', $medicine->frequency ?? '') == '½-½-0' ? 'selected' : '' }}>½-½-0</option>
                                                        <option value="½-½-½" {{ old('frequency', $medicine->frequency ?? '') == '½-½-½' ? 'selected' : '' }}>½-½-½</option>
                                                        <option value="0-0-½" {{ old('frequency', $medicine->frequency ?? '') == '0-0-½' ? 'selected' : '' }}>0-0-½</option>
                                                        <option value="0-½-0" {{ old('frequency', $medicine->frequency ?? '') == '0-½-0' ? 'selected' : '' }}>0-½-0</option>
                                                        <option value="SOS" {{ old('frequency', $medicine->frequency ?? '') == 'SOS' ? 'selected' : '' }}>SOS</option>
                                                        <option value="week wise 1" {{ old('frequency', $medicine->frequency ?? '') == 'week wise 1' ? 'selected' : '' }}>week wise 1</option>
                                                    </select>
                                                    <span class="text-danger">{{ $errors->first('frequency') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="note"> Note:</label>
                                                <div class="col-md-6">
                                                    <select class="form-control" name="note" id="note" required>
                                                        <option value="જમ્યા પછી" {{ old('note', $medicine->note ?? '') == 'જમ્યા પછી' ? 'selected' : '' }}>
                                                            જમ્યા પછી
                                                        </option>
                                                        <option value="જમ્યા પહેલાં" {{ old('note', $medicine->note ?? '') == 'જમ્યા પહેલાં' ? 'selected' : '' }}>
                                                            જમ્યા પહેલાં
                                                        </option>
                                                        <option value="જરુર પડે ત્યારે" {{ old('note', $medicine->note ?? '') == 'જરુર પડે ત્યારે' ? 'selected' : '' }}>
                                                            જરુર પડે ત્યારે
                                                        </option>
                                                        <option value="none" {{ old('note', $medicine->note ?? '') == 'none' ? 'selected' : '' }}>
                                                            None
                                                        </option>
                                                    </select>
                                                    <span class="text-danger">{{ $errors->first('note') }}</span>
                                                </div>
                                            </div>

                                        
                                        </div>


                                        <div class="form-actions">
                                            <a href="{{ url('admin/medicine') }}" class="btn btn-warning mr-1">
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

@endsection

@section('additionaljs')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
       
        $('#frequency').select2({
            placeholder: "Select a frequency",
            allowClear: true,
        });

    });
</script>
@endsection
