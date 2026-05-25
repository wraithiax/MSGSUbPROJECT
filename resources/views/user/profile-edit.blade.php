@extends('format.layout')

@section('title', 'Edit My Profile')

@section('content')
<style>
    .profile-edit-page {
        max-width: 980px;
        margin: 0 auto;
    }

    .profile-edit-header {
        margin-bottom: 1.5rem;
    }

    .profile-edit-header h1 {
        color: #ec4899;
        font-size: 2.4rem;
        font-weight: 800;
        margin: 0;
    }

    .profile-edit-header p {
        color: #9f1239;
        font-size: 1rem;
        margin-top: 0.4rem;
    }

    .profile-card {
        background: #fff;
        border: 1px solid #fbcfe8;
        border-radius: 14px;
        box-shadow: 0 8px 18px rgba(236, 72, 153, 0.14);
        padding: 2rem;
    }

    .profile-form-grid {
        display: grid;
        gap: 1.25rem;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .profile-field {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .profile-field.full {
        grid-column: 1 / -1;
    }

    .profile-field label {
        color: #ec4899;
        font-weight: 700;
    }

    .profile-field input {
        border: 2px solid #fce7f3;
        border-radius: 8px;
        font-size: 1rem;
        padding: 0.8rem;
        width: 100%;
    }

    .profile-field input:focus {
        border-color: #ec4899;
        box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.16);
        outline: none;
    }

    .password-input {
        position: relative;
    }

    .password-input input {
        padding-right: 2.8rem;
    }

    .toggle-password {
        align-items: center;
        background: none;
        border: none;
        color: #ec4899;
        cursor: pointer;
        display: flex;
        height: 100%;
        justify-content: center;
        position: absolute;
        right: 0.65rem;
        top: 0;
        width: 2rem;
    }

    .profile-help {
        color: #6b7280;
        font-size: 0.9rem;
        margin: 0;
    }

    .profile-alert {
        border-radius: 8px;
        margin-bottom: 1rem;
        padding: 1rem;
    }

    .profile-alert.error {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        color: #991b1b;
    }

    .profile-alert.success {
        background: #dcfce7;
        border: 1px solid #86efac;
        color: #166534;
    }

    .profile-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }

    .profile-submit,
    .profile-cancel {
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 700;
        padding: 0.85rem 1.5rem;
        text-decoration: none;
    }

    .profile-submit {
        background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
        color: #fff;
    }

    .profile-cancel {
        background: #e5e7eb;
        color: #374151;
    }

    @media (max-width: 768px) {
        .profile-edit-header h1 {
            font-size: 2rem;
        }

        .profile-card {
            padding: 1.25rem;
        }

        .profile-form-grid {
            grid-template-columns: 1fr;
        }

        .profile-actions {
            flex-direction: column;
        }

        .profile-submit,
        .profile-cancel {
            text-align: center;
            width: 100%;
        }
    }
</style>

<div class="profile-edit-page">
    <div class="profile-edit-header">
        <h1>Edit My Profile</h1>
        <p>Update your account information.</p>
    </div>

    @if ($errors->any())
        <div class="profile-alert error">
            <ul style="margin-left: 1.25rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="profile-alert success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="profile-alert error">{{ session('error') }}</div>
    @endif

    <div class="profile-card">
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="profile-form-grid">
                <div class="profile-field">
                    <label for="username">Username <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required>
                    @error('username')
                        <small style="color: #ef4444;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="profile-field">
                    <label for="email">Email <span style="color: #ef4444;">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <small style="color: #ef4444;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="profile-field">
                    <label for="current_password">Current Password</label>
                    <div class="password-input">
                        <input type="password" name="current_password" id="current_password">
                        <button type="button" class="toggle-password" data-target="current_password" aria-label="Show current password">
                            <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="eye-closed-icon" style="display: none;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <small style="color: #ef4444;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="profile-field">
                    <label for="password">New Password</label>
                    <div class="password-input">
                        <input type="password" name="password" id="password">
                        <button type="button" class="toggle-password" data-target="password" aria-label="Show new password">
                            <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="eye-closed-icon" style="display: none;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                    <p class="profile-help">Leave blank if you do not want to change the password.</p>
                    @error('password')
                        <small style="color: #ef4444;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="profile-field full">
                    <label for="password_confirmation">Confirm New Password</label>
                    <div class="password-input">
                        <input type="password" name="password_confirmation" id="password_confirmation">
                        <button type="button" class="toggle-password" data-target="password_confirmation" aria-label="Show password confirmation">
                            <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="eye-closed-icon" style="display: none;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <small style="color: #ef4444;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="profile-actions">
                <a href="{{ route('home') }}" class="profile-cancel">Cancel</a>
                <button type="submit" class="profile-submit">Update Profile</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-target');
            const field = document.getElementById(targetId);
            const eyeIcon = this.querySelector('.eye-icon');
            const eyeClosedIcon = this.querySelector('.eye-closed-icon');

            if (field.type === 'password') {
                field.type = 'text';
                eyeIcon.style.display = 'none';
                eyeClosedIcon.style.display = 'block';
            } else {
                field.type = 'password';
                eyeIcon.style.display = 'block';
                eyeClosedIcon.style.display = 'none';
            }
        });
    });
</script>
@endsection
