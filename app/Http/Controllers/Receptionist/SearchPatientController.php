<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Patient;
class SearchPatientController extends Controller
{
    public function index()
    {
        try {
			return view( 'receptionist.search_patient.list');
		} catch ( \Exception $ex ) {
			Log::critical( "patients list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }


    public function getData(Request $request)
    {
        $query = Patient::query();
        
        return DataTables::of($query)
            ->addColumn('deleted_at', function ($query) {
                return $query->deleted_at ? $query->deleted_at->format('Y-m-d H:i:s') : 'Active';
            })
            ->addColumn('created_at', function ($query) {
                return $query->created_at ? $query->created_at->format('d-m-Y') : 'N/A';
            })
            ->addColumn('action', function ($query) {
                if ($query->trashed()) {
                    return '<span class="text-danger">Deleted</span>';
                } else {
                    return '<button class="btn btn-sm btn-danger btn-trash" data-id="' . $query->id . '"><i class="fa fa-trash"></i></button>';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
