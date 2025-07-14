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
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle" style="font-size: 0.9rem;">
                        <thead class="table-primary">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Documents</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($verifiedCandidates as $candidate)
                            <tr style="background-color: #e9ecef;">
                                <td>
                                    <div class="fw-bold text-primary">{{ $candidate->name }}</div>

                                    {{-- Verify button or badge below name --}}
                                    <div class="mt-1">
                                        @if(!$candidate->is_superadmin_verified)
                                        <form action="{{ route('superadmin.verify', $candidate->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Verify</button>
                                        </form>
                                        @else
                                        <span class="badge bg-success">Verified</span>
                                        @endif
                                    </div>
                                </td>

                                <td>{{ $candidate->email }}</td>
                                <td>{{ $candidate->phone }}</td>

                                <td>
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
                                </td>

                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        {{-- Edit Icon (Blue) --}}
                                        <a href="{{ route('candidates.edit', $candidate->id) }}" class="text-primary fs-5" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        {{-- Delete Icon (Red) --}}
                                        <form action="{{ route('candidates.destroy', $candidate->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this candidate?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 text-danger fs-5" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection