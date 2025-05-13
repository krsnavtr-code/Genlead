<!-- Horizontal Navbar -->
<div class="horizontal-navbar d-flex flex-wrap justify-content-around py-2 border-bottom mb-3">
    <a href="{{ url('/i-admin/show-leads') }}" class="btn m-1">Manage Leads</a>
    <!-- <a href="{{ url('/admin/activities/create') }}" class="btn m-1">Add Activities</a> -->
    <a href="{{ url('/admin/activities') }}" class="btn m-1">Manage Activities</a>
    <!-- <a href="{{ url('/admin/tasks/create') }}" class="btn m-1">Create/Add Tasks</a> -->
    <a href="{{ url('/admin/tasks') }}" class="btn m-1">Manage Tasks</a>
    @if ($emp_job_role === 1)
    <a href="{{ url('/i-admin/pending') }}" class="btn m-1">Pending Payment</a>
    @endif
</div>
