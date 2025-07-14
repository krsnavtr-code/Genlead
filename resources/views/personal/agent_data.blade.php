@extends('main')

@php
    use App\Models\Employee;
@endphp

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
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="agent_id">Select Agent:</label>
                                <select name="agent_id" id="agent_id" class="form-control" required>
                                    <option value="" disabled {{ !request()->has('agent_id') ? 'selected' : '' }}>Select an agent</option>
                                    @foreach($agents->sortBy('emp_name') as $agent)
                                        @php
                                            $teamLeader = $agent->reports_to ? Employee::find($agent->reports_to) : null;
                                            $teamLeaderName = $teamLeader ? $teamLeader->emp_name : 'No Team Leader';
                                        @endphp
                                        <option value="{{ $agent->id }}" data-team-leader="{{ $teamLeaderName }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                          {{ $agent->id }} {{ $agent->emp_name }} ({{ $agent->emp_job_role ?? 'No Role' }}) - Team Lead: {{ $teamLeaderName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Filter by Status:</label>
                                <select name="status" id="status" class="form-control" onchange="this.form.submit()">
                                    <option value="">All Statuses</option>
                                    <option value="other" {{ request('status') === 'other' ? 'selected' : '' }}>
                                        Other (No Status)
                                    </option>
                                    @foreach(\App\Helpers\SiteHelper::getLeadStatus() as $type => $categories)
                                    <optgroup label="{{ $type }}">
                                        @foreach($categories as $category)
                                            @foreach($category['subcategories'] as $subcategory)
                                                <option value="{{ $subcategory['code'] }}" {{ request('status') == $subcategory['code'] ? 'selected' : '' }}>
                                                    {{ $category['category'] }} - {{ $subcategory['name'] }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary">View Leads</button>
                        @if(request()->has('status'))
                            <a href="{{ route('admin.agent.data', ['agent_id' => request('agent_id')]) }}" class="btn btn-outline-secondary ml-2">
                                Clear Filters
                            </a>
                        @endif
                    </div>
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
                                    @foreach($agents->where('id', '!=', $agentData->id)->sortBy('emp_name') as $agent)
                                        <option value="{{ $agent->id }}">
                                            Id: {{ $agent->id }}, Name: {{ $agent->emp_name }}, Role: {{ $agent->emp_job_role ?? 'No Role' }},
                                            <span style="color: blue; font-weight: bold;">Team Leader: </span> {{ $agent->reports_to ? Employee::find($agent->reports_to)->emp_name : 'No Team Leader' }}
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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h6 mb-0">All of these leads are assigned to {{ $agentData->emp_name }}</h2>

        <div class="d-flex align-items-center">
            <span class="me-2 small text-muted">Select:</span>
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-secondary select-leads" data-count="20">20</button>
                <button type="button" class="btn btn-outline-secondary select-leads" data-count="50">50</button>
                <button type="button" class="btn btn-outline-secondary select-leads" data-count="80">80</button>
                <button type="button" class="btn btn-outline-secondary select-leads" data-count="100">100</button>
                <button type="button" class="btn btn-outline-secondary select-leads" data-count="150">150</button>
                <button type="button" class="btn btn-outline-secondary select-leads" data-count="200">200</button>
                <button type="button" class="btn btn-outline-primary select-leads" data-count="all">All</button>
            </div>
        </div>
    </div>

    <table class="table table-sm table-striped table-hover align-middle" style="font-size: 13px;">
        <thead class="table-dark text-center">
            <tr>
                <th width="50"><input type="checkbox" id="selectAll"></th>
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
                    <td class="text-center">
                        <input type="checkbox" name="lead_ids[]" value="{{ $lead->id }}" class="lead-checkbox">
                    </td>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <a href="{{ url('/i-admin/leads/view/'.$lead->id) }}">{{ $lead->first_name }} {{ $lead->last_name }}</a>
                    </td>
                    <td>{{ $lead->email }}</td>
                    <td><a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a></td>
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
        let allCheckboxes = document.querySelectorAll('.lead-checkbox');
        const transferBtn = document.getElementById('transferBtn');
        const transferForm = document.getElementById('transferForm');
        const targetAgentSelect = document.getElementById('target_agent_id');
        
        console.log('Elements found:', {
            selectAll: !!selectAll,
            checkboxes: allCheckboxes.length,
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
            const isChecked = this.checked;
            
            try {
                // Get fresh list of checkboxes in case of dynamic content
                const freshCheckboxes = document.querySelectorAll('.lead-checkbox');
                console.log('Found', freshCheckboxes.length, 'checkboxes to update');
                
                // First update all checkboxes directly
                freshCheckboxes.forEach(checkbox => {
                    if (checkbox.checked !== isChecked) {
                        checkbox.checked = isChecked;
                        // Trigger change event on the checkbox
                        const event = new Event('change', { bubbles: true });
                        checkbox.dispatchEvent(event);
                    }
                });
                
                // Then update the UI state
                updateTransferButton();
            } catch (error) {
                console.error('Error in select all:', error);
            }
        });
        
        // Update select all checkbox when individual checkboxes are toggled
        function setupCheckboxListeners() {
            const checkboxes = document.querySelectorAll('.lead-checkbox');
            
            checkboxes.forEach(checkbox => {
                // Remove existing listeners to prevent duplicates
                const newCheckbox = checkbox.cloneNode(true);
                checkbox.parentNode.replaceChild(newCheckbox, checkbox);
                
                newCheckbox.addEventListener('change', function() {
                    console.log('Checkbox changed:', this.checked, this.value);
                    // Get fresh list of all checkboxes
                    const allCheckboxes = document.querySelectorAll('.lead-checkbox');
                    
                    if (!this.checked) {
                        selectAll.checked = false;
                    } else {
                        // Check if all checkboxes are checked
                        const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
                        selectAll.checked = allChecked;
                    }
                    updateTransferButton();
                });
                
                // Add click event as well to ensure it works
                newCheckbox.addEventListener('click', function(e) {
                    // Small timeout to let the checkbox state update
                    setTimeout(updateTransferButton, 10);
                });
            });
            
            return document.querySelectorAll('.lead-checkbox');
        }
        
        // Initial setup of checkbox listeners
        allCheckboxes = setupCheckboxListeners();
        
        // Initial update of the transfer button
        console.log('Initial update of transfer button');
        updateTransferButton();
        
        // Force update checkboxes after a short delay to ensure DOM is ready
        // Handle select buttons
        document.querySelectorAll('.select-leads').forEach(button => {
            button.addEventListener('click', function() {
                const count = this.getAttribute('data-count');
                const checkboxes = document.querySelectorAll('.lead-checkbox');
                
                if (count === 'all') {
                    // Select all checkboxes
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = true;
                    });
                } else {
                    // Select specific number of checkboxes
                    const max = parseInt(count);
                    checkboxes.forEach((checkbox, index) => {
                        checkbox.checked = index < max;
                    });
                }
                
                // Update the select all checkbox state
                updateSelectAllCheckbox();
                // Update transfer button state
                updateTransferButton();
            });
        });

        // Function to update select all checkbox state
        function updateSelectAllCheckbox() {
            const checkboxes = document.querySelectorAll('.lead-checkbox');
            const checkedCount = document.querySelectorAll('.lead-checkbox:checked').length;
            document.getElementById('selectAll').checked = checkedCount === checkboxes.length && checkboxes.length > 0;
            document.getElementById('selectAll').indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
        }

        // Function to update transfer button state
        function updateTransferButton() {
            const checkedCount = document.querySelectorAll('.lead-checkbox:checked').length;
            document.getElementById('transferBtn').disabled = checkedCount === 0;
        }

        setTimeout(() => {
            allCheckboxes = document.querySelectorAll('.lead-checkbox');
            console.log('Updated checkboxes after delay:', allCheckboxes.length);
            
            // Update select all state after checkboxes are loaded
            updateSelectAllCheckbox();
            updateTransferButton();
        }, 500);
        
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
        testButton.className = 'btn btn-warning btn-sm d-none';
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
