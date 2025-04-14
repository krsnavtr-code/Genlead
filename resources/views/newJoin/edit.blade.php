@extends('main')

@section('title', 'Edit Candidate')

@section('content')
<div class="content-wrapper">
    <div class="content-header sty-one">
        <h1>Edit Candidate</h1>
    </div>
    <div class="content">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('candidates.update', $candidate->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $candidate->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ $candidate->email }}" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ $candidate->phone }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="/admin/new-join-panel" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
