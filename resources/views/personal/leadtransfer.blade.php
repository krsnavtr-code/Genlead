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
                            </div>

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

                                <div class="col-md-6 col-lg-4">
                                    <div class="card border rounded mb-3 shadow-sm p-3 position-relative">
                                        <h6 class="text-muted mb-2">#{{ $index + 1 }}</h6>
                                        <input type="checkbox" name="lead_ids[]" value="{{ $lead->id }}" class="position-absolute" style="top: 10px; right: 10px; transform: scale(1.2);">
                                        <h5 class="card-title mb-1">{{ $lead->first_name }} {{ $lead->last_name }}</h5>
                                        <p class="mb-1"><strong>Email:</strong> {{ $lead->email }}</p>
                                        <p class="mb-1"><strong>Phone:</strong> <a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a></p>
                                        <p class="mb-2"><strong>Lead Source:</strong> {{ $lead->lead_source }}</p>
                                        <span class="btn btn-sm {{ $buttonClass }}">{{ $statusText }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Transfer Leads</button>
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
