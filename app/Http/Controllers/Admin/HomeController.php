<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Patient;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Timesheet;
use App\Models\Charge;
use App\Models\Time;
use App\Models\AdvanceBooking;
use Yajra\DataTables\DataTables;
use Log;

class HomeController extends Controller
{
    public function index()
    {   
        $today = Carbon::today();

        $total_patient = Patient::count();

        $today_total_appointments = AdvanceBooking::whereDate('date', $today)
        ->where('status', 'Arrived')
        ->count();

        // ✅ Old patients in today's appointments
        $today_old_patients = Patient::join(
            'advance_booking',
            'patients.appointment_id',
            '=',
            'advance_booking.id'
        )
        
        ->whereDate('advance_booking.date', $today)
        ->where('advance_booking.status', 'Arrived')
        ->where('patients.charges', 'LIKE', '%"2"%')
        ->distinct('patients.id')
        ->count('patients.id');

        $today_new_patients = Patient::join(
            'advance_booking',
            'patients.appointment_id',
            '=',
            'advance_booking.id'
        )
        ->whereDate('advance_booking.date', $today)
        ->where('advance_booking.status', 'Arrived')
        ->where(function ($q) {
            $q->whereNull('patients.charges')
            ->orWhere('patients.charges', 'NOT LIKE', '%"2"%');
        })
        ->distinct('patients.id')
        ->count('patients.id');

        // ECG-1 (charges = 5)
        $today_ecg1_patients = Patient::whereDate('created_at', $today)
            ->where('charges', 'LIKE', '%"5"%')
            ->distinct('id')
            ->count('id');

        

        // ECG-2 (charges = 7)
        $today_ecg2_patients = Patient::whereDate('created_at', $today)
            ->where('charges', 'LIKE', '%"7"%')
            ->distinct('id')
            ->count('id');

        

        $ecg1_price = Charge::where('id', 5)->value('amount');
        $ecg2_price = Charge::where('id', 7)->value('amount');

        $today_ecg1_amount = $today_ecg1_patients * $ecg1_price;
        $today_ecg2_amount = $today_ecg2_patients * $ecg2_price;

        // ECG TOTAL
        $today_total_ecg_patients = $today_ecg1_patients + $today_ecg2_patients;
        $today_total_ecg_amount   = $today_ecg1_amount + $today_ecg2_amount;

         // 🔹 Total payment (all patients)
        $total_payment = Patient::whereDate('created_at', $today)->sum('amount');

        // 🔹 Payment type wise totals
        $cash_payment = Patient::where('payment_type', 'Cash')->whereDate('created_at', $today)
            ->sum('amount');

        $online_payment = Patient::where('payment_type', 'Online')->whereDate('created_at', $today)
            ->sum('amount');

        $debit_payment = Patient::where('payment_type', 'Debit')->whereDate('created_at', $today)
            ->sum('amount');
    
        // return view('admin.home', compact('total_patient','today_appointment'));
        return view('admin.home', compact(
            'total_patient',
            'today_total_appointments',
            'today_old_patients',
            'today_new_patients',
            'today_total_ecg_patients',
            'today_total_ecg_amount',
            'total_payment',
            'cash_payment',
            'online_payment',
            'debit_payment'
        ));
    }

    
    public function dashboardDayData(Request $request)
    {
        $date = Carbon::parse($request->date);

        // Appointments
        $total_patient = AdvanceBooking::whereDate('date', $date)
            ->where('status', 'Arrived')
            ->count();

        $old_patient = Patient::join('advance_booking', 'patients.appointment_id', '=', 'advance_booking.id')
            ->whereDate('advance_booking.date', $date)
            ->where('advance_booking.status', 'Arrived')
            ->where('patients.charges', 'LIKE', '%"2"%')
            ->distinct('patients.id')
            ->count('patients.id');

        $new_patient = Patient::join('advance_booking', 'patients.appointment_id', '=', 'advance_booking.id')
            ->whereDate('advance_booking.date', $date)
            ->where('advance_booking.status', 'Arrived')
            ->where(function ($q) {
                $q->whereNull('patients.charges')
                ->orWhere('patients.charges', 'NOT LIKE', '%"2"%');
            })
            ->distinct('patients.id')
            ->count('patients.id');

        // ECG
        $ecg1_patients = Patient::whereDate('created_at', $date)
            ->where('charges', 'LIKE', '%"5"%')
            ->distinct('id')
            ->count();

        $ecg2_patients = Patient::whereDate('created_at', $date)
            ->where('charges', 'LIKE', '%"7"%')
            ->distinct('id')
            ->count();

        $ecg1_price = Charge::where('id', 5)->value('amount');
        $ecg2_price = Charge::where('id', 7)->value('amount');

        $ecg_amount = ($ecg1_patients * $ecg1_price)
                    + ($ecg2_patients * $ecg2_price);

        // Payments
        $total_payment = Patient::whereDate('created_at', $date)->sum('amount');
        $cash_payment  = Patient::where('payment_type', 'Cash')->whereDate('created_at', $date)->sum('amount');
        $online_payment= Patient::where('payment_type', 'Online')->whereDate('created_at', $date)->sum('amount');
        $debit_payment = Patient::where('payment_type', 'Debit')->whereDate('created_at', $date)->sum('amount');

        return response()->json([
            'total_patient' => $total_patient,
            'new_patient'   => $new_patient,
            'old_patient'   => $old_patient,
            'ecg_patient'   => $ecg1_patients + $ecg2_patients,
            'ecg_amount'    => $ecg_amount,
            'total_payment' => $total_payment,
            'cash_payment'  => $cash_payment,
            'online_payment'=> $online_payment,
            'debit_payment' => $debit_payment,
        ]);

        
    }

    public function monthData(Request $request)
    {
        $month = $request->month;

        $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $end   = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        return response()->json([
            'total_patient'=> Patient::join('advance_booking','patients.appointment_id','=','advance_booking.id')
                ->whereBetween('advance_booking.date',[$start,$end])
                ->where('advance_booking.status','Arrived')
                ->distinct('patients.id')->count(),

            'new_patient' => Patient::join('advance_booking','patients.appointment_id','=','advance_booking.id')
                ->whereBetween('advance_booking.date',[$start,$end])
                ->where('advance_booking.status','Arrived')
                ->where(function ($q) {
                    $q->whereNull('patients.charges')
                    ->orWhere('patients.charges','NOT LIKE','%"2"%');
                })
                ->distinct('patients.id')->count(),

            'old_patient' => Patient::join('advance_booking','patients.appointment_id','=','advance_booking.id')
                ->whereBetween('advance_booking.date',[$start,$end])
                ->where('advance_booking.status','Arrived')
                ->where('patients.charges','LIKE','%"2"%')
                ->distinct('patients.id')->count(),

            $ecg1Count = Patient::whereBetween('created_at', [$start, $end])
                ->where('charges', 'LIKE', '%"5"%')
                ->count(),

            $ecg2Count = Patient::whereBetween('created_at', [$start, $end])
                ->where('charges', 'LIKE', '%"7"%')
                ->count(),

                $ecg1Price = Charge::where('id', 5)->value('amount'),
                $ecg2Price = Charge::where('id', 7)->value('amount'),

                $ecgAmount = ($ecg1Count * $ecg1Price) + ($ecg2Count * $ecg2Price),

                'ecg_patient'   => $ecg1Count + $ecg2Count,
                

                'ecg1_price' => $ecg1Price,
                'ecg2_price' => $ecg2Price,

                'ecg_amount' => $ecgAmount,

            'total_payment'  => Patient::whereBetween('created_at',[$start,$end])->sum('amount'),
            'cash_payment'   => Patient::whereBetween('created_at',[$start,$end])->where('payment_type','Cash')->sum('amount'),
            'online_payment' => Patient::whereBetween('created_at',[$start,$end])->where('payment_type','Online')->sum('amount'),
            'debit_payment'  => Patient::whereBetween('created_at',[$start,$end])->where('payment_type','Debit')->sum('amount')
        ]);
    }

    public function financialYearData(Request $request)
    {
        [$startYear, $endYear] = explode('-', $request->year);

        $start = Carbon::create($startYear, 4, 1);
        $end   = Carbon::create($endYear, 3, 31);

        return response()->json([
            'total_patient' =>
                Patient::join('advance_booking','patients.appointment_id','=','advance_booking.id')
                    ->whereBetween('advance_booking.date', [$start, $end])
                    ->where('advance_booking.status','Arrived')
                    ->distinct()->count('patients.id'),

            'new_patient' =>
                Patient::join('advance_booking','patients.appointment_id','=','advance_booking.id')
                    ->whereBetween('advance_booking.date', [$start, $end])
                    ->where('advance_booking.status','Arrived')
                    ->where(function ($q) {
                        $q->whereNull('patients.charges')
                        ->orWhere('patients.charges','NOT LIKE','%"2"%');
                    })
                    ->distinct()->count('patients.id'),

            'old_patient' =>
                Patient::join('advance_booking','patients.appointment_id','=','advance_booking.id')
                    ->whereBetween('advance_booking.date', [$start, $end])
                    ->where('advance_booking.status','Arrived')
                    ->where('patients.charges','LIKE','%"2"%')
                    ->distinct()->count('patients.id'),

                $ecg1Count = Patient::join('advance_booking','patients.appointment_id','=','advance_booking.id')
                ->whereBetween('advance_booking.date', [$start, $end])
                ->where('advance_booking.status', 'Arrived')
                ->where('patients.charges', 'LIKE', '%"5"%')
                ->distinct('patients.id')
                ->count('patients.id'),

                $ecg2Count = Patient::join('advance_booking','patients.appointment_id','=','advance_booking.id')
                ->whereBetween('advance_booking.date', [$start, $end])
                ->where('advance_booking.status', 'Arrived')
                ->where('patients.charges', 'LIKE', '%"7"%')
                ->distinct('patients.id')
                ->count('patients.id'),

                $ecgPatientCount = Patient::join('advance_booking','patients.appointment_id','=','advance_booking.id')
                ->whereBetween('advance_booking.date', [$start, $end])
                ->where('advance_booking.status','Arrived')
                ->where(function ($q) {
                    $q->where('patients.charges','LIKE','%"5"%')
                    ->orWhere('patients.charges','LIKE','%"7"%');
                })
                ->distinct('patients.id')
                ->count('patients.id'),

                $ecg1Price = Charge::where('id', 5)->value('amount'),
                $ecg2Price = Charge::where('id', 7)->value('amount'),

                $ecgAmount = ($ecg1Count * $ecg1Price) + ($ecg2Count * $ecg2Price),

                'ecg_patient'   => $ecgPatientCount,
                

                'ecg1_price' => $ecg1Price,
                'ecg2_price' => $ecg2Price,

                'ecg_amount' => $ecgAmount,

            'total_payment'  => Patient::whereBetween('created_at',[$start,$end])->sum('amount'),
            'cash_payment'   => Patient::whereBetween('created_at',[$start,$end])->where('payment_type','Cash')->sum('amount'),
            'online_payment' => Patient::whereBetween('created_at',[$start,$end])->where('payment_type','Online')->sum('amount'),
            'debit_payment'  => Patient::whereBetween('created_at',[$start,$end])->where('payment_type','Debit')->sum('amount')       
        ]);
    }


    public function weeklyChart()
    {
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek   = Carbon::now()->endOfWeek(Carbon::SUNDAY);
        
        $labels = [];
        $dates  = [];
        $totalPatients = [];
        $newPatients = [];
        $oldPatients = [];
        $ecgPatients = [];

        $period = CarbonPeriod::create($startOfWeek, $endOfWeek);

        foreach ($period as $date) {

            $labels[] = $date->format('D');          // Mon Tue
            $dates[]  = $date->format('Y-m-d'); 

            $total = AdvanceBooking::whereDate('date', $date)
                ->where('status', 'Arrived')
                ->count();

            $new = Patient::join('advance_booking', 'patients.appointment_id', '=', 'advance_booking.id')
                ->whereDate('advance_booking.date', $date)
                ->where('advance_booking.status', 'Arrived')
                ->where(function ($q) {
                $q->whereNull('patients.charges')
                ->orWhere('patients.charges', 'NOT LIKE', '%"2"%');
                })
                ->distinct('patients.id')
                ->count('patients.id');

            $old = Patient::join('advance_booking', 'patients.appointment_id', '=', 'advance_booking.id')
                ->whereDate('advance_booking.date', $date)
                ->where('advance_booking.status', 'Arrived')
                ->where('patients.charges', 'LIKE', '%"2"%')
                ->distinct('patients.id')
                ->count('patients.id');

             // ✅ ECG (charges 5 or 7)
            $ecgPatients[] = Patient::whereDate('created_at', $date)
                ->where(function ($q) {
                    $q->where('charges','LIKE','%"5"%')
                    ->orWhere('charges','LIKE','%"7"%');
                })
                ->distinct('id')
                ->count('id');

            $totalPatients[] = $total;
            $newPatients[]   = $new;
            $oldPatients[]   = $old;
        }
        

        return response()->json([
            'labels' => $labels,
            'dates'  => $dates,
            'total' => $totalPatients,
            'new' => $newPatients,
            'old' => $oldPatients,
            'ecg'    => $ecgPatients,
        ]);

    }

    public function dailyChart()
    {
        $month = Carbon::now()->month;
        $year  = Carbon::now()->year;

        $daysInMonth = Carbon::now()->daysInMonth;

        $labels = [];
        $dates  = [];
        $total = $new = $old = $ecg = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {

            $date = Carbon::create($year, $month, $day)->format('Y-m-d');

            $labels[] = $day;
            $dates[]  = $date;

            $total[] = AdvanceBooking::whereDate('date',$date)
                        ->where('status','Arrived')->count();

            $new[] = Patient::join('advance_booking','patients.appointment_id','=','advance_booking.id')
                ->whereDate('advance_booking.date',$date)
                ->where('advance_booking.status','Arrived')
                ->where(function($q){
                    $q->whereNull('patients.charges')
                    ->orWhere('patients.charges','NOT LIKE','%"2"%');
                })
                ->distinct()->count('patients.id');

            $old[] = Patient::join('advance_booking','patients.appointment_id','=','advance_booking.id')
                ->whereDate('advance_booking.date',$date)
                ->where('advance_booking.status','Arrived')
                ->where('patients.charges','LIKE','%"2"%')
                ->distinct()->count('patients.id');

            $ecg[] = Patient::whereDate('created_at',$date)
                ->where(function($q){
                    $q->where('charges','LIKE','%"5"%')
                    ->orWhere('charges','LIKE','%"7"%');
                })
                ->distinct()->count('id');
        }

        return response()->json(compact('labels','dates','total','new','old','ecg'));
    }

    public function monthlyChart()
    {
        $year = Carbon::now()->year;

        $labels = [];
        $months = [];
        $total = [];
        $new   = [];
        $old   = [];
        $ecg   = [];

        for ($m = 1; $m <= 12; $m++) {

            $start = Carbon::create($year, $m, 1)->startOfMonth();
            $end   = Carbon::create($year, $m, 1)->endOfMonth();

            $labels[] = $start->format('M');     // Jan Feb
            $months[] = $start->format('Y-m');   // 2026-01

            $total[] = Patient::join('advance_booking','patients.appointment_id','=','advance_booking.id')
                ->whereBetween('advance_booking.date', [$start, $end])
                ->where('advance_booking.status','Arrived')
                ->distinct('patients.id')
                ->count();

            $new[] = Patient::join('advance_booking','patients.appointment_id','=','advance_booking.id')
                ->whereBetween('advance_booking.date', [$start, $end])
                ->where('advance_booking.status','Arrived')
                ->where(function ($q) {
                    $q->whereNull('patients.charges')
                    ->orWhere('patients.charges','NOT LIKE','%"2"%');
                })
                ->distinct('patients.id')
                ->count();

            $old[] = Patient::join('advance_booking','patients.appointment_id','=','advance_booking.id')
                ->whereBetween('advance_booking.date', [$start, $end])
                ->where('advance_booking.status','Arrived')
                ->where('patients.charges','LIKE','%"2"%')
                ->distinct('patients.id')
                ->count();

            $ecg[] = Patient::whereBetween('created_at', [$start, $end])
                ->where(function ($q) {
                    $q->where('charges','LIKE','%"5"%')
                    ->orWhere('charges','LIKE','%"7"%');
                })
                ->count();
        }

        return response()->json([
            'labels' => $labels,
            'months' => $months,
            'total'  => $total,
            'new'    => $new,
            'old'    => $old,
            'ecg'    => $ecg
        ]);
    }

    public function financialYearChart()
    {
        $years = [
            [
                'label' => '2024-2025',
                'start' => '2024-04-01',
                'end'   => '2025-03-31',
            ],
            [
                'label' => '2025-2026',
                'start' => '2025-04-01',
                'end'   => '2026-03-31',
            ]
        ];

        $labels = $keys = $total = $new = $old = $ecg = [];

        foreach ($years as $y) {

            $labels[] = $y['label'];
            $keys[]   = $y['label'];   // 🔑 IMPORTANT FOR CLICK

            $total[] = Patient::join('advance_booking','patients.appointment_id','=','advance_booking.id')
                ->whereBetween('advance_booking.date', [$y['start'], $y['end']])
                ->where('advance_booking.status','Arrived')
                ->distinct('patients.id')
                ->count('patients.id');

            $new[] = Patient::join('advance_booking','patients.appointment_id','=','advance_booking.id')
                ->whereBetween('advance_booking.date', [$y['start'], $y['end']])
                ->where('advance_booking.status','Arrived')
                ->where(function ($q) {
                    $q->whereNull('patients.charges')
                    ->orWhere('patients.charges','NOT LIKE','%"2"%');
                })
                ->distinct('patients.id')
                ->count('patients.id');

            $old[] = Patient::join('advance_booking','patients.appointment_id','=','advance_booking.id')
                ->whereBetween('advance_booking.date', [$y['start'], $y['end']])
                ->where('advance_booking.status','Arrived')
                ->where('patients.charges','LIKE','%"2"%')
                ->distinct('patients.id')
                ->count('patients.id');

            $ecg[] = Patient::join('advance_booking','patients.appointment_id','=','advance_booking.id')
                ->whereBetween('advance_booking.date', [$y['start'], $y['end']])
                ->where('advance_booking.status','Arrived')
                ->where(function ($q) {
                    $q->where('patients.charges','LIKE','%"5"%')
                    ->orWhere('patients.charges','LIKE','%"7"%');
                })
                ->distinct('patients.id')
                ->count('patients.id');
            }

        return response()->json(compact(
            'labels','keys','total','new','old','ecg'
        ));
    }


   
    public function patientList(Request $request)
    {
        return view('admin.day_list', [
            'date' => $request->date,
            'month' => $request->month,
            'financial_year' => $request->financial_year,
            'type' => $request->type
        ]);
    }

    public function patientListData(Request $request)
    {
        $date  = $request->date ? Carbon::parse($request->date) : null;
        $type = $request->type;
        $month = $request->month;
        $financialYear = $request->financial_year;


        $query = Patient::join('advance_booking', 'patients.appointment_id', '=', 'advance_booking.id')
            ->join('time_sheet', 'advance_booking.time', '=', 'time_sheet.id')   
            ->where('advance_booking.status', 'Arrived')
            ->select([
                'time_sheet.time_sheet as booking_time',
                'patients.id',
                'patients.patient_id',
                'advance_booking.time',
                'patients.fullname',
                'patients.address', 
                'patients.phone',
                'patients.amount',
                'advance_booking.date',
                'patients.charges',
                'patients.payment_type',
                
            ]);
            

       if ($financialYear) {

                $years = explode('-', $financialYear);
                $startYear = (int)$years[0];
                $endYear   = (int)$years[1];

                $start = Carbon::create($startYear, 4, 1)->startOfDay();
                $end   = Carbon::create($endYear, 3, 31)->endOfDay();

            $query->whereBetween('advance_booking.date', [$start, $end]);
        
        }
        elseif ($month) {

            $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $end   = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

            $query->whereBetween('advance_booking.date', [$start, $end]);

        }
        elseif ($date) {

            $query->whereDate('advance_booking.date', $date);

        }


        if ($type === 'new') {
            $query->where(function ($q) {
                $q->whereNull('patients.charges')
                ->orWhere('patients.charges', 'NOT LIKE', '%"2"%');
            });
        }

        if ($type === 'old') {
            $query->where('patients.charges', 'LIKE', '%"2"%');
        }

        if ($type === 'ecg') {
            $query->where(function ($q) {
                $q->where('patients.charges','LIKE','%"5"%')
                ->orWhere('patients.charges','LIKE','%"7"%');
            });
        }

        return DataTables::of($query)
            ->addColumn('time', function ($Patient) {
                    return $Patient->booking_time 
                        ? \Carbon\Carbon::parse($Patient->booking_time)->format('h:i A') 
                        : 'N/A';
                })
            ->editColumn('date', function ($row) {
                return Carbon::parse($row->date)->format('d M Y');
            })
            ->make(true);
    }
    
  
}




