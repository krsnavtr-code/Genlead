<?php

namespace App\Helpers;

use App\Models\Accountant;
use App\Models\CollectionCenter;
use App\Models\Company;
use App\Models\Dispatcher;
use App\Models\DispatcherManager;
use App\Models\Driver;
use App\Models\GeneralSetting;
use App\Models\Operation;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class SiteHelper
{
    public static function getLeadStatus()
    {
        return [
            'Hot Lead' => [
                [
                    'category' => 'Follow Ups',
                    'subcategories' => [
                        ['code'=> 'H_follow_up_callback', 'name'=> 'Callback'],
                        ['code'=> 'H_follow_up_ringing', 'name'=> 'Ringing'],
                        ['code'=> 'H_follow_up_hang_up', 'name'=> 'Hang Up'],
                        ['code'=> 'H_follow_up_rpnc', 'name'=> 'RPNC'],
                    ]
                ],
                [
                    'category' => 'Converted',
                    'subcategories' => [
                        ['code' => 'H_registration_done', 'name' => 'Registration Done'],
                        ['code' => 'H_admission_done', 'name' => 'Admission Done'],
                    ]
                ],
            ],
            'Cold Lead' => [
                [
                    'category' => 'New Leads',
                    'subcategories' => [
                        ['code' => 'C_new', 'name' => 'New'],
                        ['code' => 'C_not_connected', 'name' => 'Not Connected'],
                    ]
                ],
                [
                    'category' => 'Interest',
                    'subcategories' => [
                        ['code' => 'C_interested', 'name' => 'Interested'],
                        ['code' => 'C_not_interested', 'name' => 'Not Interested'],
                        ['code' => 'C_wrong_number', 'name' => 'Wrong Number'],
                    ]
                ],
                [
                    'category' => 'Follow Ups',
                    'subcategories' => [
                        ['code' => 'C_follow_up_callback', 'name' => 'Callback'],
                        ['code' => 'C_follow_up_ringing', 'name' => 'Ringing'],
                        ['code' => 'C_follow_up_hang_up', 'name' => 'Hang Up'],
                        ['code' => 'C_follow_up_rpnc', 'name' => 'RPNC'],
                    ]
                ],
            ],
        ];
    }

    public static function getStatusColors()
    {
        return [
            // Hot Lead statuses
            'H_follow_up_callback' => 'bg-warning',
            'H_follow_up_ringing' => 'bg-warning',
            'H_follow_up_hang_up' => 'bg-info',
            'H_follow_up_rpnc' => 'bg-info',
            'H_registration_done' => 'bg-success',
            'H_admission_done' => 'bg-success',
            
            // Cold Lead statuses
            'C_new' => 'bg-secondary',
            'C_not_connected' => 'bg-info',
            'C_interested' => 'bg-primary',
            'C_not_interested' => 'bg-danger',
            'C_wrong_number' => 'bg-dark',
            'C_follow_up_callback' => 'bg-warning',
            'C_follow_up_ringing' => 'bg-warning',
            'C_follow_up_hang_up' => 'bg-info',
            'C_follow_up_rpnc' => 'bg-info',
            
            // Default fallback
            'default' => 'bg-secondary'
        ];
    }
}