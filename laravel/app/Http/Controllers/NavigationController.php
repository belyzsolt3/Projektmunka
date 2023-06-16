<?php

namespace App\Http\Controllers;
use App\Models\Services;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Reservations;
use App\Models\Bookings;
use App\Models\Contacts;
use Hash;

class NavigationController extends Controller
{
    public function contact(){
        $data = array();
        if (Session::has('loginId')){
            $data = User::where('id','=', Session::get('loginId'))->first();
        }
        return view('public.contact', compact('data'));
    }
    public function gallery(){
        $data = array();
        if (Session::has('loginId')){
            $data = User::where('id','=', Session::get('loginId'))->first();
        }
        return view('public.gallery', compact('data'));
    }
    public function services(){
        $data = array();
        if (Session::has('loginId')){
            $data = User::where('id','=', Session::get('loginId'))->first();
        }
        return view('public.services', compact('data'));
    }
    //Navigation-Private
    public function dashboard(){
        $data = array();
        if (Session::has('loginId')){
            $data = User::where('id','=', Session::get('loginId'))->first();
        }
        return view('private.dashboard', compact('data'));
    }
    public function profile(){
        $data = array();
        if (Session::has('loginId')){
            $data = User::where('id','=', Session::get('loginId'))->first();
        }
        return view('private.profile', compact('data'));
    }
    public function reservation(){
        $data = array();
        $services = Services::All();
        if (Session::has('loginId')){
            $data = User::where('id','=', Session::get('loginId'))->first();
        }
        return view('private.reservation', [
            'data'=>$data,
            'services'=>$services
        ]);
    }
    public function admin_dashboard()
    {
        $totalUsers = User::count();
        $totalReservations = Reservations::count();
        $totalRequests = Contacts::count();
        $AllReservations = Reservations::All();
        $AllRequests = Contacts::All();

        return view('admin.admin_dashboard', [
            'totalUsers' => $totalUsers,
            'totalReservations' => $totalReservations,
            'totalRequests' => $totalRequests,
            'AllReservations' => $AllReservations,
            'AllRequests' => $AllRequests
        ]);
    }
    public function admin_reservations(){
        $data = array();
        if (Session::has('loginId')){
            $data = User::where('id','=', Session::get('loginId'))->first();
        }
        return view('admin.admin_reservations', compact('data'));
    }

    public function users(){
        $data = array();
        if (Session::has('loginId')){
            $users = User::where('id','=', Session::get('loginId'))->first();
        }
        return view('admin.users', compact('data'));
    }
    public function make_schedule(){
        $data = array();
        if (Session::has('loginId')){
            $data = User::where('id','=', Session::get('loginId'))->first();
        }
        return view('admin.make_schedule', compact('data'));
    }
    public function requests(){
        $data = array();
        if (Session::has('loginId')){
            $data = User::where('id','=', Session::get('loginId'))->first();
        }
        return view('admin.requests', compact('data'));
    }
}
