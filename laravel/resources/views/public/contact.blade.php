<style>
    .text-gray-300 {
        text-decoration: none;
    }
    h1 {
        font-size: 2em;
        text-align: center;
    }
    .container2 {
        background-color: #fff;
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }
    h2 {
        font-size: 1.5em;
    }
    p {
        margin-top: 10px;
        margin-bottom: 10px;
        line-height: 1.5;
    }
    @media screen and (max-width: 480px) {
        .container {
            padding: 10px;
        }
    }
    form{
        background-color: #fff;
        max-width: 600px;
        margin: 0 auto;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }
    label {
        display: block;
        font-size: 1.2em;
        margin-bottom: 5px;
    }
    input[type=text], textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 2px solid #ccc;
        border-radius: 4px;
        resize: vertical;
    }
    @media screen and (max-width: 480px) {
        form {
            padding: 10px;
        }
        input[type=text], textarea {
            padding: 5px;
        }
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
    <title>Contact Us - Magic Hair</title>
    <link href="contact.style.css" rel="stylesheet" >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
          crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4" style="margin-top:20px; margin-left: 30px; width:600px;">
            <h4>Contact</h4>
            <hr>
            <div class="container2" style=" height: 480px">
                <h2>Address</h2>
                <p>9011 Győr</p>
                <p>Bárányréti út 41/B</p>

                <h2>Phone</h2>
                <p>+36 20 256 1552</p>

                <h2>Email</h2>
                <p><a href="mailto:info@hairshop.com">info@magichair.com</a></p>

                <h2>Hours of Operation</h2>
                <p>Monday-Friday: 8am-4pm</p>
                <p>Saturday: Closed</p>
                <p>Sunday: Closed</p>
            </div>
        </div>
        @if(Session()->has('loginId'))
            <div class="col-md-4 col-md-offset-4" style="margin-top:20px; margin-left: 30px; width:600px; height: auto">
                <h4>Contact Us With Any Problem</h4>
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
                <form action="{{ route('contact-submit') }}" method="POST" style="height: auto">
                    @csrf
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" required></textarea>
                    <button class="btn btn-block btn-primary" style="margin-top: 10px" type="submit">Send</button>
                </form>
            </div>
            @else
            <div class="col-md-4 col-md-offset-4" style="margin-top:20px; margin-left: 30px; width:600px; height: auto">
                <h4>Contact Us</h4>
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
                <form action="{{ route('contact-submit') }}" method="POST" style="height: auto">
                    @csrf
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" required>

                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" name="phone" required>

                    <label for="message">Message:</label>
                    <textarea id="message" name="message" required></textarea>
                    <button class="btn btn-block btn-primary" style="margin-top: 10px" type="submit">Send</button>
                </form>
            </div>
            @endif
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous">
</script>

</html>
<?php
require base_path('resources/views/partials/foot.view.php');
