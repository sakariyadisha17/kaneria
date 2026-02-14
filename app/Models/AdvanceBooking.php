<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Add this import


class AdvanceBooking extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'advance_booking';
    protected $fillable = [
        'id',
        'time',
        'fullname','address','phone','date','status',
        'created_at','updated_at'
    ];

    protected $dates = ['deleted_at'];

    
    public function patients()
    {
        return $this->hasMany(Patient::class, 'appointment_id', 'id');
    }
    public function patient()
    {
        return $this->hasOne(Patient::class, 'appointment_id', 'id');
    }
    public function tpatient()
    {
        return $this->hasOne(Patient::class, 'appointment_id');
    }

    public function charges()
    {
        return $this->hasMany(Charge::class, 'appointment_id', 'id');
    }

    public function timesheet()
    {
        return $this->belongsTo(TimeSheet::class, 'time', 'id');
    }
   
  
}
