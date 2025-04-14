<?php

namespace App\Models\personal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{

    use HasFactory;

    protected $fillable = ['agent_id', 'date', 'morning_login', 'afternoon_login', 'evening_login', 'status', 'ip_address'];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
