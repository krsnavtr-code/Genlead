@extends('main')

@section('title', 'Transfer & Share Leads')

@section('content')
<div class="content-wrapper">
    <div class="content-header sty-one">
        <h1>Transfer & Share Leads</h1>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('leads.transfer') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="agent_id">Select Agent:</label>
                        <select name="agent_id" id="agent_id" class="form-control" required>
                            <option value="" disabled selected>Select an agent</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->emp_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mt-4">
                        <label>Select Fresh Leads:</label>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label for="select_count">Select First:</label>
                                <select id="select_count" class="form-control d-inline-block w-auto ml-2">
                                    <option value="">-- Select Number --</option>
                                    <option value="10">Select 10</option>
                                    <option value="15">Select 15</option>
                                    <option value="20">Select 20</option>
                                    <option value="30">Select 30</option>
                                    <option value="40">Select 40</option>
                                    <option value="50">Select 50</option>
                                    <option value="60">Select 60</option>
                                    <option value="70">Select 70</option>
                                    <option value="80">Select 80</option>
                                    <option value="90">Select 90</option>
                                    <option value="100">Select 100</option>
                                </select>
                                <input class="ml-2" type="checkbox" id="select_all"> <label for="select_all">Select All</label>
                                <button type="submit" class="btn btn-primary ml-4">Transfer Leads</button>
                            </div>

                            <div class="table-responsive">
    <table class="table table-sm table-bordered align-middle table-striped" style="font-size: 13px;">
        <thead class="table-primary text-center">
            <tr>
                <th>#</th>
                <th>Select</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Lead Source</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($freshLeads as $index => $lead)
                @php
                    $lastFollowUp = $lead->followUps->sortByDesc('created_at')->first();
                    $daysSinceFollowUp = $lastFollowUp ? \Carbon\Carbon::parse($lastFollowUp->created_at)->diffInDays(now()) : null;
                    $statusText = 'Contacted';
                    $buttonClass = 'btn-secondary';

                    if ($daysSinceFollowUp !== null) {
                        if ($daysSinceFollowUp <= 1) {
                            $statusText = 'Active';
                            $buttonClass = 'btn-warning';
                        } elseif ($daysSinceFollowUp <= 7) {
                            $statusText = 'Engaged';
                            $buttonClass = 'btn-success';
                        } else {
                            $statusText = 'Disengaged';
                            $buttonClass = 'btn-danger';
                        }
                    }
                @endphp
                <tr>
                    <td class="text-muted text-center">#{{ $index + 1 }}</td>
                    <td class="text-center">
                        <input type="checkbox" name="lead_ids[]" value="{{ $lead->id }}" style="transform: scale(1.2);">
                    </td>
                    <td>{{ $lead->first_name }} {{ $lead->last_name }}</td>
                    <td>{{ $lead->email }}</td>
                    <td><a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a></td>
                    <td>{{ $lead->lead_source }}</td>
                    <td>
                        <span class="btn btn-sm {{ $buttonClass }}">{{ $statusText }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
   </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Select/Deselect all checkboxes
    document.getElementById('select_all').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('input[name="lead_ids[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    // Select First N Checkboxes
    document.getElementById('select_count').addEventListener('change', function () {
        const count = parseInt(this.value);
        const checkboxes = Array.from(document.querySelectorAll('input[name="lead_ids[]"]'));

        checkboxes.forEach(cb => cb.checked = false); // Uncheck all first

        for (let i = 0; i < count && i < checkboxes.length; i++) {
            checkboxes[i].checked = true;
        }
    });
</script>
@endsection
