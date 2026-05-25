<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LoginAttempt;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLogin()
    {
        if (Session::has('user_id')) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    /**
     * Handle login submission
     */
    public function submitLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

        $email = $request->email;

        // Check if account is locked
        $loginAttempt = LoginAttempt::where('email', $email)->first();
        
        if ($loginAttempt && $loginAttempt->locked && $loginAttempt->locked_until && now() < $loginAttempt->locked_until) {
            return back()->withErrors(['email' => 'Account is locked due to too many failed login attempts. Please try again later or use forgot password to reset your password.'])->withInput();
        }

        // Find user by email
        $user = User::where('email', $email)->first();

        // Verify password
        if (!$user || !Hash::check($request->password, $user->password)) {
            // Track failed attempt
            if ($loginAttempt) {
                $loginAttempt->increment('attempts');
                $loginAttempt->update(['last_attempt_at' => now()]);
            } else {
                LoginAttempt::create([
                    'email' => $email,
                    'attempts' => 1,
                    'last_attempt_at' => now(),
                ]);
                $loginAttempt = LoginAttempt::where('email', $email)->first();
            }

            // Lock account after 3 failed attempts
            if ($loginAttempt->attempts >= 3) {
                $loginAttempt->update([
                    'locked' => true,
                    'locked_until' => now()->addHours(1),
                ]);
                return back()->withErrors(['email' => 'Account is locked due to too many failed login attempts. Please try again after 1 hour or use forgot password.'])->withInput();
            }

            $attemptsLeft = 3 - $loginAttempt->attempts;
            return back()->withErrors(['email' => "Invalid email or password. You have $attemptsLeft attempt(s) left."])->withInput();
        }

        // Reset login attempts on successful login
        if ($loginAttempt) {
            $loginAttempt->update([
                'attempts' => 0,
                'locked' => false,
                'locked_until' => null,
            ]);
        }

        // Store user in session
        Session::put('user_id', $user->id);
        Session::put('user', $user);
        Session::put('user_email', $user->email);
        Session::put('user_role', $user->normalizedRole());
        Session::put('force_password_change', $user->force_password_change);

        $message = $user->force_password_change
            ? 'Login successful. Please change your password first before continuing.'
            : 'Login successful! Welcome, ' . $user->email;

        return redirect()->route('home')->with('success', $message);
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Session::flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password submission
     */
    public function submitForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email.',
            'email.exists' => 'This email is not registered in our system.',
        ]);

        $email = $request->email;

        // Generate reset token
        $token = Str::random(64);
        $expiresAt = now()->addHours(1);

        // Create password reset record
        PasswordReset::where('email', $email)->update(['used' => true]);
        
        PasswordReset::create([
            'email' => $email,
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);

        // Send reset link to email (for now, we'll just show the link)
        $resetLink = route('password.reset', ['token' => $token]);
        
        return back()->with('success', 'Password reset link has been sent to your email. Click here to reset: ' . $resetLink);
    }

    /**
     * Show password reset form
     */
    public function showResetPassword($token)
    {
        $reset = PasswordReset::where('token', $token)->where('used', false)->first();

        if (!$reset || now() > $reset->expires_at) {
            return redirect()->route('login')->withErrors(['token' => 'Invalid or expired reset token.']);
        }

        return view('auth.reset-password', ['token' => $token, 'email' => $reset->email]);
    }

    /**
     * Handle password reset submission
     */
    public function submitResetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email.',
            'email.exists' => 'This email is not registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        $reset = PasswordReset::where('token', $request->token)
                              ->where('email', $request->email)
                              ->where('used', false)
                              ->first();

        if (!$reset || now() > $reset->expires_at) {
            return back()->withErrors(['token' => 'Invalid or expired reset token.']);
        }

        // Update user password
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password),
            'force_password_change' => false,
        ]);

        // Mark reset token as used
        $reset->update(['used' => true]);

        // Reset login attempts
        LoginAttempt::where('email', $request->email)->update([
            'attempts' => 0,
            'locked' => false,
            'locked_until' => null,
        ]);

        return redirect()->route('login')->with('success', 'Password has been reset successfully. Please log in with your new password.');
    }
}

