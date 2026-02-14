<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    use HasFactory;
    protected $table = 'beds';
    protected $fillable = [
        'room_id',
        'bed_no',
        'is_occupied'
     ];

     public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
    public function roomBeds()
    {
        return $this->hasMany(RoomBed::class);
    }

    public function latestRoomBed()
    {
        return $this->hasOne(RoomBed::class)->latest();
    }

}
