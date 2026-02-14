<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientFile extends Model
{
    use HasFactory;

    protected $table = 'patient_files';
    protected $fillable = [
        'patient_id','appointment_id','file_name ','file_path','created_at','updated_at'
    ];
   

}
