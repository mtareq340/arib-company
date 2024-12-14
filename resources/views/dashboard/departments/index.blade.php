@extends('adminlte::page')

{{-- Extend and customize the browser title --}}
@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

{{-- Extend and customize the page content header --}}
@section('content_header')
    <h1>Departments</h1>
@stop

{{-- Datatable and Content Section --}}
@section('plugins.Datatables', true)
@section('content')
    @yield('content_body')

    <button class="btn btn-success mb-3" data-toggle="modal" data-target="#createModal">
        Create New Department
    </button>

    @php
        $config['paging'] = true;
        $config["lengthMenu"] = [10, 50, 100, 500];
    @endphp

    <x-adminlte-datatable id="departmentTable" :heads="$heads" :config="$config" striped hoverable>
        @foreach($departments as $department)
            <tr>
                <td>{{ $department->id }}</td>
                <td>{{ $department->name }}</td>
                <td>{{ $department->employees_count }}</td> <!-- Employee count -->
                <td>{{ $department->employees_sum_salary }}</td> <!-- Total salary -->
                <td>
                    <nobr>
                        <!-- Edit and Delete Buttons -->
                        <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" 
                                data-toggle="modal" 
                                data-target="#editModal" 
                                data-id="{{ $department->id }}" 
                                data-name="{{ $department->name }}">
                            <i class="fa fa-lg fa-fw fa-pen"></i>
                        </button>
                        <form action="{{ route('departments.destroy', $department->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this department?');">
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
                <form id="createForm" method="POST" action="{{ route('departments.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Create New Department</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="create-name">Name</label>
                            <input type="text" class="form-control" id="create-name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="create-description">Description</label>
                            <textarea class="form-control" id="create-description" name="description" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editForm" method="POST" action="{{ route('departments.update') }}">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Department</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="form-group">
                            <label for="edit-name">Name</label>
                            <input type="text" class="form-control" id="edit-name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-description">Description</label>
                            <textarea class="form-control" id="edit-description" name="description" required></textarea>
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

{{-- Footer Section --}}
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

{{-- Add JavaScript --}}
@push('js')
<script>
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var modal = $(this);

        // Fill modal fields with department data
        modal.find('#edit-id').val(button.data('id'));
        modal.find('#edit-name').val(button.data('name'));
        modal.find('#edit-description').val(button.data('description'));
    });
</script>
@endpush

{{-- Add CSS --}}
@push('css')
<style>
    /* Custom CSS for departments view */
</style>
@endpush
