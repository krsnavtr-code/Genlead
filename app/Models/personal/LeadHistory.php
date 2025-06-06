<?php

namespace App\Models\personal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use Carbon\Carbon;

class LeadHistory extends Model
{
    use HasFactory;

    protected $table = 'lead_histories';
    protected $primaryKey = 'id';

    protected $fillable = [
        'lead_id',
        'action',
        'description',
        'old_values',
        'new_values',
        'performed_by',
        'details',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'details' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Set performed_by to current user when creating a new history record
        static::creating(function ($model) {
            if (empty($model->performed_by) && Auth::check()) {
                $model->performed_by = Auth::id();
            }
        });
    }

    /**
     * Get the lead that owns the history record
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    /**
     * Get the user who performed the action
     */
    public function performer()
    {
        return $this->belongsTo(Employee::class, 'performed_by');
    }

    /**
     * Scope a query to only include history for a specific lead
     */
    public function scopeForLead($query, $leadId)
    {
        return $query->where('lead_id', $leadId);
    }

    /**
     * Scope a query to only include history for a specific action
     */
    public function scopeWithAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope a query to only include history within a date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate = null)
    {
        if (is_null($endDate)) {
            $endDate = Carbon::now();
        }
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Create a new history record
     *
     * @param Lead $lead
     * @param string $action
     * @param array $oldValues
     * @param array $newValues
     * @param string|null $description
     * @param array|null $details
     * @param int|null $performedBy
     * @return static
     */
    public static function log(
        $lead,
        string $action,
        array $oldValues = [],
        array $newValues = [],
        ?string $description = null,
        ?array $details = null,
        ?int $performedBy = null
    ): self {
        return static::create([
            'lead_id' => is_object($lead) ? $lead->id : $lead,
            'action' => $action,
            'description' => $description ?? ucfirst(str_replace('_', ' ', $action)),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'details' => $details,
            'performed_by' => $performedBy,
        ]);
    }

    /**
     * Get a human-readable diff of changes
     */
    public function getChangesAttribute(): string
    {
        if (empty($this->old_values) || empty($this->new_values)) {
            return 'No changes recorded';
        }

        $changes = [];
        $allKeys = array_unique(array_merge(
            array_keys($this->old_values),
            array_keys($this->new_values)
        ));

        foreach ($allKeys as $key) {
            $old = $this->old_values[$key] ?? null;
            $new = $this->new_values[$key] ?? null;

            if ($old != $new) {
                $changes[] = sprintf(
                    '%s changed from "%s" to "%s"',
                    ucfirst(str_replace('_', ' ', $key)),
                    $old ?? 'empty',
                    $new ?? 'empty'
                );
            }
        }

        return implode(", ", $changes);
    }
}
