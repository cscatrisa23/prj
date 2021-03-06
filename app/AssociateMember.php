<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssociateMember extends Model
{
    protected $table = 'associate_members';
    public $timestamps = false;
    protected $fillable=[
        'main_user_id', 'associated_user_id', 'created_at'
    ];

}
