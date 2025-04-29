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
                'code' => 'test1',
                'name' => 'Test 1'
            ],
            [
                'code' => 'test2',
                'name' => 'Test 2'
            ]
            ];
    }


}
