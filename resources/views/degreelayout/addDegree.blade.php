@extends('format.layout')

@section('title')
    Add Degree
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
        font-size: 3rem;
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
        max-width: 750px;
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
        padding: 0.9rem;
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
        justify-content: center;
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
</style>

<div class="background-design"></div>

<div class="center-wrapper">
    <div class="page-header">
        <h1>Add New Degree</h1>
        <p>Fill out the form below to add a new degree program to the database.</p>
    </div>

    <form action="{{ route('degrees.store') }}" method="POST" class="form-container">
        @csrf
        
        <div class="form-group">
            <label for="Degree">Degree Name <span class="required">*</span></label>
            <input type="text" name="Degree" id="Degree" placeholder="Enter degree name (e.g., BS Information Technology)" required>
        </div>

        <div class="button-group">
            <button type="submit" class="btn-submit">Add Degree</button>
            <a href="{{ route('degrees.index') }}" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>

@endsection
