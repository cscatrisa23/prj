<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = [
        'account_id', 'type', 'movement_category_id', 'date', 'value', 'start_balance', 'end_balance', 'description', 'document_id'
    ];

    public $timestamps=false;


    public function account(){
        return $this->belongsTo('App\Account');
    }

    public function document(){
        return $this->hasOne('App\Document','id', 'document_id');
    }

    public function category(){
        return $this->hasOne('App\MovementCategories', 'id', 'movement_category_id');
    }
}
