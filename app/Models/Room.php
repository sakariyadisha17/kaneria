<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $table = 'rooms';
    protected $fillable = [
        'type','amount','created_at','updated_at'
     ];


     public function beds()
     {
         return $this->hasMany(Bed::class);
     }
 
   
     public function roomBeds()
     {
         return $this->hasMany(RoomBed::class);
     }

     public function patients()
    {
        return $this->hasMany(Patient::class, 'room_type', 'id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
