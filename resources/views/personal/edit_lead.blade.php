@extends('main')

@section('title', 'Edit Lead')

@section('content')

<form action="/i-admin/leads/{id}" method="POST">
    @csrf
    
    <div class="form-group">
        <label for="owner">Lead Owner</label>
        <input type="text" name="owner" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="company">Company</label>
        <input type="text" name="company" class="form-control" value="{{ $lead->company }}" required>
    </div>

    <div class="form-group">
        <label for="lead_source">Lead Source</label>
        <input type="text" name="lead_source" class="form-control" value="{{ $lead->lead_source }}" required>
    </div>

    <div class="form-group">
        <label for="lead_status">Lead Status</label>
        <input type="text" name="lead_status" class="form-control" value="{{ $lead->lead_status }}" required>
    </div>

    <div class="form-group">
        <label for="university">University</label>
        <input type="text" name="university" class="form-control" value="{{ $lead->university }}" required>
    </div>

    <div class="form-group">
        <label for="course">Course</label>
        <input type="text" name="course" class="form-control" value="{{ $lead->courses }}" required>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" class="form-control" value="{{ $lead->email }}" required>
    </div>

    <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ $lead->phone }}" required>
    </div>

    <button type="submit" class="btn btn-primary">Update Lead</button>
</form>

@endsection
