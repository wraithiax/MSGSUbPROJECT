@extends('format.layout')

@section('title', 'Create User')

@section('content')
    <div style="margin-bottom: 40px;">
        <h1 style="color: #ec4899; font-size: 2.5rem; font-weight: 700; margin: 0;">Create Student/Teacher</h1>
        <p style="color: #9f1239; font-size: 1rem; margin-top: 0.5rem;">Add a student or teacher account to the system.</p>
    </div>

    @if ($errors->any())
        <div style="padding: 1rem; background: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px; color: #991b1b; margin-bottom: 1.5rem;">
            <ul style="margin-left: 1.5rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="max-width: 760px; background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(236, 72, 153, 0.15);">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1.5rem;">
                <label for="role" style="display: block; color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">Role <span style="color: #ef4444;">*</span></label>
                <select name="role" id="role" required style="width: 100%; padding: 0.75rem; border: 2px solid #fce7f3; border-radius: 8px;">
                    <option value="student" {{ old('role', 'student') == 'student' ? 'selected' : '' }}>Student</option>
                    <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                </select>
                @error('role')
                    <small style="color: #ef4444; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>

            <!-- STUDENT INFORMATION SECTION -->
            <div id="student-fields">
            <h3 style="color: #ec4899; font-size: 1.3rem; margin-bottom: 1rem; border-bottom: 2px solid #fce7f3; padding-bottom: 0.5rem;">Student Information</h3>

            <div style="margin-bottom: 1.5rem;">
                <label for="fname" style="display: block; color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">First Name <span style="color: #ef4444;">*</span></label>
                <input type="text" name="fname" id="fname" value="{{ old('fname') }}" required style="width: 100%; padding: 0.75rem; border: 2px solid #fce7f3; border-radius: 8px;">
                @error('fname')
                    <small style="color: #ef4444; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="mname" style="display: block; color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">Middle Name <span style="color: #ef4444;">*</span></label>
                <input type="text" name="mname" id="mname" value="{{ old('mname') }}" required style="width: 100%; padding: 0.75rem; border: 2px solid #fce7f3; border-radius: 8px;">
                @error('mname')
                    <small style="color: #ef4444; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="lname" style="display: block; color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">Last Name <span style="color: #ef4444;">*</span></label>
                <input type="text" name="lname" id="lname" value="{{ old('lname') }}" required style="width: 100%; padding: 0.75rem; border: 2px solid #fce7f3; border-radius: 8px;">
                @error('lname')
                    <small style="color: #ef4444; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="contact" style="display: block; color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">Contact Number <span style="color: #ef4444;">*</span></label>
                <input type="text" name="contact" id="contact" value="{{ old('contact') }}" placeholder="11 digits" required style="width: 100%; padding: 0.75rem; border: 2px solid #fce7f3; border-radius: 8px;">
                @error('contact')
                    <small style="color: #ef4444; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="degree_id" style="display: block; color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">Degree/Course <span style="color: #ef4444;">*</span></label>
                <select name="degree_id" id="degree_id" style="width: 100%; padding: 0.75rem; border: 2px solid #fce7f3; border-radius: 8px;">
                    <option value="" disabled {{ old('degree_id') ? '' : 'selected' }}>Select a degree</option>
                    @if(isset($degrees))
                        @foreach($degrees as $degree)
                            <option value="{{ $degree->id }}" {{ old('degree_id') == $degree->id ? 'selected' : '' }}>{{ $degree->Degree }}</option>
                        @endforeach
                    @endif
                </select>
                @error('degree_id')
                    <small style="color: #ef4444; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>
            </div>

            <!-- ACCOUNT INFORMATION SECTION -->
            <h3 style="color: #ec4899; font-size: 1.3rem; margin-top: 2rem; margin-bottom: 1rem; border-bottom: 2px solid #fce7f3; padding-bottom: 0.5rem;">Account Information</h3>
            <div style="margin-bottom: 1.5rem;">
                <label for="username" style="display: block; color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">Username <span style="color: #ef4444;">*</span></label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" required style="width: 100%; padding: 0.75rem; border: 2px solid #fce7f3; border-radius: 8px;">
                @error('username')
                    <small style="color: #ef4444; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>                   
            <div style="margin-bottom: 1.5rem;">
                <label for="email" style="display: block; color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">Email <span style="color: #ef4444;">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required style="width: 100%; padding: 0.75rem; border: 2px solid #fce7f3; border-radius: 8px;">
                @error('email')
                    <small style="color: #ef4444; display: block; margin-top: 0.25rem;">{{ $message }}</small>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">Temporary Password</label>
                <div style="width: 100%; padding: 0.75rem; border: 2px dashed #f9a8d4; border-radius: 8px; background: #fff1f2; color: #9f1239; font-weight: 600;">
                    Password123
                </div>
                <p style="margin-top: 0.5rem; color: #6b7280; font-size: 0.9rem;">Ito ang ibibigay na temporary password. Sa unang login ng user, required siyang magpalit agad sa dashboard.</p>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Create Account</button>
                <a href="{{ route('users.index') }}" style="padding: 0.75rem 1.5rem; background: #e5e7eb; color: #374151; border-radius: 8px; text-decoration: none;">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        const roleField = document.getElementById('role');
        const studentFields = document.getElementById('student-fields');
        const requiredStudentFields = studentFields.querySelectorAll('input, select');

        function toggleStudentFields() {
            const isStudent = roleField.value === 'student';
            studentFields.style.display = isStudent ? 'block' : 'none';

            requiredStudentFields.forEach((field) => {
                field.required = isStudent;
            });
        }

        roleField.addEventListener('change', toggleStudentFields);
        toggleStudentFields();
    </script>
@endsection
