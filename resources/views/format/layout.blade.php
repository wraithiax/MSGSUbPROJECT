<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pangasinan State University - Student Management Dashboard">
    <meta name="theme-color" content="#ff69b4">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PSU - Student Management Dashboard')</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', sans-serif;
            background: linear-gradient(135deg,#ffe4f1,#fff0f7);
            color: #4b1f3a;
            min-height: 100vh;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        /* NAVBAR */
        nav {
            background: #ffffff;
            padding: 1rem 2rem;
            box-shadow: 0 4px 12px rgba(255,105,180,0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 2px solid #ffc0cb;
        }

        nav ul {
            display: flex;
            align-items: center;
            width: 100%;
            max-width: 1400px;
            margin: auto;
            list-style: none;
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .hamburger {
            font-size: 1.6rem;
            margin-right: 1rem;
            cursor: pointer;
            color: #d63384;
            background: #ffe4f1;
            padding: 0.45rem 0.75rem;
            border-radius: 0.6rem;
            transition: 0.3s;
            user-select: none;
        }

        .hamburger:hover {
            background: #ffd1e6;
            transform: scale(1.05);
        }

        nav a {
            color: #a85588;
            text-decoration: none;
            padding: 0.6rem 1rem;
            border-radius: 0.5rem;
            transition: 0.3s;
            font-weight: 500;
        }

        nav a.active {
            color: white;
            background: linear-gradient(135deg,#ff69b4,#ff1493);
        }

        nav a:hover {
            background: #ffe4f1;
            color: #ff1493;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 1rem;
            justify-content: flex-end;
            margin-left: auto;
        }

        .logo a {
            text-decoration: none;
            color: #d63384;
            font-weight: 700;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo span {
            background: linear-gradient(135deg,#ff69b4,#ff1493);
            color: white;
            padding: 0.35rem 0.5rem;
            border-radius: 0.375rem;
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .nav-user-email {
            background: #ff3ea5;
            border-radius: 0.5rem;
            color: #fff;
            font-size: 0.9rem;
            padding: 0.55rem 0.75rem;
            white-space: nowrap;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
            height: 100%;
            background: #fff;
            box-shadow: 4px 0 20px rgba(0,0,0,0.12);
            z-index: 2000;
            padding: 1.5rem 1rem;
            transition: left 0.3s ease;
            overflow-y: auto;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar h2 {
            color: #d63384;
            margin-bottom: 1rem;
            font-size: 1.3rem;
            border-bottom: 2px solid #ffd1e6;
            padding-bottom: 0.8rem;
        }

        .sidebar a {
            display: block;
            padding: 0.85rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 0.75rem;
            color: #a85588;
            text-decoration: none;
            transition: 0.25s;
            font-weight: 500;
        }

        .sidebar a:hover {
            background: #ffe4f1;
            color: #ff1493;
            transform: translateX(5px);
        }

        .sidebar a.active {
            color: white;
            background: linear-gradient(135deg,#ff69b4,#ff1493);
        }

        .close-btn {
            font-size: 1.5rem;
            cursor: pointer;
            color: #d63384;
            margin-bottom: 1rem;
            display: inline-block;
            background: #ffe4f1;
            padding: 0.4rem 0.7rem;
            border-radius: 0.5rem;
        }

        /* OVERLAY */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.25);
            z-index: 1500;
            opacity: 0;
            visibility: hidden;
            transition: 0.3s;
        }

        .overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* MAIN */
        main {
            flex: 1;
            padding: 2rem;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            box-sizing: border-box;
        }

        /* FOOTER */
        footer {
            text-align: center;
            padding: 2rem;
            background: #fff;
            border-top: 2px solid #ffc0cb;
            width: 100%;
        }

        /* MOBILE */
        @media (max-width: 768px) {
            nav {
                padding: 1rem;
            }

            nav ul {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .nav-right,
            .nav-user {
                width: 100%;
                justify-content: flex-start;
                flex-wrap: wrap;
            }

            .nav-left a {
                padding: 0.5rem 0.8rem;
                font-size: 0.9rem;
            }

            .logo a {
                font-size: 1rem;
            }

            main {
                padding: 1rem;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}" defer></script>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="close-btn" onclick="toggleSidebar()">✕</div>
        <h2>Menu</h2>

        <a href="{{ url('/home') }}" @class(['active' => request()->is('home*')])>Dashboard</a>
        <a href="{{ route('profiles.index') }}" @class(['active' => request()->is('profiles*')])>Profiles</a>
        <a href="{{ url('/about') }}" @class(['active' => request()->is('about*')])>About</a>
        
        @if(in_array(session('user_role'), ['admin', 'teacher'], true))
            <a href="{{ url('/students') }}" @class(['active' => request()->is('students*')])>Students</a>
            <a href="{{ url('/degrees') }}" @class(['active' => request()->is('degrees*')])>Degrees</a>
            <a href="{{ route('courses.index') }}" @class(['active' => request()->is('courses*')])>Course</a>
            <a href="{{ route('course_students.index') }}" @class(['active' => request()->is('course_students*')])>Course Student</a>
        @endif

        @if(session('user_role') === 'admin')
            <a href="{{ route('users.index') }}" @class(['active' => request()->is('users*')])>Users</a>
            <a href="{{ route('posts.index') }}" @class(['active' => request()->is('posts*')])>Posts</a>
            <a href="{{ route('maintenance.index') }}" @class(['active' => request()->is('maintenance*')])>Maintenance</a>
        @endif

        @if(session('user_role') !== 'admin')
            <a href="{{ route('profile.edit') }}" @class(['active' => request()->is('profile/edit*')])>Edit Profile</a>
        @endif
        
        <form action="{{ route('logout') }}" method="POST" style="margin-top: 2rem;">
            @csrf
            <button type="submit" style="width: 100%; padding: 0.6rem 1rem; background: linear-gradient(135deg,#ff69b4,#ff1493); color: white; border: none; border-radius: 0.5rem; cursor: pointer; font-weight: 500;">Logout</button>
        </form>
        
    </div>

    <!-- OVERLAY -->
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <!-- NAVBAR -->
    <nav>
        <ul>
            <li class="nav-left">
                <div class="hamburger" onclick="toggleSidebar()">☰</div>

                <!-- TOP MENU ONLY -->
                <a href="{{ url('/home') }}" @class(['active' => request()->is('home*')])>Dashboard</a>
                <a href="{{ route('profiles.index') }}" @class(['active' => request()->is('profiles*')])>Profiles</a>
                <a href="{{ url('/about') }}" @class(['active' => request()->is('about*')])>About</a>
                @if(session('user_role') !== 'admin')
                    <a href="{{ route('profile.edit') }}" @class(['active' => request()->is('profile/edit*')])>My Profile</a>
                @endif
            </li>

            <li class="nav-right logo">
                <a href="{{ url('/home') }}">
                    <span>🩷</span> Pangasinan State University
                </a>
                <div class="nav-user">
                    <span class="nav-user-email">{{ session('user_email') }} ({{ ucfirst(session('user_role')) }})</span>
                    <a href="{{ route('profile.edit') }}" style="color: #ff69b4; text-decoration: none; padding: 0.5rem 0.75rem; border-radius: 0.5rem; transition: 0.3s;" onmouseover="this.style.background='#ffe4f1';" onmouseout="this.style.background='transparent';">👤 Profile</a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: #a85588; padding: 0.6rem 1rem; border-radius: 0.5rem; cursor: pointer; font-weight: 500; transition: 0.3s;" onmouseover="this.style.background='#ffe4f1'; this.style.color='#ff1493';" onmouseout="this.style.background='none'; this.style.color='#a85588';">Logout</button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; 2024 PSU Student Information System. All rights reserved.</p>
    </footer>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        }
    </script>

</body>
</html>
