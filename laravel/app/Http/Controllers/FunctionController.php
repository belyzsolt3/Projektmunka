<?php

namespace App\Http\Controllers;
use App\Models\Bookings;
use Illuminate\Http\Request;
use App\Models\Reservations;
use App\Models\Services;
use App\Models\User;
use App\Models\Appointments;
use App\Models\Contacts;
use Hash;
use Carbon\Carbon;
use Carbon\CarbonInterval;
class FunctionController extends Controller

{
    public function reservationUser(Request $request)
    {
        $user = User::find(Session()->get('loginId'));
        $service_id = $request->input('service_id');
        $appointment_id = $request->input('appointment_id');
        $reservation = new Reservations;
        $reservation->user_id = $user->id;
        $reservation->service_id = $service_id;
        $reservation->appointment_id = $appointment_id;
        $appointment = Appointments::find($appointment_id);
        $service = Services::find($service_id);
        $appointment->free = 0;
        $appointment->save();

        if ($service->time > '01:00:00') {
            $startTime = Carbon::createFromTimeString($appointment->start_time);
            $serviceDuration = $service->time;
            list($hours, $minutes, $seconds) = explode(':', $serviceDuration);
            $endTime = $startTime->copy()->addHours($hours)->addMinutes($minutes)->addSeconds($seconds);
            $appointment->end_time = $endTime;
            $appointment->free = 0;
            $appointment->save();
            $subsequentAppointments = Appointments::where('date', $appointment->date)
                ->where('start_time', '>', $appointment->start_time)
                ->where('start_time', '<', $endTime)
                ->get();
            foreach ($subsequentAppointments as $subsequentAppointment) {
                $subsequentAppointment->delete();
            }
        }

        if ($reservation->save()) {
            return redirect('reservation')->with('success', 'Your reservation has been successfully made!');
        } else {
            return redirect('reservation')->with('fail', 'Something went wrong, please try again later.');
        }
    }
    public function GetServicesData(Request $request)
    {
        $services= Services::all();
        return view('private.reservation', ['services'=>$services]);
    }
    public function submitContactForm(Request $request)
    {
        $request->validate([
            'message' => 'required',
        ]);

        if (Session()->has('loginId')) {
            $user = User::find(Session()->get('loginId'));
            $contact = Contacts::create([
                'user_id'=>$user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'handled' => 0,
                'message' => $request->message,
            ]);
        } else {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
            ]);
            $contact = Contacts::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'handled' => 0,
                'message' => $request->message,
            ]);
        }

        if ($contact) {
            return redirect('contact')->with('success', 'Your message has been sent successfully.');
        } else {
            return redirect('contact')->with('fail', 'Failed to send your message. Please try again.');
        }
    }
    public function update(Request $request, Contacts $contact)
    {
        $contact->handled = $request->status;
        $contact->save();
        return redirect('requests')->with('success', 'Request updated successfully.');
    }
    public function destroy(Contacts $contact)
    {
        $contact->delete();
        return redirect('requests')->with('success', 'Request deleted successfully.');
    }
    public function GetAppointments(Request $request)
    {
        $date = $request->input('date');
        $serviceId = $request->input('service');

        if (empty($serviceId)) {
            return redirect('reservation')->with('fail', 'Please select a service.');
        }

        if (empty($date)) {
            return redirect('reservation')->with('fail', 'Please select a date.');
        }

        $selectedService = Services::find($serviceId);
        $requestedDate = Carbon::createFromFormat('Y-m-d', $date);
        $currentDate = Carbon::today();

        if ($requestedDate->lt($currentDate)) {
            return redirect('reservation')->with('fail', 'You cannot make a reservation in the past.');
        }

        if ($requestedDate->isToday()) {
            $currentTime = Carbon::now()->toTimeString();
            $appointments = Appointments::where('date', $date)
                ->where('free', 1)
                ->whereTime('start_time', '>', $currentTime)
                ->get();
        } else {
            $appointments = Appointments::where(function ($query) use ($date) {
                $query->where('date', $date)
                    ->orWhere('start_time', 'LIKE', "%{$date}%")
                    ->orWhere('end_time', 'LIKE', "%{$date}%");
            })
                ->where('free', 1)
                ->orderBy('start_time')
                ->get();
        }

        if ($appointments->isEmpty()) {
            return redirect('reservation')->with('fail', 'Unfortunately, there are no available appointments on this day.');
        }

        $availableAppointments = $this->filterAppointmentsByDuration($appointments, $selectedService->duration);
        if ($availableAppointments->isEmpty()) {
            return redirect('reservation')->with('fail', 'Unfortunately, there are no available appointments for the selected service on this day.');
        }
        $services = Services::all();
        return view('private.reservation', [
            'availableAppointments'  => $availableAppointments,
            'services' => $services,
            'selectedService' => $selectedService
        ]);
    }

    private function filterAppointmentsByDuration($appointments, $serviceDuration)
    {
        $availableAppointments = collect();

        foreach ($appointments as $appointment) {
            $endTime = Carbon::createFromTimeString($appointment->start_time)->addHours($serviceDuration);

            // Check if the appointment duration is within the hairshop's working hours
            if ($endTime->lte(Carbon::createFromTimeString('16:00'))) {
                $availableAppointments->push($appointment);
            }
        }

        return $availableAppointments;
    }
    public function CancelReservation(Request $request, $id)
    {
        try {
            $reservation = Reservations::findOrFail($id);
            $appointment = Appointments::findOrFail($reservation->appointment_id);
            $startTime = new \DateTime($reservation->appointment->start_time);
            $endTime = new \DateTime($reservation->appointment->end_time);
            $duration = $endTime->diff($startTime);

            if ($duration->h > 1) {
                $appointment->free = 1;
                $appointment->end_time = $startTime->add(new \DateInterval('PT1H'))->format('Y-m-d H:i:s');
                $appointment->save();
                $endTime = new \DateTime($appointment->end_time);
                $newStartTime = clone $endTime;

                for ($i = 1; $i < $duration->h; $i++) {
                    $newEndTime = clone $newStartTime;
                    $newEndTime->add(new \DateInterval('PT1H'));

                    $newAppointment = new Appointments();
                    $newAppointment->date = $reservation->appointment->date;
                    $newAppointment->start_time = $newStartTime->format('Y-m-d H:i:s');
                    $newAppointment->end_time = $newEndTime->format('Y-m-d H:i:s');
                    $newAppointment->free = 1;
                    $newAppointment->save();

                    $newStartTime = clone $newEndTime;
                }
            } else {
                $appointment->free = 1;
                $appointment->save();
            }

            $reservation->delete();
            return redirect('profile')->with('CancelSuccess', 'Reservation has been canceled successfully.');
        } catch (ModelNotFoundException $exception) {
            return redirect('profile')->with('CancelFail', 'Reservation not found.');
        } catch (\Exception $exception) {
            return redirect('profile')->with('CancelFail', 'Something went wrong. Please try again later.');
        }
    }
    public function DeleteReservation($id)
    {
        $appointment = Appointments::find($id);
        if ($appointment->free == 0){
            $reservation = Reservations::find($appointment->id);
            $reservation->delete();
        }
        if ($appointment) {
            $appointment->delete();
            return redirect()->back()->with('Ressuccess', 'Appointment deleted successfully.');
        } else {
            return redirect()->back()->with('Resfail', 'Failed to delete reservation.');
        }
    }
    public function DeleteService($id)
    {
        $Services = Services::find($id);

        if ($Services) {
            $Services->delete();
            return redirect()->back()->with('Sersuccess', 'Reservation deleted successfully.');
        } else {
            return redirect()->back()->with('Serfail', 'Failed to delete reservation.');
        }
    }
    public function EditService(Request $request, $id)
    {
        $service = Services::find($id);

        if (!$service) {
            return redirect()->back()->with('Serfail', 'Service not found.');
        }

        $service->name = $request->input('name');
        $service->price = $request->input('price');
        $service->time = $request->input('time');

        if ($service->save()) {
            return redirect()->back()->with('Sersuccess', 'Service updated successfully.');
        } else {
            return redirect()->back()->with('Serfail', 'Failed to update service. Please try again.');
        }
    }
    public function AddService(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'time' => 'required',
        ]);

        $service = new Services;
        $service->name = $validatedData['name'];
        $service->price = $validatedData['price'];
        $service->time = $validatedData['time'];

        if ($service->save()) {
            return redirect()->back()->with('Sersuccess', 'Service added successfully!');
        } else {
            return redirect()->back()->with('Serfail', 'Failed to add service. Please try again.');
        }
    }
    public function SaveSchedule(Request $request)
    {
        $date = $request->input('date');
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');
        $start = Carbon::createFromFormat('H:i', $start_time);
        $end = Carbon::createFromFormat('H:i', $end_time);
        $interval = CarbonInterval::hour(1);
        if (Carbon::now()->startOfDay()->greaterThan(Carbon::parse($date))) {
            return redirect()->back()->with('Schfail', 'You cannot make an appointment in the past.');
        }
        try {
            while ($start->lt($end)) {
                $appointment = new Appointments;
                $appointment->date = $date;
                $appointment->start_time = $start->format('H:i');
                $start->add($interval);
                $appointment->end_time = $start->format('H:i');
                $appointment->free = 1;
                $appointment->save();
            }
            return redirect()->back()->with('Schsuccess', 'Schedule saved successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('Schfail', 'Failed to save the schedule. Please try again later.');
        }
    }
    public function GetAppointmentsData(Request $request)
    {
        $selectedDate = $request->input('date');
        $selectedServiceId = $request->input('service');
        $appointments = Appointments::where(function ($query) use ($selectedDate) {
            $query->where('date', $selectedDate)
                ->orWhere('start_time', 'LIKE', "%{$selectedDate}%")
                ->orWhere('end_time', 'LIKE', "%{$selectedDate}%");
        })
            ->orderBy('start_time')
            ->get();
        $selectedService = Services::find($selectedServiceId);
        $availableAppointments = [];
        $scheduleData = [];
        foreach ($appointments as $appointment) {
            $scheduleRow = [];

            if ($appointment->free == 1) {
                $scheduleRow['No'] = count($scheduleData) + 1;
                $scheduleRow['Id'] = '';
                $scheduleRow['AppointmentId'] = $appointment->id;
                $scheduleRow['User name'] = '';
                $scheduleRow['Service'] = '';
                $scheduleRow['Time period'] = date('H:i', strtotime($appointment->start_time)) . ' - ' . date('H:i', strtotime($appointment->end_time));
                $scheduleRow['Date'] = $appointment->date;
                $scheduleRow['Status'] = 'FREE';
                $scheduleData[] = $scheduleRow;
            } else {
                $reservation = Reservations::where('appointment_id', $appointment->id)->first();
                $user = User::find($reservation->user_id);
                $service = Services::find($reservation->service_id);
                $scheduleRow['No'] = count($scheduleData) + 1;
                $scheduleRow['Id'] = $user->id;
                $scheduleRow['AppointmentId'] = $appointment->id;
                $scheduleRow['Date'] = $appointment->date;
                $scheduleRow['User name'] = $user->name;
                $scheduleRow['Service'] = $service->name;
                $scheduleRow['Time period'] = date('H:i', strtotime($appointment->start_time)) . ' - ' . date('H:i', strtotime($appointment->end_time));
                $scheduleRow['Status'] = ($reservation) ? 'RESERVED' : 'FREE';
                $scheduleData[] = $scheduleRow;
            }
        }

        return view('admin.admin_reservations', [
            'scheduleData' => $scheduleData,
            'selectedDate' => $selectedDate,
            'selectedService' => $selectedService,
            'availableAppointments' => $availableAppointments,
        ]);
    }
}
