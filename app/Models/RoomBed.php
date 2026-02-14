<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomBed extends Model
{
    use HasFactory;
    protected $table = 'room_beds';

    protected $fillable = ['patient_id', 'room_id', 'bed_id', 'admit_status'];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

  
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

   
    public function bed()
    {
        return $this->belongsTo(Bed::class);
    }
}
