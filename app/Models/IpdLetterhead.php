<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpdLetterhead extends Model
{
    use HasFactory;
    protected $table = 'ipd_letterhead';
    protected $fillable = [
        'id',
        'patient_id ',
        'appointment_id ','bp','pulse','spo2','temp','rs','cvs','ecg','rbs','report','complaint','datetime','diagnosis_id','patient_note',
        'created_at','updated_at'
    ];
    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
    public function ipdPatientMedicines()
    {
        return $this->hasMany(IpdPatientMedicine::class, 'ipd_letter_id');
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function diagnosis()
    {
        return $this->belongsTo(Diagnosis::class, 'diagnosis_id', 'id');
    }


}
