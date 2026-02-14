<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomShifting extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'old_room_id',
        'old_bed_id',
        'new_room_id',
        'new_bed_id',
        'shifted_at',
        'reason',
    ];
    protected $table = 'room_shifts';

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    
   
    public function oldRoom()
    {
        return $this->belongsTo(Room::class, 'old_room_id');
    }

    public function oldBed()
    {
        return $this->belongsTo(Bed::class, 'old_bed_id');
    }

    public function newRoom()
    {
        return $this->belongsTo(Room::class, 'new_room_id');
    }

    public function newBed()
    {
        return $this->belongsTo(Bed::class, 'new_bed_id');
    }
}
