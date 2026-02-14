<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Charge;
class ChargeController extends Controller
{
    public function index()
    {
        try {
			return view( 'admin.charges.list');
		} catch ( \Exception $ex ) {
			Log::critical( "charges list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
    public function create()
    {
        try {
            return view('admin.charges.form');
		} catch ( \Exception $ex ) {
			Log::critical( "charges form Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
    public function getData(Request $request)
    {
        $query = Charge::query();


        return DataTables::of( $query )
            ->addColumn( 'action', function ( $query ) {

                $actionString = '<a href="' . URL( 'admin/charges/' . $query->id . '/edit' ) . '" title="Edit" class="btn-lg text-primary"><i class="fa fa-edit"></i></a><a data-href="' . URL( 'admin/charges/' . $query->id . '/delete' ) . '" data-toggle="modal" data-target="#confirm-delete" title="Delete" class="text-danger btn-lg"  ><i class="fa fa-trash"></i></a>';

                return $actionString;

            } )
           
            ->rawColumns( [ 'action'] )
            ->make( true );
    }
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'amount' => 'required',
        ]);
    
        try {
            $charge = new Charge();
            $charge->type = $request->type;
            $charge->amount = $request->amount;
            $charge->save();
           
            return redirect('admin/charges')->with('success', 'Charge added successfully!');
        } catch (\Exception $ex) {
            Log::error('Error adding charge: ' . $ex->getMessage());
            return redirect('admin/charges')->with('error', 'An error occurred while adding the charge.');
        }
    
    }
    public function edit($id)
    {
        $charge = Charge::findOrFail($id);
        return view('admin.charges.form', compact('charge'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required',
            'amount' => 'required',
        ]);
    
        try {
            $charge = Charge::findOrFail($id);
            $charge->type = $request->type;
            $charge->amount = $request->amount;
            $charge->save();
           
            return redirect('admin/charges')->with('success', 'Charge Updated!');
        } catch (\Exception $ex) {
            Log::error('Error adding charge: ' . $ex->getMessage());
            return redirect('admin/charges')->with('error', 'An error occurred while adding the doctor.');
        }
    }
    public function destroy(string $id)
    {
        try {
            $charge = Charge::find($id);
            
			if ( isset($charge) ) {
				$charge->delete();

				return redirect( 'admin/charges' )
					->with( 'success', 'charge deleted' );
			} else {
				return redirect( 'admin/charges' )
					->with( 'error', 'Invalid charge' );
			}
		} catch ( \Exception $ex ) {
			Log::critical( "User Delete Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );

			return redirect()->back()
			                 ->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
}
