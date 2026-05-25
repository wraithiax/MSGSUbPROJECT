@extends('format.layout')

@section('title')
    Reset Password
@endsection

@section('content')
<style>
    /* HIDE NAVBAR */
    nav {
        display: none !important;
    }

    /* BACKGROUND DESIGN */
    body {
        background: linear-gradient(135deg, #ffe4f1, #fff0f7);
    }

    .background-design {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at top left, rgba(236,72,153,0.15), transparent 40%),
                    radial-gradient(circle at bottom right, rgba(219,39,119,0.15), transparent 40%);
        z-index: -1;
    }

    /* CENTER EVERYTHING */
    .center-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        text-align: center;
    }

    /* HEADER */
    .page-header {
        margin-bottom: 30px;
    }

    .page-header h1 {
        color: #ec4899;
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0;
    }

    .page-header p {
        color: #9f1239;
        font-size: 1.1rem;
        margin-top: 0.5rem;
    }

    /* FORM CONTAINER */
    .form-container {
        width: 100%;
        max-width: 500px;
        background: #ffffff;
        padding: 2.5rem;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(236, 72, 153, 0.2);
        text-align: left;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        color: #ec4899;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .form-group .required {
        color: #ef4444;
    }

    .form-group input {
        width: 100%;
        padding: 0.9rem;
        border: 2px solid #fce7f3;
        border-radius: 10px;
        font-size: 1rem;
        transition: 0.2s;
        box-sizing: border-box;
    }

    .form-group input:focus {
        border-color: #ec4899;
        outline: none;
        box-shadow: 0 0 0 3px rgba(236,72,153,0.2);
    }

    /* PASSWORD WRAPPER */
    .password-wrapper {
        position: relative;
        width: 100%;
    }

    .password-wrapper input {
        padding-right: 40px;
    }

    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #ec4899;
        font-size: 1.2rem;
        user-select: none;
    }

    .toggle-password:hover {
        color: #db2777;
    }

    /* BUTTONS */
    .button-group {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-submit {
        padding: 0.85rem 2rem;
        background: linear-gradient(135deg, #ec4899, #db2777);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 1.05rem;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 6px 12px rgba(236, 72, 153, 0.3);
        transition: 0.3s;
        width: 100%;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 18px rgba(236, 72, 153, 0.4);
    }

    .btn-link {
        text-align: center;
        color: #ec4899;
        text-decoration: none;
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-link:hover {
        color: #db2777;
        text-decoration: underline;
    }

    /* ERROR MESSAGE */
    .error-message {
        width: 100%;
        padding: 1.5rem;
        background-color: #fee2e2;
        border-left: 5px solid #ef4444;
        border-radius: 8px;
        margin-bottom: 2rem;
        color: #7f1d1d;
    }

    .error-message h3 {
        margin-top: 0;
        color: #991b1b;
    }

    .error-message ul {
        margin: 1rem 0;
        padding-left: 1.5rem;
    }

    .error-message li {
        margin: 0.5rem 0;
        font-weight: 500;
    }
</style>

<div class="background-design"></div>

<div class="center-wrapper">
    <div class="page-header">
        <h1>Reset Password</h1>
        <p>Enter your new password below.</p>
    </div>

    @if($errors->any())
    <div class="error-message">
        <h3>Error</h3>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('password.reset.submit') }}" method="POST" class="form-container">
        @csrf
        
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="form-group">
            <label for="password">New Password <span class="required">*</span></label>
            <div class="password-wrapper">
                <input type="password" name="password" id="password" placeholder="Enter new password" required>
                <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
            </div>
            @error('password')
                <small style="color: #ef4444; display: block; margin-top: 0.25rem;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password <span class="required">*</span></label>
            <div class="password-wrapper">
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm new password" required>
                <span class="toggle-password" onclick="togglePassword('password_confirmation')">👁️</span>
            </div>
            @error('password_confirmation')
                <small style="color: #ef4444; display: block; margin-top: 0.25rem;">{{ $message }}</small>
            @enderror
        </div>

        <div class="button-group">
            <button type="submit" class="btn-submit">Reset Password</button>
            <a href="{{ route('login') }}" class="btn-link">Back to Login</a>
        </div>
    </form>
</div>

<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = event.target;
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.textContent = '🙈';
        } else {
            field.type = 'password';
            icon.textContent = '👁️';
        }
    }
</script>
