<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table ="activities";

    protected $fillable = [
        'title',
        'type',
        'description',
        'schedule_from',
        'schedule_to',
        'agent_id',
    ];

    /**
     * Get the lead associated with the activity.
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
