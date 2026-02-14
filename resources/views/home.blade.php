@extends('layouts.master')
@section('title')
    Dashboard
@endsection
@section('content')
 <!-- BEGIN: Content-->
  @include('notifications')

  @hasrole('Admin')
  <div class="app-content content">
            <div class="content-overlay"></div>
            <div class="content-wrapper">
                <div class="content-header row"></div>
                <div class="content-body">
                        <div class="row">
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media align-items-stretch">
                                        <div class="p-2 text-center bg-primary bg-darken-2">
                                            <i class="feather icon-user font-large-2 white"></i>
                                        </div>
                                        <div class="p-2 bg-gradient-x-primary white media-body">
                                            <h5>Total Patient</h5>
                                            <h5 class="text-bold-400 mb-0"><i class="feather icon-plus">{{@$total_patient}}</i> 
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media align-items-stretch">
                                        <div class="p-2 text-center bg-danger bg-darken-2">
                                            <i class="feather icon-box font-large-2 white"></i>
                                        </div>
                                        <div class="p-2 bg-gradient-x-danger white media-body">
                                            <h5>Today Patient</h5>
                                            <h5 class="text-bold-400 mb-0"><i class="feather icon-plus">
                                                {{@$today_appointment}}
                                            </i> </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
    
                      
                    </div>
                </div>
            </div>
    </div>
  @endrole

  @hasrole('Doctor')
  <div class="app-content content">
            <div class="content-overlay"></div>
            <div class="content-wrapper">
                <div class="content-header row"></div>
                <div class="content-body">
                        <div class="row">
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media align-items-stretch">
                                        <div class="p-2 text-center bg-primary bg-darken-2">
                                            <i class="feather icon-user font-large-2 white"></i>
                                        </div>
                                        <div class="p-2 bg-gradient-x-primary white media-body">
                                            <h5>Total Patient</h5>
                                            <h5 class="text-bold-400 mb-0"><i class="feather icon-plus">{{@$total_patient}}</i> 
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media align-items-stretch">
                                        <div class="p-2 text-center bg-danger bg-darken-2">
                                            <i class="feather icon-box font-large-2 white"></i>
                                        </div>
                                        <div class="p-2 bg-gradient-x-danger white media-body">
                                            <h5>Today Patient</h5>
                                            <h5 class="text-bold-400 mb-0"><i class="feather icon-plus">
                                                {{@$today_appointment}}
                                            </i> </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
    
                      
                    </div>
                </div>
            </div>
    </div>
  @endrole


  @hasrole('Medical Officer')
  <div class="app-content content">
            <div class="content-overlay"></div>
            <div class="content-wrapper">
                <div class="content-header row"></div>
                <div class="content-body">
                        <div class="row">
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media align-items-stretch">
                                        <div class="p-2 text-center bg-primary bg-darken-2">
                                            <i class="feather icon-user font-large-2 white"></i>
                                        </div>
                                        <div class="p-2 bg-gradient-x-primary white media-body">
                                            <h5>Total Patient</h5>
                                            <h5 class="text-bold-400 mb-0"><i class="feather icon-plus">{{@$total_patient}}</i> 
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media align-items-stretch">
                                        <div class="p-2 text-center bg-danger bg-darken-2">
                                            <i class="feather icon-box font-large-2 white"></i>
                                        </div>
                                        <div class="p-2 bg-gradient-x-danger white media-body">
                                            <h5>Today Patient</h5>
                                            <h5 class="text-bold-400 mb-0"><i class="feather icon-plus">
                                                {{@$today_appointment}}
                                            </i> </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
    
                      
                    </div>
                </div>
            </div>
    </div>
  @endrole


  @hasrole('Nursing')
    <div class="app-content content">
            <div class="content-overlay"></div>
            <div class="content-wrapper">
                <div class="content-header row"></div>
                <div class="content-body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media align-items-stretch">
                                        <!-- <div class="p-2 text-center bg-primary bg-darken-2">
                                            <i class="feather icon-users font-large-2 white"></i>
                                        </div>
                                        <div class="p-2 bg-gradient-x-primary white media-body">
                                            <h5>Total Contact</h5>
                                            <h5 class="text-bold-400 mb-0"><i class="feather icon-plus">{{@$total_contact}}</i> 
                                            </h5>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                      

                    </div>
                </div>
            </div>
    </div>
  @endrole

  @hasrole('Receptionist')
    <div class="app-content content">
            <div class="content-overlay"></div>
            <div class="content-wrapper">
                <div class="content-header row"></div>
                <div class="content-body">
                        <div class="row">
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media align-items-stretch">
                                        <div class="p-2 text-center bg-primary bg-darken-2">
                                            <i class="feather icon-user font-large-2 white"></i>
                                        </div>
                                        <div class="p-2 bg-gradient-x-primary white media-body">
                                            <h5>Total Patient</h5>
                                            <h5 class="text-bold-400 mb-0"><i class="feather icon-plus">{{@$total_patient}}</i> 
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media align-items-stretch">
                                        <div class="p-2 text-center bg-danger bg-darken-2">
                                            <i class="feather icon-box font-large-2 white"></i>
                                        </div>
                                        <div class="p-2 bg-gradient-x-danger white media-body">
                                            <h5>Today Patient</h5>
                                            <h5 class="text-bold-400 mb-0"><i class="feather icon-plus">
                                                {{@$today_appointment}}
                                            </i> </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media align-items-stretch">
                                        <div class="p-2 text-center bg-success bg-darken-2">
                                            <i class="feather icon-upload font-large-2 white"></i>
                                        </div>
                                        <div class="p-2 bg-gradient-x-success white media-body">
                                            <h5>Total Import</h5>
                                            <h5 class="text-bold-400 mb-0"><i class="feather icon-plus">
                                            </i> </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                 
    
                      
                    </div>
                </div>
            </div>
    </div>
  @endrole


@endsection


@section('additionalcss')
    
@endsection
@section('additionaljs')
    
@endsection