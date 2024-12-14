@extends('adminlte::page')

{{-- Extend and customize the browser title --}}

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

{{-- Extend and customize the page content header --}}

@section('content_header')
    <h1>Employees</h1>
@stop

{{-- Rename section content to content_body --}}
@section('plugins.Datatables', true)
@section('content')
    @yield('content_body')
    {{-- Setup data for datatables --}}

    <button class="btn btn-success mb-3" data-toggle="modal" data-target="#createModal">
        Create New User
    </button>

    @php
        $config['paging'] = true;
        $config["lengthMenu"] = [10, 50, 100, 500];
    @endphp

    <x-adminlte-datatable id="table3" :heads="$heads" :config="$config" striped hoverable>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td>{{ $user->salary }}</td>
                <td>{{ $user->roles->first()->name ?? 'No role assigned' }}</td>
                <td>{{ optional($user->department)->name }}</td>
                <td>{{ optional($user->manager)->name }}</td>
                <td>
                    <nobr>
                        <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" 
                                data-toggle="modal" 
                                data-target="#editModal" 
                                data-id="{{ $user->id }}" 
                                data-first_name="{{ $user->first_name }}" 
                                data-last_name="{{ $user->last_name }}" 
                                data-email="{{ $user->email }}" 
                                data-phone="{{ $user->phone }}" 
                                data-salary="{{ $user->salary }}" 
                                data-role="{{ $user->roles->first()->id }}" 
                                data-department="{{ optional($user->department)->id }}" 
                                data-manager="{{ optional($user->manager)->id }}">
                            <i class="fa fa-lg fa-fw fa-pen"></i>
                        </button>
                        <form action="{{ route('employees.destroy', $user->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this employee?');">
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
                <form id="createForm" method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Create New User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="create-name">First Name</label>
                            <input type="text" class="form-control" id="create-name" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="create-name">Last Name</label>
                            <input type="text" class="form-control" id="create-name" name="last_name" required>
                        </div>
                        <div class="form-group">
                            <label for="create-email">Email</label>
                            <input type="email" class="form-control" id="create-email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="create-phone">Phone</label>
                            <input type="text" class="form-control" id="create-phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="create-password">Password</label>
                            <input type="password" class="form-control" id="create-password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="create-password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control" id="create-password_confirmation" name="password_confirmation" required>
                        </div>
                        <div class="form-group">
                            <label for="create-salary">Salary</label>
                            <input type="number" class="form-control" id="create-salary" name="salary" step="0.01" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="create-image">Image</label>
                            <input type="file" class="form-control" id="create-image" name="image" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label for="create-role">Role</label>
                            <select class="form-control" id="create-role" name="role_id" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="create-department">Department</label>
                            <select class="form-control" id="create-department" name="department_id" required>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="manager-group">
                            <label for="create-manager">Manager</label>
                            <select class="form-control" id="create-manager" name="manager_id">
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->first_name . ' ' . $manager->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editForm" method="POST" action="{{route('employees.update')}}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Employee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="form-group">
                            <label for="edit-name">First Name</label>
                            <input type="text" class="form-control" id="edit-first_name" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-name">Last Name</label>
                            <input type="text" class="form-control" id="edit-last_name" name="last_name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-email">Email</label>
                            <input type="email" class="form-control" id="edit-email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-phone">Phone</label>
                            <input type="text" class="form-control" id="edit-phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-phone">Salary</label>
                            <input type="text" class="form-control" id="edit-salary" name="salary" required>
                        </div>
                         <div class="form-group">
                            <label for="create-image">Image</label>
                            <input type="file" class="form-control" id="create-image" name="image" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label for="edit-role">Role</label>
                            <select class="form-control" id="edit-role" name="role_id">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-department">Department</label>
                            <select class="form-control" id="edit-department" name="department_id">
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="edit-manager-group">
                            <label for="edit-manager">Manager</label>
                            <select class="form-control" id="edit-manager" name="manager_id">
                                @foreach($managers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->first_name . ' ' . $manager->last_name }}</option>
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
        
        // Fill modal fields with user data
        modal.find('#edit-id').val(button.data('id'));
        modal.find('#edit-first_name').val(button.data('first_name'));
        modal.find('#edit-last_name').val(button.data('last_name'));
        modal.find('#edit-email').val(button.data('email'));
        modal.find('#edit-phone').val(button.data('phone'));
        modal.find('#edit-salary').val(button.data('salary'));
        modal.find('#edit-role').val(button.data('role'));
        modal.find('#edit-department').val(button.data('department'));
        modal.find('#edit-manager').val(button.data('manager'));
    });

    document.addEventListener('DOMContentLoaded', function () {
        const roleDropdown = document.getElementById('create-role');
        const managerGroup = document.getElementById('manager-group');

        // Function to toggle manager visibility
        function toggleManagerVisibility() {
            if (roleDropdown.options[roleDropdown.selectedIndex].text === 'Employee') {
                managerGroup.style.display = 'block';
            } else {
                managerGroup.style.display = 'none';
            }
        }

        // Initialize visibility on page load
        toggleManagerVisibility();

        // Add change event listener
        roleDropdown.addEventListener('change', toggleManagerVisibility);
    });

    document.addEventListener('DOMContentLoaded', function () {
        const editRoleDropdown = document.getElementById('edit-role');
        const editManagerGroup = document.getElementById('edit-manager-group');

        // Function to toggle the visibility of the Manager field
        function toggleEditManagerVisibility() {
            if (editRoleDropdown.options[editRoleDropdown.selectedIndex].text === 'Employee') {
                editManagerGroup.style.display = 'block';
            } else {
                editManagerGroup.style.display = 'none';
            }
        }

        // Event listener for role changes in the Edit Modal
        editRoleDropdown.addEventListener('change', toggleEditManagerVisibility);

        // Initialize visibility when opening the Edit Modal
        $('#editModal').on('show.bs.modal', function (event) {
            toggleEditManagerVisibility();
        });
    });

</script>
@endpush

{{-- Add common CSS customizations --}}

@push('css')
<style>
    {{-- You can add AdminLTE customizations here --}}
</style>
@endpush
