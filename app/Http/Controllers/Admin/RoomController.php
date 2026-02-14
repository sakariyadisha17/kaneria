<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Room;
class RoomController extends Controller
{
    public function index()
    {
        try {
			return view( 'admin.rooms.list');
		} catch ( \Exception $ex ) {
			Log::critical( "rooms list Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
    public function create()
    {
        try {
            return view('admin.rooms.form');
		} catch ( \Exception $ex ) {
			Log::critical( "rooms form Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );
			return redirect( 'admin' )
				->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
    public function getData(Request $request)
    {
        $query = Room::query();


        return DataTables::of( $query )
            ->addColumn( 'action', function ( $query ) {

                $actionString = '<a href="' . URL( 'admin/rooms/' . $query->id . '/edit' ) . '" title="Edit" class="btn-lg text-primary"><i class="fa fa-edit"></i></a><a data-href="' . URL( 'admin/rooms/' . $query->id . '/delete' ) . '" data-toggle="modal" data-target="#confirm-delete" title="Delete" class="text-danger btn-lg"  ><i class="fa fa-trash"></i></a>';

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
            $room = new Room();
            $room->type = $request->type;
            $room->amount = $request->amount;

            $room->save();
           
            return redirect('admin/rooms')->with('success', 'Room added successfully!');
        } catch (\Exception $ex) {
            Log::error('Error adding room: ' . $ex->getMessage());
            return redirect('admin/rooms')->with('error', 'An error occurred while adding the room.');
        }
    
    }
    public function edit($id)
    {
        $room = Room::findOrFail($id);
        return view('admin.rooms.form', compact('room'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required',
            'amount' => 'required',

        ]);
    
        try {
            $room = Room::findOrFail($id);
            $room->type = $request->type;
            $room->amount = $request->amount;

            $room->save();
           
            return redirect('admin/rooms')->with('success', 'Room Updated!');
        } catch (\Exception $ex) {
            Log::error('Error adding room: ' . $ex->getMessage());
            return redirect('admin/rooms')->with('error', 'An error occurred while adding the doctor.');
        }
    }
    public function destroy(string $id)
    {
        try {
            $room = Room::find($id);
            
			if ( isset($room) ) {
				$room->delete();

				return redirect( 'admin/rooms' )
					->with( 'success', 'Room deleted' );
			} else {
				return redirect( 'admin/rooms' )
					->with( 'error', 'Invalid room' );
			}
		} catch ( \Exception $ex ) {
			Log::critical( "User Delete Page:\n" . $ex->getMessage() . " in " . $ex->getFile() . " at Line " . $ex->getLine() . ". \n[stacktrace]\n" . $ex->getTraceAsString() );

			return redirect()->back()
			                 ->with( 'error', 'Woops! Something went wrong. Please try again later.' );
		}
    }
}
