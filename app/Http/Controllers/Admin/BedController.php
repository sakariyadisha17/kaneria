<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Bed;
use App\Models\Room;


class BedController extends Controller
{
    public function index()
    {
        try {
			return view( 'admin.beds.list');
		} catch ( \Exception $ex ) {
			Log::critical( "beds list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
    public function create()
    {
        $rooms = Room::all(); 

        try {
            return view('admin.beds.form', compact('rooms'));
		} catch ( \Exception $ex ) {
			Log::critical( "beds form Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
    public function getData(Request $request)
    {
        $query = Bed::with('room'); // Eager load the room relationship
    
        return DataTables::of($query)
            ->addColumn('room_type', function ($bed) {
                return $bed->room ? $bed->room->type : 'N/A'; // Fetch room type
            })
            ->addColumn('action', function ($bed) {
                return '<a href="' . URL('admin/beds/' . $bed->id . '/edit') . '" title="Edit" class="btn-lg text-primary">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a data-href="' . URL('admin/beds/' . $bed->id . '/delete') . '" data-toggle="modal" data-target="#confirm-delete" title="Delete" class="text-danger btn-lg">
                            <i class="fa fa-trash"></i>
                        </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required',
            'bed_no' => 'required',
        ]);
    
        try {
            $bed = new Bed();
            $bed->room_id = $request->room_id;
            $bed->bed_no = $request->bed_no;
            $bed->save();
           
            return redirect('admin/beds')->with('success', 'Bed added successfully!');
        } catch (\Exception $ex) {
            Log::error('Error adding bed: ' . $ex->getMessage());
            return redirect('admin/beds')->with('error', 'An error occurred while adding the bed.');
        }
    
    }
    public function edit($id)
    {
        $bed = Bed::findOrFail($id);
        $rooms = Room::all(); // Fetch all rooms

        return view('admin.beds.form', compact('bed','rooms'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'room_id' => 'required',
            'bed_no' => 'required',
        ]);
    
        try {
            $bed = Bed::findOrFail($id);
            $bed->room_id = $request->room_id;
            $bed->bed_no = $request->bed_no;
            $bed->save();
           
            return redirect('admin/beds')->with('success', 'Bed Updated!');
        } catch (\Exception $ex) {
            Log::error('Error adding bed: ' . $ex->getMessage());
            return redirect('admin/beds')->with('error', 'An error occurred while adding the doctor.');
        }
    }
    public function destroy(string $id)
    {
        try {
            $bed = Bed::find($id);
            
			if ( isset($bed) ) {
				$bed->delete();

				return redirect( 'admin/beds' )
					->with( 'success', 'Bed deleted' );
			} else {
				return redirect( 'admin/beds' )
					->with( 'error', 'Invalid bed' );
			}
		} catch ( \Exception $ex ) {
			Log::critical( "User Delete Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );

			return redirect()->back()
			                 ->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
}
