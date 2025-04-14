<?php

namespace App\Models\personal;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Lead extends Model
{

    use HasFactory;

    protected $table ="leads";

    protected $fillable = [
        'agent_id',
        'first_name',
        'last_name',
        'email',
        'email_domain',
        'phone',
        'lead_source',
        'university',
        'courses',
        'session_duration',
    ];

    // public function getButtonColorAttribute()
    // {
    //     switch ($this->lead_status) {
    //         case 'new':
    //             return 'btn-success'; // Green for New
    //         case 'contacted':
    //             return 'btn-primary'; // Blue for Contacted
    //         case 'qualified':
    //             return 'btn-warning'; // Yellow for Qualified
    //         case 'lost':
    //             return 'btn-danger'; // Red for Lost
    //         case 'closed':
    //             return 'btn-secondary'; // Grey for Closed
    //         default:
    //             return 'btn-secondary'; // Default to Grey
    //     }
    // }

     // Define the relationship with FollowUp

    public function followUps()
    {
        return $this->hasmany(FollowUp::class);
    }

    public function agent()
    {
        return $this->belongsTo(Employee::class,'agent_id','id');
    }

    public function payments(){

        return $this->hasMany(Payment::class,'lead_id','id');
    }
}
