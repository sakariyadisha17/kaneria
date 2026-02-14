<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReferDoctor;
use Yajra\DataTables\Facades\DataTables;


class ReferDoctorController extends Controller
{
    public function index()
    {
        try {
			return view( 'admin.refer_doctor.list');
		} catch ( \Exception $ex ) {
			Log::critical( "refer_doctor list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin/dashboard' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
    public function create()
    {
        try {
            return view('admin.refer_doctor.form');
		} catch ( \Exception $ex ) {
			Log::critical( "refer_doctor form Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }

   
    public function getData(Request $request)
    {
        $query = ReferDoctor::query();


        return DataTables::of( $query )
            ->addColumn( 'action', function ( $query ) {

                $actionString = '<a href="' . URL( 'admin/refer_doctor/' . $query->id . '/edit' ) . '" title="Edit" class="btn-lg text-primary"><i class="fa fa-edit"></i></a><a data-href="' . URL( 'admin/refer_doctor/' . $query->id . '/delete' ) . '" data-toggle="modal" data-target="#confirm-delete" title="Delete" class="text-danger btn-lg"  ><i class="fa fa-trash"></i></a>';

                return $actionString;

            } )
           
            ->rawColumns( [ 'action'] )
            ->make( true );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);
    
        try {
            $doctorName = $request['name'];
            if (substr($doctorName, 0, 3) !== 'Dr.') {
                $doctorName = 'Dr. ' . $doctorName;
            }
            $refer_doctor = new ReferDoctor();
            $refer_doctor->name = $doctorName;
            $refer_doctor->phone = $request->phone;
            $refer_doctor->address = $request->address;
            $refer_doctor->save();
           
            return redirect('admin/refer_doctor')->with('success', 'Refer Doctor added successfully!');
        } catch (\Exception $ex) {
            Log::error('Error adding doctor: ' . $ex->getMessage());
            return redirect('admin/refer_doctor')->with('error', 'An error occurred while adding the doctor.');
        }
    
    }
    
    public function edit($id)
    {
        $refer_doctor = ReferDoctor::findOrFail($id);
        return view('admin.refer_doctor.form', compact('refer_doctor'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);
    
    
        try {
            $refer_doctor = ReferDoctor::findOrFail($id);
            $refer_doctor->name = $request->name;
            $refer_doctor->phone = $request->phone;
            $refer_doctor->address = $request->address;
            $refer_doctor->save();
           
            return redirect('admin/refer_doctor')->with('success', 'Refer Doctor Updated!');
        } catch (\Exception $ex) {
            Log::error('Error adding doctor: ' . $ex->getMessage());
            return redirect('admin/refer_doctor')->with('error', 'An error occurred while adding the doctor.');
        }
    }

    public function destroy(string $id)
    {
        try {
            $doctor = ReferDoctor::find($id);
            
			if ( isset($doctor) ) {
				$doctor->delete();

				return redirect( 'admin/refer_doctor' )
					->with( 'success', 'doctor deleted' );
			} else {
				return redirect( 'admin/refer_doctor' )
					->with( 'error', 'Invalid doctor' );
			}
		} catch ( \Exception $ex ) {
			Log::critical( "User Delete Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );

			return redirect()->back()
			                 ->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
}
