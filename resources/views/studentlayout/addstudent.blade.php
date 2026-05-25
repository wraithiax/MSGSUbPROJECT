@extends('format.layout')

@section('title')
    Add Student
@endsection

@section('content')
<style>
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
        min-height: 80vh;
        text-align: center;
    }

    /* HEADER */
    .page-header {
        margin-bottom: 30px;
    }

    .page-header h1 {
        color: #ec4899;
        font-size: 3rem; /* MAS MALAKI */
        font-weight: 800;
        margin: 0;
    }

    .page-header p {
        color: #9f1239;
        font-size: 1.1rem;
        margin-top: 0.5rem;
    }

    /* FORM CONTAINER (MAS MALAKI + CENTERED) */
    .form-container {
        width: 100%;
        max-width: 750px; /* MAS MALAKI */
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

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.9rem; /* MAS MALAKI INPUT */
        border: 2px solid #fce7f3;
        border-radius: 10px;
        font-size: 1rem;
        transition: 0.2s;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #ec4899;
        outline: none;
        box-shadow: 0 0 0 3px rgba(236,72,153,0.2);
    }

    /* BUTTONS */
    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        justify-content: center; /* CENTER BUTTONS */
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
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 18px rgba(236, 72, 153, 0.4);
    }

    .btn-cancel {
        padding: 0.85rem 2rem;
        background-color: #e5e7eb;
        color: #374151;
        text-decoration: none;
        border-radius: 10px;
        font-size: 1.05rem;
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-cancel:hover {
        background-color: #d1d5db;
    }

    .password-wrapper {
        position: relative;
        width: 100%;
    }

    .password-wrapper input {
        width: 100%;
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
</style>

<div class="background-design"></div>

<div class="center-wrapper">
    <div class="page-header">
        <h1>Add New Student</h1>
        <p>Fill out the form below to add a new student to the database.</p>
    </div>

    @if($errors->any())
    <div style="width: 100%; max-width: 750px; padding: 1.5rem; background-color: #fee2e2; border-left: 5px solid #ef4444; border-radius: 8px; margin-bottom: 2rem; color: #7f1d1d;">
        <h3 style="margin-top: 0; color: #991b1b;">Please fix the following errors:</h3>
        <ul style="margin: 1rem 0; padding-left: 1.5rem;">
            @foreach($errors->all() as $error)
                <li style="margin: 0.5rem 0; font-weight: 500;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
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

@endsection