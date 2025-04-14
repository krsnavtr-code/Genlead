<!-- resources/views/admin/deals/edit.blade.php -->
@extends('main')

@section('title', 'Edit Deal')

@section('content')
<div class="content-wrapper">
    <div class="content-header sty-one">
        <h1>Edit Deal</h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin/deals') }}">Deals</a></li>
            {{-- <li class="fa fa-angle-right">Edit Deal</li> --}}
        </ol>
    </div>

    <div class="content">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('deals.update', $deal->id) }}" method="POST">
                    @csrf
        
                    <div class="form-group">
                        <label for="deal_name">Deal Name</label>
                        <input type="text" class="form-control" id="deal_name" name="deal_name" value="{{ $deal->deal_name }}" required>
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" value="{{ $deal->amount }}" required>
                    </div>

                    <div class="form-group">
                        <label for="stage">Stage</label>
                        <select class="form-control" id="stage" name="stage" required>
                            <option value="Prospecting" {{ $deal->stage == 'Prospecting' ? 'selected' : '' }}>Prospecting</option>
                            <option value="Qualification" {{ $deal->stage == 'Qualification' ? 'selected' : '' }}>Qualification</option>
                            <option value="Proposal" {{ $deal->stage == 'Proposal' ? 'selected' : '' }}>Proposal</option>
                            <option value="Negotiation" {{ $deal->stage == 'Negotiation' ? 'selected' : '' }}>Negotiation</option>
                            <option value="Closed Won" {{ $deal->stage == 'Closed Won' ? 'selected' : '' }}>Closed Won</option>
                            <option value="Closed Lost" {{ $deal->stage == 'Closed Lost' ? 'selected' : '' }}>Closed Lost</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="contact_phone">Contact Phone</label>
                        <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="{{ $deal->contact_phone }}" required>
                    </div>

                    <div class="form-group">
                        <label for="priority">Priority</label>
                        <select class="form-control" id="priority" name="priority" required>
                            <option value="High" {{ $deal->priority == 'High' ? 'selected' : '' }}>High</option>
                            <option value="Medium" {{ $deal->priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="Low" {{ $deal->priority == 'Low' ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="estimated_close_date">Estimated Close Date</label>
                        <input type="date" class="form-control" id="estimated_close_date" name="estimated_close_date" value="{{ $deal->estimated_close_date }}" required>
                    </div>

                    <div class="form-group">
                        <label for="contact_email">Contact Email</label>
                        <input type="email" class="form-control" id="contact_email" name="contact_email" value="{{ $deal->contact_email }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Deal</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
