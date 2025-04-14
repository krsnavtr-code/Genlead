@php
    $emp_job_role = session()->get('emp_job_role');
@endphp
@php
    // Retrieve the logged-in employee's name using the stored user_id
    $loggedInEmployee = \App\Models\Employee::find(session('user_id'));
@endphp
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="margin-left: 254px;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/admin/home" class="nav-link">Home</a>
      </li>
            
      @if($loggedInEmployee)
      <li class="nav-item d-none d-sm-inline-block">
          <a href="{{ url('/admin/my-account') }}" class="nav-link">My Account</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
          <span class="nav-link">Hi, {{ $loggedInEmployee->emp_name }}</span>
      </li>
    @endif
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        {{-- <a class="nav-link" data-widget="navbar-search" href="#" role="button" style="padding-right: 3rem; margin-right:180px;">
          <i class="fas fa-search"></i>
        </a> --}}
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search Leads" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

       <!-- HRMS -->
       @if($emp_job_role === 4 || $emp_job_role === 1)
       <li class="nav-item">
           <a class="nav-link badge badge-primary navbar-badge" href="{{ route('hrms.manage_employees')}}" style="font-size: 18px; right:29px; padding: 10px;">
               HRMS
           </a>
       </li>
     @endif
    

  <!-- LEADS Dropdown Menu -->
  @if($emp_job_role === 2 || $emp_job_role === 1)
            <li class="nav-item">
                <a class="nav-link badge badge-success navbar-badge" href="{{ url('/i-admin/leads/add-lead') }}" style="font-size: 18px; margin-right:120px; padding: 10px;">
                    LEADS
                </a>
            </li>
    @endif
    {{-- <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
      <a href="{{ url('/i-admin/leads/add-lead')}}" class="dropdown-item">
        <!-- Add Lead Option -->
        <i class="fas fa-plus-circle mr-2"></i> Add Lead
      </a> --}}
      {{-- <div class="dropdown-divider"></div>
      <a href="{{ url('/i-admin/show-leads')}}" class="dropdown-item">
        <!-- Manage Lead Option -->
        <i class="fas fa-cogs mr-2"></i> Manage Leads
      </a>
      <div class="dropdown-divider"></div>
      <a href="{{ url('/admin/activities/create')}}" class="dropdown-item">
        <i class="fas fa-eye mr-2"></i> Add Activities
      </a>
      <div class="dropdown-divider"></div>
      <a href="{{ url('/admin/activities')}}" class="dropdown-item">
        <i class="fas fa-eye mr-2"></i> Manage Activities
      </a>
      <div class="dropdown-divider"></div>
      <a href="{{ url('/admin/tasks/create')}}" class="dropdown-item">
        <i class="fas fa-eye mr-2"></i> Create/Add Tasks
      </a>
      <div class="dropdown-divider"></div>
      <a href="{{ url('/admin/tasks')}}" class="dropdown-item">
        <i class="fas fa-eye mr-2"></i> Manage Tasks
      </a>
      <div class="dropdown-divider"></div>
      <a href="{{ url('/i-admin/pending')}}" class="dropdown-item">
        <i class="fas fa-eye mr-2"></i>Pending Payment
      </a> --}}
      {{-- <div class="dropdown-divider"></div>
      <a href="{{ url('/admin/lists/show')}}" class="dropdown-item">
        <i class="fas fa-eye mr-2"></i>Manage Lists
      </a> --}}
    {{-- </div> --}}
     

      <!-- New Join Panel: Visible to Super Admin -->
      @if($emp_job_role === 1)
          <li class="nav-item">
              <a class="nav-link badge badge-info navbar-badge" href="{{ url('/admin/new-join-panel') }}" style="font-size: 17px; right:95px;padding: 11px;">
               NEW JOIN PANEL
              </a>
         </li>
     @endif

  <li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
      <i class="far fa-bell"></i>
      <span class="badge badge-warning navbar-badge">15</span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
      <span class="dropdown-item dropdown-header">15 Product Updates</span>
      <div class="dropdown-divider"></div>
      <a href="#" class="dropdown-item">
        <i class="fas fa-envelope mr-2"></i> 4 new messages
        <span class="float-right text-muted text-sm">3 mins</span>
      </a>
      <div class="dropdown-divider"></div>
      {{-- <a href="#" class="dropdown-item">
        <i class="fas fa-users mr-2"></i> 8 friend requests
        <span class="float-right text-muted text-sm">12 hours</span>
      </a> --}}
      <div class="dropdown-divider"></div>
      <a href="#" class="dropdown-item">
        <i class="fas fa-file mr-2"></i> 3 new reports
        <span class="float-right text-muted text-sm">2 days</span>
      </a>
      <div class="dropdown-divider"></div>
      <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
    </div>
  </li>

  <li class="nav-item">
    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
      <i class="fas fa-expand-arrows-alt"></i>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="{{ route('logout') }}">
        Logout
    </a>
</li>
</ul>
</nav>
