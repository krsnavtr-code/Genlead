@extends('main')

@section('title', 'Team Member Leads')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lead Details for {{ $agent->emp_name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.team.management') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-arrow-left"></i> Back to Team Management
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-user"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Agent Name</span>
                                    <span class="info-box-number">{{ $agent->emp_name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-phone"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Phone</span>
                                    <span class="info-box-number">{{ $agent->emp_phone }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-chart-line"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Leads</span>
                                    <span class="info-box-number">{{ $leads->total() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-4">
                                    <h3 class="card-title">Leads</h3>
                                </div>
                                <div class="col-8">
                                    <form action="{{ request()->url() }}" method="GET" class="">
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control" placeholder="Search leads by name, email, phone or status" value="{{ request('search') }}">
                                            @if(request('search'))
                                                <a href="{{ request()->url() }}" class="btn btn-outline-secondary" type="button">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            @endif
                                            <button class="btn btn-outline-primary" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>                                            
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap table-striped table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <!-- <th>Email</th> -->
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Follow-ups</th>
                                        <!-- <th>Created At</th> -->
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="leadsTable">
                                    @php
                                        $serialNumber = ($leads->currentPage() - 1) * $leads->perPage() + 1;
                                    @endphp
                                    @forelse($leads as $lead)
                                        <tr data-lead-id="{{ $lead->id }}" 
                                            data-first-name="{{ strtolower($lead->first_name) }}" 
                                            data-last-name="{{ strtolower($lead->last_name) }}"
                                            data-email="{{ strtolower($lead->email) }}"
                                            data-phone="{{ $lead->phone }}"
                                            data-status-text="{{ is_object($lead->status) && isset($lead->status->name) ? strtolower($lead->status->name) : strtolower($lead->status ?? 'not talked') }}">
                                            <td>{{ $serialNumber++ }}</td>
                                            <td class="name-column">{{ $lead->first_name }} {{ $lead->last_name }}</td>
                                            <!-- <td class="email-column">
                                                {{ substr($lead->email, 0, 3) . '*****' . substr($lead->email, strpos($lead->email, '@') - 3) }}
                                            </td> -->
                                            <td class="phone-column">{{ substr($lead->phone, 0, 2) . '***' . substr($lead->phone, -2) }}</td>
                                            <td class="status-column">
                                                @if(is_object($lead->status) && isset($lead->status->name))
                                                    {{ $lead->status->name }}
                                                @else
                                                    {{ $lead->status ?? 'Not Talked' }}
                                                @endif
                                            </td>
                                            <td>{{ $lead->total_followups ?? 0 }}</td>
                                            <!-- <td>{{ $lead->created_at->format('M d, Y h:i A') }}</td> -->
                                            <td>
                                                <a href="{{ route('admin.team.member.lead-details', $lead->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No leads found for this agent.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            
                            @if($leads->isEmpty() && request('search'))
                            <div class="alert alert-info m-3">
                                No results found for "{{ request('search') }}"
                            </div>
                            @endif
                        </div>
                        <div class="card-footer clearfix">
                            {{ $leads->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .info-box {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        border-radius: .25rem;
        background: #fff;
        display: -ms-flexbox;
        display: flex;
        margin-bottom: 1rem;
        min-height: 80px;
        padding: .5rem;
        position: relative;
    }
    .info-box .info-box-icon {
        border-radius: .25rem;
        -ms-flex-align: center;
        align-items: center;
        display: -ms-flexbox;
        display: flex;
        font-size: 1.875rem;
        -ms-flex-pack: center;
        justify-content: center;
        text-align: center;
        width: 70px;
    }
    .info-box .info-box-content {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        -ms-flex-pack: center;
        justify-content: center;
        line-height: 1.8;
        -ms-flex: 1;
        flex: 1;
        padding: 0 10px;
    }
    .info-box .info-box-text {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .info-box .info-box-number {
        display: block;
        margin-top: .25rem;
        font-weight: 700;
    }
    
    /* Table styling */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Button styling */
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.765625rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }
    
    .btn i {
        margin-right: 0.25rem;
    }
    
    /* Make sure the table cells don't wrap */
    .table td, .table th {
        white-space: nowrap;
    }
    
    /* Add some padding to the action buttons */
    .table td:last-child {
        padding: 0.5rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table {
            margin-bottom: 0;
        }
    }
    
    /* Modal overlay styles */
    .modal-content {
        position: relative;
    }
    
    .modal .overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        border-radius: 0.3rem;
    }
    
    .modal .overlay i {
        color: #007bff;
    }
    
    /* Button states */
    .btn:disabled {
        cursor: not-allowed;
        opacity: 0.65;
    }
    
    /* Make sure select2 fits in the modal */
    .select2-container {
        width: 100% !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: 'style',
        dropdownParent: $('#statusUpdateModal')
    });
    
    // Close Select2 dropdown when modal is closed
    $('#statusUpdateModal').on('hidden.bs.modal', function () {
        $('.select2-dropdown').remove();
    });
    // Debounce function to limit how often search executes
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Search functionality
    $('#searchBtn').click(function() {
        searchLeads();
    });
    
    // Toggle clear button visibility based on input
    function toggleClearButton() {
        const hasValue = $('#searchInput').val().trim() !== '';
        $('#clearSearch').toggle(hasValue);
    }
    
    // Clear search and focus input
    $('#clearSearch').click(function() {
        $('#searchInput').val('').trigger('input').focus();
        toggleClearButton();
    });
    
    // Handle input events
    $('#searchInput')
        .on('input', function() {
            toggleClearButton();
            // Don't trigger search here, let the debounced handler do it
        })
        .on('keyup', debounce(function(e) {
            if (e.which === 13) {
                e.preventDefault();
                searchLeads();
            } else {
                searchLeads();
            }
        }, 500));
    
    // Initialize search from URL if present
    const urlParams = new URLSearchParams(window.location.search);
    const searchParam = urlParams.get('search');
    if (searchParam) {
        $('#searchInput').val(searchParam);
        searchLeads();
    }
    
    // Initialize clear button state
    toggleClearButton();
    
    // Function to show/hide loading indicator
    function setSearchLoading(isLoading) {
        const $searchBtn = $('#searchBtn');
        if (isLoading) {
            $searchBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Searching...');
        } else {
            $searchBtn.prop('disabled', false).html('<i class="fas fa-search"></i> Search');
        }
    }
    
    // Function to perform the search
    function searchLeads() {
        const searchTerm = $('#searchInput').val().trim().toLowerCase();
        const $rows = $('tbody tr');
        let hasResults = false;
        
        // Show loading indicator
        setSearchLoading(true);
        
        if (searchTerm.length === 0) {
            // If search term is empty, show all rows except the "no leads" row
            $rows.each(function() {
                if ($(this).find('td:eq(0)').text().trim() !== '') {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            return;
        }
        
        // Search through rows
        $rows.each(function() {
            const $row = $(this);
            
            // Skip the "no leads found" row during search
            if ($row.find('td:eq(0)').text().trim() === '') {
                $row.hide();
                return;
            }
            
            const name = $row.find('td:eq(1)').text().toLowerCase();
            const email = $row.find('td:eq(2)').text().toLowerCase();
            const phone = $row.find('td:eq(3)').text().toLowerCase();
            
            // Check if any field contains the search term
            if (name.includes(searchTerm) || 
                email.includes(searchTerm) || 
                phone.includes(searchTerm)) {
                $row.show();
                hasResults = true;
            } else {
                $row.hide();
            }
        });
        
        // Show "no results" message if no rows are visible
        const $noResultsRow = $('tbody tr:contains("No leads found")');
        if (!hasResults) {
            $noResultsRow.show();
        } else {
            $noResultsRow.hide();
        }
        
        // Update URL with search query for refresh support
        const url = new URL(window.location);
        if (searchTerm) {
            url.searchParams.set('search', searchTerm);
        } else {
            url.searchParams.delete('search');
        }
        window.history.replaceState({}, '', url);
        
        // Hide loading indicator
        setSearchLoading(false);
    }

    // Status update functionality has been removed
});
</script>
@endpush
