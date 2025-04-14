<?php

namespace App\Models\personal;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Authenticatable
{

    use HasFactory;

     // Define the table name
     protected $table = 'agents';

     // Specify the fillable attributes
     protected $fillable = [
         'name',
         'username',
         'phone',
         'password',
         'role',
     ];

     // Define the relationship with FollowUp
     public function followUps()
     {
        return $this->hasmany(FollowUp::class);
     }

     // Define the relationship with Lead
     public function leads()
     {
        return $this->hasMany(Lead::class);
        
     }
}
