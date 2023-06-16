<style>
    .text-gray-300 {
        text-decoration: none;
    }
</style>
<?php
require base_path('resources/views/partials/head.view.php');
require base_path('resources/views/partials/loggedin.navigation.view.php');
require base_path('resources/views/partials/header.view.php');
use App\Models\User;
use App\Models\Reservations;
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
        <div class="col-md-6" style="margin-top: 20px">
            <h4>Previous reservations</h4>
            <hr>
            <?php
            $user = User::find(session('loginId'));
            $currentDateTime = date('Y-m-d H:i:s');
            $previousReservations = Reservations::with(['user', 'service', 'appointment'])
                ->where('user_id', $user['id'])
                ->whereHas('appointment', function ($query) use ($currentDateTime) {
                    $query->where('date', '<', date('Y-m-d'))
                        ->orWhere(function ($query) use ($currentDateTime) {
                            $query->where('date', '=', date('Y-m-d'))
                                ->where('start_time', '<=', date('H:i:s'));
                        });
                })
                ->paginate(3, ['*'], 'previous_page');
            $previousNo = ($previousReservations->currentPage() - 1) * $previousReservations->perPage() + 1;
            ?>
            <table class="table">
                <thead>
                <th>No.</th>
                <th>Name</th>
                <th>Service</th>
                <th>Date</th>
                <th>Time</th>
                </thead>
                <tbody>
                @foreach($previousReservations as $reservation)
                    <tr>
                        <td>{{$previousNo}}.</td>
                        <td>{{$reservation->user->name}}</td>
                        <td>{{$reservation->service->name}}</td>
                        <td>{{$reservation->appointment->date}}</td>
                        <td>{{date('H:i', strtotime($reservation->appointment->start_time))}} - {{date('H:i', strtotime($reservation->appointment->end_time))}}</td>
                    </tr>
                        <?php $previousNo += 1; ?>
                @endforeach
                </tbody>
            </table>
            {{ $previousReservations->links('pagination::bootstrap-4', ['paginator' => $previousReservations, 'pageName' => 'previous_page']) }}
        </div>
        <div class="col-md-6" style="margin-top: 20px">
            <h4>Profile</h4>
            <hr>
            @if(session('profileSuccess'))
                <div class="alert alert-success" id="profile-success-message">
                    {{ session('profileSuccess') }}
                </div>
            @endif
            @if(session('profileFail'))
                <div class="alert alert-danger" id="profile-fail-message">
                    {{ session('profileFail') }}
                </div>
            @endif
            <script>
                setTimeout(function() {
                    document.getElementById('profile-success-message').style.display = 'none';
                }, 10000);
                setTimeout(function() {
                    document.getElementById('profile-fail-message').style.display = 'none';
                }, 10000);
            </script>
            <table class="table">
                <thead>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                </thead>
                <tbody>
                <tr>
                    <td>{{$data->name}}</td>
                    <td>{{$data->email}}</td>
                    <td>{{$data->phone}}</td>
                </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
                Update Data
            </button>
        </div>
        <div class="col-md-6" style="margin-top: 20px">
            <h4>My complaints</h4>
            <hr>
            <?php
            $user = User::find(session('loginId'));
            $contacts = Contacts::where('user_id', $user['id'])->paginate(3, ['*'], 'complaints_page');
            $complaintsNo = ($contacts->currentPage() - 1) * $contacts->perPage() + 1;
            ?>
            <table class="table">
                <thead>
                <th>No.</th>
                <th>Name</th>
                <th>Message</th>
                <th>Date</th>
                <th>Status</th>
                </thead>
                <tbody>
                @foreach($contacts as $contact)
                    <tr class="table-row" style="height: 50px;">
                        <td>{{$complaintsNo}}.</td>
                        <td>{{$contact->name}}</td>
                        <td>
                            @if(strlen($contact['message']) > 0)
                                <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#viewMessageModal{{$contact->id}}">View</a>
                            @else
                                {{ $contact['message'] }}
                            @endif
                        </td>
                        <td>{{$contact->created_at}}</td>
                        <td>
                            @if ($contact['handled'] == 2)
                                Handled
                            @elseif ($contact['handled'] == 1)
                                In Progress
                            @else
                                Not Handled
                            @endif
                        </td>
                    </tr>
                        <?php $complaintsNo += 1; ?>
                @endforeach
                </tbody>
            </table>
            {{ $contacts->links('pagination::bootstrap-4', ['paginator' => $contacts, 'pageName' => 'complaints_page']) }}
        </div>
        <div class="col-md-6" style="margin-top: 20px">
            <h4>Upcoming reservations</h4>
            <hr>
            @if(session('CancelSuccess'))
                <div class="alert alert-success" id="profile-success-message">
                    {{ session('CancelSuccess') }}
                </div>
            @endif
            @if(session('CancelFail'))
                <div class="alert alert-danger" id="profile-fail-message">
                    {{ session('CancelFail') }}
                </div>
            @endif
            <script>
                setTimeout(function() {
                    document.getElementById('profile-success-message').style.display = 'none';
                }, 10000);
                setTimeout(function() {
                    document.getElementById('profile-fail-message').style.display = 'none';
                }, 10000);
            </script>
            <?php
            $user = User::find(session('loginId'));
            $currentDateTime = date('Y-m-d H:i:s');
            $upcomingReservations = Reservations::with(['service', 'appointment'])
                ->where('user_id', $user['id'])
                ->whereHas('appointment', function ($query) use ($currentDateTime) {
                    $query->where('date', '>', date('Y-m-d'))
                        ->orWhere(function ($query) use ($currentDateTime) {
                            $query->where('date', '=', date('Y-m-d'))
                                ->where('end_time', '>=', date('H:i:s'));
                        });
                })
                ->paginate(3, ['*'], 'upcoming_page');
            $upcomingNo = ($upcomingReservations->currentPage() - 1) * $upcomingReservations->perPage() + 1;
            ?>
            <table class="table">
                <thead>
                <th>No.</th>
                <th>Service</th>
                <th>Date</th>
                <th>Time</th>
                <th>Action</th>
                </thead>
                <tbody>
                @foreach($upcomingReservations as $reservation)
                    <tr>
                        <td>{{$upcomingNo}}.</td>
                        <td>{{$reservation->service->name}}</td>
                        <td>{{$reservation->appointment->date}}</td>
                        <td>{{ date('H:i', strtotime($reservation->appointment->start_time)) }} - {{ date('H:i', strtotime($reservation->appointment->end_time)) }}</td>
                        <td>
                            <form action="{{ route('cancelReservation', ['id' => $reservation->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="margin-bottom: -15px ">Cancel</button>
                            </form>
                        </td>
                    </tr>
                        <?php $upcomingNo += 1; ?>
                @endforeach
                </tbody>
            </table>
            {{ $upcomingReservations->links('pagination::bootstrap-4', ['paginator' => $upcomingReservations, 'pageName' => 'upcoming_page']) }}
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('edit-user-data', $data->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $data->email }}">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ $data->phone }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveChanges" data-bs-dismiss="modal">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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
