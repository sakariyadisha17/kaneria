<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    use HasFactory;
    protected $table = 'time_sheet';
    protected $fillable = [
       'id','time_id ','time_sheet','status','created_at','updated_at'];

    public function time()
    {
        return $this->belongsTo(Time::class, 'time_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(AdvanceBooking::class, 'time', 'id');
    }
}
