<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            
            // Get notifications directly from database
            $notifications = DatabaseNotification::where('notifiable_type', get_class($user))
                ->where('notifiable_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);
            
            // Mark as read
            DatabaseNotification::where('notifiable_type', get_class($user))
                ->where('notifiable_id', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
            
            return view('notifications.index', compact('notifications'));
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Notification error: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error($e->getTraceAsString());
            return back()->with('error', 'Error loading notifications');
        }
    }

    public function markAsRead($id)
    {
        try {
            $user = Auth::user();
            $notification = DatabaseNotification::where('id', $id)
                ->where('notifiable_type', get_class($user))
                ->where('notifiable_id', $user->id)
                ->whereNull('read_at')
                ->first();
            
            if ($notification) {
                $notification->update(['read_at' => now()]);
                
                // Get updated unread count
                $unreadCount = DatabaseNotification::where('notifiable_type', get_class($user))
                    ->where('notifiable_id', $user->id)
                    ->whereNull('read_at')
                    ->count();
                
                return response()->json([
                    'success' => true,
                    'unread_count' => $unreadCount
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Notification not found or already read'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking notification as read'
            ], 500);
        }
    }

    public function markAllAsRead()
    {
        try {
            $user = Auth::user();
            
            $updated = DatabaseNotification::where('notifiable_type', get_class($user))
                ->where('notifiable_id', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
                
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read',
                'marked_read' => $updated
            ]);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mark all as read error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error marking all notifications as read'
            ], 500);
        }
    }
}
