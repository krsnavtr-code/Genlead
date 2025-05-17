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
                'code' => 'interested',
                'name' => 'Interested'
            ],
            [
                'code'=> 'not_interested',
                'name'=> 'Not Interested'
            ],
            [
                'code'=> 'follow_up_callback',
                'name'=> 'Follow Up / Callback'
            ],
            [
                'code'=> 'follow_up_ringing',
                'name'=> 'Follow Up / Ringing'
            ],
            [
                'code'=> 'follow_up_hang_up',
                'name'=> 'Follow Up / Hang Up'
            ],
            [
                'code'=> 'follow_up_rpnc',
                'name'=> 'Follow Up / RPNC'
            ],
            [
                'code'=> 'not_contacted',
                'name'=> 'Not Contacted'
            ],
            [
                'code'=> 'registration_done',
                'name'=> 'Registration Done'
            ],
            [
                'code'=> 'admission_done',
                'name'=> 'Admission Done'
            ],    
        ];
    }
}