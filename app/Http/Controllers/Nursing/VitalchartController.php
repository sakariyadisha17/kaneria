<?php

namespace App\Http\Controllers\Nursing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use Yajra\DataTables\Facades\DataTables;
use App\Models\VitalChart;
use Carbon\Carbon;
class VitalchartController extends Controller
{
    public function index()
    {
        try {
			return view( 'nursing.vital_chart.list');
		} catch ( \Exception $ex ) {
			Log::critical( "patient list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'nursing' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }

    public function getData(Request $request)
    {
        $query = Patient::with(['room', 'bed']) 
            ->where('admit_status', 'Admitted')
            ->orderBy('id', 'desc');
    
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                $showUrl = URL('nursing/vitals_chart/show/' . $row->id);  

            $showButton = '<a href="' . $showUrl . '" title="View vital chart" class="btn-sm" style="background-color: #ff69b4; color: #fff;">Vital chart</a>';
            
                            

            return '<div style="display: flex; gap: 10px; align-items: center;">' 
                         . $showButton .
                   '</div>';
            })
            ->editColumn('room_type', function ($query) {
                return $query->room ? $query->room->type : 'No Room Assigned'; 
            })
            ->addColumn('bed_no', function ($query) {
                return optional($query->bed)->bed_no ?: 'No Bed Assigned';
            })
            ->rawColumns(['action']) 
            ->make(true);
    }

    public function showdata($id)
    {
        $patient = Patient::find($id);

        if (!$patient) {
            return redirect()->back()->with('error', 'Patient not found');
        }
        return view('nursing.vital_chart.show', compact( 'patient'));
    }

    public function getvitaldata(Request $request)
    {
        $query = VitalChart::query();
        if ($request->has('select_date') && !empty($request->select_date)) {
            // Filter records by date (assuming 'datetime' stores the datedatetime)
            $query->whereDate('datetime', $request->select_date);
        }
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                if (empty($row->datetime)) {
                    return '<button class="btn btn-sm addBtn" data-id="' . $row->id . '"><i class="fa fa-plus"></i></button>';
                } else {
                    return '<button class="btn btn-sm btn-info editBtn" data-id="' . $row->id . '"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger deleteBtn" data-id="' . $row->id . '"><i class="fa fa-trash"></i></button>';
                }
            })
            ->editColumn('datedatetime', function ($row) {
                return $row->datetime ? Carbon::parse($row->datetime)->format('d-m-Y H:i') : '';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function storevital(Request $request)
    {
        if (!empty($request->id)) {
            $vital = VitalChart::find($request->id);
            if (!$vital) {
                return response()->json(['success' => false, 'message' => 'Record not found']);
            }
        } else {
            $vital = new VitalChart();
            $vital->datetime = Carbon::now();
        }
        $vital->patient_id = $request->patient_id;
        $vital->temp = $request->temp;
        $vital->pulse = $request->pulse;
        $vital->bp = $request->bp;
        $vital->spo2 = $request->spo2;
        $vital->input = $request->input;
        $vital->output = $request->output;
        $vital->rbs = $request->rbs;
        $vital->rt = $request->rt;
        $vital->remark = $request->remark;
        $vital->save();

        return response()->json([
            'success' => true,
            'id' => $vital->id,
            'datetime' => $vital->datetime ? Carbon::parse($vital->datetime)->format('d-m-Y H:i') : ''
        ]);
    }
   
    public function updatevital(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:vital_chart,id',
            'patient_id' => 'required|exists:patients,id',
            'temp' => 'nullable|string|max:255',
            'pulse' => 'nullable|string|max:255',
            'bp' => 'nullable|string|max:255',
            'spo2' => 'nullable|string|max:255',
            'input' => 'nullable|string|max:255',
            'output' => 'nullable|string|max:255',
            'rbs' => 'nullable|string|max:255',
            'rt' => 'nullable|string|max:255',
            'remark' => 'nullable|string|max:255',
        ]);

        $vital = VitalChart::findOrFail($request->id);
        $vital->update([
            
            'patient_id' => $request->patient_id,
            'datetime' => Carbon::now(),
            'temp' => $request->temp,
            'pulse' => $request->pulse,
            'bp' => $request->bp,
            'spo2' => $request->spo2,
            'input' => $request->input,
            'output' => $request->output,
            'rbs' => $request->rbs,
            'rt' => $request->rt,
            'remark' => $request->remark,
        ]);

        return response()->json(['success' => true]);
    }

    // Delete a record
    public function destroy($id)
    {
        $vital = VitalChart::find($id);
        if (!$vital) {
            return response()->json(['success' => false, 'message' => 'Record not found']);
        }
        $vital->delete();
        return response()->json(['success' => true]);
    }

    public function print($id)
    {
        $patient = Patient::findOrFail($id);
        $vitals = VitalChart::where('patient_id', $id)->get();
        return view('nursing.vital_chart.print', compact('patient','vitals'));
    }
}
