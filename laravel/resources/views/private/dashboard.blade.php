<style>
    .text-gray-300 {
        text-decoration: none;
    }
    .haircut-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 50px;
    }
    .haircut-img {
        width: 400px;
        height: 400px;
        margin-bottom: 30px;
    }
    .haircut-description {
        text-align: justify;
        line-height: 1.5;
        font-size: 20px;
    }
</style>
<?php
require base_path('resources/views/partials/head.view.php');
require base_path('resources/views/partials/loggedin.navigation.view.php');
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
        <div class="col-md-4 col-md-offset-4" style="margin-top:20px; margin-left: 30px;width: 1260px">
            <h4>Welcome {{$data->name}}</h4>
            <hr>
            @if(session('fail'))
                <div class="alert alert-danger" id="fail-message">
                    {{ session('fail') }}
                </div>
            @endif
            <script>
                setTimeout(function() {
                    document.getElementById('fail-message').style.display = 'none';
                }, 10000);
            </script>
            <div class="haircut-info" style="float: right" >
                <img src="images/8.png" alt="Haircut" class="haircut-img">
            </div>
            <div style="padding-right:10px;width: 770px ">
                <h2>Haircut</h2>
                <p class="haircut-description">At our Barber Shop, we understand that getting a great haircut is essential to feeling confident and looking your best. That's why we offer a wide range of haircut styles to choose from, so you can find the perfect one to suit your individual needs and preferences. Our expert stylists are trained to work with all types of hair and can help you achieve the exact look you're going for, whether you want something classic and timeless or something more edgy and modern.</p>
                <p class="haircut-description">In addition to our extensive range of haircut styles, we also use only the highest quality tools and products to ensure that you get the best possible haircut every time you visit us. From our precision cutting shears to our top-of-the-line hair products, we spare no expense in ensuring that you receive the best possible service and results.</p>
                <p class="haircut-description">So why wait? Come in today and let us give you the perfect haircut! With our expert stylists, top-of-the-line tools and products, and commitment to customer satisfaction, you're guaranteed to leave our salon feeling confident, refreshed, and ready to take on the world.</p>
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
