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
            [
                'code' => 'new',
                'name' => 'New'
            ],
            [
                'code' => 'contacted',
                'name' => 'Contacted'
            ],
            [
                'code'=> 'not_connected',
                'name'=> 'Not Connected'
            ],
            [
                'code'=> 'qualified',
                'name'=> 'Qualified'
            ],
            [
                'code'=> 'not_qualified',
                'name'=> 'Not Qualified'
            ],
            [
                'code'=> 'future',
                'name'=> 'Contact in Future'
            ],
            [
                'code'=> 'lost',
                'name'=> 'Lost'
            ],
            [
                'code'=> 'closed',
                'name'=> 'Closed'
            ],            
        ];
    }
}