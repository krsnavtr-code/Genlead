@extends('main')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Team Member</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.team.management') }}">Team Management</a></li>
                        <li class="breadcrumb-item active">Edit Team Member</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Team Member Details</h3>
                        </div>

                        <form method="POST" action="{{ route('admin.team.member.update', $teamMember->id) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="emp_name">Name</label>
                                    <input type="text" class="form-control @error('emp_name') is-invalid @enderror" 
                                           id="emp_name" name="emp_name" value="{{ old('emp_name', $teamMember->emp_name) }}" required>
                                    @error('emp_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="emp_email">Email</label>
                                    <input type="email" class="form-control @error('emp_email') is-invalid @enderror" 
                                           id="emp_email" name="emp_email" value="{{ old('emp_email', $teamMember->emp_email) }}" required>
                                    @error('emp_email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="emp_phone">Phone</label>
                                    <input type="text" class="form-control @error('emp_phone') is-invalid @enderror" 
                                           id="emp_phone" name="emp_phone" value="{{ old('emp_phone', $teamMember->emp_phone) }}" required>
                                    @error('emp_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control @error('emp_status') is-invalid @enderror" name="emp_status" required>
                                        <option value="active" {{ old('emp_status', $teamMember->emp_status) === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('emp_status', $teamMember->emp_status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('emp_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('admin.team.management') }}" class="btn btn-default float-right">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
