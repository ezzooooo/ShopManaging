<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'store_id', 'phone', 'name', 'gender', 'birth', 'sales'
    ];

    public function reservations() {
        return $this->hasMany('App\Reservation');
    }
}
