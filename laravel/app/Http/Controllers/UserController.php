<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
class UserController extends Controller
{
    public function GetUserData()
    {
        $users= User::all();
        return view('private.reservation', ['users'=>$users]);
    }
    public function GetUserDataForAdmin()
    {
        $users= User::all();
        return view('admin.users', ['users'=>$users]);
    }
    public function DeleteUser(User $user)
    {
        $user->delete();
        return redirect('users')->with('success', 'User deleted successfully');
    }
    public function AddUser(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:5|max:12',
                'phone' => 'required|regex:/^\+36\d{9}$/',
                'role' => 'required|in:0,1', // Validate the role value
            ], [
                'phone.required' => 'Phone number is required.',
                'phone.regex' => 'Phone number must be in the format "+36/20/30/70XXXXXXX',
                'role.required' => 'Role is required.',
                'role.in' => 'Invalid role value.',
            ]);

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role; // Set the role based on the selected value
            $user->password = Hash::make($request->password);
            $user->phone = $request->phone;
            $res = $user->save();

            if ($res) {
                return redirect('users')->with('success', 'You have added a new user successfully');
            } else {
                return redirect('users')->with('fail', 'Not correct information');
            }
        } catch (\Exception $e) {
            return redirect('users')->with('fail', 'An error occurred while adding the user');
        }
    }
    public function Edit(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:5|max:12',
            'phone' => 'required|regex:/^\+36\d{9}$/',
        ], [
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Phone number must be in the format "+36XXXXXXXXX".',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->role = $request->role; // Update the role based on the submitted value
        $user->phone = $request->phone; // Update the phone number based on the submitted value
        $user->save();
        return redirect('users')->with('success', 'User edited successfully');
    }
    public function EditUserData(Request $request, User $user)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|regex:/^\+36\d{9}$/',
        ], [
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Phone number must be in the format "+36XXXXXXXXX".',
        ]);
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->updated_at = Carbon::now();
        $user->save();
        if($user){
            return redirect('profile')->with('profileSuccess', 'User edited successfully');
        }
       else  return redirect('profile')->with('profileFail', 'Something went wrong');
    }
}
