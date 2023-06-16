<style>
    .text-gray-300 {
        text-decoration: none;
    }
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    header {
        background-color: #333;
        color: #fff;
    }
    nav ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    nav li {
        display: inline-block;
        margin-right: 20px;
    }

    nav a {
        color: #fff;
        text-decoration: none;
    }

    nav a:hover {
        text-decoration: underline;
    }

    main {
        padding: 20px;
    }

    .gallery-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .gallery-item {
        margin: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        overflow: hidden;
    }

    .gallery-item img {
        display: block;
        width: 100%;
        height: auto;
    }

    .gallery-caption {
        background-color: rgba(0, 0, 0, 0.7);
        color: #fff;
        padding: 10px;
        text-align: center;
    }

</style>
<?php
require base_path('resources/views/partials/head.view.php');

if(Session()->has('loginId')) {
    require base_path('resources/views/partials/loggedin.navigation.view.php');
} else {
    require base_path('resources/views/partials/navigation.view.php');
}
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
    <link rel="stylesheet" href="css/gallery.style.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-4" style="margin-top:20px; margin-left: 30px; width:1200px">
            <h4>Gallery</h4>
            <hr>
            <div class="gallery-container">
                <div class="gallery-item">
                    <img class="h-8 w-8" src="images/2.jpg" alt="Your Company" style="width: 100%;height: 255px;">
                    <div class="gallery-caption">
                        <p>Short Haircut</p>
                    </div>
                </div>
                <div class="gallery-item">
                    <img class="h-8 w-8" src="images/1.jpg" alt="Your Company" style="width: 100%; height: 255px;">
                    <div class="gallery-caption">
                        <p>Extra Haircut</p>
                    </div>
                </div>
                <div class="gallery-item">
                    <img class="h-8 w-8" src="images/3.jpg" alt="Your Company" style="width: 100%; height: 255px;">
                    <div class="gallery-caption">
                        <p>Fade Haircut</p>
                    </div>
                </div>
                <div class="gallery-item">
                    <img class="h-8 w-8" src="images/4.jpg" alt="Your Company" style="width: 100%; height: 255px;">
                    <div class="gallery-caption">
                        <p>Layered Haircut</p>
                    </div>
                </div>
                <div class="gallery-item">
                    <img class="h-8 w-8" src="images/5.jpeg" alt="Your Company" style="width: 100%; height: 255px;">
                    <div class="gallery-caption">
                        <p>Pixie Haircut</p>
                    </div>
                </div>
                <div class="gallery-item">
                    <img class="h-8 w-8" src="images/6.jpg" alt="Your Company" style="width: 100%; height: 255px;">
                    <div class="gallery-caption">
                        <p>Buzz cut</p>
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
?>
