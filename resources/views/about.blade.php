@extends('format.layout')

@section('title', 'About')

@section('content')

<div style="margin-bottom: 40px;">
    <h1 style="color: #ec4899; font-size: 2.5rem; font-weight: 700; margin: 0;">About This Project</h1>
    <p style="color: #9f1239; font-size: 1rem; margin-top: 0.5rem;">Learn more about our Student Management System</p>
</div>

<div style="background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(236, 72, 153, 0.15);">
    <p style="font-size: 1rem; line-height: 1.8; margin-bottom: 1.5rem; color: #374151;">
        This project is a <strong style="color: #ec4899;">Student Management Dashboard</strong> developed using 
        <strong style="color: #ec4899;">Laravel Blade Templates</strong>. It demonstrates how Laravel can be used 
        to build dynamic and reusable web interfaces with modern design patterns.
    </p>

    <p style="font-size: 1rem; line-height: 1.8; margin-bottom: 1.5rem; color: #374151;">
        The system uses <strong style="color: #ec4899;">Blade layout inheritance</strong> to maintain a consistent 
        design across pages. It also implements <strong style="color: #ec4899;">loops</strong> and 
        <strong style="color: #ec4899;">conditional statements</strong> to dynamically display student information with a clean UI.
    </p>

    <p style="font-size: 1rem; line-height: 1.8; color: #374151;">
        This project highlights the use of Laravel's templating features to create 
        a clean, organized, and maintainable web application interface with beautiful pink-themed design aesthetics.
    </p>
</div>

@endsection