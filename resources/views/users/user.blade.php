@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
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
                <div class="d-flex">
                    <h3>User Table</h3>
                    @can('user-create')
                        <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal"
                            data-bs-target="#addUserModal">
                            Add
                        </button>
                    @endcan
                </div>
                <div class="table-responsive">
                    <div class="mb-3">
                        <label for="roleFilter" class="form-label">Filter by Role:</label>
                        <select id="roleFilter" class="form-select">
                            <option value="">All Roles</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <table class="table" id="users">
                        <thead>
                            <tr>
                                <th scope="col">sr no</th>
                                <th scope="col">Name</th>
                                <th scope="col">email</th>
                                <th scope="col">role</th>
                                @can('user-edit', 'user-delete')
                                    <th scope="col">Action</th>
                                @endcan
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <form id="addform" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">
                            Add User Modal
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
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        aria-describedby="emailHelp" required>
                                    <div class="text-danger" id="erremail"></div>
                                </div>
                                <div class="mb-1">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" id="password"
                                        aria-describedby="passwordHelp" required>
                                </div>
                                <div class="mb-1">
                                    <label for="confirmpassword" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirmpassword"
                                        aria-describedby="confirmpasswordHelp" required>
                                    <div class="text-danger" id="errpassword"></div>
                                </div>
                                <div class="mb-1">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select form-select" name="roles" id="role" required>
                                        <option selected>Select one</option>
                                        @foreach ($roles as $value)
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

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <form id="editform" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">
                            Update User Modal
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center align-items-center g-2">
                            <div class="col-md-10">
                                <input type="text" class="form-control d-none" name="userid" id="editid"
                                    placeholder="" />
                                <div class="mb-1">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" id="editname"
                                        aria-describedby="emailHelp" required>
                                </div>
                                <div class="mb-1">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" id="editemail"
                                        aria-describedby="emailHelp" required>
                                    <div class="text-danger" id="erreditemail"></div>
                                </div>
                                <div class="mb-1">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" id="editpassword"
                                        aria-describedby="passwordHelp">
                                </div>
                                <div class="mb-1">
                                    <label for="confirmpassword" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="editconfirmpassword"
                                        aria-describedby="confirmpasswordHelp">
                                    <div class="text-danger" id="erreditpassword"></div>
                                </div>
                                <div class="mb-1">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select form-select" name="roles" id="editrole" required>
                                        <option selected>Select one</option>
                                        @foreach ($roles as $value)
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

            // user datatable
            $('#users').dataTable({
                "ajax": {
                    "url": "{{ route('userslist') }}",
                    "type": "GET"
                },
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "email"
                    },
                    {
                        "data": "role"
                    },
                    @can('user-edit')
                        {
                            "data": "action"
                        },
                    @endcan
                ],
                "rowCallback": function(row, data, index) {
                    $('td:eq(0)', row).html(index + 1);
                }
            });

            // add users
            $("#addform").submit(function(e) {
                e.preventDefault();
                if ($("#password").val() != $("#confirmpassword").val()) {
                    $("#errpassword").html("Please enter same password");
                } else {
                    $("#errpassword").html("");
                    $.ajax({
                        url: "{{ route('users.store') }}",
                        type: "POST",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                            if (data.success) {
                                $('#addUserModal').modal('hide');
                                $('#users').DataTable().ajax.reload();
                                showAlert('#successAlert', 'User added successfully.');
                            }
                        },
                        error: function(data) {
                            if (data.responseJSON.errors.email) {
                                $("#erremail").html(data.responseJSON.errors.email[0]);
                            }
                        }
                    });
                }
            });

            // edit users
            $('#users').on('click', '.editRoleBtn', function() {
                let userId = $(this).data('id');
                let url = "{{ route('users.edit', ':userId') }}";
                url = url.replace(':userId', userId);
                $.get(url, function(data) {
                    console.log(data);
                    $('#editid').val(userId);
                    $('#editname').val(data.user.name);
                    $('#editemail').val(data.user.email);
                    $('#editpassword').val();
                    $('#editconfirmpassword').val();
                    $('#editrole').val(data.userRole);
                    $('#editUserModal').modal('show');
                });
            });

            // update users
            $("#editform").submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let url = "{{ route('users.update', ':userId') }}";
                url = url.replace(':userId', $('#editid').val());
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
                            $('#editUserModal').modal('hide');
                            $('#users').DataTable().ajax.reload();
                            showAlert('#successAlert', 'User updated successfully.');
                        }
                    },
                });
            });

            //delete user
            $('#users').on('click', '.deleteRoleBtn', function() {
                let userId = $(this).data('id');
                let url = "{{ route('users.destroy', ':userId') }}";
                url = url.replace(':userId', userId);
                if (confirm("Are you sure you want to delete this user?")) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        success: function(data) {
                            if (data.success) {
                                $('#users').DataTable().ajax.reload();
                                showAlert('#successAlert', 'User deleted successfully.');
                            }
                        }
                    });
                }
            });

            // filter user by role
            $('#roleFilter').on('change', function() {
                let role = $(this).val();
                $('#users').DataTable().column(3).search(role).draw();
            });
        });
    </script>
@endsection
