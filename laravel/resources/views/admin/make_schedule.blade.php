<style>
    .text-gray-300 {
        text-decoration: none;
    }
</style>
<?php
require base_path('resources/views/partials/head.view.php');
require base_path('resources/views/partials/admin.navigation.view.php');
require base_path('resources/views/partials/header.view.php');
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
            <h4>Make Schedule</h4>
            <hr>
            @if(session('Schsuccess'))
                <div class="alert alert-success" id="success-message">
                    {{ session('Schsuccess') }}
                </div>
            @endif
            @if(session('Schfail'))
                <div class="alert alert-danger" id="fail-message">
                    {{ session('Schfail') }}
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
            <form action="{{ route('saveSchedule') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" name="date">
                </div>
                <div class="mb-3">
                    <label for="start_time" class="form-label">Start Time</label>
                    <input type="time" class="form-control" id="start_time" name="start_time">
                </div>
                <div class="mb-3">
                    <label for="end_time" class="form-label">End Time</label>
                    <input type="time" class="form-control" id="end_time" name="end_time">
                </div>
                <button type="submit" class="btn btn-primary">Save Schedule</button>
            </form>
        </div>
        <div class="col-md-6" style="margin-top: 20px">
            <h4>Make New Service</h4>
            <hr>
            @if(session('Sersuccess'))
                <div class="alert alert-success" id="success-message">
                    {{ session('Sersuccess') }}
                </div>
            @endif
            @if(session('fail'))
                <div class="alert alert-danger" id="fail-message">
                    {{ session('fail') }}
                </div>
            @endif
            <form action="{{ route('addService') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" required>
                </div>
                <div class="mb-3">
                    <label for="time" class="form-label">Time</label>
                    <input type="time" class="form-control" id="time" name="time" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Service</button>
            </form>
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
