@extends('main') <!-- Extend the main layout -->

@section('title', 'View Lead') <!-- Set the title for the page -->

@section('content') <!-- Content section -->

<style>
      label {
        font-size: 16px;
        color: #495057;
        font-family: sans-serif;
        font-weight: 100 !important;
        
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
        <h1>View Lead</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('leads.index') }}">Leads</a></li>
            {{-- <li class="fa fa-angle-right">View Lead</li> --}}
        </ol>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <fieldset class="form-group">
                            <label for="first_name">First Name</label>
                            <input class="form-control" id="first_name" name="first_name" type="text" value="{{ $lead->first_name }}" disabled>
                        </fieldset>

                        <fieldset class="form-group">
                            <label for="title">Title</label>
                            <input class="form-control" id="title" name="title" type="text" value="{{ $lead->title }}" disabled>
                        </fieldset>

                        <fieldset class="form-group">
                            <label for="last_name">Last Name</label>
                            <input class="form-control" id="last_name" name="last_name" type="text" value="{{ $lead->last_name }}" disabled>
                        </fieldset>

                        <fieldset class="form-group">
                            <label for="email">Email</label>
                            <input class="form-control" id="email" name="email" type="email" value="{{ $lead->email }}" disabled>
                        </fieldset>

                        <fieldset class="form-group">
                            <label for="phone">Phone</label>
                            <input class="form-control" id="phone" name="phone" type="text" value="{{ $lead->phone }}" disabled>
                        </fieldset>

                        <fieldset class="form-group">
                            <label for="company">Company</label>
                            <input class="form-control" id="company" name="company" type="text" value="{{ $lead->company }}" disabled>
                        </fieldset>

                        <fieldset class="form-group">
                            <label for="source">Lead Source</label>
                            <input class="form-control" id="source" name="lead_source" type="text" value="{{ $lead->lead_source }}" disabled>
                        </fieldset>

                        <fieldset class="form-group">
                            <label for="status">Lead Status</label>
                            <input class="form-control" id="status" name="lead_status" type="text" value="{{ $lead->lead_status }}" disabled>
                        </fieldset>
                    </div>
                    <div class="col-lg-6">
                        <!-- Address Information -->
                        <h4 class="text-black">Address Information:</h4>
                        <fieldset class="form-group">
                            <label for="street">Street</label>
                            <input class="form-control" id="street" name="street" type="text" value="{{ $lead->street }}" disabled>
                        </fieldset>

                        <fieldset class="form-group">
                            <label for="state">State</label>
                            <input class="form-control" id="state" name="state" type="text" value="{{ $lead->state }}" disabled>
                        </fieldset>

                        <fieldset class="form-group">
                            <label for="country">Country</label>
                            <input class="form-control" id="country" name="country" type="text" value="{{ $lead->country }}" disabled>
                        </fieldset>

                        <fieldset class="form-group">
                            <label for="city">City</label>
                            <input class="form-control" id="city" name="city" type="text" value="{{ $lead->city }}" disabled>
                        </fieldset>

                        <fieldset class="form-group">
                            <label for="zip">Zip Code</label>
                            <input class="form-control" id="zip" name="zip_code" type="text" value="{{ $lead->zip_code }}" disabled>
                        </fieldset>

                        <!-- Description Information -->
                        <h4 class="text-black">Description:</h4>
                        <fieldset class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" disabled>{{ $lead->description }}</textarea>
                        </fieldset>
                    </div>
                </div>

                  <!-- Convert Button -->
                  <div class="text-right mt-3">
                    <a href="{{ url('/admin/leads/convert/'.$lead->id) }}" class="btn btn-success">Convert Lead</a>
                </div>

            </div>
        </div>
    </div>
    <!-- /.content -->
</div>
@endsection
