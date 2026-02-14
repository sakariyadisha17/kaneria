<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpdLetterhead extends Model
{
    use HasFactory;
    protected $table = 'opd_letterhead';
    protected $fillable = [
        'id',
        'patient_id ',
        'appointment_id ','bp','pulse','spo2','temp','rs','cvs','ecg','rbs','report','next_report','addition','complaint','past_history','note','next_date','diagnosis','diat',
        'created_at','updated_at'
    ];

    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
    public function opdPatientMedicines()
    {
        return $this->hasMany(OpdPatientMedicine::class, 'opd_letter_id');
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function diagnosisName()
    {
        return $this->belongsTo(Diagnosis::class, 'diagnosis', 'id');
    }
    public function diagnoses()
    {
        return $this->belongsToMany(Diagnosis::class, 'diagnosis');
    }

}
