<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/install-mpdf', function () {
    exec('composer require mpdf/mpdf 2>&1', $output, $return_var);
    
    return response()->json([
        'output' => $output,
        'status' => $return_var === 0 ? 'Success' : 'Failed'
    ]);
});

Route::get('/clear', function () {
    // Disconnect the database to close all open prepared statements
    DB::disconnect('mysql');

    // Run Laravel cache and config clearing commands
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear'); // Clears compiled views
    Artisan::call('route:clear'); // Clears route cache
    Artisan::call('optimize:clear'); // Clears everything

    return response()->json([
        'status'  => 'success',
        'message' => 'Cache cleared, database disconnected, and application optimized.',
    ]);
});

Route::get('/error-display-page', function () {
    return view('errors.simple'); // You can make errors/simple.blade.php
});

Route::get('/cron/send-whatsapp-reminders', [App\Http\Controllers\HomeController::class, 'sendReminders']);

Route::get('/check-time', function () {
    return "Server: " . date('Y-m-d H:i:s');
});

Route::get('/contact', 'App\Http\Controllers\OnlineBookController@contact_us');

Route::get('/available-times', 'App\Http\Controllers\OnlineBookController@getAvailableTimes');
Route::get( '/appointment', 'App\Http\Controllers\OnlineBookController@index');
Route::get('/patients/search', 'App\Http\Controllers\OnlineBookController@search')->name('patients.search');
Route::post( '/save_appointment', 'App\Http\Controllers\OnlineBookController@storeBooking');

Route::get( '/survey', 'App\Http\Controllers\SurveyController@index');
Route::post( '/survey', 'App\Http\Controllers\SurveyController@store')->name('survey.store');



Route::get('/', 'App\Http\Controllers\Auth\LoginController@showLoginForm');
Route::post('/login', 'App\Http\Controllers\Auth\LoginController@login')->name('login');
Route::get('/login', function() { return redirect('/'); });
Route::get( 'logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');
Route::get( 'forgetPassword', 'App\Http\Controllers\Auth\ForgotPasswordController@showForgetPasswordForm')->name('forget.password.get');
Route::post('forgetPassword', 'App\Http\Controllers\Auth\ForgotPasswordController@submitForgetPasswordForm')->name('forget.password.post'); 
Route::get('reset-password/{token}', 'App\Http\Controllers\Auth\ForgotPasswordController@showResetPasswordForm')->name('reset.password.get');
Route::post('reset-password', 'App\Http\Controllers\Auth\ForgotPasswordController@submitResetPasswordForm')->name('reset.password.post');


Route::group( [ "middleware" => "auth" ], function () {
		
    Route::get( 'profile','App\Http\Controllers\HomeController@profile')->name('profile');
    Route::post( 'update_profile','App\Http\Controllers\HomeController@update_profile')->name('update_profile');
    Route::post( 'change_password','App\Http\Controllers\HomeController@change_password')->name('change_password');
    Route::get('/appointments/statusCounts', 'App\Http\Controllers\HomeController@statusCounts')->name('appointments.statusCounts');
    Route::get('print-letterhead/{id}', 'App\Http\Controllers\Medicalofficer\AppointmentController@printLetterhead')->name('letterhead.print');
    Route::group( [ 'prefix' => 'admin'], function () {

        Route::get( '/dashboard', 'App\Http\Controllers\Admin\HomeController@index' );
        Route::get('/dashboard/day-data', 'App\Http\Controllers\Admin\HomeController@dashboardDayData');
        Route::get('/dashboard/month-data', 'App\Http\Controllers\Admin\HomeController@monthData');
        Route::get('/dashboard/financial-year-data', 'App\Http\Controllers\Admin\HomeController@financialYearData');
        Route::get( '/dashboard/weekly-patient-chart', 'App\Http\Controllers\Admin\HomeController@weeklyChart' );
        Route::get( '/dashboard/chart/daily', 'App\Http\Controllers\Admin\HomeController@dailyChart' );
        Route::get( '/dashboard/chart/monthly', 'App\Http\Controllers\Admin\HomeController@monthlyChart' );
        Route::get( '/dashboard/chart/financial-year', 'App\Http\Controllers\Admin\HomeController@financialYearChart' );
        Route::get('/dashboard/patient-list', 'App\Http\Controllers\Admin\HomeController@patientList');
        Route::get('/dashboard/patient-list-data', 'App\Http\Controllers\Admin\HomeController@patientListData')->name('admin.patient.list.data');
      
        Route::group( [ 'prefix' => 'doctors'], function () {
            Route::get( '/', 'App\Http\Controllers\Admin\DoctorController@index' );
            Route::get( '/add', 'App\Http\Controllers\Admin\DoctorController@create');
            Route::post( '/add', 'App\Http\Controllers\Admin\DoctorController@store');
            Route::get( '{id}/edit', 'App\Http\Controllers\Admin\DoctorController@edit')->name('doctor.edit');
            Route::put( '{id}/edit', 'App\Http\Controllers\Admin\DoctorController@update')->name('doctor.update');
            Route::get('{id}/delete','App\Http\Controllers\Admin\DoctorController@destroy');
            Route::get( 'getdata', 'App\Http\Controllers\Admin\DoctorController@getdata')->name( 'doctor.data' );
            Route::get('/doctors-pdf','App\Http\Controllers\Admin\DoctorController@doctorsPDF')->name( 'doctor.pdf' );
        });

        Route::group( [ 'prefix' => 'refer_doctor'], function () {
            Route::get( '/', 'App\Http\Controllers\Admin\ReferDoctorController@index' );
            Route::get( '/add', 'App\Http\Controllers\Admin\ReferDoctorController@create');
            Route::post( '/add', 'App\Http\Controllers\Admin\ReferDoctorController@store');
            Route::get( '{id}/edit', 'App\Http\Controllers\Admin\ReferDoctorController@edit')->name('refer_doctor.edit');
            Route::put( '{id}/edit', 'App\Http\Controllers\Admin\ReferDoctorController@update')->name('refer_doctor.update');
            Route::get('{id}/delete','App\Http\Controllers\Admin\ReferDoctorController@destroy');
            Route::get( 'getdata', 'App\Http\Controllers\Admin\ReferDoctorController@getdata')->name( 'refer_doctor.data' );
        });

        Route::group( [ 'prefix' => 'times'], function () {
            Route::get( '/', 'App\Http\Controllers\Admin\TimeController@index' );
            Route::get( '/add', 'App\Http\Controllers\Admin\TimeController@create');
            Route::post( '/add', 'App\Http\Controllers\Admin\TimeController@store');
            Route::get( '{id}/edit', 'App\Http\Controllers\Admin\TimeController@edit')->name('time.edit');
            Route::put( '{id}/edit', 'App\Http\Controllers\Admin\TimeController@update')->name('time.update');
            Route::get('{id}/delete','App\Http\Controllers\Admin\TimeController@destroy');
            Route::get( 'getdata', 'App\Http\Controllers\Admin\TimeController@getdata')->name( 'time.data' );
            Route::post('{id}/add-timesheets', 'App\Http\Controllers\Admin\TimeController@addTimesheets');

        });

        Route::group( [ 'prefix' => 'charges'], function () {
            Route::get( '/', 'App\Http\Controllers\Admin\ChargeController@index' );
            Route::get( '/add', 'App\Http\Controllers\Admin\ChargeController@create');
            Route::post( '/add', 'App\Http\Controllers\Admin\ChargeController@store');
            Route::get( '{id}/edit', 'App\Http\Controllers\Admin\ChargeController@edit')->name('charge.edit');
            Route::put( '{id}/edit', 'App\Http\Controllers\Admin\ChargeController@update')->name('charge.update');
            Route::get('{id}/delete','App\Http\Controllers\Admin\ChargeController@destroy');
            Route::get( 'getdata', 'App\Http\Controllers\Admin\ChargeController@getdata')->name( 'charge.data' );
        });

        Route::group( [ 'prefix' => 'services'], function () {
            Route::get( '/', 'App\Http\Controllers\Admin\ServiceController@index' );
            Route::get( '/add', 'App\Http\Controllers\Admin\ServiceController@create');
            Route::post( '/add', 'App\Http\Controllers\Admin\ServiceController@store');
            Route::get( '{id}/edit', 'App\Http\Controllers\Admin\ServiceController@edit')->name('service.edit');
            Route::put( '{id}/edit', 'App\Http\Controllers\Admin\ServiceController@update')->name('service.update');
            Route::get('{id}/delete','App\Http\Controllers\Admin\ServiceController@destroy');
            Route::get( 'getdata', 'App\Http\Controllers\Admin\ServiceController@getdata')->name( 'service.data' );
        });

        Route::group( [ 'prefix' => 'diagnosis'], function () {
            Route::get( '/', 'App\Http\Controllers\Admin\DiagnosisController@index' );
            Route::get( '/add', 'App\Http\Controllers\Admin\DiagnosisController@create');
            Route::post( '/add', 'App\Http\Controllers\Admin\DiagnosisController@store');
            Route::get( '{id}/edit', 'App\Http\Controllers\Admin\DiagnosisController@edit')->name('diagnosis.edit');
            Route::put( '{id}/edit', 'App\Http\Controllers\Admin\DiagnosisController@update')->name('diagnosis.update');
            Route::get('{id}/delete','App\Http\Controllers\Admin\DiagnosisController@destroy');
            Route::get( 'getdata', 'App\Http\Controllers\Admin\DiagnosisController@getdata')->name( 'diagnosis.data' );
        });

        Route::group( [ 'prefix' => 'medicine'], function () {
            Route::get( '/', 'App\Http\Controllers\Admin\MedicineController@index' );
            Route::get( '/add', 'App\Http\Controllers\Admin\MedicineController@create');
            Route::post( '/add', 'App\Http\Controllers\Admin\MedicineController@store');
            Route::get( '{id}/edit', 'App\Http\Controllers\Admin\MedicineController@edit')->name('medicine.edit');
            Route::put( '{id}/edit', 'App\Http\Controllers\Admin\MedicineController@update')->name('medicine.update');
            Route::get('{id}/delete','App\Http\Controllers\Admin\MedicineController@destroy');
            Route::get( 'getdata', 'App\Http\Controllers\Admin\MedicineController@getdata')->name( 'medicine.data' );

            Route::post('medicine/import', 'App\Http\Controllers\Admin\MedicineController@processImport')->name('medicine.import.process');
            Route::get('/import/sample', 'App\Http\Controllers\Admin\MedicineController@downloadSample')->name('medicine.import.sample');


        });

        Route::group( [ 'prefix' => 'patients'], function () {
            Route::get( '/', 'App\Http\Controllers\Admin\PatientsController@index' );
            Route::get( 'getdata', 'App\Http\Controllers\Admin\PatientsController@getdata')->name( 'admin.patients.data' );
            Route::post('/soft-delete/{id}', 'App\Http\Controllers\Admin\PatientsController@softDelete')->name('admin.patients.soft-delete');
            Route::get('/export-pdf', 'App\Http\Controllers\Admin\PatientsController@exportPatientListPDF')->name('patients.exportPDF');

        });

        Route::group(['prefix' => 'old_patient'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\ImportController@index');
            Route::get('getdata', 'App\Http\Controllers\Admin\ImportController@getdata')->name('admin.patients.import');
            Route::post('patient/import', 'App\Http\Controllers\Admin\ImportController@processImport')->name('patients.import.process');
            Route::get('/delete/{id}', 'App\Http\Controllers\Admin\ImportController@delete')->name('admin.patients.delete');
            Route::get('/import/sample', 'App\Http\Controllers\Admin\ImportController@downloadSample')->name('patients.import.sample');

        });

        Route::group(['prefix' => 'survey'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\SurveyController@index');
            Route::get('getdata', 'App\Http\Controllers\Admin\SurveyController@getdata')->name('admin.patient_survey');
            Route::get('/{id}/view', 'App\Http\Controllers\Admin\SurveyController@show');
            Route::get('/export-pdf', 'App\Http\Controllers\Admin\SurveyController@exportPDF')->name('admin.survey.pdf');
        });
        
        Route::group( [ 'prefix' => 'discharge'], function () {
            Route::get( '/', 'App\Http\Controllers\Nursing\BillingController@index' );
            Route::get( 'getdata', 'App\Http\Controllers\Nursing\BillingController@getdata')->name( 'admin.patient_bill.data' );
            Route::get('/{id}/delete', 'App\Http\Controllers\Nursing\BillingController@destroy');

        });
        Route::group( [ 'prefix' => 'rooms'], function () {
            Route::get( '/', 'App\Http\Controllers\Admin\RoomController@index' );
            Route::get( '/add', 'App\Http\Controllers\Admin\RoomController@create');
            Route::post( '/add', 'App\Http\Controllers\Admin\RoomController@store');
            Route::get( '{id}/edit', 'App\Http\Controllers\Admin\RoomController@edit')->name('room.edit');
            Route::put( '{id}/edit', 'App\Http\Controllers\Admin\RoomController@update')->name('room.update');
            Route::get('{id}/delete','App\Http\Controllers\Admin\RoomController@destroy');
            Route::get( 'getdata', 'App\Http\Controllers\Admin\RoomController@getdata')->name( 'room.data' );
        });

        Route::group( [ 'prefix' => 'beds'], function () {
            Route::get( '/', 'App\Http\Controllers\Admin\BedController@index' );
            Route::get( '/add', 'App\Http\Controllers\Admin\BedController@create');
            Route::post( '/add', 'App\Http\Controllers\Admin\BedController@store');
            Route::get( '{id}/edit', 'App\Http\Controllers\Admin\BedController@edit')->name('bed.edit');
            Route::put( '{id}/edit', 'App\Http\Controllers\Admin\BedController@update')->name('bed.update');
            Route::get('{id}/delete','App\Http\Controllers\Admin\BedController@destroy');
            Route::get( 'getdata', 'App\Http\Controllers\Admin\BedController@getdata')->name( 'bed.data' );
        });


        Route::group( [ 'prefix' => 'users'], function () {
             Route::get( '/', 'App\Http\Controllers\Admin\UsersController@index' );
             Route::get( '/add', 'App\Http\Controllers\Admin\UsersController@create');
             Route::post( '/add', 'App\Http\Controllers\Admin\UsersController@store');
             Route::get( '{id}/edit', 'App\Http\Controllers\Admin\UsersController@edit')->name('users.edit');
             Route::put( '{id}/edit', 'App\Http\Controllers\Admin\UsersController@update')->name('users.update');
             Route::get( '{id}/delete','App\Http\Controllers\Admin\UsersController@destroy');
             Route::get( 'getdata', 'App\Http\Controllers\Admin\UsersController@getdata')->name( 'users.data' );
        });

    });

        

    Route::group([ 'prefix' => 'receptionist'], function () {
        
        Route::get( '/booking', 'App\Http\Controllers\Receptionist\HomeController@bookfor' );
        Route::get('/patients/search', 'App\Http\Controllers\Receptionist\HomeController@search')->name('patients.search');
        Route::post( '/save_booking', 'App\Http\Controllers\Receptionist\HomeController@storeBooking');
        Route::get( '/dashboard', 'App\Http\Controllers\Receptionist\HomeController@index' );
        Route::get('/dashboard/day-data', 'App\Http\Controllers\Receptionist\HomeController@dashboardDayData');
        Route::get('/dashboard/month-data', 'App\Http\Controllers\Receptionist\HomeController@monthData');
        Route::get('/dashboard/financial-year-data', 'App\Http\Controllers\Receptionist\HomeController@financialYearData');
        Route::get( '/dashboard/weekly-patient-chart', 'App\Http\Controllers\Receptionist\HomeController@weeklyChart' );
        Route::get( '/dashboard/chart/daily', 'App\Http\Controllers\Receptionist\HomeController@dailyChart' );
        Route::get( '/dashboard/chart/monthly', 'App\Http\Controllers\Receptionist\HomeController@monthlyChart' );
        Route::get( '/dashboard/chart/financial-year', 'App\Http\Controllers\Receptionist\HomeController@financialYearChart' );
        Route::get('/dashboard/patient-list', 'App\Http\Controllers\Receptionist\HomeController@patientList');
        Route::get('/dashboard/patient-list-data', 'App\Http\Controllers\Receptionist\HomeController@patientListData')->name('receptionist.patient.list.data');

    
    
        
       

        Route::group(['prefix' => 'patient'], function () {
            Route::get('/', 'App\Http\Controllers\Receptionist\PatientController@index');
            Route::get('getdata', 'App\Http\Controllers\Receptionist\PatientController@getdata')->name('advance.booking');
            Route::post('booking/store', 'App\Http\Controllers\Receptionist\PatientController@storeBooking')->name('booking.store');
            Route::get('advance_booking/{id}', 'App\Http\Controllers\Receptionist\PatientController@show')->name('advance_booking.show');
            
            Route::get('patientformdata','App\Http\Controllers\Receptionist\PatientController@patientformdata')->name('patientformdata');
            Route::post('/store', 'App\Http\Controllers\Receptionist\PatientController@storePatient')->name('patient.store');
            Route::match(['get', 'post'], '/edit/{id}', 'App\Http\Controllers\Receptionist\PatientController@edit_patient')->name('patient.edit');
             //old patient id wise details
            Route::get('/details', 'App\Http\Controllers\Receptionist\PatientController@getDetails')->name('patient.getDetails');
            //new patient id generate
            Route::get('/generateId', 'App\Http\Controllers\Receptionist\PatientController@generateId')->name('patient.generatePatientIds');

            Route::post('/delete/{appointmentId}', 'App\Http\Controllers\Receptionist\PatientController@destroy_patient')->name('appointment.delete');
            Route::post('/soft-delete/{appointmentId}', 'App\Http\Controllers\Receptionist\PatientController@softDeletePatient')->name('patient.soft.delete');
            // Route::get('/patients/day', 'App\Http\Controllers\Receptionist\PatientController@dayPatients');
        });

        Route::group( [ 'prefix' => 'appointments'], function () {
            Route::get( '/', 'App\Http\Controllers\Receptionist\AppointmentController@index' );
            Route::get( 'getdata', 'App\Http\Controllers\Receptionist\AppointmentController@getdata')->name( 'appointments.data' );
            Route::post('/update-status', 'App\Http\Controllers\Receptionist\AppointmentController@updateStatus')->name('appointments.updateStatus');
            Route::get( '{id}/delete','App\Http\Controllers\Receptionist\AppointmentController@delete');
            Route::post('/upload-files', 'App\Http\Controllers\Receptionist\AppointmentController@uploadFiles')->name('appointments.uploadFiles');
            Route::get( '{id}/open','App\Http\Controllers\Receptionist\AppointmentController@showFile')->name('appointments.open');
            Route::get('/generate-pdf/{id}', 'App\Http\Controllers\Receptionist\AppointmentController@generatePDF')->name('generate.pdf');
            Route::get( '{id}/print','App\Http\Controllers\Receptionist\AppointmentController@printFile')->name('appointments.print');


            Route::get('/export-csv', 'App\Http\Controllers\Receptionist\AppointmentController@exportCSV')->name('appointments.exportCSV');
            Route::get('/export-pdf', 'App\Http\Controllers\Receptionist\AppointmentController@exportPDF')->name('appointments.exportPDF');

              //letterhead routes
            Route::get('{id}/letterhead', 'App\Http\Controllers\Receptionist\AppointmentController@showletter')->name('appointments.letterhead');
            Route::post('/get-diagnosis-details', 'App\Http\Controllers\Receptionist\AppointmentController@getDiagnosisDetails')->name('get.diagnosis.details');
            Route::post('/letterhead/save/{id}', 'App\Http\Controllers\Receptionist\AppointmentController@storeLetterhead')->name('letterhead.save');

            Route::get('/get-letterhead', 'App\Http\Controllers\Receptionist\AppointmentController@getLetterhead')->name('get.letterhead');
            Route::get('/medicine-list', 'App\Http\Controllers\Receptionist\AppointmentController@getMedicineList')->name('get.medicine.list');
            // Route::get('/letterhead/print/{id}', 'App\Http\Controllers\Receptionist\AppointmentController@print')->name('reception.letterhead.print');
            Route::get('print-letterhead/{id}', 'App\Http\Controllers\Receptionist\AppointmentController@printLetterhead')->name('reception.letterhead.print');
            // Route::get('/letterhead/editprint/{id}', 'App\Http\Controllers\Receptionist\AppointmentController@editprint')->name('reception.letterhead.editprint');


            Route::get('{id}/edit-letterhead', 'App\Http\Controllers\Receptionist\AppointmentController@editletter')->name('reception.letterhead.edit');
            Route::post('{id}/edit-letterhead', 'App\Http\Controllers\Receptionist\AppointmentController@updateLetterhead')->name('reception.letterhead.save');
            Route::delete('/delete-letterhead-medicine/{id}', 'App\Http\Controllers\Receptionist\AppointmentController@deleteptmedicine')->name('delete.medicine');

        });
       
        Route::group( [ 'prefix' => 'vitals'], function () {
            Route::get( '/', 'App\Http\Controllers\Receptionist\VitalsController@index');
            Route::get( 'getdata', 'App\Http\Controllers\Receptionist\VitalsController@getdata')->name( 'vitals.data' );
            Route::get('/letterhead/{id}', 'App\Http\Controllers\Receptionist\VitalsController@show')->name('receptionist.vitals.letterhead.show');
            Route::get('{id}/delete', 'App\Http\Controllers\Receptionist\VitalsController@delete')->name('receptionist.vitals.delete');

        });

        Route::group( [ 'prefix' => 'patients'], function () {
            Route::get( '/', 'App\Http\Controllers\Receptionist\SearchPatientController@index' );
            Route::get( 'getdata', 'App\Http\Controllers\Receptionist\SearchPatientController@getdata')->name( 'search.patients.data' );
        });

        Route::group( [ 'prefix' => 'reports'], function () {
            Route::get( '/', 'App\Http\Controllers\Receptionist\ReportsController@index' );
            Route::get('/exportCSV','App\Http\Controllers\Receptionist\ReportsController@exportCSV')->name('receptionist.appointments.exportCSV');
            Route::get('/exportCustomCSV','App\Http\Controllers\Receptionist\ReportsController@exportCustomCSV')->name('receptionist.appointments.exportCustomCSV');
        });

    });

    Route::group( [ 'prefix' => 'doctor'], function (){
        Route::get( '/dashboard', 'App\Http\Controllers\Doctor\HomeController@index' );

        Route::group( [ 'prefix' => 'appointments'], function () {
            Route::get( '/', 'App\Http\Controllers\Doctor\AppointmentController@index' );
            Route::get( 'getdata', 'App\Http\Controllers\Doctor\AppointmentController@getdata')->name( 'doctor.appointments.data' );
            Route::post('/update-status', 'App\Http\Controllers\Doctor\AppointmentController@updateStatus')->name('doctor.appointments.updateStatus');
            Route::get( '{id}/delete','App\Http\Controllers\Doctor\AppointmentController@delete');
            Route::post('/upload-files', 'App\Http\Controllers\Doctor\AppointmentController@uploadFiles')->name('doctor.appointments.uploadFiles');
            Route::get( '{id}/open','App\Http\Controllers\Doctor\AppointmentController@showFile')->name('doctor.appointments.open');
            Route::get('/generate-pdf/{id}', 'App\Http\Controllers\Doctor\AppointmentController@generatePDF')->name('doctor.generate.pdf');

            Route::get('/export-csv', 'App\Http\Controllers\Doctor\AppointmentController@exportCSV')->name('doctor.appointments.exportCSV');
            Route::get('/export-pdf', 'App\Http\Controllers\Doctor\AppointmentController@exportPDF')->name('doctor.appointments.exportPDF');
              //letterhead routes
            Route::get('{id}/letterhead', 'App\Http\Controllers\Doctor\AppointmentController@showletter');
            Route::post('/get-diagnosis-details', 'App\Http\Controllers\Doctor\AppointmentController@getDiagnosisDetails')->name('doctor.get.diagnosis.details');
            Route::post('/letterhead/save/{id}', 'App\Http\Controllers\Doctor\AppointmentController@storeLetterhead')->name('doctor.letterhead.save');

            Route::get('/get-letterhead', 'App\Http\Controllers\Doctor\AppointmentController@getLetterhead')->name('doctor.get.letterhead');
            Route::get('/medicine-list', 'App\Http\Controllers\Doctor\AppointmentController@getMedicineList')->name('doctor.get.medicine.list');

        });
        Route::group( [ 'prefix' => 'vitals'], function () {
            Route::get( '/', 'App\Http\Controllers\Doctor\VitalsController@index');
            Route::get( 'getdata', 'App\Http\Controllers\Doctor\VitalsController@getdata')->name( 'doctor.vitals.data' );
            Route::get('/letterhead/{id}', 'App\Http\Controllers\Doctor\VitalsController@show')->name('doctor.vitals.letterhead.show');
            Route::get('{id}/delete', 'App\Http\Controllers\Doctor\VitalsController@delete')->name('doctor.vitals.delete');

        });
        Route::group( [ 'prefix' => 'patients'], function () {
            Route::get( '/', 'App\Http\Controllers\Doctor\PatientController@index' );
            Route::delete('{id}/delete', 'App\Http\Controllers\Doctor\PatientController@destroy');
            Route::get( 'getdata', 'App\Http\Controllers\Doctor\PatientController@getdata')->name( 'doctor.patient.data' );
        });

        Route::group( [ 'prefix' => 'patient_dose'], function () {
            Route::get( '/', 'App\Http\Controllers\Doctor\DoseController@index' );
            Route::get( 'getdata', 'App\Http\Controllers\Doctor\DoseController@getdata')->name( 'doctor.patient_dose.data' );
            Route::get( 'letterhead/{id}', 'App\Http\Controllers\Doctor\DoseController@dose_page' );
            Route::post('/letterhead/save/{id}', 'App\Http\Controllers\Doctor\DoseController@storeLetterhead')->name('doctor.letterhead.save');
            Route::get('/show/{id}', 'App\Http\Controllers\Doctor\DoseController@showdata');

            Route::get( '{id}/open','App\Http\Controllers\Doctor\DoseController@showFile');
            Route::get('/generate-pdf/{id}', 'App\Http\Controllers\Doctor\DoseController@generatePDF')->name('doctor.generate.pdf');
        });
    });

    Route::group( [ 'prefix' => 'medical_officer'], function (){
        Route::get( '/dashboard', 'App\Http\Controllers\Medicalofficer\HomeController@index' );

        Route::group( [ 'prefix' => 'appointments'], function () {
            Route::get( '/', 'App\Http\Controllers\Medicalofficer\AppointmentController@index' );
            Route::get( 'getdata', 'App\Http\Controllers\Medicalofficer\AppointmentController@getdata')->name( 'medical_officer.appointments.data' );
            Route::post('/update-status', 'App\Http\Controllers\Medicalofficer\AppointmentController@updateStatus')->name('medical_officer.appointments.updateStatus');
            Route::get( '{id}/delete','App\Http\Controllers\Medicalofficer\AppointmentController@delete');
            Route::post('/upload-files', 'App\Http\Controllers\Medicalofficer\AppointmentController@uploadFiles')->name('medical_officer.appointments.uploadFiles');
            Route::get( '{id}/open','App\Http\Controllers\Medicalofficer\AppointmentController@showFile')->name('medical_officer.appointments.open');
            Route::delete('/patient-files/{id}', 'App\Http\Controllers\Medicalofficer\AppointmentController@destroy')->name('patient-files.destroy');
            Route::get('/generate-pdf/{id}', 'App\Http\Controllers\Medicalofficer\AppointmentController@generatePDF')->name('medical_officer.generate.pdf');


            Route::get('/export-csv', 'App\Http\Controllers\Medicalofficer\AppointmentController@exportCSV')->name('medical_officer.appointments.exportCSV');
            Route::get('/export-pdf', 'App\Http\Controllers\Medicalofficer\AppointmentController@exportPDF')->name('medical_officer.appointments.exportPDF');
              //letterhead routes
            Route::get('{id}/letterhead', 'App\Http\Controllers\Medicalofficer\AppointmentController@showletter');
            Route::post('/get-diagnosis-details', 'App\Http\Controllers\Medicalofficer\AppointmentController@getDiagnosisDetails')->name('medical_officer.get.diagnosis.details');
            Route::post('/letterhead/save/{id}', 'App\Http\Controllers\Medicalofficer\AppointmentController@storeLetterhead')->name('medical.letterhead.save');
            

            Route::get('/get-letterhead', 'App\Http\Controllers\Medicalofficer\AppointmentController@getLetterhead')->name('medical_officer.get.letterhead');
            Route::get('/medicine-list', 'App\Http\Controllers\Medicalofficer\AppointmentController@getMedicineList')->name('medical_officer.get.medicine.list');

            Route::get('{id}/edit-letterhead', 'App\Http\Controllers\Medicalofficer\AppointmentController@editletter')->name('medical_officer.letterhead.edit');
            Route::post('{id}/edit-letterhead', 'App\Http\Controllers\Medicalofficer\AppointmentController@updateLetterhead')->name('medical_officer.letterhead.save');
            Route::delete('/delete-letterhead-medicine/{id}', 'App\Http\Controllers\Medicalofficer\AppointmentController@deleteptmedicine');

        });
        Route::group( [ 'prefix' => 'vitals'], function () {
            Route::get( '/', 'App\Http\Controllers\Medicalofficer\VitalsController@index');
            Route::get( 'getdata', 'App\Http\Controllers\Medicalofficer\VitalsController@getdata')->name( 'medical_officer.vitals.data' );
            Route::get('/letterhead/{id}', 'App\Http\Controllers\Medicalofficer\VitalsController@show')->name('medical_officer.vitals.letterhead.show');
            Route::get('{id}/delete', 'App\Http\Controllers\Medicalofficer\VitalsController@delete')->name('medical_officer.vitals.delete');
            Route::get('/export-pdf', 'App\Http\Controllers\Medicalofficer\VitalsController@exportPatientVitalPDF')->name('medical_officer.vitals.exportPatientVitalPDF');
        });
        Route::group( [ 'prefix' => 'patients'], function () {
            Route::get( '/', 'App\Http\Controllers\Medicalofficer\PatientController@index' );
            Route::delete('{id}/delete', 'App\Http\Controllers\Medicalofficer\PatientController@destroy');
            Route::get( 'getdata', 'App\Http\Controllers\Medicalofficer\PatientController@getdata')->name( 'medical_officer.patient.data' );
        });
        Route::group( [ 'prefix' => 'patient_dose'], function () {
            Route::get( '/', 'App\Http\Controllers\Medicalofficer\DoseController@index' );
            Route::get( 'getdata', 'App\Http\Controllers\Medicalofficer\DoseController@getdata')->name( 'medical_officer.patient_dose.data' );
            Route::get( 'letterhead/{id}', 'App\Http\Controllers\Medicalofficer\DoseController@dose_page' );
            Route::post('/letterhead/save/{id}', 'App\Http\Controllers\Medicalofficer\DoseController@storeLetterhead')->name('medical_officer.letterhead.save');
            Route::get('/show/{id}', 'App\Http\Controllers\Medicalofficer\DoseController@showdata');

            Route::get( '{id}/open','App\Http\Controllers\Medicalofficer\DoseController@showFile');
            Route::get('/generate-pdf/{id}', 'App\Http\Controllers\Medicalofficer\DoseController@generatePDF')->name('medical_officer.generate.pdf');
        });
        Route::group( [ 'prefix' => 'to_do_list'], function () {
            Route::get( '/', 'App\Http\Controllers\Medicalofficer\TodoController@index' );
            Route::get('/show/{id}', 'App\Http\Controllers\Medicalofficer\TodoController@showdata');
            Route::get( 'getdata', 'App\Http\Controllers\Medicalofficer\TodoController@getdata')->name( 'medical_officer.to_do_list.data' );
        });
        Route::group( [ 'prefix' => 'discharge_summary'], function () {
            Route::get( '/', 'App\Http\Controllers\Medicalofficer\SummaryController@index' );
            Route::get( 'getdata', 'App\Http\Controllers\Medicalofficer\SummaryController@getdata')->name( 'medical_officer.patients.data' );
            Route::get( '/show/{id}', 'App\Http\Controllers\Medicalofficer\SummaryController@show' );
            Route::post('/patients/{id}/save-discharge-summary', 'App\Http\Controllers\Medicalofficer\SummaryController@saveDischargeSummary')->name('patients.saveDischargeSummary');
            Route::get('/patients/{id}/print-discharge-summary', 'App\Http\Controllers\Medicalofficer\SummaryController@printDischargeSummary')->name('patients.printDischargeSummary');
        });

    });

    Route::group( [ 'prefix' => 'nursing'], function () { 
        Route::get( '/dashboard', 'App\Http\Controllers\Nursing\HomeController@index' );
        Route::get('/medicine-list', 'App\Http\Controllers\Nursing\HomeController@getMedicineList')->name('nursing.get.medicine.list');

        
        Route::group( [ 'prefix' => 'patients'], function () {
            Route::get( '/', 'App\Http\Controllers\Nursing\PatientController@index' );
            Route::get( '/add', 'App\Http\Controllers\Nursing\PatientController@create');
            Route::post( '/add', 'App\Http\Controllers\Nursing\PatientController@store');
            Route::get( '{id}/edit', 'App\Http\Controllers\Nursing\PatientController@edit')->name('nursing.patient.edit');
            Route::put( '{id}/edit', 'App\Http\Controllers\Nursing\PatientController@update')->name('nursing.patient.update');
            Route::delete('{id}/delete', 'App\Http\Controllers\Nursing\PatientController@destroy');
            Route::get( 'getdata', 'App\Http\Controllers\Nursing\PatientController@getdata')->name( 'nursing.patient.data' );

            Route::get('/search','App\Http\Controllers\Nursing\PatientController@searchPatient');
            Route::get('/getBeds', 'App\Http\Controllers\Nursing\PatientController@getBeds');

        });

        Route::group( [ 'prefix' => 'patient_dose'], function () {
            Route::get( '/', 'App\Http\Controllers\Nursing\DoseController@index' );
            Route::get( 'getdata', 'App\Http\Controllers\Nursing\DoseController@getdata')->name( 'nursing.patient_dose.data' );
            Route::get( 'letterhead/{id}', 'App\Http\Controllers\Nursing\DoseController@dose_page' );
            Route::post('/letterhead/save/{id}', 'App\Http\Controllers\Nursing\DoseController@storeLetterhead')->name('nursing.letterhead.save');
            Route::get('/show/{id}', 'App\Http\Controllers\Nursing\DoseController@showdata');
          
          
            Route::get('/service/{id}', 'App\Http\Controllers\Nursing\DoseController@service');
            Route::post('/patient-service-start', 'App\Http\Controllers\Nursing\DoseController@startservice');
            Route::post('/patient-service-stop', 'App\Http\Controllers\Nursing\DoseController@stopService');
            Route::post('/patient-service-delete',  'App\Http\Controllers\Nursing\DoseController@deleteService')->name('patient.service.delete');
            Route::post('/update-service-amount', 'App\Http\Controllers\Nursing\DoseController@updateAmount');

            Route::get('view_patients', 'App\Http\Controllers\Nursing\DoseController@getAllPatient');

        });

        Route::group( [ 'prefix' => 'treatment_sheet'], function () {
            Route::get( '/', 'App\Http\Controllers\Nursing\TreatmentController@index' );
            Route::get( 'getdata', 'App\Http\Controllers\Nursing\TreatmentController@getdata')->name( 'nursing.treatment.data' );
            Route::get('/show/{id}', 'App\Http\Controllers\Nursing\TreatmentController@showdata');

            Route::get('/get-patient-medicines/{id}', 'App\Http\Controllers\Nursing\TreatmentController@getPatientMedicines');
            Route::post('/update-medicine-status', 'App\Http\Controllers\Nursing\TreatmentController@updateMedicineStatus');
            Route::post('/upload-files', 'App\Http\Controllers\Nursing\TreatmentController@uploadFiles')->name('nursing.uploadFiles');
            Route::get( '{id}/open','App\Http\Controllers\Nursing\TreatmentController@showFile')->name('nursing.open');
            Route::get('/generate-pdf/{id}', 'App\Http\Controllers\Nursing\TreatmentController@generatePDF')->name('nursing.generate.pdf');

        });

        Route::group( [ 'prefix' => 'to_do_list'], function () {
            Route::get( '/', 'App\Http\Controllers\Nursing\TodoController@index' );
            Route::get('/show/{id}', 'App\Http\Controllers\Nursing\TodoController@showdata');
            Route::get( 'getdata', 'App\Http\Controllers\Nursing\TodoController@getdata')->name( 'nursing.to_do_list.data' );
        });

        Route::group( [ 'prefix' => 'vitals_chart'], function () {
            Route::get( '/', 'App\Http\Controllers\Nursing\VitalchartController@index' );
            Route::get( 'getdata', 'App\Http\Controllers\Nursing\VitalchartController@getdata')->name( 'nursing.vital_chart.data' );
            Route::get('/show/{id}', 'App\Http\Controllers\Nursing\VitalchartController@showdata');

            Route::get('list', 'App\Http\Controllers\Nursing\VitalchartController@getvitaldata')->name('nursing.vital_chart');
            Route::post('vital/store', 'App\Http\Controllers\Nursing\VitalchartController@storevital')->name('nursing.vital_chart.store');
            Route::DELETE('/delete/{id}', 'App\Http\Controllers\Nursing\VitalchartController@destroy')->name('nursing.vitals_chart.delete');
            Route::post('vital/update', 'App\Http\Controllers\Nursing\VitalchartController@updatevital')->name('nursing.vital_chart.update');
            Route::get('/vital/print/{id}', 'App\Http\Controllers\Nursing\VitalchartController@print')->name('nursing.vital_chart.print');

        });

        Route::group( [ 'prefix' => 'room_shifting'], function () {
            Route::get( '/', 'App\Http\Controllers\Nursing\ShiftingController@index' );
            Route::get( '/add', 'App\Http\Controllers\Nursing\ShiftingController@create');
            Route::post( '/add', 'App\Http\Controllers\Nursing\ShiftingController@store');
            Route::get( '{id}/edit', 'App\Http\Controllers\Nursing\ShiftingController@edit')->name('nursing.room_shift.edit');
            Route::put( '{id}/edit', 'App\Http\Controllers\Nursing\ShiftingController@update')->name('nursing.room_shift.update');
            Route::delete('{id}/delete', 'App\Http\Controllers\Nursing\ShiftingController@destroy');
            Route::get( 'getdata', 'App\Http\Controllers\Nursing\ShiftingController@getdata')->name( 'nursing.room_shift.data' );
            Route::get('/getBeds', 'App\Http\Controllers\Nursing\ShiftingController@getBeds');
            Route::get('getPatientDetails',  'App\Http\Controllers\Nursing\ShiftingController@getPatientDetails');

        });

        Route::group( [ 'prefix' => 'discharge'], function () {
            Route::get( '/', 'App\Http\Controllers\Nursing\DischargeController@index' );
            Route::post('/discharge/save', 'App\Http\Controllers\Nursing\DischargeController@store')->name('discharge.save');
        });

         
        Route::group( [ 'prefix' => 'patient_bill'], function () {
            Route::get( '/', 'App\Http\Controllers\Nursing\BillingController@index' );
            Route::get( 'getdata', 'App\Http\Controllers\Nursing\BillingController@getdata')->name( 'nursing.patient_bill.data' );
            Route::get('/{id}/delete', 'App\Http\Controllers\Nursing\BillingController@destroy');

        });

    });
});
