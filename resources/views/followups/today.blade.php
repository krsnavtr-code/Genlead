@extends('main')

@section('title', 'Today\'s Follow-Ups')

@section('content')
<div class="content-wrapper">
    <!-- Header -->
    <div class="content-header sty-one">
        <h1>Today's Follow-Ups</h1>
        <ol class="breadcrumb">
            <li><a href="">Leads</a></li>
        </ol>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="card">
            <div class="card-body">
                <h4 class="text-black">Follow-Ups Scheduled for Today</h4>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Lead Name</th>
                                <th>Agent Name</th>
                                <th>Follow-Up Time</th>
                                <th>Comments</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($followUps as $followUp)
                                <tr>
                                    <td>{{ $followUp->lead->first_name }} {{ $followUp->lead->last_name }}</td>
                                    <td>{{ $followUp->agent->emp_name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('d M Y, h:i A') }}</td>
                                    <td>{{ $followUp->comments }}</td>
                                    <td>
                                        <a href="{{ url('/i-admin/leads/view/'.$followUp->lead_id) }}" class="btn btn-sm btn-primary">View Lead</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No follow-ups scheduled for today.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Back Button -->
                <a href="{{ url('/i-admin/show-leads') }}" class="btn btn-secondary mt-3">Back to Leads</a>
            </div>
        </div>
    </div>
</div>
@endsection
