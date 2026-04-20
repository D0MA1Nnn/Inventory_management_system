<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'position',
        'salary',
        'hire_date',
        'status',
        'profile_image'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'salary' => 'decimal:2',
    ];
}