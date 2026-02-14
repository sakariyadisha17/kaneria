<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Time;
use Yajra\DataTables\Facades\DataTables;
use DB;

class TimeController extends Controller
{
    public function index()
    {
        try {
			return view( 'admin.times.list');
		} catch ( \Exception $ex ) {
			Log::critical( "times list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }

    public function create()
    {
        try {
            return view('admin.times.form');
		} catch ( \Exception $ex ) {
			Log::critical( "times form Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }

   
    public function getData(Request $request)
    {
        $query = Time::query();


        return DataTables::of( $query )
            ->addColumn( 'action', function ( $query ) {

                $actionString = '<a href="' . URL( 'admin/times/' . $query->id . '/edit' ) . '" title="Edit" class="btn-lg text-primary"><i class="fa fa-edit"></i></a><a data-href="' . URL( 'admin/times/' . $query->id . '/delete' ) . '" data-toggle="modal" data-target="#confirm-delete" title="Delete" class="text-danger btn-lg"  ><i class="fa fa-trash"></i></a>';

                return $actionString;

            } )
            ->editColumn('time', function ($query){
                return date('h:i A', strtotime($query->time));
            })
           
            ->rawColumns( [ 'action'] )
            ->make( true );
    }

    public function store(Request $request)
    {
        $request->validate([
            'time' => 'required',
        ]);
    
        try {
            $times = new Time();
            $times->time = $request->time;
            $times->save();
           
            return redirect('admin/times')->with('success', 'Times added successfully!');
        } catch (\Exception $ex) {
            Log::error('Error adding times: ' . $ex->getMessage());
            return redirect('admin/times')->with('error', 'An error occurred while adding the doctor.');
        }
    
    }
    
    public function edit($id)
    {
        $time = Time::findOrFail($id);
        return view('admin.times.form', compact('time'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'time' => 'required',
        ]);
    
    
        try {
            $times = Time::findOrFail($id);
            $times->time = $request->time;
            $times->save();
           
            return redirect('admin/times')->with('success', 'Times Updated!');
        } catch (\Exception $ex) {
            Log::error('Error adding times: ' . $ex->getMessage());
            return redirect('admin/times')->with('error', 'An error occurred while adding the doctor.');
        }
    }

    public function destroy(string $id)
    {
        try {
            $times = Time::find($id);
            
			if ( isset($times) ) {
				$times->delete();

				return redirect( 'admin/times' )
					->with( 'success', 'Times deleted' );
			} else {
				return redirect( 'admin/times' )
					->with( 'error', 'Invalid times' );
			}
		} catch ( \Exception $ex ) {
			Log::critical( "User Delete Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );

			return redirect()->back()
			                 ->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }

    public function addTimesheets(Request $request, $id)
    {
        $request->validate([
            'timesheet_count' => 'required|integer|min:1',
        ]);

        try {
            $time = Time::findOrFail($id);

            for ($i = 0; $i < $request->timesheet_count; $i++) {
                DB::table('time_sheet')->insert([
                    'time_id' => $time->id,
                    'time_sheet' => $time->time,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return redirect()->back()->with('success', 'Timesheets added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

}
