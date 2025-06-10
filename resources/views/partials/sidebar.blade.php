@php
    $emp_job_role = session()->get('emp_job_role');
    $loggedInEmployee = \App\Models\Employee::find(session('user_id'));
@endphp

<style>
    .nav-sidebar .nav-item .nav-link.active {
        background-color:var(--logo-color) !important;
        color: #fff !important;
    }
    .nav-sidebar .nav-item .nav-link.active i {
        color: #fff !important;
    }
</style>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/admin/home" class="brand-link">
        <img src="{{ asset('images/gen-logo.jpeg') }}" alt="Logo"
            style="border-radius: 50%; width: 50px; height: 50px;">
        <span class="brand-text font-weight-light" style="font-size: 20px;">GEN LEAD</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- SidebarSearch Form -->
        <div class="form-inline mt-2">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search Leads">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" style="gap: 5px;" data-widget="treeview" role="menu">
                <li class="nav-item ">
                    <a href="/admin/home" class="nav-link w-100 {{ request()->is('admin/home') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Home</p>
                    </a>
                </li>

                <!-- Show My Account for All Roles -->
                @if(in_array($emp_job_role, [1, 2, 3, 4, 5, 6, 7]))
                <li class="nav-item">
                    <a href="{{ url('/admin/my-account') }}" class="nav-link w-100 {{ request()->is('admin/my-account') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>My Account</p>
                    </a>
                </li>
                @endif

                <!-- Show Leads for Agent (2), Admin (1) -->
                @if(in_array($emp_job_role, [1, 2, 7]))
                <li class="nav-item">
                    <a href="{{ url('/i-admin/show-leads') }}" class="nav-link w-100 {{ request()->is('i-admin/show-leads*') || request()->is('admin/activities*') || request()->is('admin/tasks*') || request()->is('i-admin/followups*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-plus-circle" style="color: #1E7E34;"></i>
                        <p>Leads</p>
                    </a>
                </li>
                @endif

                <!-- Team management for Team Leader (6) -->
                @if(in_array($emp_job_role, [1, 6]))
                <li class="nav-item">
                    <a href="{{ url('/admin/team-management') }}" class="nav-link w-100 {{ request()->is('admin/team-management*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-friends" style="color: #0062CC;"></i>
                        <p>Team Leader (Office)</p>
                    </a>
                </li>
                @endif

                <!-- Agent referral team chain view -->
                @if(in_array($emp_job_role, [1, 7]))
                <li class="nav-item">
                    <a href="{{ url('/admin/agent-referral-chain') }}" class="nav-link w-100 {{ 
                        request()->is('admin/agent-referral-chain*') || 
                        request()->is('team/referral-chain*') || 
                        request()->is('admin/agent-referral-leads-details*') ||
                        request()->is('admin/team/member/*/leads-details*')
                        ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users" style="color: #0062CC;"></i>
                        <p>Manage Team</p>
                    </a>
                </li>
                @endif

                <!-- Show HRMS for HR (4), Admin (1) -->
                @if(in_array($emp_job_role, [1, 4]))
                <li class="nav-item">
                    <a href="{{ route('hrms.manage_employees') }}" class="nav-link w-100 {{ request()->is('admin/hrms*') || request()->is('hrms/employee*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-tie" style="color: #0062CC;"></i>
                        <p>HRMS</p>
                    </a>
                </li>
                @endif

                <!-- Show New Join Panel for Admin (1) -->
                @if(in_array($emp_job_role, [1]))
                <li class="nav-item">
                    <a href="{{ url('/admin/new-join-panel') }}" class="nav-link w-100 {{ request()->is('admin/new-join-panel') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-plus" style="color: #117A8B;"></i>
                        <p>New Join Panel</p>
                    </a>
                </li>
                @endif

                <!-- Show All Login Access for Admin (1) -->
                @if(in_array($emp_job_role, [1]))
                <li class="nav-item">
                    <a href="{{ url('/admin/all-login-access') }}" class="nav-link w-100 {{ request()->is('admin/all-login-access') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>All Login Access</p>
                    </a>
                </li>
                @endif

                <!-- Show Payment Verification for Accountant (5), Admin (1) -->
                @if(in_array($emp_job_role, [1, 5]))
                <li class="nav-item">
                    <a href="{{ route('payment.verify') }}" class="nav-link w-100 {{ request()->is('payment/verify*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-check-circle"></i>
                        <p>Payment Verification</p>
                    </a>
                </li>
                @endif

                <!-- Show How to Use for Agent (2), Admin (1) -->
                @if(in_array($emp_job_role, [1, 2, 4, 5, 6, 7]))
                <li class="nav-item">
                    <a href="{{ route('how-to-use') }}" class="nav-link w-100 {{ request()->is('how-to-use') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-question-circle"></i>
                        <p>How to Use</p>
                    </a>
                </li>
                @endif

                <!-- Show Logout for All Roles -->
                <li class="nav-item">
                    <a href="{{ route('logout') }}" style="color: var(--logo-color);" class="nav-link text-bold w-100 {{ request()->is('logout') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
