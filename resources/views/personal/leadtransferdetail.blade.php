@extends('main')

@section('title', 'Transfer & Share Leads Details')

@section('content')
    <div class="content-wrapper">
        <div class="content-header sty-one">
            <h1>Transfer & Share Leads Details</h1>
        </div>
        <div class="content">
            <div class="card">
                <div class="card-body">

                    <form action="{{ route('leads.transfer.detail') }}" method="GET">
                        <div class="row">
                            <div class="form-group col-4">
                                <label for="status">Status<span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Select Status</option>
                                    @foreach(App\Helpers\SiteHelper::getLeadStatus() as $status)
                                        <option value="{{ $status['code'] }}" @if(request()->status == $status['code']) selected @endif>{{ $status['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-4">
                            <label for="agent_id">Select Agent:</label>
                            <select name="agent_id" id="agent_id" class="form-control" required>
                                <option value="" disabled selected>Select an agent</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->emp_name }}</option>
                                @endforeach
                            </select>
                        </div>

                            <div class="form-group col-4">
                                <button type="submit" class="btn btn-primary btn-sm">Filter</button>

                            </div>
                        </div>
                    </form>


                    <form action="{{ route('leads.transfer') }}" method="POST">
                        @csrf

                        

                        <div class="form-group">
                            <label for="lead_ids">Select Fresh Leads:</label>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select_all"></th>
                                        <th>Lead Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Lead Source</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($freshLeads as $lead)
                                                                    <tr>
                                                                        <td><input type="checkbox" name="lead_ids[]" value="{{ $lead->id }}"></td>
                                                                        <td>{{ $lead->first_name }} {{ $lead->last_name }}</td>
                                                                        <td>{{ $lead->email }}</td>
                                                                        <td>{{ $lead->phone }}</td>
                                                                        <td>{{ $lead->lead_source }}</td>
                                                                        <td>
                                                                            @php
                                                                                $lastFollowUp = $lead->followUps->sortByDesc('created_at')->first();
                                                                                $daysSinceFollowUp = $lastFollowUp ? \Carbon\Carbon::parse($lastFollowUp->created_at)->diffInDays(now()) : null;
                                                                                $statusText = 'Contacted'; // Default status
                                                                                $buttonClass = 'btn-secondary'; // Default color

                                                                                if ($daysSinceFollowUp !== null) {
                                                                                    if ($daysSinceFollowUp <= 1) {
                                                                                        $statusText = 'Active';
                                                                                        $buttonClass = 'btn-warning'; // Green for Active
                                                                                    } elseif ($daysSinceFollowUp <= 7) {
                                                                                        $statusText = 'Engaged';
                                                                                        $buttonClass = 'btn-success'; // Yellow for Engaged
                                                                                    } else {
                                                                                        $statusText = 'Disengaged';
                                                                                        $buttonClass = 'btn-danger'; // Red for Disengaged
                                                                                    }
                                                                                }
                                                                              @endphp
                                                                            <button class="btn {{ $buttonClass }}">{{ $statusText }}</button>
                                                                        </td>
                                                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
    {{ $freshLeads->links() }}
</div>

                        <!-- <button type="submit" class="btn btn-primary">Transfer Leads</button> -->
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Select/Deselect all leads
        document.getElementById('select_all').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('input[name="lead_ids[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    </script>
@endsection