<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientService extends Model
{
    use HasFactory;

    protected $table = 'patient_service';

    protected $fillable = [
        'patient_id',
        'service_id',
        'start_datetime',
        'end_datetime',
        'calculate_amount',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
   
}
