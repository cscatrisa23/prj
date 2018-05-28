<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Account extends Model
{

    public $timestamps=false;
    public function user() {
        return $this->belongsTo('App\User', 'owner_id');
    }


    public function movements(){
        return $this->hasMany('App\Movement');
    }

    public function deleteAccount()
    {
        $this->forceDelete();
    }

    public function isOpen(){
        return $this->deleted_at==null;
    }

    public function close(){
        $this->deleted_at = Carbon::now();
        $this->save();
    }

    public function reopen(){
        $this->deleted_at = null;
        $this->save();
    }
}
