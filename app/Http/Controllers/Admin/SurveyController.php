<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Survey;
use PDF;
use Log;
class SurveyController extends Controller
{
    public function index()
    {
        try {
			return view( 'admin.survey.list');
		} catch ( \Exception $ex ) {
			Log::critical( "survey list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
    public function getData(Request $request)
    {
        $query = Survey::query();
    
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }
    
        return DataTables::of($query)
            ->addColumn('action', function($query) {
                $showUrl = url('admin/survey/' . $query->id . '/view');
                $showButton = '<a href="' . $showUrl . '" title="View details" class="btn-sm" style="background-color: #ff69b4; color: #fff;">Survey Details</a>';
    
                return '<div style="display: flex; gap: 10px; align-items: center;">' . $showButton . '</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    public function show($id)
    {
        $survey = Survey::find($id);
        return view('admin.survey.show' , compact('survey'));
    }

    public function exportPDF(Request $request)
    {
        try {
            $start = $request->start_date;
            $end = $request->end_date;
    
            // Fetch data based on date filters or get all
            $query = Survey::query();
    
            if ($start && $end) {
                $query->whereBetween('created_at', [$start, $end]);
            }
    
            $surveys = $query->orderBy('created_at', 'desc')->get();

    
            // Pass data to view
            $pdf = PDF::loadView('admin.survey.export_pdf', compact('surveys', 'start', 'end'))
            ->setPaper('A4','landscape')
            ->setOptions([
                'defaultFont' => 'Noto Sans Gujarati', // Supports Gujarati text
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);
    
            $filename = 'survey_' . now()->format('Ymd_His') . '.pdf';
    
            return $pdf->download($filename);
    
        } catch (\Exception $ex) {
            Log::critical("survey pdf Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString());
            return redirect()->back()->with('error', 'Woops! Something went wrong. Please try again later.');
        }
    }
    
}
