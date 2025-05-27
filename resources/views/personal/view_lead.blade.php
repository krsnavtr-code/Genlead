@extends('main')

@section('title', 'Lead Details')

@section('content')

<style>
    /* Adjust the layout for the left and right sections */
    .lead-details-container {
        display: flex;
        flex-direction: column;
        margin-top: 0px;
    }

    /* Left side (Lead Properties) */
    .lead-left {
        width: 100%;
        background-color: #ffffff;
        padding: 20px;
        border-right: 1px solid #ddd;
        box-sizing: border-box;
    }

    /* Lead Header (Top Section) */
    .lead-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .lead-header .avatar {
        background-color: #ff914d;
        color: white;
        width: 60px;
        height: 60px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 24px;
        border-radius: 50%;
        margin-right: 20px;
    }

    .lead-header .lead-info {
        line-height: 1.2;
    }

    .lead-header .lead-info h2 {
        margin: 0;
    color: #333;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    font-size: 20px;
    font-weight: 600;
    line-height: 28px;
    margin-bottom: 5px;
    }

    .lead-header .lead-info p {
        margin: 0;
        font-size: 14px;
        color: #666;
        margin-bottom: 5px;
    }

    /* Lead Properties (Lower Section) */
    .lead-properties {
        margin-top: 20px;
    }

    .lead-properties h4 {
        font-size: 18px;
        margin-bottom: 20px;
        color: #333;
    }

    .lead-properties p {
        margin-bottom: 10px;
        font-size: 14px;
        color: #555;
    }

    .lead-properties p strong {
        font-weight: bold;
        color: #333;
    }

    /* Right side (Tabs and Content) */
    .lead-right {
        width: 100%;
        margin-bottom: 100px;
        padding: 5px;
        box-sizing: border-box;
    }

    .lead-right h3 {
        font-size: 20px;
        margin-bottom: 20px;
    }

    /* Tab Styles */
    .tabs {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 20px;
        border-bottom: 2px solid #ddd;
    }

    .tabs span {
        padding: 4px 8px;
        margin: 5px 10px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        background-color:var(--logo-color);
        color: white;
        border-radius: 5px;
    }

    .active-tab {
        border-bottom: 3px solid var(--primary-color);
        font-weight: bold;
    }

    .tab-content {
        display: none;
        
    }

    .active-content {
        display: block;
    }

    .tab-content h4 {
        font-size: 18px;
        margin-bottom: 15px;
    }
    /* input[type="datetime-local"] {
        width: 100%;
        max-width: 200px;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    } */

    .lead-section {
        margin-bottom: 20px;
    }

    .lead-section h4 {
        font-weight: bold;
    }

    .lead-section {
        display: flex;
        justify-content: space-between;
    }

    .lead-section p {
        margin-bottom: 10px;
    }

    .lead-section {
        border: 1px solid #ddd;
        padding: 20px;
        margin-bottom: 20px;
        background-color: #ffffff;
        position: relative;
        width: 130%;
        box-sizing: border-box;
    }

   .lead-section h4 {
    margin-bottom: 20px;
    cursor: pointer;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    font-size: 16px;
    line-height: 20px;
    color: #181818;
    font-weight: 600;
    text-overflow: ellipsis;
    overflow: hidden;
    }

    /* .lead-info-box {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    } */

    /* Modal Styles */
    .modal {
        display: none;
        /* position: fixed; */
        /* top: 0; */
        /* right: -100%; */
        /* margin-top: 400px; */
        /* width: 30%; */
        height: 100%;
        background-color: transparent;
        border-left: 1px solid #ddd;
        box-shadow: -2px 0 5px rgba(0, 0, 0, 0.2);
        /* padding: 20px; */
        overflow-y: auto;
        transition: right 0.3s ease;
    }

    .modal.show {
        /* position: absolute; */
        /* left: 0; */
        display: block;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
    }

    .lead-info-box p {
        margin-bottom: 5px;
        font-size: 14px;
        color: #555;
    }

    .lead-info-box strong {
        font-weight: bold;
        color: #333;
    }

    .toggle-button {
        position: absolute;
        top: 20px;
        Left: 5px;
        cursor: pointer;
        font-size: 18px;
    }

    .collapsible-content {
        display: none;
    }

    .visible {
        display: block;
    }

    .conversation-box {
        border: 1px solid #ddd;
        padding: 10px;
        margin-bottom: 10px;
        background-color: #f9f9f9;
    }

    .btn-secondary {
    padding: 5px 10px;
    font-size: 14px;
  }

  .fAaWVZ {
    font-size: 16px;
    font-weight: 400;
    line-height: 17px;
    color: rgb(var(--marvin-tertiary-text));
    flex-basis: 37%;
    overflow: hidden;
    text-overflow: ellipsis;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    word-break: break-word;
    text-align: start;
    color: #637381;
    margin-bottom: 5px;
}

.gpZObK {
    margin-left: 5px;
    font-size: 15px;
    font-weight: 400;
    line-height: 16px;
    color: #212b36;
    flex-basis: 59%;
    word-break: break-all;
    overflow: hidden;
}

.lead-properties div{
margin-bottom: 10px;
}
.eWUmU {
    color: #919eab;
    font-size: 15px;
    margin-inline-end: 10px;
}

.lagyBr {
    color:#212b36;
    font-size: 15px;
   font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    font-weight: 400;
    line-height: 1.5;
    
}

.conversation-box div{
    margin-bottom: 10px;
}




.payment-card {
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 16px;
        background-color: #ffffff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: transform 0.2s ease;
    }

    .payment-card:hover {
        transform: translateY(-3px);
    }

    .payment-label {
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 4px;
    }

    .payment-value {
        font-size: 1rem;
        margin-bottom: 8px;
    }

    .summary-card {
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
</style>

<div class="lead-details-container">
    <!-- Left Sidebar with Lead Information -->
    <div class="lead-left">
        <!-- Lead Header -->
        <div class="lead-header">
    <!-- Back Button -->
    <a href="/i-admin/show-leads" class="btn btn-secondary" style="margin-right: 15px; margin-left: -33px; margin-top:-67px;">Back</a>

            <div class="avatar">
                {{ strtoupper(substr($lead->first_name, 0, 1)) }}{{ strtoupper(substr($lead->last_name, 0, 1)) }}
            </div>
            <div class="lead-info">
                <h2>{{ $lead->first_name }} {{ $lead->last_name }}</h2>
                <p style="color: #722ed1;">{{ $lead->email }}</p>
                <p>{{ $lead->phone }}</p>
            </div>
        </div>

        <!-- <div class="lead-properties">
            <h4>Lead Properties:</h4>
            <div><span class="fAaWVZ">Lead Age:</span><span class="gpZObK">{{ intval(\Carbon\Carbon::parse($lead->created_at)->diffInDays(now())) }} Days</span></div>
            <div><span class="fAaWVZ">Lead Source:</span><span class="gpZObK">{{ $lead->lead_source }}</span></div>
            {{-- <div><span class="fAaWVZ">Lead Status:</span><span class="gpZObK"> {{ ucfirst($lead->lead_status) }}</span></div> --}}
            <div><span class="fAaWVZ">University:</span><span class="gpZObK">{{ $lead->university ?? '-' }}</span></div>
            <div><span class="fAaWVZ">Course:</span><span class="gpZObK">{{ $lead->courses ?? '-' }}</span></div>
        </div> -->
    </div>

    <!-- Right Content Area with Tabs and Content -->
    <div class="lead-right">
        <div class="tabs">
            <span class="active-tab" data-tab="lead-details">Lead Details</span>
            <!-- <span data-tab="follow-up">Follow-Up</span> -->
            <span data-tab="conversations">Conversations</span>
            <span data-tab="payment-history">Payment History</span>
        </div>

        <!-- Tab Contents -->
        <div id="lead-details" class="tab-content active-content">
            <h4>Lead Details: </h4>

         <!-- Lead Information Section -->   
    {{-- <div class="lead-section"> 
        <h4 onclick="toggleSection('leadInfo', 'iconLead')">Lead Information:</h4>
        <div id="iconLead" class="toggle-button" onclick="toggleSection('leadInfo', 'iconLead')">^</div>

        <!-- Edit Button -->
      <!-- <button class="btn btn-warning btn-sm" style="position: absolute; right: 10px; top: 10px;" onclick="openModal()">Edit</button> -->

        <div id="leadInfo" class="collapsible-content visible">
            <div class="lead-info-box">
                <!--  <div><span class="eWUmU">Lead Owner:</span><span class="lagyBr">{{ $lead->owner }}</span></div> -->
                <!--  <div><span class="eWUmU">Company:</span><span class="lagyBr">{{ $lead->company }}</span></div> -->
                <div><span class="eWUmU">Lead Source:</span><span class="lagyBr"> {{ $lead->lead_source }}</span> </div>
                <!-- <div><span class="eWUmU">Lead Status:</span><span class="lagyBr">{{ ucfirst($lead->lead_status) }}</span></div> -->
            </div>
            <div class="lead-info-box">
            <div><span class="eWUmU">University:</span><span class="lagyBr">{{ ucfirst($lead->university) }}</span></div>
            <div><span class="eWUmU">Course:</span><span class="lagyBr">{{ ucfirst($lead->courses) }}</span></div>
            <div><span class="eWUmU">College:</span><span class="lagyBr">{{ ucfirst($lead->college) }}</span></div>
            <div><span class="eWUmU">Branch:</span><span class="lagyBr">{{ ucfirst($lead->branch) }}</span></div>
        </div>
        <div class="lead-info-box">
            <div><span class="eWUmU">Email:</span><span class="lagyBr">{{ $lead->email }}</span></div>
            <div><span class="eWUmU">Phone:</span><span class="lagyBr">{{ $lead->phone }}</span></div>
        </div>
        </div>
    </div> --}}

    <div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h5 class="mb-0" style="cursor: pointer;" onclick="toggleSection('leadInfo', 'iconLead')">
                Lead Information
            </h5>
            <span id="iconLead" onclick="toggleSection('leadInfo', 'iconLead')" style="cursor: pointer;">&#9650;</span>
        </div>

        <!-- <button class="btn btn-warning btn-sm position-absolute top-0 end-0 m-2" onclick="openModal()">Edit</button> -->

        <div id="leadInfo" class="card-body">
            <div class="row mb-3">
                <div class="col-md-6 mb-2">
                    <strong>Lead Source:</strong> {{ $lead->lead_source }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>University:</strong> {{ ucfirst($lead->university) }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Course:</strong> {{ ucfirst($lead->courses) }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>College:</strong> {{ ucfirst($lead->college) }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Branch:</strong> {{ ucfirst($lead->branch) }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Email:</strong> {{ $lead->email }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Phone:</strong> {{ $lead->phone }}
                </div>
            </div>
        </div>
    </div>
</div>

    
    <!-- Payment Button -->
<div style="text-align: left; margin-top: 20px;">
    <a href="{{ route('payment.page', ['leadId' => $lead->id]) }}" class="btn btn-primary">Proceed to Payment Page</a>
</div>

</div>

        <div id="follow-up" class="tab-content">
            <h4>Follow-Up</h4>
            <!-- Form for adding follow-up -->
            <form action="{{ route('follow-ups.store') }}" method="POST">
                @csrf
                <input type="hidden" name="lead_id" value="{{ $lead->id }}">

                <textarea name="comments" class="form-control" placeholder="Add follow-up comments" required></textarea>
                <label for="follow_up_time" style="font-weight: 500">Schedule Follow-Up Time:</label>
                <input type="datetime-local" name="follow_up_time" class="form-control" required>
                <button type="submit" class="btn btn-primary mt-2">Submit Follow-Up</button>
            </form>
        </div>

        <!-- <div id="conversations" class="tab-content">
            <h4>Recent Conversations</h4>
            @foreach($lead->followUps as $followUp)
            <div class="conversation-box">
                {{-- <p><strong>Agent:</strong> {{ $followUp->agent->name }}</p> --}}
                <div><span class="eWUmU rc">Date:</span> <span  class="lagyBr">{{ $followUp->created_at->format('d M Y, H:i') }}</span></div>
                <div><span class="eWUmU">Comments:</span> <span  class="lagyBr">{{ $followUp->comments }}</span></div>
                <div><span class="eWUmU">Follow-up Time:</span> <span  class="lagyBr">{{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('d M Y, H:i') }}</span></div>
            </div>
            @endforeach
        </div> -->
        <div id="conversations" class="tab-content mt-4">
            <h4 class="mb-3 text-primary">Recent Conversations</h4>

            @foreach($lead->followUps->sortByDesc('created_at') as $followUp)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    {{-- <p class="mb-2"><strong>Agent:</strong> {{ $followUp->agent->name }}</p> --}}
            
            <p class="mb-1">
                <span class="fw-bold text-secondary">Date:</span>
                <span class="text-dark">{{ $followUp->created_at->format('d M Y, H:i') }}</span>
            </p>
            
            <p class="mb-1">
                <span class="fw-bold text-secondary">Comments:</span>
                <span class="text-dark">{{ $followUp->comments }}</span>
            </p>
            
            <p class="mb-0">
                <span class="fw-bold text-secondary">Follow-up Time:</span>
                <span class="text-dark">{{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('d M Y, H:i') }}</span>
            </p>
        </div>
    </div>
    @endforeach
</div>

        <!-- Payment History Tab -->
        <div id="payment-history" class="tab-content">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h4 class="mb-2 mb-md-0">Payment History</h4>
        <a href="{{ route('payment.page', ['leadId' => $lead->id]) }}" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> Add Payment
        </a>
    </div>

    @if($lead->payments->count() > 0)
        <div class="row g-3">
            @foreach($lead->payments as $payment)
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="payment-card h-100">
                        <div class="payment-label">Date</div>
                        <div class="payment-value">{{ $payment->created_at->format('d M Y') }}</div>

                        <div class="payment-label">Amount</div>
                        <div class="payment-value text-success">₹{{ number_format($payment->payment_amount, 2) }}</div>

                        <div class="payment-label">Payment Mode</div>
                        <div class="payment-value">{{ ucfirst($payment->payment_mode) }}</div>

                        <div class="payment-label">UTR No</div>
                        <div class="payment-value">{{ $payment->utr_no }}</div>

                        <div class="payment-label">Status</div>
                        <div class="payment-value">
                            @if($payment->payment_verify)
                                <span class="badge bg-success">Verified</span>
                            @else
                                <span class="badge bg-warning">Pending Verification</span>
                            @endif
                        </div>

                        <!-- <div class="mt-2">
                            <button class="btn btn-sm btn-outline-primary w-100" onclick="viewPaymentDetails({{ $payment->id }})">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </div> -->
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Payment Summary -->
        <div class="row mt-4">
            <div class="col-12 col-md-4">
                <div class="card summary-card bg-light">
                    <div class="card-body">
                        <h6 class="text-muted">Total Paid</h6>
                        <h5 class="text-success">₹{{ number_format($lead->payments->sum('payment_amount'), 2) }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card summary-card bg-light">
                    <div class="card-body">
                        <h6 class="text-muted">Last Payment</h6>
                        <h5>
                            ₹{{ number_format($lead->payments->sortByDesc('created_at')->first()->payment_amount, 2) }}
                            <small class="d-block text-muted">
                                {{ $lead->payments->sortByDesc('created_at')->first()->created_at->format('d M Y') }}
                            </small>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card summary-card bg-light">
                    <div class="card-body">
                        <h6 class="text-muted">Pending Amount</h6>
                        @php
                            $totalPaid = $lead->payments->sum('payment_amount');
                            $latestPayment = $lead->payments->sortByDesc('created_at')->first();
                            $totalAmount = $latestPayment ? $latestPayment->total_amount : 0;
                            $pendingAmount = max($totalAmount - $totalPaid, 0);
                        @endphp
                        <h5>
                            @if($pendingAmount <= 0)
                                <span class="text-success">Fully Paid</span>
                            @else
                                ₹{{ number_format($pendingAmount, 2) }} <small class="text-muted">(Pending)</small>
                            @endif
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info mt-3">
            No payment records found for this lead.
        </div>
    @endif
</div>

    </div>
</div>

{{-- <!-- Payment Details Modal -->
<div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="paymentDetailsContent">
                <!-- Payment details will be loaded here via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> --}}

<!-- Modal for Edit Form -->
<div id="editModal" class="modal">
    <div class="modal-header">
        <h4>Edit Lead</h4>
        <button class="close-btn" onclick="closeModal()">&times;</button>
    </div>
    <form method="POST" action="">
        @csrf
        <div class="lead-info-box">
            <div>
                <label for="owner">Lead Owner:</label>
                <input type="text" id="owner" name="owner" class="form-control" value="{{ $lead->owner }}">
            </div>
            <div>
                <label for="lead_source">Lead Source:</label>
                <input type="text" id="lead_source" name="lead_source" class="form-control" value="{{ $lead->lead_source }}">
            </div>
        </div>
        <div class="lead-info-box">
            <div>
                <label for="university">University:</label>
                <input type="text" id="university" name="university" class="form-control" value="{{ $lead->university }}">
            </div>
            <div>
                <label for="courses">Course:</label>
                <input type="text" id="courses" name="courses" class="form-control" value="{{ $lead->courses }}">
            </div>
            <div>
                <label for="college">College:</label>
                <input type="text" id="college" name="college" class="form-control" value="{{ $lead->college }}">
            </div>
            <div>
                <label for="branch">Branch:</label>
                <input type="text" id="branch" name="branch" class="form-control" value="{{ $lead->branch }}">
            </div>
        </div>
        <div class="lead-info-box">
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ $lead->email }}">
            </div>
            <div>
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ $lead->phone }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Update</button>
    </form>
</div>

<script>
// Function to view payment details
// function viewPaymentDetails(paymentId) {
//     fetch(`/api/payments/${paymentId}`)
//         .then(response => response.json())
//         .then(data => {
//             const modal = new bootstrap.Modal(document.getElementById('paymentDetailsModal'));
//             const content = document.getElementById('paymentDetailsContent');
            
//             // Format the payment details
//             const details = `
//                 <div class="row">
//                     <div class="col-md-6">
//                         <p><strong>Payment Date:</strong> ${new Date(data.created_at).toLocaleDateString()}</p>
//                         <p><strong>Amount:</strong> ₹${parseFloat(data.payment_amount).toLocaleString('en-IN', {minimumFractionDigits: 2})}</p>
//                         <p><strong>Payment Mode:</strong> ${data.payment_mode.charAt(0).toUpperCase() + data.payment_mode.slice(1)}</p>
//                         <p><strong>UTR Number:</strong> ${data.utr_no}</p>
//                     </div>
//                     <div class="col-md-6">
//                         <p><strong>Session Duration:</strong> ${data.session_duration}</p>
//                         <p><strong>Session Type:</strong> ${data.session === 'semester' ? 'Semester Fee' : data.session === 'year' ? 'Yearly Fee' : 'Full Course'}</p>
//                         <div class="payment-value">
//                             ${data.payment_verify 
//                                 ? '<span class="badge bg-success">Verified</span>' 
//                                 : '<span class="badge bg-warning">Pending Verification</span>'}
//                         </div>
//                     </div>
//                 </div>
//                 ${data.payment_screenshot ? `
//                     <div class="mt-3">
//                         <h6>Payment Screenshot:</h6>
//                         <img src="/${data.payment_screenshot}" alt="Payment Screenshot" class="img-fluid">
//                     </div>
//                 ` : ''}
//                 ${data.payment_details_input ? `
//                     <div class="mt-3">
//                         <h6>Payment Details:</h6>
//                         <p>${data.payment_details_input}</p>
//                     </div>
//                 ` : ''}
//             `;
            
//             content.innerHTML = details;
//             modal.show();
//         })
//         .catch(error => {
//             console.error('Error fetching payment details:', error);
//             alert('Error loading payment details. Please try again.');
//         });
// }

// Initialize Bootstrap tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});

function openModal() {
        document.getElementById('editModal').classList.add('show');
    }

    function closeModal() {
        document.getElementById('editModal').classList.remove('show');
    }

    // JavaScript for switching tabs
    document.querySelectorAll('.tabs span').forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs and content
            document.querySelectorAll('.tabs span').forEach(t => t.classList.remove('active-tab'));
            document.querySelectorAll('.tab-content').forEach(tc => tc.classList.remove('active-content'));

            // Add active class to clicked tab and corresponding content
            this.classList.add('active-tab');
            document.getElementById(this.getAttribute('data-tab')).classList.add('active-content');
        });
    });

    function toggleSection(sectionId, iconId) {
        const section = document.getElementById(sectionId);
        const icon = document.getElementById(iconId);

        // Toggle visibility of the section
        section.classList.toggle('visible');

        // Change the icon based on the visibility of the section
        if (section.classList.contains('visible')) {
            icon.innerHTML = '^'; // Show the up arrow
        } else {
            icon.innerHTML = 'v'; // Show the down arrow
        }
    }
    
    // JavaScript for toggling visibility of sections
    function toggleSection(sectionId, iconId) {
        const section = document.getElementById(sectionId);
        const icon = document.getElementById(iconId);
        if (section.style.display === 'none' || section.classList.contains('d-none')) {
            section.style.display = 'block';
            icon.innerHTML = '&#9650;'; // Up arrow
        } else {
            section.style.display = 'none';
            icon.innerHTML = '&#9660;'; // Down arrow
        }
    }

    // Function to view payment details
    function viewPaymentDetails(paymentId) {
        // Show loading state
        $('#paymentDetailsContent').html('<div class="text-center my-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        
        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('paymentDetailsModal'));
        modal.show();
        
        // Fetch payment details via AJAX
        $.ajax({
            url: '/i-admin/api/payment/' + paymentId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const payment = response.data;
                    const paymentDate = new Date(payment.created_at).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    
                    // Format the payment amount
                    const paymentAmount = parseFloat(payment.payment_amount).toFixed(2);
                    
                    // Create the HTML content
                    let html = `
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Payment Date:</strong> ${paymentDate}</p>
                                <p><strong>Amount:</strong> ₹${paymentAmount}</p>
                                <p><strong>Payment Mode:</strong> ${payment.payment_mode || 'N/A'}</p>
                                <p><strong>UTR Number:</strong> ${payment.utr_no || 'N/A'}</p>
                                <p><strong>Status:</strong> ${payment.payment_verify ? '<span class="badge bg-success">Verified</span>' : '<span class="badge bg-warning">Pending Verification</span>'}</p>
                                <p><strong>Notes:</strong> ${payment.notes || 'N/A'}</p>
                            </div>
                            <div class="col-md-6">
                    `;
                    
                    // Add payment screenshot if available
                    if (payment.payment_screenshot) {
                        html += `
                            <p><strong>Payment Screenshot:</strong></p>
                            <img src="{{ asset('storage') }}/${payment.payment_screenshot}" alt="Payment Screenshot" class="img-fluid rounded">
                        `;
                    } else {
                        html += '<p class="text-muted">No screenshot available</p>';
                    }
                    
                    html += '</div></div>';
                    
                    // Update modal content
                    $('#paymentDetailsContent').html(html);
                } else {
                    $('#paymentDetailsContent').html(`
                        <div class="alert alert-danger">
                            Failed to load payment details: ${response.message || 'Unknown error occurred'}
                        </div>
                    `);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching payment details:', error);
                $('#paymentDetailsContent').html(`
                    <div class="alert alert-danger">
                        An error occurred while fetching payment details. Please try again later.
                    </div>
                `);
            }
        });
    }

    // flatpickr("#followUpTime", {
    //     enableTime: true,
    //     dateFormat: "Y-m-d H:i",
    //     minDate: "today"
    // });

</script>

@endsection
