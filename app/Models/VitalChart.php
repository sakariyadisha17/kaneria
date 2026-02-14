<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VitalChart extends Model
{
    use HasFactory;
    protected $table = 'vital_chart'; // Or your actual table name

    protected $fillable = [
        'datetime','patient_id',
        'temp',
        'pulse',
        'bp',
        'spo2',
        'input',
        'output',
        'rbs',
        'rt',
        'remark',
    ];

    protected $dates = ['datetime'];
}
