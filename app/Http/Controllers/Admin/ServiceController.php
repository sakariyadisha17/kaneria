<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Service;
class ServiceController extends Controller
{
    public function index()
    {
        try {
			return view( 'admin.services.list');
		} catch ( \Exception $ex ) {
			Log::critical( "services list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
    public function create()
    {
        try {
            return view('admin.services.form');
		} catch ( \Exception $ex ) {
			Log::critical( "services form Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
    public function getData(Request $request)
    {
        $query = Service::query();


        return DataTables::of( $query )
            ->addColumn( 'action', function ( $query ) {

                $actionString = '<a href="' . URL( 'admin/services/' . $query->id . '/edit' ) . '" title="Edit" class="btn-lg text-primary"><i class="fa fa-edit"></i></a><a data-href="' . URL( 'admin/services/' . $query->id . '/delete' ) . '" data-toggle="modal" data-target="#confirm-delete" title="Delete" class="text-danger btn-lg"  ><i class="fa fa-trash"></i></a>';

                return $actionString;

            } )
           
            ->rawColumns( [ 'action'] )
            ->make( true );
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'amount' => 'required',
        ]);
    
        try {
            $service = new Service();
            $service->name = $request->name;
            $service->amount = $request->amount;
            $service->save();
           
            return redirect('admin/services')->with('success', 'Service added successfully!');
        } catch (\Exception $ex) {
            Log::error('Error adding service: ' . $ex->getMessage());
            return redirect('admin/services')->with('error', 'An error occurred while adding the service.');
        }
    
    }
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.form', compact('service'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'amount' => 'required',
        ]);
    
        try {
            $service = Service::findOrFail($id);
            $service->name = $request->name;
            $service->amount = $request->amount;
            $service->save();
           
            return redirect('admin/services')->with('success', 'Service Updated!');
        } catch (\Exception $ex) {
            Log::error('Error adding services: ' . $ex->getMessage());
            return redirect('admin/services')->with('error', 'An error occurred while adding the doctor.');
        }
    }
    public function destroy(string $id)
    {
        try {
            $service = Service::find($id);
            
			if ( isset($service) ) {
				$service->delete();

				return redirect( 'admin/services' )
					->with( 'success', 'Service deleted' );
			} else {
				return redirect( 'admin/services' )
					->with( 'error', 'Invalid service' );
			}
		} catch ( \Exception $ex ) {
			Log::critical( "User Delete Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );

			return redirect()->back()
			                 ->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
}
