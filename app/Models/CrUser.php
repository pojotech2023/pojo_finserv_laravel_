<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // ✅ important
use Illuminate\Notifications\Notifiable;

class CrUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'cr_users';

    protected $fillable = ['name', 'email', 'password','role'];

    protected $hidden = ['password', 'remember_token'];
}
