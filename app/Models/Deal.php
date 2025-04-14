<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    protected $table ="deals";

    protected $fillable = [
        'deal_name',
        'amount',
        'stage',
        'contact_phone',
        'contact_email',
        // 'lead_id',
        'lead_source',
        'referral_name',
        'priority',
        'estimated_close_date',
        'lead_status',
        'notes',
        'attachments',
        'follow_up',
    ];

    protected $casts = [
        'attachments' => 'array',  // Cast attachments to an array
    ];

    // public function lead()
    // {
    //     return $this->belongsTo(Lead::class, 'lead_id');
    // }
}
