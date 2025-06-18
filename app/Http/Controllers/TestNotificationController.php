<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\TestNotification;

class TestNotificationController extends Controller
{
    public function sendTestNotification()
    {
        $user = User::first(); // Get the first user, or use auth()->user() for the current user
        
        if ($user) {
            $user->notify(new TestNotification([
                'message' => 'This is a test notification',
                'url' => url('/notifications')
            ]));
            
            return 'Test notification sent successfully';
        }
        
        return 'No user found to send notification';
    }
}
