<?php

namespace App\Http\Controllers\Nursing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Patient;
class TodoController extends Controller
{
    public function index()
    {
        try {
			return view( 'nursing.todo.list');
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
                $showUrl = URL('nursing/to_do_list/show/' . $row->id);  

                $showButton = '<a href="' . $showUrl . '" title="View todo Sheet" class="btn-sm" style="background-color: #ff69b4; color: #fff;">To do list</a>';

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
        return view('nursing.todo.show', compact( 'patient'));
    }
}
