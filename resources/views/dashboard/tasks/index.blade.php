@extends('adminlte::page')

{{-- Extend and customize the browser title --}}

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

{{-- Extend and customize the page content header --}}

@section('content_header')
    <h1>Tasks</h1>
@stop

{{-- Rename section content to content_body --}}
@section('plugins.Datatables', true)
@section('content')
    @yield('content_body')
    {{-- Setup data for datatables --}}

    <button class="btn btn-success mb-3" data-toggle="modal" data-target="#createModal">
        Create New Task
    </button>

    @php
        $config['paging'] = true;
        $config["lengthMenu"] = [10, 50, 100, 500];
    @endphp

    <x-adminlte-datatable id="taskTable" :heads="$heads" :config="$config" striped hoverable>
        @foreach($tasks as $task)
            <tr>
                <td>{{ $task->id }}</td>
                <td>{{ $task->title }}</td>
                <td>{{ $task->description }}</td>
                <td>{{ $task->status }}</td>
                <td>{{ optional($task->employee)->name }}</td>
                <td>{{ optional($task->manager)->name }}</td>
                <td>
                    <nobr>
                        <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" 
                                data-toggle="modal" 
                                data-target="#editModal" 
                                data-id="{{ $task->id }}" 
                                data-title="{{ $task->title }}" 
                                data-description="{{ $task->description }}" 
                                data-status="{{ $task->status }}" 
                                data-employee="{{ optional($task->employee)->id }}" 
                                data-manager="{{ optional($task->manager)->id }}">
                            <i class="fa fa-lg fa-fw fa-pen"></i>
                        </button>
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                                <i class="fa fa-lg fa-fw fa-trash"></i>
                            </button>
                        </form>
                    </nobr>
                </td>
            </tr>
        @endforeach
    </x-adminlte-datatable>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="createForm" method="POST" action="{{ route('tasks.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Create New Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="create-title">Title</label>
                            <input type="text" class="form-control" id="create-title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="create-description">Description</label>
                            <textarea class="form-control" id="create-description" name="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="create-status">Status</label>
                            <select class="form-control" id="create-status" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="create-employee">Employee</label>
                            <select class="form-control" id="create-employee" name="employee_id" required>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . $employee->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="create-manager">Manager</label>
                            <select class="form-control" id="create-manager" name="manager_id" required>
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->first_name . ' ' . $manager->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editForm" method="POST" action="{{ route('tasks.update') }}">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="form-group">
                            <label for="edit-title">Title</label>
                            <input type="text" class="form-control" id="edit-title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-description">Description</label>
                            <textarea class="form-control" id="edit-description" name="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit-status">Status</label>
                            <select class="form-control" id="edit-status" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-employee">Employee</label>
                            <select class="form-control" id="edit-employee" name="employee_id">
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-manager">Manager</label>
                            <select class="form-control" id="edit-manager" name="manager_id">
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Create a common footer --}}

@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>

    <strong>
        <a href="{{ config('app.company_url', '#') }}">
            {{ config('app.company_name', 'My company') }}
        </a>
    </strong>
@stop

{{-- Add common Javascript/Jquery code --}}

@push('js')
<script>
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var modal = $(this);
        
        // Fill modal fields with task data
        modal.find('#edit-id').val(button.data('id'));
        modal.find('#edit-title').val(button.data('title'));
        modal.find('#edit-description').val(button.data('description'));
        modal.find('#edit-status').val(button.data('status'));
        modal.find('#edit-employee').val(button.data('employee'));
        modal.find('#edit-manager').val(button.data('manager'));
    });
</script>
@endpush

{{-- Add common CSS customizations --}}

@push('css')
<style>
    {{-- You can add AdminLTE customizations here --}}
</style>
@endpush
