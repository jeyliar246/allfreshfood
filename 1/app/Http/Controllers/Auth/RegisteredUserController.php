<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required'],
            'address' => ['required', 'string', 'max:255'],
            'postcode' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            ]);

        $verify_code = rand(1000, 9999);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'postcode' => $request->postcode,
            'phone' => $request->phone,
            'verify_code' => $verify_code,
        ]);

      
        

        event(new Registered($user));

        Auth::login($user);
        
        Mail::to($user->email)->send(new VerifyEmail('Verify Email', [
                    'user' => $user,
                    'verify_code' => $verify_code,
        ]));

        notyf()->info('You have registered successfully.');

        return redirect('/verify');

        // return redirect(route('dashboard', absolute: false));
    }
}
