@extends('main') <!-- Extend the main layout -->

@section('title', 'Edit Lead') <!-- Set the page title -->

@section('content')
<div class="content-wrapper" >
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
        <h1 class="display-4">Edit Lead</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/leads') }}">Leads</a></li>
            {{-- <li class="breadcrumb-item active" aria-current="page">Edit Lead</li> --}}
        </ol>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="card">
            <div class="card-body">
                {{-- <h4 class="card-title">Edit Lead Information</h4> --}}
                <form method="POST" action="{{ url('/i-admin/leads/update/'.$lead->id) }}">
                    @csrf
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="first_name">First Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="first_name" name="first_name" type="text" value="{{ $lead->first_name }}" required>
                        </div>
                       
                        <div class="form-group col-md-6">
                            <label for="last_name">Last Name <span class="text-danger">*</span></label>
                            <input class="form-control" id="last_name" name="last_name" type="text" value="{{ $lead->last_name }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input class="form-control" id="email" name="email" type="email" value="{{ $lead->email }}" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="phone">Phone <span class="text-danger">*</span></label>
                            <input class="form-control" id="phone" name="phone" type="text" value="{{ $lead->phone }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="university">University</label>
                            <input class="form-control" id="university" name="university" type="text" value="{{ $lead->university }}" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="courses">Courses <span class="text-danger">*</span></label>
                            <input class="form-control" id="courses" name="courses" type="text" value="{{ $lead->courses }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        {{-- <div class="form-group col-md-6">
                            <label for="company">Company <span class="text-danger">*</span></label>
                            <input class="form-control" id="company" name="company" type="text" value="{{ $lead->company }}" required>
                        </div> --}}

                        <div class="form-group col-md-6">
                            <label for="source">Lead Source</label>
                            <select class="form-control" id="source" name="lead_source">
                                @php
                                    $lead_source = array('Advertising','Social Media','Direct Call','Search');
                                @endphp
                                @foreach ($lead_source as $single)
                                <option value="{{ $single }}" {{ $lead->lead_source == $single ? 'selected' : '' }}>{{ $single }}</option>  
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
    <!-- /.content -->
</div>
@endsection


