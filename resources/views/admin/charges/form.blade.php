@extends('layouts.master')
@section('title')
    Charge
@endsection
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Charge</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL('/admin/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ URL('admin/charges') }}">Charge</a></li>
                            <li class="breadcrumb-item active">
                                <a href="#">{{ isset($charge) ? 'Edit' : 'Add' }}</a>
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
                                <h4 class="card-title" id="horz-layout-basic">Charge Details</h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    @include('notifications')

                                    <form method="POST" action="{{ isset($charge) ? url('admin/charges/' . $charge->id . '/edit') : url('admin/charges/add') }}" id="charge-form">
                                        @csrf
                                        @if(isset($charge))
                                            @method('PUT')
                                        @endif

                                        <input type="hidden" name="id" value="{{ $charge->id ?? 0 }}">

                                        <div class="form-body">
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="type"> Type:</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="type" id="type" 
                                                        placeholder="Type" 
                                                        value="{{ old('type', $charge->type ?? '') }}" 
                                                        required>
                                                    <span class="text-danger">{{ $errors->first('type') }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="amount"> Amount:</label>
                                                <div class="col-md-6">
                                                    <input amount="text" class="form-control" name="amount" id="amount" 
                                                        placeholder="Amount" 
                                                        value="{{ old('amount', $charge->amount ?? '') }}" 
                                                        required>
                                                    <span class="text-danger">{{ $errors->first('amount') }}</span>
                                                </div>
                                            </div>
                                        
                                        </div>


                                        <div class="form-actions">
                                            <a href="{{ url('admin/charges') }}" class="btn btn-warning mr-1">
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

@endsection

@section('additionaljs')

@endsection
