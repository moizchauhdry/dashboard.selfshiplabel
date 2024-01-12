<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use \App\Models\Billing;
use \App\Models\Billpay;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Country;


class AccountsContoller extends Controller
{
    public function profile()
    {

        $user = Auth::user();

        $profile = [
            'name' => $user->name,
            'email' => $user->email,
            'country' => $user->country,
            'city' => $user->city,
            'state' => $user->state,
            'postal_code' => $user->postal_code,
            'phone_no' => $user->phone_no,
            'address1' => $user->address1,
            'address2' => $user->address2,
        ];

        $countries = Country::all(['id', 'nicename as name'])->toArray();

        return Inertia::render('Profile', [
            'profile' => $profile,
            'countries' => $countries,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = User::find(Auth::user()->id);

        $validated = $request->validate([
            'name' => 'required|string',
            'phone_no' => 'required|string|max:255|unique:users,phone_no,' . $user->id,
            // 'email' => 'required|email',
            // 'country' => 'required|string',
            // 'state' => 'required|string',
            // 'city' => 'required|string',
            // 'postal_code' => 'required|string',
            // 'address1' => 'required|string',
        ]);

        $user->name = $validated['name'];
        $user->phone_no = $validated['phone_no'];
        // $user->email = $validated['email'];
        // $user->country = $validated['country'];
        // $user->state = $validated['state'];
        // $user->city = $validated['city'];
        // $user->postal_code = $validated['postal_code'];
        // $user->address1 = $validated['address1'];
        // $user->address2 = $request->input('address2');
        $user->save();

        return redirect()->back()->with('success', 'The profile have been updated successfully.');
    }


    //AccountsContoller

    public function billing()
    {

        $user_type = \Auth::user()->type;
        $user_id = \Auth::user()->id;

        if ($user_type == 'admin')
            $billings  = Billing::paginate(25);
        elseif ($user_type == 'customer')
            $billings  = Billing::where('user_id', $user_id)->paginate(25);
        return Inertia::render('Billings', compact('billings'));
    }

    public function payments()
    {

        $user_type = \Auth::user()->type;
        $user_id = \Auth::user()->id;

        if ($user_type == 'admin')
            $payments  = Billpay::with('user')->paginate(25);
        elseif ($user_type == 'customer')
            $payments  = Billpay::where('user_id', $user_id) - with('user')->paginate(25);

        return Inertia::render('Payments', compact('payments'));
    }

    public function edit()
    {

        return Inertia::render('UpdatePassword');
    }

    public function update(Request $request)
    {

        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8',
            'confirm_new_password' => 'required|string|min:8',

        ]);

        if ($request->new_password != $request->confirm_new_password) {
            return redirect()->back()->withErrors(['Password and Confirm password didnot match'])->withInput();;
        }

        $hashedPassword = \Auth::user()->password;

        if (\Hash::check($request->old_password, $hashedPassword)) {
            if (!\Hash::check($request->new_password, $hashedPassword)) {

                $users = \App\Models\User::find(\Auth::user()->id);
                $users->password = Hash::make($request->new_password);

                \App\Models\User::where('id', \Auth::user()->id)->update(array('password' =>  $users->password));
                session()->flash('success', 'password updated successfully');
                return redirect()->back();
            } else {
                session()->flash('error', 'new password can not be the old password!');
                return redirect()->back();
            }
        } else {
            return redirect()->back()->withErrors(['old password doesnt matched'])->withInput();;
        }
    }
}
