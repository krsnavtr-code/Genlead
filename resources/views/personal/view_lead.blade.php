@extends('main')

@section('title', 'Lead Details')

@section('content')

<style>
    /* Adjust the layout for the left and right sections */
    .lead-details-container {
        display: flex;
        margin-top: 0px;
        margin-left: 254px;
        margin-right: 315px;
        max-width: 70%;  /* Ensure the container takes full width */
        width: 70%;
    }

    /* Left side (Lead Properties) */
    .lead-left {
        width: 35%;
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
        width: 65%;
        padding: 20px;
        box-sizing: border-box;
    }

    .lead-right h3 {
        font-size: 20px;
        margin-bottom: 20px;
    }

    /* Tab Styles */
    .tabs {
        display: flex;
        margin-bottom: 20px;
        border-bottom: 2px solid #ddd;
    }

    .tabs span {
        padding: 10px 20px;
        margin-right: 20px;
        cursor: pointer;
    }

    .active-tab {
        border-bottom: 3px solid #007bff;
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

    .lead-section {
        margin-bottom: 20px;
    }

    .lead-section h4 {
        font-weight: bold;
    }

    .lead-section .lead-info-box {
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

    .lead-info-box {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        right: -100%;
        width: 30%;
        height: 100%;
        background-color: #fff;
        border-left: 1px solid #ddd;
        box-shadow: -2px 0 5px rgba(0, 0, 0, 0.2);
        padding: 20px;
        overflow-y: auto;
        transition: right 0.3s ease;
    }

    .modal.show {
        position: absolute;
        left: 0;
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

        <div class="lead-properties">
            <h4>Lead Properties:</h4>
            <div><span class="fAaWVZ">Lead Age:</span><span class="gpZObK">{{ intval(\Carbon\Carbon::parse($lead->created_at)->diffInDays(now())) }} Days</span></div>
            <div><span class="fAaWVZ">Lead Source:</span><span class="gpZObK">{{ $lead->lead_source }}</span></div>
            {{-- <div><span class="fAaWVZ">Lead Status:</span><span class="gpZObK"> {{ ucfirst($lead->lead_status) }}</span></div> --}}
            <div><span class="fAaWVZ">University:</span><span class="gpZObK">{{ $lead->university ?? '-' }}</span></div>
            <div><span class="fAaWVZ">Course:</span><span class="gpZObK">{{ $lead->courses ?? '-' }}</span></div>
    </div>
    </div>

    <!-- Right Content Area with Tabs and Content -->
    <div class="lead-right">
        <div class="tabs">
            <span class="active-tab" data-tab="overview">Overview</span>
            <span data-tab="follow-up">Follow-Up</span>
            <span data-tab="conversations">Conversations</span>
        </div>

        <!-- Tab Contents -->
        <div id="overview" class="tab-content active-content">
            <h4>Lead Details: </h4>

         <!-- Lead Information Section -->   
      <div class="lead-section">
        <h4 onclick="toggleSection('leadInfo', 'iconLead')">Lead Information:</h4>
        <div id="iconLead" class="toggle-button" onclick="toggleSection('leadInfo', 'iconLead')">^</div>

        <!-- Edit Button -->
      {{-- <button class="btn btn-warning btn-sm" style="position: absolute; right: 10px; top: 10px;" onclick="openModal()">Edit</button> --}}

        <div id="leadInfo" class="collapsible-content visible">
            <div class="lead-info-box">
                {{-- <div><span class="eWUmU">Lead Owner:</span><span class="lagyBr">{{ $lead->owner }}</span></div> --}}
                {{-- <div><span class="eWUmU">Company:</span><span class="lagyBr">{{ $lead->company }}</span></div> --}}
                <div><span class="eWUmU">Lead Source:</span><span class="lagyBr"> {{ $lead->lead_source }}</span> </div>
                {{-- <div><span class="eWUmU">Lead Status:</span><span class="lagyBr">{{ ucfirst($lead->lead_status) }}</span></div> --}}
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

        <div id="conversations" class="tab-content">
            <h4>Recent Conversations</h4>
            @foreach($lead->followUps as $followUp)
            <div class="conversation-box">
                {{-- <p><strong>Agent:</strong> {{ $followUp->agent->name }}</p> --}}
                <div><span class="eWUmU rc">Date:</span> <span  class="lagyBr">{{ $followUp->created_at->format('d M Y, H:i') }}</span></div>
                <div><span class="eWUmU">Comments:</span> <span  class="lagyBr">{{ $followUp->comments }}</span></div>
                <div><span class="eWUmU">Follow-up Time:</span> <span  class="lagyBr">{{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('d M Y, H:i') }}</span></div>
            </div>
        @endforeach
        </div>
    </div>
</div>


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
</script>

@endsection
