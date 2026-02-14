<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpdPatientMedicine extends Model
{
    use HasFactory;
    protected $table = 'ipd_patient_medicine';
    protected $fillable = [
        'ipd_letter_id',  
        'patient_id',     
        'name',
        'quantity',
        'frequency','note','status','time1'.'time2','time3','time4','diagnosis_id','patient_note',
        'created_at',
        'updated_at',
    ];
    public function ipdLetterhead()
    {
        return $this->belongsTo(IpdLetterhead::class, 'ipd_letter_id');
    }
}
