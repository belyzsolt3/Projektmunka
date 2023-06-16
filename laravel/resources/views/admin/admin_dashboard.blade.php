<style>
    .text-gray-300 {
        text-decoration: none;
    }
</style>
<?php
require base_path('resources/views/partials/head.view.php');
require base_path('resources/views/partials/admin.navigation.view.php');
require base_path('resources/views/partials/header.view.php');
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
        <div class="col-md-4 col-md-offset-4" style="margin-top:20px; margin-left: 30px;width: 1250px">
            <h4>Welcome to the Admin Dashboard</h4>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <div class="dashboard-card">
                        <h3>Users</h3>
                        <p>Total Users: {{ $totalUsers }}</p>
                        <a href="users" class="btn btn-primary">Manage Users</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card">
                        <h3>Reservations</h3>
                        <p>Total Reservations: {{ $totalReservations }}</p>
                        <a href="admin-reservations" class="btn btn-primary">Manage Reservations</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card">
                        <h3>Requests</h3>
                        <p>Total Requests: {{ $totalRequests }}</p>
                        <a href="requests" class="btn btn-primary">Manage Requests</a>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <h3>Recent Reservations</h3>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Date</th>
                                <th>Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $recentReservations = Reservations::with('user')
                                ->where('created_at', '>=', date('Y-m-d'))
                                ->paginate(5, ['*'], 'recent_reservations_page');
                            ?>
                            @foreach ($recentReservations as $reservation)
                                <tr>
                                    <td>{{ $reservation->user->name }}</td>
                                    <td>{{ $reservation->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $reservation->created_at->format('H:i A') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $recentReservations->appends(['recent_reservations_page' => $recentReservations->currentPage()])->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                <div class="col-md-6">
                    <h3>Recent Requests</h3>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $recentRequests = Contacts::where('created_at', '>=', date('Y-m-d'))->paginate(5, ['*'], 'recent_requests_page'); ?>
                            @foreach ($recentRequests as $request)
                                <tr>
                                    <td><?= $request->name ?></td>
                                    <td><?= $request->created_at->format('Y-m-d') ?></td>
                                    <td><?= $request->created_at->format('H:i A') ?></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $recentRequests->appends(['recent_requests_page' => $recentRequests->currentPage()])->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous">
</script>

</html>
<?php
require base_path('resources/views/partials/foot.view.php');
