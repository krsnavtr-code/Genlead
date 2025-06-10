@extends('main')

@section('title', 'Manage Leads Agent Network')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Agent Network</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Agent Network</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="manage-leads-nav my-3 d-flex">
                <a href="{{ url('/admin/agent-referral-leads-details') }}" class="btn btn-sm d-flex align-items-baseline gap-2 rounded" style="background-color: var(--logo-color); color: #fff;">
                    <i class="nav-icon fas fa-users mr-1" style="color: #fff;"></i>
                    My Leads Details
                </a>
                <a href="{{ route('admin.referr-agent-earning.index') }}" class="ml-2 btn btn-sm btn-success d-flex align-items-baseline gap-2 rounded">
                    <i class="fas fa-wallet mr-1"></i>
                    My Earnings
                </a>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-network-wired mr-1"></i>
                                {{ $isChainTeam ? 'Team Network' : 'Agent Network' }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-center align-items-center position-relative p-0">
                            @if(count($referralTree) > 0)
                                <div class="w-100 px-3 pt-3 d-flex justify-content-between align-items-center">
                                    <div class="orgchart-legend">
                                        <span class="badge bg-primary mr-2"><i class="fas fa-user-tie"></i> You</span>
                                        @if($isChainTeam)
                                            <span class="badge bg-success mr-2"><i class="fas fa-link"></i> Chain Team</span>
                                        @else
                                            <span class="badge bg-info mr-2"><i class="fas fa-user"></i> Agent</span>
                                        @endif
                                    </div>
                                    <div class="orgchart-ctrls">
                                        <button id="zoom-in" class="btn btn-sm btn-outline-secondary" title="Zoom In">
                                            <i class="fas fa-search-plus"></i>
                                        </button>
                                        <button id="zoom-out" class="btn btn-sm btn-outline-secondary" title="Zoom Out">
                                            <i class="fas fa-search-minus"></i>
                                        </button>
                                        <button id="zoom-reset" class="btn btn-sm btn-outline-secondary" title="Reset View">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="orgchart-container w-100">
                                    <div class="orgchart">
                                        @foreach($referralTree as $agent)
                                            {!! renderReferralTree($agent, $currentUser->id) !!}
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info w-100 m-4 text-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    No agents found in your network. Start by referring new agents to build your team!
                                </div>
                            @endif
                        </div>
                        @if(count($referralTree) > 0)
                            <div class="card-footer text-muted">
                                <small>
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Showing your complete referral network. Click on any agent to view more details.
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* Base styles for the org chart */
.orgchart-container {
    width: 100%;
    overflow: auto;
    padding: 20px 0;
    min-height: 500px;
}

.orgchart {
    display: flex;
    justify-content: center;
    transition: all 0.3s ease;
    padding: 20px 0;
}

.orgchart > ul {
    display: flex;
    justify-content: center;
    padding-top: 20px;
    position: relative;
    transition: all 0.3s ease;
}

.orgchart ul {
    display: flex;
    padding: 0;
    margin: 0;
    list-style: none;
}

.orgchart li {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    padding: 20px 5px 0 5px;
}

/* Node styles */
.agent-node, .chain-team-node {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    min-width: 200px;
    background: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    position: relative;
    z-index: 10;
}

.chain-team-node {
    border-left: 4px solid #28a745;
}

.agent-node {
    border-left: 4px solid #17a2b8;
}

.current-user {
    background-color: #f8f9fa;
    box-shadow: 0 0 0 2px #007bff;
}

/* Agent info styles */
.agent-name {
    font-weight: 600;
    margin-bottom: 8px;
    color: #343a40;
    display: flex;
    align-items: center;
}

.agent-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    margin-bottom: 10px;
}

.agent-info {
    font-size: 0.85rem;
    color: #6c757d;
}

.agent-referrals, .team-size {
    display: flex;
    align-items: center;
    margin-top: 5px;
}

/* Connector lines */
.orgchart li::before,
.orgchart li::after {
    content: '';
    position: absolute;
    top: 0;
    right: 50%;
    width: 50%;
    height: 20px;
    border-top: 2px solid #dee2e6;
}

.orgchart li::after {
    right: auto;
    left: 50%;
    border-left: 2px solid #dee2e6;
}

.orgchart > ul > li::before,
.orgchart > ul > li::after {
    border: 0 none;
}

.orgchart li:only-child::after,
.orgchart li:only-child::before {
    display: none;
}

.orgchart li:first-child::before,
.orgchart li:last-child::after {
    border: 0 none;
}

.orgchart li:first-child::after {
    border-radius: 0 0 0 5px;
}

.orgchart li:last-child::before {
    border-right: 2px solid #dee2e6;
    border-radius: 0 5px 0 0;
}

/* Controls */
.orgchart-ctrls {
    display: flex;
    gap: 5px;
    margin-bottom: 15px;
}

.orgchart-legend {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .orgchart {
        transform: scale(0.8);
        transform-origin: top center;
    }
    
    .orgchart li {
        padding: 20px 2px 0 2px;
    }
}

/* Zoom controls */
#zoom-in, #zoom-out, #zoom-reset {
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 5px 10px;
    cursor: pointer;
    transition: all 0.2s;
}

#zoom-in:hover, #zoom-out:hover, #zoom-reset:hover {
    background: #f8f9fa;
}

/* Level-based styling */
.level-1 { opacity: 0.95; }
.level-2 { opacity: 0.9; }
.level-3 { opacity: 0.85; }
.level-4 { opacity: 0.8; }
.level-5 { opacity: 0.75; }
.level-6 { opacity: 0.7; }
.level-7 { opacity: 0.65; }
.level-8 { opacity: 0.6; }
.level-9 { opacity: 0.55; }
.level-10 { opacity: 0.5; }

/* Hover effects */
.agent-node:hover, .chain-team-node:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    z-index: 20;
}

/* Badge styles */
.badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
    font-size: 0.75em;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.bg-primary { background-color: #007bff !important; }
.bg-success { background-color: #28a745 !important; }
.bg-info { background-color: #17a2b8 !important; }

/* Animation for loading */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.orgchart li {
    animation: fadeIn 0.3s ease-out forwards;
}

/* Ensure proper container sizing */
.orgchart-container {
    overflow-x: auto;
    max-width: 100%;
    -webkit-overflow-scrolling: touch;
}
.orgchart {
    display: inline-block;
    min-width: 100%;
    text-align: center;
    padding: 20px 0;
    position: relative;
}
.orgchart > ul {
    padding-top: 0 !important;
}
.orgchart ul {
    padding: 40px 0 0 0;
    margin: 0;
    display: flex;
    justify-content: center;
    position: relative;
    transition: all 0.5s;
    -webkit-transform: scale(1);
    transform: scale(1);
}
.orgchart li {
    float: left;
    list-style-type: none;
    position: relative;
    padding: 40px 10px 0 10px;
    transition: all 0.5s;
}
.orgchart li::before, .orgchart li::after {
    content: '';
    position: absolute;
    top: 0;
    right: 50%;
    border-top: 2px solid #4a89dc;
    width: 50%;
    height: 30px;
}
.orgchart li::after {
    right: auto;
    left: 50%;
    border-left: 2px solid #4a89dc;
}
.orgchart li:only-child::after, 
.orgchart li:only-child::before {
    display: none;
}
.orgchart li:only-child {
    padding-top: 0;
}
.orgchart > ul > li::before, 
.orgchart > ul > li::after {
    display: none;
}
.orgchart li:first-child::before, 
.orgchart li:last-child::after {
    border: 0 none;
}
.orgchart li:last-child::before {
    border-right: 2px solid #4a89dc;
    border-radius: 0 5px 0 0;
}
.orgchart li:first-child::after {
    border-left: 2px solid #4a89dc;
    border-radius: 5px 0 0 0;
}
.orgchart ul ul::before {
    content: '';
    position: absolute;
    top: 0;
    left: 50%;
    border-left: 2px solid #4a89dc;
    width: 0;
    height: 30px;
}
/* Zoom controls */
/* .orgchart-ctrls {
    position: absolute;
    top: 10px;
    right: 20px;
    z-index: 10;
} */

.orgchart-ctrls {
    flex-direction: column;
    position: fixed; /* fixed instead of absolute */
    top: 60px; /* distance from top, adjust as needed */
    right: 10px; /* distance from right */
    z-index: 999;
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 5px 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
}
.orgchart-ctrls button {
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 5px 10px;
    cursor: pointer;
    transition: all 0.3s;
}
.orgchart-ctrls button:hover {
    background: #f8f9fa;
}
.orgchart-ctrls button i {
    pointer-events: none;
}
.agent-node {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    display: inline-block;
    padding: 12px 15px;
    background: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    min-width: 200px;
    position: relative;
    transition: all 0.3s ease;
    border-left: 4px solid #4a89dc;
}

.chain-team-node {
    border: 1px solid #6f42c1;
    border-radius: 8px;
    display: inline-block;
    padding: 12px 15px;
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
    box-shadow: 0 3px 6px rgba(111, 66, 193, 0.15);
    min-width: 220px;
    position: relative;
    transition: all 0.3s ease;
    border-left: 4px solid #6f42c1;
}

.chain-team-node .agent-name {
    color: #6f42c1;
    font-weight: 700;
    font-size: 1.05em;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.agent-node .agent-name {
    color: #2c3e50;
    font-weight: 600;
    font-size: 1em;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.role-badge {
    display: inline-block;
    padding: 3px 8px;
    font-size: 10px;
    font-weight: 700;
    border-radius: 12px;
    margin-left: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.chain-badge {
    background-color: #6f42c1;
    color: white;
}

.agent-badge {
    background-color: #4a89dc;
    color: white;
}
.agent-node:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}
.agent-name {
    font-weight: 600;
    color: #007bff;
    margin-bottom: 5px;
}
.agent-details {
    font-size: 12px;
    color: #555;
    line-height: 1.6;
}
.agent-details div {
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.agent-details i {
    width: 14px;
    text-align: center;
    color: #888;
}
.agent-badge {
    position: absolute;
    top: -10px;
    right: -10px;
    background: #28a745;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize variables
    let scale = 1;
    const container = $('.orgchart');
    const zoomStep = 0.1;
    const minScale = 0.5;
    const maxScale = 2;
    let isDragging = false;
    let startPos = { x: 0, y: 0 };
    let translate = { x: 0, y: 0 };

    // Apply initial transform
    function applyTransform() {
        container.css('transform', `translate(${translate.x}px, ${translate.y}px) scale(${scale})`);
    }

    // Zoom in
    $('#zoom-in').on('click', function() {
        if (scale < maxScale) {
            scale = Math.min(maxScale, scale + zoomStep);
            applyTransform();
            updateZoomButtons();
        }
    });

    // Zoom out
    $('#zoom-out').on('click', function() {
        if (scale > minScale) {
            scale = Math.max(minScale, scale - zoomStep);
            applyTransform();
            updateZoomButtons();
        }
    });

    // Reset zoom and position
    $('#zoom-reset').on('click', function() {
        scale = 1;
        translate = { x: 0, y: 0 };
        applyTransform();
        updateZoomButtons();
    });

    // Update button states based on current scale
    function updateZoomButtons() {
        $('#zoom-in').prop('disabled', scale >= maxScale);
        $('#zoom-out').prop('disabled', scale <= minScale);
    }

    // Initialize button states
    updateZoomButtons();

    // Pan functionality
    container.on('mousedown', function(e) {
        // Only start dragging on left mouse button
        if (e.which !== 1) return;
        
        isDragging = true;
        startPos = {
            x: e.clientX - translate.x,
            y: e.clientY - translate.y
        };
        container.css('cursor', 'grabbing');
        e.preventDefault();
    });

    $(document).on('mousemove', function(e) {
        if (!isDragging) return;
        
        translate.x = e.clientX - startPos.x;
        translate.y = e.clientY - startPos.y;
        
        applyTransform();
    });

    $(document).on('mouseup', function() {
        isDragging = false;
        container.css('cursor', 'grab');
    });

    // Handle double click to reset view
    container.on('dblclick', function(e) {
        // Only reset on double-click of the background
        if ($(e.target).closest('.agent-node, .chain-team-node').length === 0) {
            translate = { x: 0, y: 0 };
            scale = 1;
            applyTransform();
            updateZoomButtons();
        }
    });

    // Prevent text selection while dragging
    container.on('selectstart', function(e) {
        return !isDragging;
    });

    // Handle touch events for mobile
    let touchStartX = 0;
    let touchStartY = 0;
    let touchStartDistance = 0;
    let initialScale = 1;
    let initialDistance = 0;

    container.on('touchstart', function(e) {
        if (e.originalEvent.touches.length === 1) {
            const touch = e.originalEvent.touches[0];
            touchStartX = touch.clientX - translate.x;
            touchStartY = touch.clientY - translate.y;
        } else if (e.originalEvent.touches.length === 2) {
            // Handle pinch zoom
            const touch1 = e.originalEvent.touches[0];
            const touch2 = e.originalEvent.touches[1];
            initialDistance = Math.hypot(
                touch2.clientX - touch1.clientX,
                touch2.clientY - touch1.clientY
            );
            initialScale = scale;
            e.preventDefault();
        }
    });

    $(document).on('touchmove', function(e) {
        if (e.originalEvent.touches.length === 1) {
            // Handle pan
            const touch = e.originalEvent.touches[0];
            translate.x = touch.clientX - touchStartX;
            translate.y = touch.clientY - touchStartY;
            applyTransform();
            e.preventDefault();
        } else if (e.originalEvent.touches.length === 2) {
            // Handle pinch zoom
            const touch1 = e.originalEvent.touches[0];
            const touch2 = e.originalEvent.touches[1];
            const currentDistance = Math.hypot(
                touch2.clientX - touch1.clientX,
                touch2.clientY - touch1.clientY
            );
            
            if (initialDistance > 0) {
                const newScale = Math.min(maxScale, Math.max(minScale, 
                    initialScale * (currentDistance / initialDistance)));
                
                // Calculate the focal point for zooming
                const rect = container[0].getBoundingClientRect();
                const focalX = (touch1.clientX + touch2.clientX) / 2 - (rect.left + translate.x);
                const focalY = (touch1.clientY + touch2.clientY) / 2 - (rect.top + translate.y);
                
                // Update scale and adjust position to zoom toward the focal point
                const prevScale = scale;
                scale = newScale;
                
                translate.x -= (focalX * (scale - prevScale)) / prevScale;
                translate.y -= (focalY * (scale - prevScale)) / prevScale;
                
                applyTransform();
                updateZoomButtons();
                e.preventDefault();
            }
        }
    });

    // Prevent elastic scrolling on iOS
    document.body.addEventListener('touchmove', function(e) {
        if (e.target.className.includes('orgchart')) {
            e.preventDefault();
        }
    }, { passive: false });
});
</script>
@endpush

@php
    function renderReferralTree($agent, $currentUserId = null) {
        $isChainTeamAgent = $agent->emp_job_role == 7;
        $isCurrentUser = $agent->id == $currentUserId;
        $nodeClass = $isChainTeamAgent ? 'chain-team-node' : 'agent-node';
        
        // Add highlight class if this is the current user
        if ($isCurrentUser) {
            $nodeClass .= ' current-user';
        }
        
        // Prepare badges
        $badges = [];
        if ($isCurrentUser) {
            $badges[] = '<span class="badge bg-primary"><i class="fas fa-user-tie"></i> You</span>';
        }
        if ($isChainTeamAgent) {
            $badges[] = '<span class="badge bg-success"><i class="fas fa-link"></i> Chain Team</span>';
        } else {
            $badges[] = '<span class="badge bg-info"><i class="fas fa-user"></i> Agent</span>';
        }
        
        // Team size info
        $teamSize = isset($agent->team_size) ? $agent->team_size : 0;
        $teamInfo = $teamSize > 0 
            ? "<div class='team-size'><i class='fas fa-users mr-1'></i>Team: {$teamSize}</div>" 
            : '';

        // Build the HTML
        $html = "<li>";
        $html .= "<div class='{$nodeClass}'>";
        $html .= "<div class='agent-name'><i class='fas fa-user-circle mr-1'></i>{$agent->emp_name}</div>";
        $html .= "<div class='agent-badges mb-2'>" . implode(' ', $badges) . "</div>";
        $html .= "<div class='agent-info'>";
        $html .= "<div class='agent-referrals'><i class='fas fa-user-plus mr-1'></i>Direct: {$agent->direct_referrals_count}</div>";
        $html .= $teamInfo;
        $html .= "</div>"; // Close agent-info
        $html .= "</div>"; // Close node

        // Process referrals recursively
        if ($agent->referrals->isNotEmpty()) {
            $html .= "<ul class='level-{$agent->level}'>";
            foreach ($agent->referrals as $referral) {
                $html .= renderReferralTree($referral, $currentUserId);
            }
            $html .= "</ul>";
        }

        $html .= "</li>";
        return $html;
    }
@endphp
