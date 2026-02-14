<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OpdLetterhead;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Patient; 
use App\Models\OpdPatientMedicine;
use PDF;
use App\Models\PatientFile; 


class VitalsController extends Controller
{
    public function index()
    {
        try {
           
            return view('doctor.vitals.list');
		} catch ( \Exception $ex ) {
			Log::critical( "patient form Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}  
    }

    public function getData(Request $request)
    {
        $query = OpdLetterhead::with('patient')->orderBy('created_at','desc');
    
        return DataTables::of($query)
            ->addColumn('fullname', function ($row) {
                return $row->patient ? $row->patient->fullname : 'N/A';
            })
            ->addColumn('patient_id', function ($row) {
                return $row->patient ? $row->patient->patient_id : 'N/A';
            })
            ->editColumn('date', function ($row) {
                return $row->created_at ? $row->created_at->format('d-m-Y') : 'N/A';
            })
            ->addColumn('actions', function ($row) {
                $showUrl = '';
                $deleteUrl = '';
                
                if (auth()->user()->hasRole('Doctor')) {
                    $showUrl = route('doctor.vitals.letterhead.show', $row->id); 
                    $deleteUrl = route('doctor.vitals.delete', $row->id); 
                } elseif (auth()->user()->hasRole('Medical Officer')) {
                    $showUrl = route('medical_officer.vitals.letterhead.show', $row->id); 
                    $deleteUrl = route('medical_officer.vitals.delete', $row->id); 
                } elseif (auth()->user()->hasRole('Receptionist')) {
                    $showUrl = route('receptionist.vitals.letterhead.show', $row->id); 
                    $deleteUrl = route('receptionist.vitals.delete', $row->id); 
                }
            
                $showButton = '<a href="' . $showUrl . '"  title="View Record"><i class="fa fa-eye"></i></a>';
                
                $deleteButton = '<a href="javascript:void(0);" 
                                    data-href="' . $deleteUrl . '" 
                                    data-toggle="modal" 
                                    data-target="#confirm-delete" 
                                    class="text-danger btn-lg"
                                    title="Delete Record">
                                    <i class="fa fa-trash"></i>
                                 </a>';
            
                return '<div style="display: flex; gap: 10px; align-items: center;">' . $showButton  . $deleteButton . '</div>';

            })
            
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->search['value'] != '') {
                    $searchValue = $request->search['value'];
                    $query->whereHas('patient', function ($q) use ($searchValue) {
                        $q->where('patient_id', 'like', "%{$searchValue}%")
                            ->orWhere('fullname', 'like', "%{$searchValue}%");
                    });
                }
            })
            ->rawColumns(['actions']) 
            ->make(true);
    }
    
    public function show($id)
    {
        $vital = OpdLetterhead::with('diagnosisName')->findOrFail($id);
        $medicines = OpdPatientMedicine::where('patient_id', $vital->patient_id)->get();
        $patientFiles = PatientFile::where('patient_id', $vital->patient_id)
        ->where('appointment_id', $vital->appointment_id)
        ->get();

        return view('doctor.vitals.show', compact('vital', 'medicines', 'patientFiles'));
    }

    public function delete($id)
    {
        try {
            $vital = OpdLetterhead::findOrFail($id);
    
            OpdPatientMedicine::where('opd_letter_id', $vital->id)
                ->where('patient_id', $vital->patient_id)
                ->delete();
    
            $vital->delete();
    
            if (auth()->user()->hasRole('Doctor')) {
                return redirect('doctor/vitals')->with('success', 'Record deleted successfully!');
            }
            if (auth()->user()->hasRole('Medical Officer')) {
                return redirect('medical_officer/vitals')->with('success', 'Record deleted successfully!');
            }
            if (auth()->user()->hasRole('Receptionist')) {
                return redirect('receptionist/vitals')->with('success', 'Record deleted successfully!');
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return response()->json(['success' => false, 'message' => 'Record not found!'], 404);
        } catch (\Exception $ex) {
            Log::error('Error deleting record: ' . $ex->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while deleting the record.'], 500);
        }
    }
    



}
