<style>
    .text-gray-300 {
        text-decoration: none;
    }
</style>
<?php
require base_path('resources/views/partials/head.view.php');
require base_path('resources/views/partials/admin.navigation.view.php');
require base_path('resources/views/partials/header.view.php');
use App\Models\Services;
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
    <style>
        .services-panel {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-6" style="margin-top: 20px">
            <h4>Reservations</h4>
            <hr>
            @if(session('Ressuccess'))
                <div class="alert alert-success" id="success-message">
                    {{ session('Ressuccess') }}
                </div>
            @endif
            @if(session('Resfail'))
                <div class="alert alert-danger" id="fail-message">
                    {{ session('Resfail') }}
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
            <form action="{{ route('get-appointments-data') }}" method="GET">
                @csrf
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" class="form-control">
                </div>
                <br>
                <div class="form-group">
                    <button class="btn btn-block btn-primary" type="submit">Check Schedule</button>
                </div>
            </form>
            <h4>Services</h4>
            <hr>
            @if(session('Sersuccess'))
                <div class="alert alert-success" id="success-message">
                    {{ session('Sersuccess') }}
                </div>
            @endif
            @if(session('Serfail'))
                <div class="alert alert-danger" id="fail-message">
                    {{ session('Serfail') }}
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
            <?php
            $services = Services::all();
            $No = 1;
            ?>
            <table class="table">
                <thead>
                <th>No</th>
                <th>Name</th>
                <th>Price</th>
                <th>Time</th>
                <th>Action</th>
                </thead>
                <tbody>
                <?php $services = Services::paginate(3); ?>
                @foreach($services as $service)
                    <tr style="padding: 5px">
                        <td>{{ $No }}.</td>
                        <td>{{ $service['name'] }}</td>
                        <td>{{ $service['price'] }} Ft</td>
                        <td>{{ date('H:i', strtotime($service['time'])) }}</td>
                        <td>
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $service['id'] }}">Edit</a>
                            <form action="{{ route('deleteService', ['id' => $service['id']]) }}" method="POST"
                                  style="display: inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                        <?php $No++; ?>
                @endforeach
                </tbody>
            </table>
            {{ $services->links('pagination::bootstrap-4', ['paginator' => $services, 'pageName' => 'previous_page']) }}
        </div>
        <div class="col-md-6 services-panel"style="margin-top: 20px">
            @if(isset($selectedDate))
                <table class="table">
                    <thead>
                    <th>No</th>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Action</th>
                    </thead>
                    <tbody>
                    @foreach($scheduleData as $scheduleRow)
                        <tr>
                            <td>{{ $scheduleRow['No'] }}</td>
                            <td>{{ $scheduleRow['Id'] }}</td>
                            <td>{{ $scheduleRow['User name'] }}</td>
                            <td>{{ $scheduleRow['Service'] }}</td>
                            <td>{{ $scheduleRow['Date'] }}</td>
                            <td>{{ $scheduleRow['Time period'] }}</td>
                            <td>{{ $scheduleRow['Status'] }}</td>
                            <td>
                                <form action="{{ route('deleteReservation', ['id' => $scheduleRow['AppointmentId']]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="margin-bottom: -15px">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@foreach($services as $service)
    <div class="modal fade" id="editModal{{ $service['id'] }}" tabindex="-1"
         aria-labelledby="editModal{{ $service['id'] }}Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModal{{ $service['id'] }}Label">Edit Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('editService', ['id' => $service['id']]) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="{{ $service['name'] }}">
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="text" class="form-control" id="price" name="price"
                                   value="{{ $service['price'] }}">
                        </div>
                        <div class="mb-3">
                            <label for="time" class="form-label">Time</label>
                            <input type="time" class="form-control" id="time" name="time"
                                   value="{{ $service['time'] }}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
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
