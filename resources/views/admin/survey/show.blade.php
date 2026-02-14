@extends('layouts.master')
@section('title')
    Survey details
@endsection
@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
                <h3 class="content-header-title mb-0">Patient survey details</h3>
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ URL('admin/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Survey</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
           
            <section id="column">
                <div class="row">
                    <div class="col-12">
                        <div class="text-right">
                            <a href="{{url('admin/survey')}}" class="btn btn-info">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">📌 Patient survey details</h4>
                                <a class="heading-elements-toggle"><i class="fas fa-ellipsis-v"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="expand"><i data-feather="maximize"></i></a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <h4 class="text-primary">👤 દર્દી ની માહિતી</h4>
                                    <p><strong>મોબાઇલ :</strong> {{ $survey->mobile }}</p>

                                    <hr>

                                    <h4 class="text-primary">👨‍⚕️ ડોક્ટર અને સારવાર</h4>
                                    <p><strong>ડોક્ટરની સેવા વિષે તમારું મંતવ્ય :</strong> {{ $survey->doctor_rating }}</p>

                                    <hr>

                                    <h4 class="text-primary">👩‍⚕️ હોસ્પિટલ નર્સિંગ સ્ટાફ</h4>
                                    <p><strong>સ્ટાફના વ્યવહાર વિષે તમારું મંતવ્ય :</strong> {{ $survey->staff_rating }}</p>

                                    <hr>

                                    <h4 class="text-primary">👩‍⚕️ રિસેપ્શન અને બિલિંગ સ્ટાફ</h4>
                                    <p><strong>સ્ટાફના વ્યવહાર વિષે તમારું મંતવ્ય :</strong> {{ $survey->recep_rating }}</p>

                                    <hr>

                                    <h4 class="text-primary">💊 મેડિકલ સ્ટોર અને લેબોરેટરી સેવા</h4>
                                    <p><strong>દવાઓ ની ઉપલબ્ધતા :</strong> {{ $survey->medicine_availability }}</p>
                                    <p><strong>લેબ પરીક્ષણ સમય :</strong> {{ $survey->lab_services }}</p>

                                    <hr>

                                    <h4 class="text-primary">⭐ સમીક્ષા અને સૂચનાઓ</h4>
                                    <p>{{ $survey->suggestions }}</p>
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
@endsection

@section('additionaljs')
<script src="https://unpkg.com/feather-icons"></script>
<script>
    feather.replace();
</script>
@endsection
