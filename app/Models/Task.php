<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table ="tasks";

    protected $fillable = [
        'subject',
        'task_type',
        'task_status',
        'schedule_from',
        'schedule_to',
        'description',
        'agent_id',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
