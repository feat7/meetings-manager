<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function groups()
    {
        return $this->hasMany(Group::class);
    }

     public function remainders()
    {
        return $this->hasMany(Remainder::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }


    public function addContact(Contact $contact)
    {
        $contact->user_id = Auth::id();

        $this->contacts()->save($contact);
    }
}
