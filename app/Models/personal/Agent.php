<?php

namespace App\Models\personal;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Authenticatable
{
    use HasFactory;

    // Define the table name
    protected $table = 'employees';

    // Specify the fillable attributes
    protected $fillable = [
        'emp_name',
        'emp_email',
        'emp_phone',
        'emp_location',
        'emp_password',
        'emp_job_role',
        'emp_username',
        'emp_branch',
        'emp_salary',
        'emp_pic',
        'emp_join_date',
        'referrer_id',
        'referral_code'
    ];

    // Specify the hidden attributes
    protected $hidden = [
        'emp_password'
    ];
    
    // Tell Laravel which field to use for authentication
    public function getAuthPassword()
    {
        return $this->emp_password;
    }

     // Define the relationship with Lead
     public function leads()
     {
        return $this->hasMany(Lead::class);
        
     }
}
