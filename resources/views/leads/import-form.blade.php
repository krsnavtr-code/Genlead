@extends('main') <!-- Extend the main layout -->

@section('title', 'Import Lead') <!-- Set the page title -->

@section('content')

<div class="import-leads-wrapper" style="margin-left: 250px;">
    <h2>Import Leads</h2>
    
    <div class="steps" style="display: flex; gap: 304px;">
        <div class="step active">Step 01 <br> <span style="display: block;width: 118px;">Import CSV File</span></div>
        <div class="step">Step 02 <br> <span style="display: block;width: 118px;">Map Fields</span></div>
        <div class="step">Step 03 <br><span style="display: block;width: 118px;"> Actions </span></div>
        <div class="step">Step 04 <br><span style="display: block;width: 118px;"> Summary</span></div>
    </div>
    
    <p>Import your existing Leads through CSV file</p>
    
    <form action="{{ url('/admin/leads/import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label for="leads_file">Select File</label>
            <p>Supports csv file and zipped csv file with comma separated values.</p>
            <input type="file" name="leads_file" id="leads_file" required>
        </div>
        
        <p>Approximate time to upload 2MB file is 30 seconds.</p>
        
        <p>
            Click <a href="{{ url('/admin/leads/download-sample-csv') }}">here</a> to download sample csv
        </p>
        
        <div class="form-actions">
            <button type="button" class="btn cancel">Cancel</button>
            <button type="submit" class="btn next">Next</button>
        </div>
    </form>
</div>
@endsection
