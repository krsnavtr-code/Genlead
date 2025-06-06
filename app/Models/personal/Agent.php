<?php

namespace App\Models\personal;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Agent extends Authenticatable
{
    use HasFactory;

    // Define the table name
    protected $table = 'employees';

    // Specify the fillable attributes
    protected $fillable = [
        'emp_name',
        'emp_email',
        'emp_phone',
        'emp_location',
        'emp_password',
        'emp_job_role',
        'emp_username',
        'emp_branch',
        'emp_salary',
        'emp_pic',
        'emp_join_date',
        'referrer_id',
        'referral_code'
    ];

    // Specify the hidden attributes
    protected $hidden = [
        'emp_password'
    ];
    
    // Tell Laravel which field to use for authentication
    public function getAuthPassword()
    {
        return $this->emp_password;
    }

    // Define the relationship with Lead
    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
    
    // Referrer relationship
    public function referrer()
    {
        return $this->belongsTo(Agent::class, 'referrer_id');
    }
    
    // Direct referrals (level 1)
    public function referrals()
    {
        return $this->hasMany(Agent::class, 'referrer_id');
    }
    
    // Network levels relationship
    public function networkLevels()
    {
        return $this->hasMany(AgentNetworkLevel::class, 'agent_id');
    }
    
    // Referred agents in network at any level
    public function networkReferrals()
    {
        return $this->belongsToMany(
            Agent::class,
            'agent_network_levels',
            'agent_id',
            'referral_id'
        )->withPivot('level');
    }
    
    /**
     * Generate a unique referral code
     */
    public static function generateReferralCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (static::where('referral_code', $code)->exists());
        
        return $code;
    }
    
    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($agent) {
            if (empty($agent->referral_code)) {
                $agent->referral_code = static::generateReferralCode();
            }
        });
        
        static::created(function ($agent) {
            // If this agent was referred by someone, create network level entries
            if ($agent->referrer_id) {
                $agent->addToReferrerNetwork($agent->referrer_id);
            }
        });
    }
    
    /**
     * Add agent to referrer's network at all levels
     */
    public function addToReferrerNetwork($referrerId, $level = 1)
    {
        $maxLevels = 10; // Maximum number of levels to go up
        
        while ($referrerId && $level <= $maxLevels) {
            // Create network level record
            AgentNetworkLevel::updateOrCreate(
                [
                    'agent_id' => $referrerId,
                    'referral_id' => $this->id
                ],
                ['level' => $level]
            );
            
            // Move up one level
            $referrer = Agent::find($referrerId);
            $referrerId = $referrer->referrer_id;
            $level++;
        }
    }
}
