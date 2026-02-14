<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Doctor;
use App\Http\Requests\DoctorRequest;
use PDF;

class DoctorController extends Controller
{
    public function index()
    {
        try {
			return view( 'admin.doctors.list');
		} catch ( \Exception $ex ) {
			Log::critical( "doctors list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }

    public function doctorsPDF(Request $request){
        try {
            $doctors = Doctor::all();
            $pdf = PDF::loadView('admin.doctors.doctors_pdf', [
                'doctors' => $doctors,
            ]);
        
            $filename = 'doctors_list_.pdf';
        
            return $pdf->download($filename);
        } catch (\Exception $ex) {
            Log::critical( "doctors pdf Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect()->back()
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
        }
    }

    public function create()
    {
        try {
            return view('admin.doctors.form');
		} catch ( \Exception $ex ) {
			Log::critical( "doctors form Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }

   
    public function getData(Request $request)
    {
        $query = Doctor::query();


        return DataTables::of( $query )
            ->addColumn( 'action', function ( $query ) {

                $actionString = '<a href="' . URL( 'admin/doctors/' . $query->id . '/edit' ) . '" title="Edit" class="btn-lg text-primary"><i class="fa fa-edit"></i></a><a data-href="' . URL( 'admin/doctors/' . $query->id . '/delete' ) . '" data-toggle="modal" data-target="#confirm-delete" title="Delete" class="text-danger btn-lg"  ><i class="fa fa-trash"></i></a>';

                return $actionString;

            } )
           
            ->rawColumns( [ 'action'] )
            ->make( true );
    }

    public function store(DoctorRequest $request)
    {
       
    
        try {
            $doctorName = $request['name'];
            if (substr($doctorName, 0, 3) !== 'Dr.') {
                $doctorName = 'Dr. ' . $doctorName;
            }
    
            $doctor = new Doctor();
            $doctor->name = $doctorName;
            $doctor->phone = $request->phone; // Optional field
            $doctor->address = $request->address; // Optional field
            $doctor->save();
    
            return redirect('admin/doctors')->with('success', 'Doctor added successfully!');
        } catch (\Exception $ex) {
            Log::error('Error adding doctor: ' . $ex->getMessage());
            return redirect('admin/doctors')->with('error', 'An error occurred while adding the doctor.');
        }
    }
    
    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);
        return view('admin.doctors.form', compact('doctor'));
    }
    
    public function update(DoctorRequest $request, $id)
    {
    
        try {
           
            $doctor = Doctor::findOrFail($id);
            $doctor->name = $request->name;
            $doctor->phone = $request->phone;
            $doctor->address = $request->address;
            $doctor->save();
           
            return redirect('admin/doctors')->with('success', 'Doctor Updated!');
        } catch (\Exception $ex) {
            Log::error('Error adding doctor: ' . $ex->getMessage());
            return redirect('admin/doctors')->with('error', 'An error occurred while adding the doctor.');
        }
    }

    public function destroy(string $id)
    {
        try {
            $doctor = Doctor::find($id);
            
			if ( isset($doctor) ) {
				$doctor->delete();

				return redirect( 'admin/doctors' )
					->with( 'success', 'doctor deleted' );
			} else {
				return redirect( 'admin/doctors' )
					->with( 'error', 'Invalid doctor' );
			}
		} catch ( \Exception $ex ) {
			Log::critical( "User Delete Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );

			return redirect()->back()
			                 ->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
}
