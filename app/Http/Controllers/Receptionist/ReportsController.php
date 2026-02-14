<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Patient;

class ReportsController extends Controller
{
    public function index(){
        return view('receptionist.report.list');
    }


    public function exportCSV(Request $request)
    {
        $query = Patient::with('appointment');
        $filenamePrefix = 'patients_report_';
    
        if ($request->has('date')) {
            $date = Carbon::parse($request->date);
            $query->whereHas('appointment', function ($q) use ($date) {
                $q->whereDate('date', $date);
            });
    
            $filenamePrefix .= $date->format('d-m-Y');
        }
    
        $patients = Patient::query()
            ->select([
                'patients.*',
                'time_sheet.time_sheet as booking_time',
            ])
            ->join('advance_booking', 'patients.appointment_id', '=', 'advance_booking.id') 
            ->join('time_sheet', 'advance_booking.time', '=', 'time_sheet.id') 
            ->whereDate('patients.created_at', now()->format('Y-m-d'))
            ->get();
    
        $cashTotal = $patients->where('payment_type', 'Cash')->sum('amount');
        $onlineTotal = $patients->where('payment_type', 'Online')->sum('amount');
        $debitTotal = $patients->where('payment_type', 'Debit')->sum('amount');

        $totalAmount = $cashTotal + $onlineTotal;
    
        $filename = $filenamePrefix . '.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        ];
    
        $columns = ['Patient ID', 'Time', 'Full Name', 'Age', 'Phone', 'Payment Type', 'Amount', 'Payment Date', 'Status'];
    
        $callback = function () use ($patients, $columns, $cashTotal, $onlineTotal,$debitTotal, $totalAmount) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, $columns);
    
            foreach ($patients as $patient) {
                $formattedTime = $patient->booking_time 
                    ? \Carbon\Carbon::parse($patient->booking_time)->format('h:i A') 
                    : 'N/A';
                $bookingDate = \Carbon\Carbon::parse($patient->created_at)->format('d-m-Y');
                
                fputcsv($file, [
                    $patient->id,
                    $formattedTime, 
                    $patient->fullname,
                    $patient->age,
                    $patient->phone,
                    $patient->payment_type,
                    $patient->amount,
                    $bookingDate,
                    $patient->status,
                ]);
            }
    
            fputcsv($file, []); 
            fputcsv($file, ['', '', '', '', '', '', 'Cash Payment Total', $cashTotal]);
            fputcsv($file, ['', '', '', '', '', '', 'Online Payment Total', $onlineTotal]);
            fputcsv($file, ['', '', '', '', '', '', 'Debit Payment Total', $debitTotal]);
            fputcsv($file, ['', '', '', '', '', '', 'Total Amount', $totalAmount]);
    
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }
    

     public function exportCustomCSV(Request $request)
    {
        $fromDate = Carbon::parse($request->from_date)->startOfDay();
        $toDate = Carbon::parse($request->to_date)->endOfDay();

        $filenamePrefix = 'patients_report_' . $fromDate->format('d-m-Y') . '_to_' . $toDate->format('d-m-Y');
    
        return $this->generateCSV($fromDate, $toDate, $filenamePrefix);
    }
    
    private function generateCSV($fromDate, $toDate, $filenamePrefix)
    {
        $patients = Patient::query()
            ->select([
                'patients.*',
                'time_sheet.time_sheet as booking_time',
            ])
            ->join('advance_booking', 'patients.appointment_id', '=', 'advance_booking.id') 
            ->join('time_sheet', 'advance_booking.time', '=', 'time_sheet.id') 
            ->whereBetween('patients.created_at', [$fromDate, $toDate])
            ->get();
    
        $cashTotal = $patients->where('payment_type', 'Cash')->sum('amount');
        $onlineTotal = $patients->where('payment_type', 'Online')->sum('amount');
        $debitTotal = $patients->where('payment_type', 'Debit')->sum('amount');

        // return response()->json([$debitTotal]);
        $totalAmount = $cashTotal + $onlineTotal;
    
        $filename = $filenamePrefix . '.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
    
        $columns = ['Patient ID', 'Time', 'Full Name', 'Age', 'Phone', 'Payment Type', 'Amount', 'Payment Date', 'Status'];
    
        $callback = function () use ($patients, $columns, $cashTotal, $onlineTotal,$debitTotal, $totalAmount) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
    
            foreach ($patients as $patient) {
                $formattedTime = $patient->booking_time 
                    ? \Carbon\Carbon::parse($patient->booking_time)->format('h:i A') 
                    : 'N/A';
                $bookingDate = \Carbon\Carbon::parse($patient->created_at)->format('d-m-Y');
                
                fputcsv($file, [
                    $patient->id,
                    $formattedTime,  
                    $patient->fullname,
                    $patient->age,
                    $patient->phone,
                    $patient->payment_type,
                    $patient->amount,
                    $bookingDate,
                    $patient->status,
                ]);
            }
    
            fputcsv($file, []); 
            fputcsv($file, ['', '', '', '', '', '', 'Cash Payment Total', $cashTotal]);
            fputcsv($file, ['', '', '', '', '', '', 'Online Payment Total', $onlineTotal]);
            fputcsv($file, ['', '', '', '', '', '', 'Debit Payment Total', $debitTotal]);

            fputcsv($file, ['', '', '', '', '', '', 'Total Amount', $totalAmount]);
    
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }
    



}
