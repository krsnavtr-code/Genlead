@extends('main')

@section('content')
<div class="content-wrapper mt-5">
    <div class="content-header">
        <div class="container-fluid">
            <h5 class="m-0">Agent Network</h5>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title m-0 text-sm">My Team</h5>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-center align-items-center position-relative">
                            <div class="d-flex justify-content-between w-100 mb-3">
                                @if(count($referralTree) > 0)
                                <div class="orgchart-ctrls ">
                                    <button id="zoom-in" title="Zoom In"><i class="fas fa-search-plus"></i></button>
                                    <button id="zoom-out" title="Zoom Out"><i class="fas fa-search-minus"></i></button>
                                    <button id="zoom-reset" title="Reset Zoom"><i class="fas fa-sync-alt"></i></button>
                                </div>
                                @endif
                            </div>
                            @if(count($referralTree) > 0)
                                <div class="orgchart-container">
                                    <div class="orgchart">
                                        @foreach($referralTree as $agent)
                                            {!! renderReferralTree($agent) !!}
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info w-100 text-center">
                                    No agents found in the referral network.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.orgchart-container {
    overflow-x: auto;
    padding: 20px 0;
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
    border-radius: 4px;
    display: inline-block;
    padding: 10px 15px;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    min-width: 180px;
    position: relative;
    transition: all 0.3s ease;
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
    color: #6c757d;
    line-height: 1.4;
}
.agent-details div {
    margin-bottom: 2px;
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
    // Zoom functionality
    let scale = 1;
    const container = $('.orgchart');
    const zoomStep = 0.1;
    const minScale = 0.5;
    const maxScale = 2;

    // Zoom in
    $('#zoom-in').on('click', function() {
        if (scale < maxScale) {
            scale += zoomStep;
            updateZoom();
        }
    });

    // Zoom out
    $('#zoom-out').on('click', function() {
        if (scale > minScale) {
            scale -= zoomStep;
            updateZoom();
        }
    });

    // Reset zoom
    $('#zoom-reset').on('click', function() {
        scale = 1;
        updateZoom();
    });

    // Update zoom level
    function updateZoom() {
        container.css({
            'transform': `scale(${scale})`,
            'transform-origin': 'top center',
            'min-width': `${100/scale}%`,
            'width': 'auto',
            'display': 'inline-block'
        });
    }


    // Make the orgchart draggable
    let isDragging = false;
    let startX, startY, scrollLeft, scrollTop;
    const orgchartContainer = document.querySelector('.orgchart-container');

    if (orgchartContainer) {
        orgchartContainer.addEventListener('mousedown', (e) => {
            isDragging = true;
            startX = e.pageX - orgchartContainer.offsetLeft;
            startY = e.pageY - orgchartContainer.offsetTop;
            scrollLeft = orgchartContainer.scrollLeft;
            scrollTop = orgchartContainer.scrollTop;
            orgchartContainer.style.cursor = 'grabbing';
        });

        orgchartContainer.addEventListener('mouseleave', () => {
            isDragging = false;
            orgchartContainer.style.cursor = 'grab';
        });

        orgchartContainer.addEventListener('mouseup', () => {
            isDragging = false;
            orgchartContainer.style.cursor = 'grab';
        });

        orgchartContainer.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            e.preventDefault();
            const x = e.pageX - orgchartContainer.offsetLeft;
            const y = e.pageY - orgchartContainer.offsetTop;
            const walkX = (x - startX) * 2; // Scroll faster
            const walkY = (y - startY) * 2; // Scroll faster
            orgchartContainer.scrollLeft = scrollLeft - walkX;
            orgchartContainer.scrollTop = scrollTop - walkY;
        });

        // Add grab cursor
        orgchartContainer.style.cursor = 'grab';
    }
});
</script>
@endpush

@php
    function renderReferralTree($agent) {
        $html = '<li>';
        $html .= '<div class="agent-node">';
        $html .= '<div class="agent-name">' . e($agent->emp_name) . '</div>';
        $html .= '<div class="agent-details">';
        $html .= '<div>ID: ' . e($agent->id) . '</div>';
        $html .= '<div>Referrals: ' . (isset($agent->direct_referrals_count) ? $agent->direct_referrals_count : '0') . '</div>';
        $html .= '<div>Join Date: ' . ($agent->emp_join_date ? \Carbon\Carbon::parse($agent->emp_join_date)->format('d M Y') : 'N/A') . '</div>';
        $html .= '</div>';
        
        if (isset($agent->direct_referrals_count) && $agent->direct_referrals_count > 0) {
            $html .= '<span class="agent-badge">' . $agent->direct_referrals_count . '</span>';
        }
        
        $html .= '</div>';
        
        // Render children
        if (isset($agent->referrals) && $agent->referrals->count() > 0) {
            $html .= '<ul>';
            foreach ($agent->referrals as $referral) {
                $html .= renderReferralTree($referral);
            }
            $html .= '</ul>';
        }
        
        $html .= '</li>';
        return $html;
    }
@endphp
