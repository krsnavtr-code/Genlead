@extends('main')

@section('title', 'Add New List')


@section('content')
<div class="container">
    <h2>Create New List</h2>
    <form action="{{ route('lists.submit') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="list_name">List Name:</label>
            <input type="text" class="form-control" id="list_name" name="list_name" required>
        </div>
        <div class="form-group">
            <label for="list_type">List Type:</label>
            <select class="form-control" id="list_type" name="list_type">
                <option value="All">All</option>
                <option value="Static">Static</option>
                <option value="Dynamic">Dynamic</option>
                <option value="Refreshable">Refreshable</option>
            </select>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter a brief description of the list"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create List</button>
    </form>
</div>
@endsection