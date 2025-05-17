@extends('main')

@section('title', 'Agent Leads')

@section('content')

@php
    $emp_job_role = session()->get('emp_job_role');
@endphp

<div class="container mt-5">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="h4 mb-4">Select Agent</h1>

            @if($agents->count() > 0)
                <form action="{{ route('admin.agent.data') }}" method="GET" class="mb-4">
                    @csrf
                    <div class="form-group">
                        <label for="agent_id">Select Agent:</label>
                        <select name="agent_id" id="agent_id" class="form-control" required>
                            <option value="" disabled {{ !request()->has('agent_id') ? 'selected' : '' }}>Select an agent</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->emp_name }} ({{ $agent->emp_job_role ?? 'No Role' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">View Leads</button>
                </form>
            @else
                <div class="alert alert-warning">
                    No agents found in the system. Please add agents first.
                </div>
            @endif

            @if(isset($agentData) && $agentLeads->count() > 0)
                <div class="agent-info mb-4">
                    <p class="text-muted">
                        <strong>Email:</strong> {{ $agentData->emp_email }} | 
                        <strong>Phone:</strong> {{ $agentData->emp_phone }}
                    </p>
                </div>

                <form id="transferForm" action="{{ route('admin.leads.transfer') }}" method="POST">
                    @csrf
                    <input type="hidden" name="agent_id" value="{{ $agentData->id }}">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="target_agent_id">Transfer Selected Leads To:</label>
                                <select name="target_agent_id" id="target_agent_id" class="form-control" required>
                                    <option value="" disabled selected>Select target agent</option>
                                    @foreach($agents->where('id', '!=', $agentData->id) as $agent)
                                        <option value="{{ $agent->id }}">
                                            {{ $agent->emp_name }} ({{ $agent->emp_job_role ?? 'No Role' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center justify-content-start">
                            <button type="submit" class="btn btn-success" id="transferBtn" style="margin-top: 17px;">
                                <i class="fas fa-exchange-alt me-1"></i> Re-Transfer Leads
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <h2 class="h5 mb-3">All of these leads are assigned to {{ $agentData->emp_name }}</h2>
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($agentLeads as $index => $lead)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="lead_ids[]" value="{{ $lead->id }}" class="lead-checkbox">
                                        </td>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ url('/i-admin/leads/view/'.$lead->id) }}">{{ $lead->first_name }} {{ $lead->last_name }}</a>
                                        </td>
                                        <td>{{ $lead->email }}</td>
                                        <td>{{ $lead->phone }}</td>
                                        <td>
                                            @php
                                                $statusClass = [
                                                    'new' => 'badge bg-primary',
                                                    'contacted' => 'badge bg-info',
                                                    'qualified' => 'badge bg-warning',
                                                    'lost' => 'badge bg-danger',
                                                    'closed' => 'badge bg-secondary'
                                                ][$lead->status] ?? 'badge bg-secondary';
                                                $statusLabel = ucfirst($lead->status);
                                            @endphp
                                            <span class="{{ $statusClass }}">{{ $statusLabel }}</span>
                                        </td>
                                        <td>{{ $lead->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            @elseif(isset($agentData) && $agentLeads->count() === 0)
                <div class="alert alert-info">
                    No leads found for this agent.
                </div>
            @elseif(request()->has('agent_id'))
                <div class="alert alert-warning">
                    No agent found with the selected ID.
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM fully loaded');
        
        // Get all necessary elements
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.lead-checkbox');
        const transferBtn = document.getElementById('transferBtn');
        const transferForm = document.getElementById('transferForm');
        const targetAgentSelect = document.getElementById('target_agent_id');
        
        console.log('Elements found:', {
            selectAll: !!selectAll,
            checkboxes: checkboxes.length,
            transferBtn: !!transferBtn,
            transferForm: !!transferForm,
            targetAgentSelect: !!targetAgentSelect
        });
        
        if (!selectAll || !transferBtn || !transferForm || !targetAgentSelect) {
            console.error('One or more required elements not found');
            return;
        }
        
        // Function to update the transfer button state
        function updateTransferButton() {
            try {
                const checkedBoxes = document.querySelectorAll('.lead-checkbox:checked');
                const isDisabled = checkedBoxes.length === 0;
                console.log('Updating transfer button. Checked boxes:', checkedBoxes.length, 'Disabled:', isDisabled);
                transferBtn.disabled = isDisabled;
                
                // Force a reflow to ensure the disabled state is applied
                transferBtn.style.display = 'none';
                transferBtn.offsetHeight;
                transferBtn.style.display = '';
            } catch (error) {
                console.error('Error in updateTransferButton:', error);
            }
        }
        
        // Toggle all checkboxes
        selectAll.addEventListener('change', function() {
            console.log('Select all changed:', this.checked);
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                // Manually trigger change event
                const event = new Event('change');
                checkbox.dispatchEvent(event);
            });
            updateTransferButton();
        });
        
        // Update select all checkbox when individual checkboxes are toggled
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                console.log('Checkbox changed:', this.checked, this.value);
                if (!this.checked) {
                    selectAll.checked = false;
                } else {
                    // Check if all checkboxes are checked
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    selectAll.checked = allChecked;
                }
                updateTransferButton();
            });
            
            // Add click event as well to ensure it works
            checkbox.addEventListener('click', function() {
                // Small timeout to let the checkbox state update
                setTimeout(updateTransferButton, 10);
            });
        });
        
        // Update transfer button when target agent changes
        targetAgentSelect.addEventListener('change', function() {
            console.log('Target agent changed:', this.value);
            updateTransferButton();
        });
        
        // Confirm before transfer
        transferForm.addEventListener('submit', function(e) {
            console.log('Form submit attempted');
            const checkedBoxes = document.querySelectorAll('.lead-checkbox:checked');
            const targetAgent = document.getElementById('target_agent_id');
            
            if (checkedBoxes.length === 0) {
                console.log('No leads selected');
                e.preventDefault();
                alert('Please select at least one lead to transfer.');
                return false;
            }
            
            if (!targetAgent.value) {
                console.log('No target agent selected');
                e.preventDefault();
                alert('Please select a target agent.');
                return false;
            }
            
            const confirmMessage = `Are you sure you want to transfer ${checkedBoxes.length} selected lead(s) to ${targetAgent.options[targetAgent.selectedIndex].text}?`;
            console.log('Confirm message:', confirmMessage);
            
            if (!confirm(confirmMessage)) {
                e.preventDefault();
                return false;
            }
            
            console.log('Form submission proceeding');
            return true;
        });
        
        // Initial update of the transfer button
        console.log('Initial update of transfer button');
        updateTransferButton();
        
        // Add a manual override button for testing
        const testButton = document.createElement('button');
        testButton.textContent = 'Check Button State';
        testButton.className = 'btn btn-warning btn-sm';
        testButton.style.marginLeft = '10px';
        testButton.onclick = function() {
            const checkedBoxes = document.querySelectorAll('.lead-checkbox:checked');
            console.log('Manual check:', {
                checkedBoxes: checkedBoxes.length,
                transferBtnDisabled: transferBtn.disabled,
                transferBtn: transferBtn
            });
            // Force enable the button
            transferBtn.disabled = checkedBoxes.length === 0;
        };
        transferBtn.parentNode.insertBefore(testButton, transferBtn.nextSibling);
    });
</script>
@endpush

@push('styles')
<style>
    .table th {
        white-space: nowrap;
    }
    .badge {
        font-size: 0.8rem;
        padding: 0.35em 0.65em;
    }
</style>
@endpush

@endsection
