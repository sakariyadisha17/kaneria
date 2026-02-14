<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpdPatientMedicine extends Model
{
    use HasFactory;
    protected $table = 'opd_patient_medicine';
    protected $fillable = [
        'opd_letter_id',  
        'patient_id',     
        'name',
        'quantity',
        'note',
        'extra_note',
        'frequency','note',
        'created_at',
        'updated_at',
    ];
    public function opdLetterhead()
    {
        return $this->belongsTo(OpdLetterhead::class, 'opd_letter_id');
    }

    // Relationship with the Patient model
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
