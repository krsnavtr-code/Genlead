@php
    $emp_job_role = session()->get('emp_job_role');
    $loggedInEmployee = \App\Models\Employee::find(session('user_id'));
@endphp

<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="position: fixed; width: -webkit-fill-available;
    top: 0px;">
    <!-- Sidebar Toggle Icon -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="/admin/home" class="nav-link">GEN LEAD</a>
        </li>
    </ul>

    

    <!-- Right navbar icons (optional) -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a href="#" class="nav-link disabled" style="color: black;">
                <i class="nav-icon fas fa-smile"></i>
                <span>Hi, {{ $loggedInEmployee->emp_name }}</span>
            </a>
        </li>
        <!-- Fullscreen Button -->
        <!-- <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li> -->
        <!-- Notifications Dropdown -->
        @php
            use Illuminate\Support\Facades\Auth;
            use Illuminate\Notifications\DatabaseNotification;
            
            $user = Auth::user();
            $unreadNotifications = $user ? DatabaseNotification::where('notifiable_type', get_class($user))
                ->where('notifiable_id', $user->id)
                ->whereNull('read_at')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get() : collect();
                
            $unreadCount = $unreadNotifications->count();
        @endphp
        
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                @if($unreadCount > 0)
                    <span class="badge badge-warning navbar-badge notification-badge">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">
                    {{ $unreadCount }} New Notifications
                    @if($unreadCount > 0)
                        <a href="{{ route('notifications.markAllRead') }}" class="float-right text-sm">
                            Mark all as read
                        </a>
                    @endif
                </span>
                <div class="dropdown-divider"></div>
                <div id="notification-list">
                    @forelse($unreadNotifications as $notification)
                        <a href="{{ route('notifications.index') }}" class="dropdown-item">
                            <div class="media">
                                <div class="media-body">
                                    <p class="text-sm">{{ $notification->data['message'] ?? 'New notification' }}</p>
                                    <p class="text-sm text-muted">
                                        <i class="far fa-clock mr-1"></i> 
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                    @empty
                        <a href="#" class="dropdown-item text-center">
                            No new notifications
                        </a>
                    @endforelse
                </div>
                <div class="dropdown-divider"></div>
                <a href="{{ route('notifications.index') }}" class="dropdown-item dropdown-footer">
                    View All Notifications
                </a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('notifications.index') }}" title="View all notifications">
                <i class="far fa-bell"></i>
            </a>
        </li>
        <!-- Logout -->
        <li class="nav-item">
            <a class="nav-link" style="color: red;" href="{{ route('logout') }}">Logout</a>
        </li>
    </ul>
</nav>

