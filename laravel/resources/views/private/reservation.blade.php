<style>
    .text-gray-300 {
        text-decoration: none;
    }
</style>
<?php
require base_path('resources/views/partials/head.view.php');
require base_path('resources/views/partials/loggedin.navigation.view.php');
require base_path('resources/views/partials/header.view.php');
use App\Models\Appointments;
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
            <h4>Reservation</h4>
            <hr>
            <form action="{{ route('get-appointments') }}" method="GET">
                @csrf
                <div class="form-group">
                    <label for="service">Service</label>
                    <select name="service" id="service" class="form-control">
                        <option value="">Select a service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" class="form-control">
                </div>
                <br>
                <div class="form-group">
                    <button class="btn btn-block btn-primary" type="submit">Check Schedule</button>
                </div>
            </form>
        </div>
        <div class="col-md-6" style="margin-top: 20px">
            <h4>Free Appointments</h4>
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
            @if(isset($availableAppointments) && count($availableAppointments) > 0)
                <table class="table">
                    <thead>
                    <th>No.</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Service</th>
                    <th>Action</th>
                    </thead>
                    <tbody>
                    @php
                        $lastAppointment = $availableAppointments->last();
                        $lastAppointmentEndTime = Carbon\Carbon::createFromTimeString($lastAppointment['end_time']);
                    @endphp
                    @foreach($availableAppointments as $index => $appointment)
                        @php
                            $startTime = date('H:i', strtotime($appointment['start_time']));
                            $serviceDuration = $selectedService->time;
                            list($hours, $minutes, $seconds) = explode(':', $serviceDuration);
                            $endTime = Carbon\Carbon::createFromTimeString($appointment['start_time'])
                                ->addHours($hours)
                                ->addMinutes($minutes)
                                ->addSeconds($seconds);

                            $nextAppointment = isset($availableAppointments[$index + 1]) ? $availableAppointments[$index + 1] : null;
                            $nextAppointmentStartTime = $nextAppointment ? Carbon\Carbon::createFromTimeString($nextAppointment['start_time']) : null;

                            // Check if the appointment end time is within the working hours
                            // and if it is greater than the start time of the next appointment
                            $validAppointment = true;
                            if ($serviceDuration > "01:00:00") {
                                $durationInHours = intval($hours);
                                $currentAppointmentIndex = $index;
                                for ($i = 1; $i < $durationInHours; $i++) {
                                    $currentAppointmentIndex++;
                                    if ($currentAppointmentIndex >= count($availableAppointments)) {
                                        $validAppointment = false;
                                        break;
                                    }

                                    $currentAppointment = $availableAppointments[$currentAppointmentIndex];

                                    if (!$currentAppointment || !$currentAppointment['free']) {
                                        $validAppointment = false;
                                        break;
                                    }

                                    $currentEndTime = Carbon\Carbon::createFromTimeString($currentAppointment['end_time']);
                                    if ($currentEndTime->gt($endTime)) {
                                        $validAppointment = false;
                                        break;
                                    }
                                }
                                if ($validAppointment) {
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $appointment['date'] }}</td>
                            <td>
                                {{ $startTime }} - {{ $endTime->format('H:i') }}
                            </td>
                            <td>{{ $selectedService->name }}</td>
                            <td>
                                <form action="{{ route('make-reservation') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="service_id"
                                           value="{{ $selectedService->id }}">
                                    <input type="hidden" name="appointment_id"
                                           value="{{ $appointment['id'] }}">
                                    <button type="submit" class="btn btn-primary"
                                            style="margin-bottom: -15px">Reserve
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @php
                            }
                        } else {
                            if ($appointment['free'] == 1) {
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $appointment['date'] }}</td>
                            <td>
                                {{ date('H:i', strtotime($appointment['start_time'])) }} - {{
                                            date('H:i', strtotime($appointment['end_time'])) }}
                            </td>
                            <td>{{ $selectedService->name }}</td>
                            <td>
                                <form action="{{ route('make-reservation') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="service_id"
                                           value="{{ $selectedService->id }}">
                                    <input type="hidden" name="appointment_id"
                                           value="{{ $appointment['id'] }}">
                                    <button type="submit" class="btn btn-primary"
                                            style="margin-bottom: -15px">Reserve
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @php
                            }
                        }
                        @endphp
                    @endforeach
                    </tbody>
                </table>
            @else
                @php return redirect('reservation')->with('fail', 'Unfortunately, there are no available appointments for the selected service on this day.');@endphp
            @endif
        </div>
    </div>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</html>
<?php
require base_path('resources/views/partials/foot.view.php');
