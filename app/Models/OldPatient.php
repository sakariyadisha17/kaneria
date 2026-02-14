<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OldPatient extends Model
{
    use HasFactory;
    protected $table = 'old_patients';
    protected $fillable = [
        'id',
        'name',
        'phone',
        'created_at','updated_at'
    ];
}
