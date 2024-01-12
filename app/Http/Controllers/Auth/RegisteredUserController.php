<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Billing;
use App\Notifications\AdminUserRegistered;
use App\Notifications\UserWelcomeEmail;
use App\Providers\RouteServiceProvider;
use App\Rules\Recaptcha;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_no' => 'required|string|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'hear_from' => 'required|string|max:100',
            // 'captcha_token' => ['required', new Recaptcha]
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'password' => Hash::make($request->password),
            'hear_from' => $request->hear_from,
            'type' => 'customer',
        ]);

        $admins = User::where(['type' => 'admin'])->get();

        try {
            $user->notify(new UserWelcomeEmail());
            Notification::send($admins, new AdminUserRegistered($user));
        } catch (\Throwable $e) {
        }

        event(new Registered($user));
        Auth::login($user);

        // return redirect(RouteServiceProvider::HOME);
        return redirect()->route('dashboard-success');
    }

    public function update_experience(Request $request)
    {
        $user = User::find($request->id);
        $user->experience = $request->experience;
        $user->save();
        // return redirect('/');
    }
}
