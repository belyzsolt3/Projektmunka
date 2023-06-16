<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;

class CustomAuthController extends Controller
{
    public function login()
    {
        return view('auth.login');

    }
    public function registration()
    {
        return view('auth.registration');
    }
    public function registerUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|max:12',
            'phone' => 'required|regex:/^\+36\d{9}$/',
        ], [
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Phone number must be in the format "+36/20/30/70XXXXXXX',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = 0;
        $user->password = Hash::make($request->password);
        $user->phone = $request->phone;

        if ($user->save()) {
            return back()->with('success', 'You have registered successfully');
        } else {
            return back()->with('fail', 'Something went wrong');
        }
    }
    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5|max:12'
        ]);

        $user = User::where('email', '=', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $user->last_login = now();
                $user->save();
                if ($user->role == '1') {
                    $request->session()->put('loginId', $user->id);
                    return redirect('admin-dashboard');
                } else {
                    $request->session()->put('loginId', $user->id);
                    return redirect('dashboard');
                }
            } else {
                return back()->with('fail', 'Password does not match');
            }
        } else {
            return back()->with('fail', 'This email is not registered');
        }
    }

    public function dashboard(){
        $data = array();
        if (Session::has('loginId')){
            $data = User::where('id','=', Session::get('loginId'))->first();
        }
        return view('auth.dashboard', compact('data'));
    }
    public function logout(){
        if(Session::has('loginId')){
            Session::pull('loginId');
            return redirect('/');
        }
    }
}
