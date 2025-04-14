<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    use HasFactory;

    protected $table = 'employees';
    protected $fillable = ['emp_name','emp_email', 'emp_phone', 'emp_branch', 'emp_location', 'emp_pic', 'emp_join_date', 'emp_username', 'emp_password', 'emp_job_role','referrer_id', 'referral_code'];

    public function jobRole()
    {
        return $this->belongsTo(JobRole::class, 'emp_job_role');
    }

    public function referrer()
    {
        return $this->belongsTo(Employee::class, 'referrer_id');
    }

    public function referrals()
    {
        return $this->hasMany(Employee::class, 'referrer_id');
    }

    public function getAllDescendants()
    {
        $descendants = collect([$this]);

        foreach ($this->referrals as $referral) {
            $descendants = $descendants->merge($referral->getAllDescendants());
        }

        return $descendants;
    }
}
