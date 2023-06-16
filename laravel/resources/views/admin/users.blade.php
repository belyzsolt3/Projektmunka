<style>
    .text-gray-300 {
        text-decoration: none;
    }
</style>
<?php
require base_path('resources/views/partials/head.view.php');
require base_path('resources/views/partials/admin.navigation.view.php');
require base_path('resources/views/partials/header.view.php');
use App\Models\User;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Custom Authentication</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
          crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4" style="margin-top:20px; margin-left: 30px; width:1000px" >
            <div class="d-flex justify-content-between">
                <h4>Users</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    Add User
                </button>
            </div>
            <hr>
            @if(session('success'))
                <div class="alert alert-success" id="success-message">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('fail'))
                <div class="alert alert-danger" id="fail-message">
                    {{ session('fail') }}
                </div>
            @endif
            <script>
                setTimeout(function() {
                    document.getElementById('success-message').style.display = 'none';
                }, 10000);
                setTimeout(function() {
                    document.getElementById('fail-message').style.display = 'none';
                }, 10000);
            </script>
            <table class="table">
                <thead>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Registration</th>
                <th>Last Logged In</th>
                <th>Role</th>
                <th>Action</th>
                </thead>
                <tbody>
                <?php $users = User::paginate(5); ?>
                @foreach($users as $user)
                    <tr>
                        <td>{{$user['id']}}.</td>
                        <td>{{$user['name']}}</td>
                        <td>{{$user['email']}}</td>
                        <td>{{$user['created_at']}}</td>
                        <td>{{$user['last_login']}}</td>
                        <td>
                            @if($user['role'] == 1)
                                Admin
                            @else
                                User
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('delete-user', $user->id) }}" method="POST">
                                <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#editUserModal{{$user->id}}">Edit</a>
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
    </div>
</div>
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('add-user') }}" method="POST" id="addUserForm">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" id="role" class="form-control">
                            <option value="0">User</option>
                            <option value="1">Admin</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@foreach($users as $user)
    <div class="modal fade" id="editUserModal{{$user->id}}" tabindex="-1" aria-labelledby="editUserModal{{$user->id}}Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModal{{$user->id}}Label">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('edit-user', ['user' => $user]) }}" method="POST" id="editUserForm{{$user->id}}">
                        @csrf
                        <div class="form-group">
                            <label for="editName{{$user->id}}">Name</label>
                            <input type="text" name="name" id="editName{{$user->id}}" class="form-control" value="{{$user->name}}">
                        </div>
                        <div class="form-group">
                            <label for="editEmail{{$user->id}}">Email</label>
                            <input type="email" name="email" id="editEmail{{$user->id}}" class="form-control" value="{{$user->email}}">
                        </div>
                        <div class="form-group">
                            <label for="editPhone{{$user->id}}">Phone</label>
                            <input type="text" name="phone" id="editPhone{{$user->id}}" class="form-control" value="{{$user->phone}}">
                        </div>
                        <div class="form-group">
                            <label for="editPassword{{$user->id}}">Password</label>
                            <input type="password" name="password" id="editPassword{{$user->id}}" class="form-control" value="">
                        </div>
                        <div class="form-group">
                            <label for="editRole{{$user->id}}">Role</label>
                            <select name="role" id="editRole{{$user->id}}" class="form-control">
                                <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Admin</option>
                                <option value="0" {{ $user->role == 0 ? 'selected' : '' }}>User</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="editUserForm{{$user->id}}">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous">
</script>

</html>
<?php
require base_path('resources/views/partials/foot.view.php');
