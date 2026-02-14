<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $table = 'survey';

    protected $fillable = [
        'mobile',
        'doctor_rating',
        'staff_rating',
        'recep_rating',
        'medical_store_staff',
        'lab_services',
        'suggestions'
    ];
}
