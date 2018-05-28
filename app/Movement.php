<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    public function account(){
        return $this->belongsTo('App\Account');
    }
}
