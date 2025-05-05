<?php

namespace App\Models\personal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    use HasFactory;

    protected $fillable = [
        'lead_id',
        'agent_id',
        'session_duration',
        'session',
        'payment_screenshot',
        'utr_no',
        'payment_mode',
        'payment_details_input',
        'payment_amount',
        'pending_amount',
        'bank',
        'loan_amount',
        'loan_details'
    ];

    // Relationship with Lead model
    public function lead()
    {
        return $this->belongsTo(Lead::class,'lead_id','id');
    }
}
