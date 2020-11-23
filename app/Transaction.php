<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = ['customer_id', 'barang_id', 'amount', 'total_price', 'status'];

    public function customer(){
        return $this->belongsTo('App\Customer');
    }
}
