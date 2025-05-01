<?php

namespace App\Models\personal;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FollowUp extends Model
{

    use HasFactory;

    protected $fillable = ['lead_id', 'agent_id', 'follow_up_time', 'comments', 'action'];


    // Define the relationship with Lead
    public function lead()
    {
        return $this->belongsTo(Lead::class,'lead_id');
    }
    

    // Define the relationship with Agent
     public function agent()

     {
        return $this->belongsTo(Employee::class, 'agent_id'); 
     }
}
