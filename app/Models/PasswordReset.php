<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PasswordReset extends Model
{

    use HasFactory;


    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];

    protected $table = 'password_resets';

    /**
     * Check if the token is still valid (e.g., not expired).
     *
     * @return bool
     */
    public function isValid()
    {
        // Assume the token expires after 60 minutes
        return Carbon::parse($this->created_at)->addMinutes(10)->isFuture();
    }
}
