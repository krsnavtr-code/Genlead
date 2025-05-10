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
        <!-- <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <span class="dropdown-item dropdown-header">15 Product Updates</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> 3 new reports
                    <span class="float-right text-muted text-sm">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li> -->
        <!-- Logout -->
        <li class="nav-item">
            <a class="nav-link" style="color: red;" href="{{ route('logout') }}">Logout</a>
        </li>
    </ul>
</nav>

