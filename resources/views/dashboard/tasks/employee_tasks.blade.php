@extends('adminlte::page')

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

@section('content_header')
    <h1>Employee Tasks</h1>
@stop

@section('plugins.Datatables', true)

@section('content')
    @yield('content_body')

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
                    <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Update Status" 
                            data-toggle="modal" 
                            data-target="#updateStatusModal" 
                            data-id="{{ $task->id }}" 
                            data-status="{{ $task->status }}">
                        <i class="fa fa-lg fa-fw fa-edit"></i> Update Status
                    </button>
                </td>
            </tr>
        @endforeach
    </x-adminlte-datatable>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('tasks.updateStatus') }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="id" id="edit-id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateStatusModalLabel">Update Task Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="task-id">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
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

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

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

@push('js')
<script>
    $('#updateStatusModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var modal = $(this);

        // Set the task id and status dynamically
        modal.find('#edit-id').val(button.data('id'));
        modal.find('#task-id').val(button.data('id'));
        modal.find('#status').val(button.data('status'));
    });
</script>
@endpush
