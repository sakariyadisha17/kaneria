<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;
    protected $table = 'medicine';
    protected $fillable = [
        'id','name','quantity', 'frequency', 'note','created_at','updated_at'
    ];

    public function diagnoses()
    {
        return $this->belongsToMany(Diagnosis::class, 'diagnosis_medicine', 'medicine_id', 'diagnosis_id');
    }

}
