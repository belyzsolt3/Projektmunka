<style>
    .text-gray-300 {
        text-decoration: none;
    }
    .table {
        width: 100%;
        max-width: 800px;
        margin: 20px auto;
        border-collapse: collapse;
        border-spacing: 0;
        background-color: #fff;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
    }

    .table th, .table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .table th {
        background-color: #f9f9f9;
        font-weight: bold;
        color: #333;
        text-transform: uppercase;
    }
    body {
        font-family: Arial, sans-serif;
        margin: 0;
    }

    header {
        background-color: #333;
        color: #fff;
        display: flex;
        justify-content: space-between;
    }

    nav a {
        color: #fff;
        text-decoration: none;
        margin-right: 20px;
    }

    main {
        padding: 50px;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
</style>
<?php
require base_path('resources/views/partials/head.view.php');

if(Session()->has('loginId')) {
    require base_path('resources/views/partials/loggedin.navigation.view.php');
} else {
    require base_path('resources/views/partials/navigation.view.php');
}
require base_path('resources/views/partials/header.view.php');
use App\Models\Services;
use Resources\css;
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
    <link rel="stylesheet" href="css/services.css" type="text/css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4" style="margin-top:20px; margin-left: 30px; width: 1100px">
            <h4>Services</h4>
            <hr>
            <?php $services = Services::all(); ?>
            <table class="table" style="width: 500px;float: left">
                <thead>
                <th>Name</th>
                <th>Price</th>
                <th>Time</th>
                </thead>
                <tbody>
                @foreach($services as $service)
                    <tr>
                        <td>{{$service['name']}}  </td>
                        <td>{{$service['price']}} Ft</td>
                        <td><?php echo date('g', strtotime($service['time'])) . ' hour'; ?></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="services-grid" style="float: right" >
                <div class="service">
                    <h2>Haircuts</h2>
                    <p>Get a fresh new haircut from one of our experienced stylists.</p>
                </div>
                <div class="service">
                    <h2>Hair Coloring</h2>
                    <p>Looking to try out a new hair color? We've got you covered.</p>
                </div>
                <div class="service">
                    <h2>Beard</h2>
                    <p>Get a fresh new beard cut from one of our experienced barber.</p>
                    <a href="/reservation" class="btn btn-block btn-primary">Book Now</a>
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
