<style>
    .text-gray-300 {
        text-decoration: none;
    }
</style>
<?php
require base_path('resources/views/partials/head.view.php');
require base_path('resources/views/partials/admin.navigation.view.php');
require base_path('resources/views/partials/header.view.php');
use App\Models\Contacts;
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
        <div class="col-md-4 col-md-offset-4" style="margin-top:20px; margin-left: 30px; width: 1200px">
            <h4>Request</h4>
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
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Message</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $contacts = Contacts::paginate(5); ?>
                @foreach($contacts as $contact)
                    <tr>
                        <td>{{ $contact['id'] }}</td>
                        <td>{{ $contact['name'] }}</td>
                        <td>
                            @if($contact['user_id'])
                                User (ID: {{ $contact['user_id'] }})
                            @else
                                Not User
                            @endif
                        </td>
                        <td>{{ $contact['email'] }}</td>
                        <td>{{ $contact['phone'] }}</td>
                        <td>@if(strlen($contact['message']) > 0)
                                <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#viewMessageModal{{$contact->id}}">View</a>
                            @else
                                {{ $contact['message'] }}
                            @endif
                        </td>
                        <td>{{ $contact['created_at'] }}</td>
                        <td>
                            @if ($contact['handled'] == 2)
                                Handled
                            @elseif ($contact['handled'] == 1)
                                In Progress
                            @else
                                Not Handled
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('delete-contact', $contact->id) }}" method="POST">
                                <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#editRequestModal{{$contact->id}}">Edit</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $contacts->links() }}

        </div>
    </div>
</div>
@foreach($contacts  as $contact)
    <div class="modal fade" id="editRequestModal{{$contact->id}}" tabindex="-1" aria-labelledby="editRequestModal{{$contact->id}}Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRequestModal{{$contact->id}}Label">Edit Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('edit-request', ['contact' => $contact]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="status{{$contact->id}}">Status</label>
                            <select name="status" id="status{{$contact->id}}" class="form-control">
                                <option value="0" {{$contact->handled == 0 ? 'selected' : ''}}>Not Handled</option>
                                <option value="1" {{$contact->handled == 1 ? 'selected' : ''}}>In Progress</option>
                                <option value="2" {{$contact->handled == 2 ? 'selected' : ''}}>Handled</option>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@foreach($contacts as $contact)
    <div class="modal fade" id="viewMessageModal{{$contact->id}}" tabindex="-1" aria-labelledby="viewMessageModal{{$contact->id}}Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewMessageModal{{$contact->id}}Label">View Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ $contact['message'] }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous">
</script>

</html>
<?php
require base_path('resources/views/partials/foot.view.php');
