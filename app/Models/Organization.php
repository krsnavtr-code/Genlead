<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = ['name', 'email', 'contact_number', 'role_no', 'slug'];

    // 👇 This must be inside the class
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
