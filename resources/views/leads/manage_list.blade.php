@extends('main')

@section('title', 'Manage Lists')

@section('content')
<div class="container">
    <h2>Manage Lists</h2>
    <div class="row mb-3">
        <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Search Lead Lists">
        </div>
        <div class="col-md-2">
            <select class="form-control">
                <option value="All">List Type</option>
                <option value="All">All</option>
                <option value="Static">Static</option>
                <option value="All">Dynamic</option>
                <option value="Refreshable">Refreshable</option>
            </select>
        </div>
        <div class="col-md-2">
            <select class="form-control">
                <option value="Any">Created By</option>
                <option value="User1">User1</option>
                <option value="User2">User2</option>
            </select>
        </div>
        {{-- <div class="col-md-2">
            <button class="btn btn-secondary">Tags</button>
        </div>
        <div class="col-md-2">
            <button class="btn btn-secondary">Actions</button>
        </div> --}}
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">
                    <input type="checkbox">
                </th>
                <th scope="col">List Name</th>
                {{-- <th scope="col">Member Count</th> --}}
                <th scope="col">List Type</th>
                <th scope="col">Created by (date)</th>
                <th scope="col">Modified by (date)</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lists as $list)
            <tr>
                <td>
                    <input type="checkbox">
                </td>
                <td>{{ $list->list_name }}</td>
                {{-- <td>{{ $list->leads->count() }}</td>> --}}
                <td>{{ $list->list_type }}</td>
                {{-- <td>{{ $list->created_at->format('Y-m-d') }}</td> --}}
                {{-- <td>{{ $list->updated_at->format('Y-m-d') }}</td> --}}
                <td>
                    <a href="" class="btn btn-primary btn-sm">Edit</a>
                    <form action="" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No records to display.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
