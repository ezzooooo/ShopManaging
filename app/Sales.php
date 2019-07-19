<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $fillable = [
        'id', 'store_id', 'customer_name', 'customer_kind', 'sales_type', 'sales_stat'
    ];

    public function reservations() {
        return $this->belongsTo('App\Reservation', 'reservation_id');
    }
}
