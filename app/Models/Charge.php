<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    use HasFactory;
    protected $table = 'charges';
    protected $fillable = [
        'id',
        'type',
        'amount',
        'created_at','updated_at'
    ];

}
