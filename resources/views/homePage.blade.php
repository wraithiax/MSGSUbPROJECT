@extends('format.layout')

@section('title')
    Home
@endsection

@section('content')
    @if (session('success'))
        <div style="padding: 1rem; background: #dcfce7; border: 1px solid #86efac; border-radius: 8px; color: #166534; margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="padding: 1rem; background: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px; color: #991b1b; margin-bottom: 1.5rem;">
            {{ session('error') }}
        </div>
    @endif

    <div style="margin-bottom: 40px; text-align: center;">
        <h1 style="color: #ec4899; font-size: 2.5rem; font-weight: 700; margin: 0;">Student Management Portal</h1>
        <p style="color: #9f1239; font-size: 1rem; margin-top: 0.5rem;">A modern portal to manage and view enrolled students</p>
    </div>

    @if ($user->force_password_change)
        <div style="max-width: 900px; margin: 0 auto 2rem;">
            <!-- Student Details Section -->
            @if ($user->student)
                <div style="background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(236, 72, 153, 0.15); border: 1px solid #fbcfe8; margin-bottom: 2rem;">
                    <h2 style="color: #be185d; margin-top: 0; margin-bottom: 1.5rem;">Your Student Information</h2>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                        <div>
                            <label style="display: block; color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">First Name</label>
                            <p style="margin: 0; padding: 0.75rem; background: #fdf2f8; border-radius: 8px; color: #6b7280;">{{ $user->student->fname }}</p>
                        </div>
                        <div>
                            <label style="display: block; color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">Middle Name</label>
                            <p style="margin: 0; padding: 0.75rem; background: #fdf2f8; border-radius: 8px; color: #6b7280;">{{ $user->student->mname }}</p>
                        </div>
                        <div>
                            <label style="display: block; color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">Last Name</label>
                            <p style="margin: 0; padding: 0.75rem; background: #fdf2f8; border-radius: 8px; color: #6b7280;">{{ $user->student->lname }}</p>
                        </div>
                        <div>
                            <label style="display: block; color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">Contact</label>
                            <p style="margin: 0; padding: 0.75rem; background: #fdf2f8; border-radius: 8px; color: #6b7280;">{{ $user->student->contact }}</p>
                        </div>
                        @if ($user->student->degree)
                            <div>
                                <label style="display: block; color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">Degree</label>
                                <p style="margin: 0; padding: 0.75rem; background: #fdf2f8; border-radius: 8px; color: #6b7280;">{{ $user->student->degree->Degree }}</p>
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('profile.edit') }}" style="padding: 0.75rem 1.5rem; background: #3b82f6; color: #fff; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-block;">Edit Your Details</a>
                </div>
            @endif

            <!-- Change Password Section -->
            <div style="background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(236, 72, 153, 0.15); border: 1px solid #fbcfe8;">
                <h2 style="color: #be185d; margin-top: 0; margin-bottom: 0.5rem;">Change Your Password First</h2>
                <p style="color: #6b7280; margin-bottom: 1.5rem;">Ito ang first login mo gamit ang temporary password. Bago ka makapagpatuloy, palitan mo muna ang password mo dito sa dashboard.</p>

                @if ($errors->any())
                    <div style="padding: 1rem; background: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px; color: #991b1b; margin-bottom: 1.5rem;">
                        <ul style="margin-left: 1.5rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('dashboard.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div style="margin-bottom: 1rem;">
                        <label for="current_password" style="display: block; color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">Temporary Password</label>
                        <input type="password" name="current_password" id="current_password" required style="width: 100%; padding: 0.75rem; border: 2px solid #fce7f3; border-radius: 8px;">
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label for="password" style="display: block; color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">New Password</label>
                        <input type="password" name="password" id="password" required style="width: 100%; padding: 0.75rem; border: 2px solid #fce7f3; border-radius: 8px;">
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="password_confirmation" style="display: block; color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required style="width: 100%; padding: 0.75rem; border: 2px solid #fce7f3; border-radius: 8px;">
                    </div>

                    <button type="submit" style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Save New Password</button>
                </form>
            </div>
        </div>
    @endif

    @if (! $user->force_password_change)
    <div style="max-width: 1000px; margin: 0 auto 2rem; background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(236, 72, 153, 0.15);">
        @if ($user->isAdmin())
            <h2 style="color: #be185d; margin-top: 0;">Admin Dashboard</h2>
            <p style="color: #6b7280; margin-bottom: 1.5rem;">Manage student and teacher accounts, course records, and maintenance settings.</p>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="{{ route('users.create') }}" style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff; border-radius: 8px; font-weight: 600; text-decoration: none;">Add Student/Teacher</a>
                <a href="{{ route('users.index') }}" style="padding: 0.75rem 1.5rem; background: #3b82f6; color: #fff; border-radius: 8px; font-weight: 600; text-decoration: none;">Manage Users</a>
                <a href="{{ route('students.index') }}" style="padding: 0.75rem 1.5rem; background: #ec4899; color: #fff; border-radius: 8px; font-weight: 600; text-decoration: none;">View Students</a>
            </div>
        @elseif ($user->isTeacher())
            <h2 style="color: #be185d; margin-top: 0;">Teacher Dashboard</h2>
            <p style="color: #6b7280; margin-bottom: 1.5rem;">View student records, courses, and enrollment assignments.</p>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="{{ route('students.index') }}" style="padding: 0.75rem 1.5rem; background: #ec4899; color: #fff; border-radius: 8px; font-weight: 600; text-decoration: none;">View Students</a>
                <a href="{{ route('courses.index') }}" style="padding: 0.75rem 1.5rem; background: #3b82f6; color: #fff; border-radius: 8px; font-weight: 600; text-decoration: none;">View Courses</a>
                <a href="{{ route('course_students.index') }}" style="padding: 0.75rem 1.5rem; background: #10b981; color: #fff; border-radius: 8px; font-weight: 600; text-decoration: none;">Enrollments</a>
            </div>
        @else
            <h2 style="color: #be185d; margin-top: 0;">Student Dashboard</h2>
            <p style="color: #6b7280; margin-bottom: 1.5rem;">View your student information and manage your account profile.</p>
            @if ($user->student)
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                    <p style="margin: 0; padding: 0.75rem; background: #fdf2f8; border-radius: 8px;"><strong>Name:</strong> {{ $user->student->fname }} {{ $user->student->mname }} {{ $user->student->lname }}</p>
                    <p style="margin: 0; padding: 0.75rem; background: #fdf2f8; border-radius: 8px;"><strong>Contact:</strong> {{ $user->student->contact }}</p>
                    <p style="margin: 0; padding: 0.75rem; background: #fdf2f8; border-radius: 8px;"><strong>Degree:</strong> {{ $user->student->degree?->Degree ?? 'Not assigned' }}</p>
                </div>
            @endif
            <a href="{{ route('profile.edit') }}" style="padding: 0.75rem 1.5rem; background: #ec4899; color: #fff; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-block;">Edit My Profile</a>
        @endif
    </div>

    @if (! $user->isStudent())
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 2rem;">
        <div style="background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(236, 72, 153, 0.15); transition: all 0.3s ease; cursor: pointer; text-align: center;" 
             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 12px rgba(236, 72, 153, 0.3)'" 
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(236, 72, 153, 0.15)'">
            <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; box-shadow: 0 4px 6px rgba(236, 72, 153, 0.25);">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" viewBox="0 0 16 16">
                    <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.759 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                </svg>
            </div>
            <h5 style="color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">View Students</h5>
            <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem;">Browse the full list of enrolled students and their details.</p>
            <a href="{{ url('/students') }}" style="padding: 0.5rem 1.25rem; background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); color: #fff; text-decoration: none; border-radius: 6px; font-weight: 600; display: inline-block; transition: box-shadow 0.2s ease;" onmouseover="this.style.boxShadow='0 4px 12px rgba(236, 72, 153, 0.4)'" onmouseout="this.style.boxShadow='0 2px 4px rgba(236, 72, 153, 0.15)'">Go to Students</a>
        </div>
        <div style="background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(236, 72, 153, 0.15); transition: all 0.3s ease; cursor: pointer; text-align: center;" 
             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 12px rgba(236, 72, 153, 0.3)'" 
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(236, 72, 153, 0.15)'">
            <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; box-shadow: 0 4px 6px rgba(236, 72, 153, 0.25);">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" viewBox="0 0 16 16">
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                </svg>
            </div>
            <h5 style="color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">About</h5>
            <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem;">Learn more about this Student Management Portal.</p>
            <a href="{{ url('/about') }}" style="padding: 0.5rem 1.25rem; background-color: #e5e7eb; color: #374151; text-decoration: none; border-radius: 6px; font-weight: 600; display: inline-block; transition: background-color 0.2s ease;" onmouseover="this.style.backgroundColor='#d1d5db'" onmouseout="this.style.backgroundColor='#e5e7eb'">Learn More</a>
        </div>
    </div>
    @endif
    @endif
@endsection
