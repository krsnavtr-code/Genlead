@extends('main')

@section('title', 'Edit Task')

@section('content')
<div class="content-wrapper">
    <div class="content-header sty-one">
        <h1>Edit Task</h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin/tasks') }}">Tasks</a></li>
            {{-- <li class="fa fa-angle-right">Edit Task</li> --}}
        </ol>
    </div>

    <div class="content">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" value="{{ $task->subject }}" required>
                    </div>

                    <div class="form-group">
                        <label for="task_type">Task Type</label>
                        <select class="form-control" id="task_type" name="task_type" required>
                            <option value="follow_up" {{ $task->task_type == 'follow_up' ? 'selected' : '' }}>Follow-Up</option>
                            <option value="payment_failure" {{ $task->task_type == 'payment_failure' ? 'selected' : '' }}>Payment Failure</option>
                            <!-- Add more task types as needed -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="task_status">Task Status</label>
                        <select class="form-control" id="task_status" name="task_status" required>
                            <option value="open" {{ $task->task_status == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="overdue" {{ $task->task_status == 'overdue' ? 'selected' : '' }}>Overdue</option>
                            <option value="pending" {{ $task->task_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ $task->task_status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="schedule_from">Schedule From</label>
                        <input type="datetime-local" class="form-control" id="schedule_from" name="schedule_from" value="{{ date('Y-m-d\TH:i', strtotime($task->schedule_from)) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="schedule_to">Schedule To</label>
                        <input type="datetime-local" class="form-control" id="schedule_to" name="schedule_to" value="{{ date('Y-m-d\TH:i', strtotime($task->schedule_to)) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4">{{ $task->description }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Task</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
