<?php

namespace App\Models\Admin;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Adminuser extends Authenticatable
{
    use Notifiable;

    // The attributes that are mass assignable
    protected $fillable = [
        'email',
        'password',
    ];

    // If you want to hide the password when serializing
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Cast attributes
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
