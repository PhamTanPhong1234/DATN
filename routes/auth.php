<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\AccountActivation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;


Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    // Route::post('register', [RegisteredUserController::class, 'store']);
    Route::post('register', function(Request $request){
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:users,email',
        //     'password' => ['required', 'confirmed', Password::defaults()],
        // ]);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ], [

            'name.required' => 'Tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại trong hệ thống.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);
    

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60), 
        ]);
        
        Log::info('User registered:', ['user' => $user]);
        
        $activationToken = Str::random(60);
        $user->activation_token = $activationToken;
        $user->save();
        $activationLink = route('account.activate', ['id' => $user->id, 'token' => $activationToken]);
        Mail::to($user->email)->send(new AccountActivation($activationLink));
        Log::info('Activation email sent to:', ['user' => $user]);
        
        session()->flash('success', 'Email kích hoạt đã được gửi. Vui lòng kiểm tra email của bạn để kích hoạt tài khoản.');
        
        return redirect('/login');
    });

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});




Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
