@extends('layouts.master')

@section('title', 'Discharge')

@section('content')
<div class="app-content content">
  <div class="content-overlay"></div>
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Patient Discharge</h3>
        <div class="breadcrumbs-top">
          <div class="breadcrumb-wrapper">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="{{ URL('nursing/dashboard') }}">Dashboard</a>
              </li>
              <li class="breadcrumb-item active">Patient Discharge</li>
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
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Discharge Form</h4>
                                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="feather icon-minus"></i></a></li>
                                        <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body card-dashboard">
                                    @include('notifications')
                                    <form id="treatmentForm" method="POST" action="{{ route('discharge.save') }}">
                                      @csrf
                                          <div class="form-group col-6">
                                            <label for="patientSelect">Select Patient</label>
                                            <select class="form-control" id="patientSelect" name="patient_id" required>
                                              <option value="">-- Select Patient --</option>
                                              @foreach($patients as $patient)
                                                <option value="{{ $patient->id }}">{{ $patient->fullname }}</option>
                                              @endforeach
                                            </select>
                                          </div>
                                          <div class="form-group col-6">
                                            <label for="dischargeType">Discharge Type</label>
                                            <select class="form-control" id="dischargeType" name="discharge_type" required>
                                              <option value="select"> -- Select type --</option>
                                              <option value="dama">Dama</option>
                                              <option value="discharge">Discharge to home</option>
                                              <option value="center">Discharge to higher center</option>
                                              <option value="request">Discharge on request</option>
                                              <option value="Death">Death</option>
                                            </select>
                                          </div>
                                          <div class="form-group col-6">
                                            <label for="dischargeDatetime">Discharge Date & Time</label>
                                            <input type="datetime-local" class="form-control" id="dischargeDatetime" name="discharge_datetime" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                          </div>
                                          <button type="submit" class="btn btn-info">Save</button>
                                         
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
@endsection

@section('additionaljs')
<script>
  $(document).ready(function() {
    $('#dischargeTabs a').on('click', function (e) {
      e.preventDefault();
      $(this).tab('show');
    });
  });
</script>
@endsection
