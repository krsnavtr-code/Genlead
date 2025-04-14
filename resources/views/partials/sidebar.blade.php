@php
    $emp_job_role = session()->get('emp_job_role');
@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4">
     {{-- <!-- Brand Logo -->
    <a href="/home" class="brand-link">
     {{-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> --}}
      {{-- <span class="brand-text font-weight-light">Admin</span>
    </a>  --}} 

    <!-- Brand Logo -->
<a href="/admin/home" class="brand-link">
  {{-- <img src="{{ asset('images/7015971.jpg') }}" alt="Lead Force" class="brand-image img-circle elevation-3"> --}}
  {{-- <span class="brand-initials" style="font-size: 24px; font-weight: bold;">Lf</span>
  <span class="brand-text font-weight-light">LEAD FORCE</span> --}}

  <img src="{{ asset('images/logo.jpeg') }}" style=" border-radius: 50%; width: 39px; height: 35px; display: flex; align-items: center; justify-content: center;" alt="Logo">
  <span class="brand-text font-weight-light" style="font-size: 20px; margin-top: 5px;">GEN LEAD</span>
</a>
     <!-- SidebarSearch Form -->
     <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                {{-- <i class="right fas fa-angle-left"></i> --}}
              </p>
            </a>
        </li>
                 
         <!-- All Login Access Section -->
         @if($emp_job_role === 1)
         <li class="nav-item">
          <a href="{{ url('/admin/all-login-access')}}" class="nav-link">
            <i class="nav-icon fas fa-briefcase"></i>
            <p>
              All Login Access
              <i class="fas fa-angle-right right"></i>
            </p>
          </a>
        </li>
        @endif
          @if($emp_job_role === 5 || $emp_job_role === 1)
             <li class="nav-item">
              <a href="{{ url('/admin/lead/payment-verify')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Payment Verification</p>
              </a>
            </li>
          </ul> 
        </li>
        @endif    
</nav>
<!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>