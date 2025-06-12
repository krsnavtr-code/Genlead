@extends('main')

@section('title', 'Lead Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lead Details</h3>
                    <div class="card-tools">
                        <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Lead Information</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%;">Name:</th>
                                    <td>{{ $lead->first_name }} {{ $lead->last_name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ substr($lead->email, 0, 3) . '*****' . substr($lead->email, strpos($lead->email, '@') - 3) }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ substr($lead->phone, 0, 2) . '***' . substr($lead->phone, -2) }}</td>
                                </tr>
                                <tr>
                                    <th>Couese:</th>
                                    <td>{{ $lead->courses }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @php
                                            $statusColor = 'secondary';
                                            $statusName = 'No Status';
                                            
                                            if (is_object($lead->status)) {
                                                $statusColor = $lead->status->color;
                                                $statusName = $lead->status->name;
                                            } elseif (is_string($lead->status)) {
                                                $statusName = $lead->status;
                                            }
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }}">
                                            {{ $statusName }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Assigned Agent:</th>
                                    <td>
                                        @if($lead->agent)
                                            {{ $lead->agent->emp_name }}
                                        @else
                                            Not Assigned
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At:</th>
                                    <td>{{ $lead->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>{{ $lead->updated_at->format('M d, Y h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4>Additional Information</h4>
                            <table class="table table-bordered">
                                @foreach([
                                    'address' => 'Address',
                                    'city' => 'City',
                                    'state' => 'State',
                                    'zip_code' => 'ZIP Code',
                                    'country' => 'Country',
                                    'source' => 'Lead Source',
                                    'notes' => 'Notes'
                                ] as $field => $label)
                                    @if(!empty($lead->$field))
                                        <tr>
                                            <th style="width: 30%;">{{ $label }}:</th>
                                            <td>{{ $lead->$field }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h4>Follow-up History</h4>
                            @if($lead->followUps->count() > 0)
                                <div class="timeline">
                                    @foreach($lead->followUps as $followUp)
                                        <div class="time-label">
                                            <span class="bg-{{ $followUp->status->color ?? 'info' }}">
                                                {{ $followUp->follow_up_time->format('d M. Y') }}
                                            </span>
                                        </div>
                                        <div>
                                            <i class="fas fa-comments bg-blue"></i>
                                            <div class="timeline-item">
                                                <span class="time">
                                                    <i class="far fa-clock"></i> 
                                                    {{ $followUp->follow_up_time->format('h:i A') }}
                                                </span>
                                                <h3 class="timeline-header">
                                                    @if($followUp->createdBy)
                                                        {{ $followUp->createdBy->emp_name }}
                                                    @else
                                                        System
                                                    @endif
                                                    <small>updated the status to </small>
                                                    <span class="badge bg-{{ $followUp->status->color ?? 'secondary' }}">
                                                        {{ $followUp->status->name ?? 'N/A' }}
                                                    </span>
                                                </h3>
                                                @if($followUp->comments)
                                                    <div class="timeline-body">
                                                        {{ $followUp->comments }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    <div>
                                        <i class="fas fa-clock bg-gray"></i>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    No follow-up history found for this lead.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Documents section removed as the relationship is not defined --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        margin: 0 0 30px 0;
        padding: 0;
        list-style: none;
    }
    .timeline > div {
        position: relative;
        margin-right: 10px;
        margin-bottom: 15px;
    }
    .timeline > div:before, .timeline > div:after {
        content: "";
        display: table;
    }
    .timeline > div:after {
        clear: both;
    }
    .timeline > div > .timeline-item {
        box-shadow: 0 0 1px rgba(0,0,0,.125);
        border-radius: .25rem;
        margin-top: 0;
        background: #fff;
        color: #444;
        margin-left: 60px;
        margin-right: 15px;
        padding: 0;
        position: relative;
    }
    .timeline > div > .timeline-item > .time {
        color: #999;
        float: right;
        padding: 10px;
        font-size: 12px;
    }
    .timeline > div > .timeline-item > .timeline-header {
        margin: 0;
        color: #555;
        border-bottom: 1px solid #f4f4f4;
        padding: 10px;
        font-size: 16px;
        line-height: 1.1;
    }
    .timeline > div > .timeline-item > .timeline-body, 
    .timeline > div > .timeline-item > .timeline-footer {
        padding: 10px;
    }
    .timeline > div > .fa, 
    .timeline > div > .fas, 
    .timeline > div > .far, 
    .timeline > div > .fab, 
    .timeline > div > .glyphicon, 
    .timeline > div > .ion {
        width: 30px;
        height: 30px;
        font-size: 15px;
        line-height: 30px;
        position: absolute;
        color: #666;
        background: #d2d6de;
        border-radius: 50%;
        text-align: center;
        left: 18px;
        top: 0;
    }
    .timeline > .time-label > span {
        font-weight: 600;
        padding: 5px 10px;
        display: inline-block;
        color: #fff;
        border-radius: 4px;
    }
    .timeline > .time-label {
        position: relative;
        width: 100px;
        margin: 0;
        padding: 5px;
        font-size: 14px;
        text-align: right;
    }
</style>
@endpush
