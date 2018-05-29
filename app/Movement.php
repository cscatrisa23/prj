<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = [
        'account_id', 'type', 'movement_category_id', 'date', 'value', 'start_balance', 'end_balance', 'description', 'document_id'
    ];

    public function account(){
        return $this->belongsTo('App\Account');
    }

    public function category(){
        return $this->hasOne('App\Movement_category', 'id', 'movement_category_id');
    }
}
