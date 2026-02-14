<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Medicine;
use Illuminate\Support\Facades\Log;
class MedicineController extends Controller
{
    public function index()
    {
        try {
			return view( 'admin.medicine.list');
		} catch ( \Exception $ex ) {
			Log::critical( "medicine list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
    public function create()
    {
        try {
            return view('admin.medicine.form');
		} catch ( \Exception $ex ) {
			Log::critical( "medicine form Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }

    public function getData(Request $request)
    {
        $query = Medicine::query();


        return DataTables::of( $query )
            ->addColumn( 'action', function ( $query ) {

                $actionString = '<a href="' . URL( 'admin/medicine/' . $query->id . '/edit' ) . '" title="Edit" class="btn-lg text-primary"><i class="fa fa-edit"></i></a><a data-href="' . URL( 'admin/medicine/' . $query->id . '/delete' ) . '" data-toggle="modal" data-target="#confirm-delete" title="Delete" class="text-danger btn-lg"  ><i class="fa fa-trash"></i></a>';

                return $actionString;

            } )
           
            ->rawColumns( [ 'action'] )
            ->make( true );
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'quantity' => 'required',
            'frequency' => 'required',

        ]);
    
        try {
            $medicine = new Medicine();
            $medicine->name = $request->name;
            $medicine->quantity = $request->quantity;
            $medicine->frequency = $request->frequency;
            $medicine->note = $request->note;
            $medicine->save();
           
            return redirect('admin/medicine')->with('success', 'Medicine added successfully!');
        } catch (\Exception $ex) {
            Log::error('Error adding diagnosis: ' . $ex->getMessage());
            return redirect('admin/medicine')->with('error', 'An error occurred while adding the medicine.');
        }
    
    }
    
    public function edit($id)
    {
        $medicine = Medicine::findOrFail($id);
        return view('admin.medicine.form', compact('medicine'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'quantity' => 'required',
            'frequency' => 'required',
        ]);
    
        try {
            $medicine = Medicine::findOrFail($id);
            $medicine->name = $request->name;
            $medicine->quantity = $request->quantity;
            $medicine->frequency = $request->frequency;
            $medicine->note = $request->note;
            $medicine->save();
           
            return redirect('admin/medicine')->with('success', 'Medicine Updated!');
        } catch (\Exception $ex) {
            Log::error('Error adding medicine: ' . $ex->getMessage());
            return redirect('admin/medicine')->with('error', 'An error occurred while adding the doctor.');
        }
    }

    public function destroy(string $id)
    {
        try {
            $medicine = Medicine::find($id);
            
			if ( isset($medicine) ) {
				$medicine->delete();

				return redirect( 'admin/medicine' )
					->with( 'success', 'Medicine deleted' );
			} else {
				return redirect( 'admin/medicine' )
					->with( 'error', 'Invalid Medicine' );
			}
		} catch ( \Exception $ex ) {
			Log::critical( "User Delete Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );

			return redirect()->back()
			                 ->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }


    public function processImport(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:csv,txt',
            ]);

            $file = $request->file('file');
            $handle = fopen($file, 'r');

            if ($handle === false) {
                return redirect()->back()->with('error', 'Failed to open the file. Please try again.');
            }
            fgetcsv($handle); 

            while (($row = fgetcsv($handle)) !== false) {
                $name = trim($row[0] ?? '');
                $quantity = trim($row[1] ?? '');
                $frequency = trim($row[2] ?? '');

                if (!empty($name)) {
                    $existingmedicine = Medicine::where('name', $name)->first();

                    if (!$existingmedicine) {
                        Medicine::create([
                            'name' => $name,
                            'quantity' => $quantity,
                            'frequency' => $frequency,
                        ]);
                    }
                }
            }

            fclose($handle);

            return redirect()->back()->with('success', 'Medicine imported successfully!');
        } catch (\Exception $ex) {
            return response(['error', $ex->getMessage()]);
        }
    }

    public function downloadSample()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sample.csv"',
        ];

        $content = "name,quantity,frequency,note";

        return response($content, 200, $headers);
    }

}
