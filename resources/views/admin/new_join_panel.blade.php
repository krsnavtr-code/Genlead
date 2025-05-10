@extends('main')

@section('title', 'New Join Panel')

@section('content')
<div class="content-wrapper">
    <div class="content-header sty-one text-center mb-4">
        <h1>New Join Panel</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <div class="content">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h4 class="mb-4">New Joinee Candidates:</h4>
                @if($verifiedCandidates->isEmpty())
                    <div class="alert alert-info text-center">No candidates have been fully verified by HR yet.</div>
                @else
                <div class="row g-4">
                    @foreach($verifiedCandidates as $candidate)
                    <div class="col-md-6 col-lg-4" style="margin-bottom: 15px;">
                        <div class="card h-100 shadow-sm border-start border-primary border-4" style="background-color: #e9ecef;">
                            <div class="card-body">
                                <h5 class="card-title text-primary fw-bold">{{ $candidate->name }}</h5>
                                <br>
                                <p class="mb-1"><strong>Email:</strong> {{ $candidate->email }}</p>
                                <p class="mb-2"><strong>Phone:</strong> {{ $candidate->phone }}</p>

                                <div class="mb-3">
                                    <strong>Documents:</strong>
                                    <ul class="mb-0 ps-3">
                                        @if($candidate->company_pan_file)
                                            <li><a href="{{ asset($candidate->company_pan_file) }}" target="_blank">Marksheet</a></li>
                                        @endif
                                        @if($candidate->personal_aadhar_file)
                                            <li><a href="{{ asset($candidate->personal_aadhar_file) }}" target="_blank">Personal Aadhar</a></li>
                                        @endif
                                        @if($candidate->personal_pan_file)
                                            <li><a href="{{ asset($candidate->personal_pan_file) }}" target="_blank">Personal PAN</a></li>
                                        @endif
                                    </ul>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                    @if(!$candidate->is_superadmin_verified)
                                        <form action="{{ route('superadmin.verify', $candidate->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Verify</button>
                                        </form>
                                    @else
                                        <span class="badge bg-success">Verified</span>
                                    @endif

                                    <a href="{{ route('candidates.edit', $candidate->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                    <form action="{{ route('candidates.destroy', $candidate->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this candidate?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
