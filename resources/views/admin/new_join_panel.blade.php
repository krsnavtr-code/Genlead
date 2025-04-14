@extends('main')

@section('title', 'New Join Panel')

@section('content')
<div class="content-wrapper">
    <div class="content-header sty-one">
        <h1 style="text-align:center;">New Join Panel</h1>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
   @endif


    <div class="content">
        <div class="card">
            <div class="card-body">
                <h4>New Joinee Candidates:-</h4>
                @if($verifiedCandidates->isEmpty())
                    <p>No candidates have been fully verified by HR yet.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
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
                                <tr>
                                    <td>{{ $candidate->name }}</td>
                                    <td>{{ $candidate->email }}</td>
                                    <td>{{ $candidate->phone }}</td>
                                    <td>
                                        <ul>
                                            @if($candidate->company_pan_file)
                                                <li><a href="{{ asset( $candidate->company_pan_file) }}" target="_blank"> Marksheet</a></li>
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
                                        @if(!$candidate->is_superadmin_verified)
                                             <form action="{{ route('superadmin.verify', $candidate->id) }}" method="POST">
                                                @csrf
                                                 <button type="submit" class="btn btn-success">Verify as Super Admin</button>
                                             </form>
                                      @else
                                           <span class="badge badge-success">Verified</span>
                                     @endif

                                     
                                     <a href="{{ route('candidates.edit', $candidate->id) }}" class="btn btn-warning">Edit</a>

                                     <form action="{{ route('candidates.destroy', $candidate->id) }}" method="POST" style="display: inline-block;">
                                         @csrf
                                         @method('DELETE')
                                         <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this candidate?');">Delete</button>
                                     </form>

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
