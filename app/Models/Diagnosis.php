<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasFactory;
    protected $table = 'diagnosis';
    protected $fillable = [
        'id',
        'name',
        'diat',
        'created_at','updated_at'
    ];

   
    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'diagnosis_medicine', 'diagnosis_id', 'medicine_id');
    }
    public function patients()
    {
        return $this->belongsToMany(Patient::class);
    }
    

}
