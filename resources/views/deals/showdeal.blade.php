<!-- resources/views/admin/deals/index.blade.php -->
@extends('main')

@section('title', 'View Deals')

@section('content')
<style>
      th,td {
        font-size: 17px;
        color: #495057;
        font-family: sans-serif;
        font-weight: 100 !important;
        
    }
</style>
<div class="content-wrapper">
    <div class="content-header sty-one">
        <h1>All Deals</h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin/deals') }}">Deals</a></li>
            {{-- <li class="fa fa-angle-right"> View Deals</li> --}}
        </ol>
    </div>

    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Deal Name</th>
                                <th>Amount</th>
                                <th>Stage</th>
                                <th>Contact Phone</th>
                                <th>Priority</th>
                                <th>Estimated Close Date</th>
                                <th>Contact Email</th>
                                {{-- <th>Account Name</th> --}}
                                {{-- <th>Company Name</th> --}}
                                <th>Actions</th> <!-- Actions Column -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deals as $deal)
                            <tr>
                                <td>{{ $deal->deal_name }}</td>
                                <td>{{ $deal->amount }}</td>
                                <td>{{ $deal->stage }}</td>
                                <td>{{ $deal->contact_phone }}</td>
                                <td>{{ $deal->priority }}</td>
                                <td>{{ $deal->estimated_close_date }}</td>
                                <td>{{ $deal->contact_email }}</td>
                                {{-- <td>{{ $deal->lead ? $deal->lead->first_name . ' ' . $deal->lead->last_name : '-' }}</td> --}}
                                {{-- <td>{{ $deal->lead ? $deal->lead->company : '-' }}</td> --}}
                                <td>
                                    <!-- Edit Button -->
                                    <a href="{{ route('deals.edit', $deal->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('deals.destroy', $deal->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this deal?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
