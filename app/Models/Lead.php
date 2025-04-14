<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $table ="leads";

    protected $fillable = [
        'first_name',
        'title',
        'last_name',
        'email',
        'phone',
        'company',
        'lead_source',
        'lead_status',
        'university',
        'courses',
        'street',
        'state',
        'country',
        'city',
        'zip_code',
        'description',
    ];

    public function getButtonColorAttribute()
    {
        switch ($this->lead_status) {
            case 'new':
                return 'btn-success'; // Green for New
            case 'contacted':
                return 'btn-primary'; // Blue for Contacted
            case 'qualified':
                return 'btn-warning'; // Yellow for Qualified
            case 'lost':
                return 'btn-danger'; // Red for Lost
            case 'closed':
                return 'btn-secondary'; // Grey for Closed
            default:
                return 'btn-secondary'; // Default to Grey
        }
    }
}
