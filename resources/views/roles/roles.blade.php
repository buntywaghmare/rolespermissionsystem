@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="d-flex">
                    <h3>Roles Table</h3>
                    @can('role-create')
                    <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal"
                        data-bs-target="#addRolesModal">
                        Add
                    </button>
                    @endcan
                </div>
                <!-- Success Alert -->
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert"
                    style="display: none;">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <!-- Error Alert -->
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errorAlert"
                    style="display: none;">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="table-responsive">
                    <table class="table" id="roles">
                        <thead>
                            <tr>
                                <th scope="col">sr no</th>
                                <th scope="col">Name</th>
                                <th scope="col">Permissions</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- Add Role Modal -->
    <div class="modal fade" id="addRolesModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <form id="addform" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">
                            Add Roles Modal
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center align-items-center g-2">
                            <div class="col-md-10">
                                <div class="mb-1">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        aria-describedby="emailHelp" required>
                                </div>
                                <div class="mb-1">
                                    <label for="role" class="form-label">Permission</label>
                                    <select multiple class="form-select form-select selectpicker" name="permission[]"
                                        id="permission" required>
                                        @foreach ($permission as $value)
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary" id="saveuser">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div class="modal fade" id="editRolesModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <form id="editform" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">
                            Update Roles Modal
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center align-items-center g-2">
                            <div class="col-md-10">
                                <input type="text" class="form-control d-none" name="roleid" id="editid"
                                    placeholder="" />
                                <div class="mb-1">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" id="editname"
                                        aria-describedby="emailHelp" required>
                                </div>
                                <div class="mb-1">
                                    <label for="role" class="form-label">Permission</label>
                                    <select multiple class="form-select form-select selectpicker" name="permission[]"
                                        id="editpermission" required>
                                        @foreach ($permission as $value)
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary" id="updateuser">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        $(function() {
            // function for show alert and hide after 5 sec
            function showAlert(alertId, message) {
                $(alertId).html(message).fadeIn();
                setTimeout(function() {
                    $(alertId).fadeOut();
                }, 5000);
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // datatables of roles
            let table = $('#roles').dataTable({
                "ajax": {
                    "url": "{{ route('roleslist') }}",
                    "type": "GET"
                },
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "permission"
                    },
                    {
                        "data": "action"
                    },
                ],
                "rowCallback": function(row, data, index) {
                    // Generate index and set it in the first column
                    $('td:eq(0)', row).html(index + 1);
                }
            });

            // add roles
            $("#addform").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('roles.store') }}",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.success) {
                            $('#addRolesModal').modal('hide');
                            $('#roles').DataTable().ajax.reload();
                            showAlert('#successAlert', 'Role added successfully.');
                        }
                    },
                });
            });

            // edit role
            $('#roles').on('click', '.editRoleBtn', function() {
                let roleId = $(this).data('id');
                let url = "{{ route('roles.edit', ':roleId') }}";
                url = url.replace(':roleId', roleId);
                $.get(url, function(data) {
                    console.log(data);
                    $('#editid').val(roleId);
                    $('#editname').val(data.role.name);
                    $('#editpermission').val(data.permissions.map(permission => permission.id));
                    $('#editRolesModal').modal('show');
                });
            });

            // update role
            $("#editform").submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let url = "{{ route('roles.update', ':roleId') }}";
                url = url.replace(':roleId', $('#editid').val());
                formData.append('_method', 'PUT');
                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.success) {
                            $('#editRolesModal').modal('hide');
                            $('#roles').DataTable().ajax.reload();
                            showAlert('#successAlert', 'Role updated successfully.');
                        }
                    },
                });
            });

            // delete role
            $('#roles').on('click', '.deleteRoleBtn', function() {
                let roleId = $(this).data('id');
                let url = "{{ route('roles.destroy', ':roleId') }}";
                url = url.replace(':roleId', roleId);
                if (confirm("Are you sure you want to delete this role?")) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function(data) {
                            if (data.success) {
                                $('#roles').DataTable().ajax.reload();
                                showAlert('#successAlert', 'Role deleted successfully.');
                            }
                        }
                    });
                }
            });

        });
    </script>
@endsection
