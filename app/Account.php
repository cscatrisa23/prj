<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{

    public function user() {
        return $this->belongsTo('App\User', 'owner_id');
    }

    public function deleteAccount()
    {
        $this->delete();
    }
}
