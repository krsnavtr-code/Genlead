<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRole extends Model
{

    use HasFactory;

    protected $table = 'jobroles';
    protected $fillable = ['job_role_title', 'permissions'];

    public function getPermissionsAttribute($value)
    {
        return json_decode($value, true);
    }
}
