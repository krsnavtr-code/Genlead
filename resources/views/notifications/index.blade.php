@extends('main')

@section('title', 'Notifications')

@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Notifications\DatabaseNotification;
    
    $user = Auth::user();
    $unreadCount = $user ? DatabaseNotification::where('notifiable_type', get_class($user))
        ->where('notifiable_id', $user->id)
        ->whereNull('read_at')
        ->count() : 0;
@endphp

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Notifications</h5>
                    @if($unreadCount > 0)
                        <a href="{{ route('notifications.markAllRead') }}" class="btn btn-sm btn-outline-secondary">
                            Mark all as read
                        </a>
                    @endif
                </div>

                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                @php
                                    $isUnread = is_null($notification->read_at);
                                @endphp
                                <li class="list-group-item {{ $isUnread ? 'bg-light' : '' }}">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <p class="mb-1">
                                                @if($isUnread)
                                                    <span class="badge bg-primary me-2">New</span>
                                                @endif
                                                {{ $notification->data['message'] ?? 'New notification' }}
                                            </p>
                                            <small class="text-muted">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        @if($isUnread)
                                            <button class="btn btn-sm btn-link mark-as-read" data-id="{{ $notification->id }}">
                                                <i class="fas fa-check"></i> Mark as read
                                            </button>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="p-3">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center p-4">
                            <p class="text-muted mb-0">No notifications found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mark notification as read
        document.querySelectorAll('.mark-as-read').forEach(button => {
            button.addEventListener('click', function() {
                const notificationId = this.dataset.id;
                const notificationItem = this.closest('li');
                
                fetch(`/notifications/${notificationId}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        notificationItem.classList.remove('bg-light');
                        button.remove();
                        
                        // Update notification count in navbar if it exists
                        const badge = document.querySelector('.notification-badge');
                        if (badge) {
                            const count = parseInt(badge.textContent) - 1;
                            badge.textContent = count > 0 ? count : '';
                            if (count <= 0) {
                                badge.classList.add('d-none');
                            }
                        }
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection
