<?php 
namespace App\Http\Controllers\Auth;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use App\Mail\AccountActivation;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function store(Request $request): RedirectResponse
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email', // Ensure email is unique
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60), // Generate remember_token
        ]);

        // Generate activation token
        $activationToken = Str::random(60);
        $user->activation_token = $activationToken;
        $user->save();

        // Generate activation link
        $activationLink = route('account.activate', ['id' => $user->id, 'token' => $activationToken]);

        // Send activation email
        Mail::to($user->email)->send(new AccountActivation($activationLink));

        // Show success message
        session()->flash('success', 'Email kích hoạt đã được gửi. Vui lòng kiểm tra email của bạn để kích hoạt tài khoản.');

        // Redirect to login page
        return redirect('/login');
    }
}
