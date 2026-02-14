@extends('layouts.master')

@section('title', 'patient-roombeds')

@section('content')
<div class="app-content content">
  <div class="content-overlay"></div>
  <div class="content-wrapper">
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title">Patient Room-bed</h3>
        <div class="breadcrumbs-top">
          <div class="breadcrumb-wrapper">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="{{ URL('nursing/dashboard') }}">Dashboard</a>
              </li>
              <li class="breadcrumb-item active">Patient Room-bed</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
 @include('notifications')
    <div class="content-body">
      <section class="section">
        <div class="row justify-content-center">
          <div class="col-lg-12 col-md-12">
            <div class="card shadow-lg">
              <div class="card-body">
           
    @foreach($rooms as $room)
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h4>{{ $room->type }}</h4> 
            </div>
            <div class="card-body d-flex flex-wrap">
                @foreach($room->beds as $bed)
                    @php 
                        // Get the latest admitted patient
                        $roomBed = $bed->roomBeds->where('admit_status', 'Admitted')->last();
                        $patient = $roomBed ? $roomBed->patient : null;
                    @endphp
                    <div class="bed-box {{ $roomBed ? 'bg-success' : 'bg-warning' }} 
                        text-white p-3 m-2" style="cursor:pointer; min-width: 120px;">
                        Bed {{ $bed->bed_no }}
                        {!! $patient ? '<br><h6>' . $patient->fullname . '</h6>' : '<br><span class="text-muted"></span>' !!}
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
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

@endsection





















