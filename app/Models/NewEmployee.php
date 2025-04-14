<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewEmployee extends Model
{
    use HasFactory;

    protected $table = 'new_joinee';

    protected $fillable = [

     'name', 'email', 'phone', 'branch', 'location', 'salary_discussed', 
     'salary_amount', 'resume', 'interview_process', 'interview_date_time',
     'interview_result','username','password','link_expiry'
     
    ];

    protected $hidden = [
        'password',
    ];
}
