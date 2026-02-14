<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'patients';

    protected $fillable = [
        'patient_id', 
        'appointment_id', 
        'fullname', 
        'age', 
        'gender', 
        'address', 
        'phone', 
        'referred_by', 
        'charges', 
        'amount', 
        'return_amount', 
        'total_amount', 
        'payment_type', 
        'status', 
        'is_relative', 
        'created_at', 
        'updated_at', 
        'admit_datetime','discharge_datetime',
        'complaints',
        'discharge_medication',
        'room_type',
        'bed',
        'relative_name',
        'relative_num',
        'admit_status','mlc','type'
    ];

    protected $dates = ['deleted_at'];

    
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
   
    public function booking()
    {
        return $this->belongsTo(AdvanceBooking::class, 'appointment_id', 'id');
    }
    public function appointment()
    {
        return $this->belongsTo(AdvanceBooking::class, 'appointment_id', 'id');
    }
    public function advanceBooking()
    {
        return $this->belongsTo(AdvanceBooking::class, 'appointment_id', 'id'); 
    }
    public function opdPatientMedicines()
    {
        return $this->hasMany(OpdPatientMedicine::class, 'patient_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_type', 'id'); 
    }
   

    public function bed()
    {
        return $this->belongsTo(Bed::class, 'bed' , 'id'); 
    }
   
    public function activeServices()
    {
        return $this->hasMany(PatientService::class)->whereNull('end_datetime'); 
    }

        public function inactiveServices()
    {
        return $this->hasMany(PatientService::class)->whereNotNull('end_datetime');
    }

    
}
