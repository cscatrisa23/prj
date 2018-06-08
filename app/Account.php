<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Account extends Model
{


    protected $fillable = [
        'owner_id','account_type_id', 'code','date','start_balance','current_balance','description'
    ];


    public $timestamps=false;

    public function category(){
        return $this->hasOne('App\Account_type', 'id', 'account_type_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'owner_id');
    }

    public function type(){
        return $this->hasOne('App\Account_type', 'id', 'account_type_id');
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
