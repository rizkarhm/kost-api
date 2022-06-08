<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'kost_id',
        'start_date',
        'end_date',
        'total',
        'status',
        'payment_url',
    ];

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->timestamp;
    }
    public function getUpdatedAtAttribute($value){
        return Carbon::parse($value)->timestamp;
    }

    public function kost(){
        return $this->hasOne(Kost::class, 'id', 'kost_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function duration($id, $start, $end){
        $booking = Booking::findOrFail($id);

        $booking->start_date = $start;
        $booking->end_date = $end;

        $to_date = Carbon::createFromFormat('Y-m-d', $end);
        $from_date = Carbon::createFromFormat('Y-m-d', $start);

        $duration = $to_date->diffInMonths($from_date);
        return $duration;
    }

}
