<?php

namespace App\Models\personal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Registration;

/**
 * @property int $id
 * @property int $lead_id
 * @property int $agent_id
 * @property string $status
 * @property string $payment_amount
 * @property string $total_amount
 * @property \Carbon\Carbon $payment_date
 * @property \Carbon\Carbon $verified_at
 * @property \App\Models\personal\Lead $lead
 * @property \App\Models\personal\Agent $agent
 */
class Payment extends Model
{

    use HasFactory;

    protected $fillable = [
        'lead_id',
        'agent_id',
        'session_duration',
        'start_year',
        'duration',
        'session',
        'fee_type',
        'payment_screenshot',
        'utr_no',
        'payment_mode',
        'payment_details_input',
        'payment_amount',
        'total_amount',
        'pending_amount',
        'bank',
        'loan_amount',
        'loan_details',
        'payment_date',
        'reference_number',
        'status',
        'verified_by',
        'verified_at',
        'notes'
    ];

    protected $dates = [
        'payment_date',
        'verified_at',
        'created_at',
        'updated_at'
    ];

    // Payment status constants
    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';
    const STATUS_REJECTED = 'rejected';

    /**
     * Get the lead that owns the payment.
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id', 'id');
    }

    /**
     * Get the agent who created the payment.
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }

    /**
     * Get the admin who verified the payment.
     */
    public function verifiedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'verified_by', 'id');
    }
}
