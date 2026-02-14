<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagMedicine extends Model
{
    use HasFactory;
    protected $table = 'diagnosis_medicine';
    protected $fillable = [
        'id',
        'diagnosis_id',
        'medicine_id',
        'created_at','updated_at'
    ];
}
