<?php

namespace App\Http\Controllers;
use App\Models\Reservations;
use App\Models\User;
class RerservationController extends Controller
{
    public function GetReservations()
    {
        $reservations= Reservations::all();
        return view('admin.admin_reservations', ['reservations'=>$reservations]);
    }
    public function user()
    {
        $user= User::all();
        return view('admin.admin_reservations', ['user'=>$user]);
    }


}
