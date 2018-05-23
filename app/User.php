<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Account;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'profile_photo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function typeToStr()
    {
        switch ($this->admin) {
            case 0:
                return 'User';
            case 1:
                return 'Administrator';
        }
    }

    public function associated_of() {
        return $this->belongsToMany(User::class, 'associate_members', 'associated_user_id', 'main_user_id');
    }

    public function associateds() {
        return $this->belongsToMany(User::class, 'associate_members', 'main_user_id', 'associated_user_id');
    }

    public function allAccounts() {
        return $this->hasMany('Account', 'owner_id');
    }


    public function block(){
        $this->blocked= 1;
        $this->save();
    }

    public function unblock(){
        $this->blocked= 0;
        $this->save();
    }

    public function promote(){
        $this->admin= 1;
        $this->save();
    }

    public function demote(){
        $this->admin= 0;
        $this->save();
    }

    public function blockedToStr()
    {
        switch ($this->blocked) {
            case 0:
                return 'Not Blocked';
            case 1:
                return 'Blocked';
        }

        return 'Unknown';
    }

    public function isAdministrator()
    {
        return $this->admin == '1';
    }

    public function isUser()
    {
        return $this->admin == '0';
    }
}
