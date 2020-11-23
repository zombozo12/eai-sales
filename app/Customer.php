<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    protected $fillable = ['user_id', 'name', 'birthday', 'address'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function transactions(){
        return $this->hasMany('App\Transaction');
    }
}
