<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Diagnosis;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Medicine;
use App\Models\DiagMedicine;
use Illuminate\Support\Facades\Log;


class DiagnosisController extends Controller
{
    public function index()
    {
        try {
			return view( 'admin.diagnosis.list');
		} catch ( \Exception $ex ) {
			Log::critical( "diagnosis list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
    public function create()
    {
        try {
            $medicines = Medicine::all();
            return view('admin.diagnosis.form', compact('medicines'));
        } catch (\Exception $ex) {
            Log::critical("Diagnosis form Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine());
            return redirect('admin')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function getData(Request $request)
    {
        $query = Diagnosis::query();


        return DataTables::of( $query )

            ->addColumn( 'action', function ( $query ) {

                $actionString = '<a href="' . URL( 'admin/diagnosis/' . $query->id . '/edit' ) . '" title="Edit" class="btn-lg text-primary"><i class="fa fa-edit"></i></a><a data-href="' . URL( 'admin/diagnosis/' . $query->id . '/delete' ) . '" data-toggle="modal" data-target="#confirm-delete" title="Delete" class="text-danger btn-lg"  ><i class="fa fa-trash"></i></a>';

                return $actionString;

            } )
           
            ->rawColumns( [ 'action'] )
            ->make( true );
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'diat' => 'nullable',
            'note' => 'nullable',
            'medicines' => 'array',
        ]);

        try {
            // Create the diagnosis
            $diagnosis = new Diagnosis();
            $diagnosis->name = $request->name;
            $diagnosis->diat = $request->diat;
            $diagnosis->note = $request->note;
            $diagnosis->save();

            // Attach medicines if provided
            if ($request->has('medicines')) {
                $diagnosis->medicines()->attach($request->medicines);
            }

            return redirect('admin/diagnosis')->with('success', 'Diagnosis added successfully!');
        } catch (\Exception $ex) {
            Log::error('Error adding diagnosis: ' . $ex->getMessage());
            return redirect('admin/diagnosis')->with('error', 'An error occurred while adding the diagnosis.');
        }
    }

    public function edit($id)
    {
        try {
            $diagnosis = Diagnosis::findOrFail($id);
            $medicines = Medicine::all(); // Fetch all medicines
            $selectedMedicines = $diagnosis->medicines; // Fetch the associated Medicine objects
    
            return view('admin.diagnosis.form', compact('diagnosis', 'medicines', 'selectedMedicines'));
        } catch (\Exception $ex) {
            Log::error("Error in edit method: " . $ex->getMessage());
            return redirect('admin/diagnosis')->with('error', 'Something went wrong. Please try again later.');
        }
    }
    


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'diat' => 'nullable',            
            'note' => 'nullable',
            'medicines' => 'array',
        ]);

        try {
            // Update the diagnosis
            $diagnosis = Diagnosis::findOrFail($id);
            $diagnosis->name = $request->name;
            $diagnosis->diat = $request->diat;
            $diagnosis->note = $request->note;
            $diagnosis->save();

            // Sync medicines if provided
            if ($request->has('medicines')) {
                $diagnosis->medicines()->sync($request->medicines);
            }

            return redirect('admin/diagnosis')->with('success', 'Diagnosis updated successfully!');
        } catch (\Exception $ex) {
            Log::error('Error updating diagnosis: ' . $ex->getMessage());
            return redirect('admin/diagnosis')->with('error', 'An error occurred while updating the diagnosis.');
        }
    }
    
    public function destroy(string $id)
    {
        try {
            $diagnosis = Diagnosis::find($id);
            
			if ( isset($diagnosis) ) {
				$diagnosis->delete();

				return redirect( 'admin/diagnosis' )
					->with( 'success', 'Diagnosis deleted' );
			} else {
				return redirect( 'admin/diagnosis' )
					->with( 'error', 'Invalid diagnosis' );
			}
		} catch ( \Exception $ex ) {
			Log::critical( "User Delete Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );

			return redirect()->back()
			                 ->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
   
}
