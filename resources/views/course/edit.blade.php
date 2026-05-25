@extends('format.layout')

@section('title', 'Edit Course')

@section('content')
    <div style="margin-bottom: 40px;">
        <h1 style="color: #ec4899; font-size: 2.5rem; font-weight: 700; margin: 0;">Edit Course</h1>
        <p style="color: #9f1239; font-size: 1rem; margin-top: 0.5rem;">Update course information</p>
    </div>

    @if ($errors->any())
        <div style="padding: 1rem; background: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px; color: #991b1b; margin-bottom: 1.5rem;">
            <strong>Validation Errors:</strong>
            <ul style="margin: 0.5rem 0 0 1.5rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="max-width: 700px; background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(236, 72, 153, 0.15);">
        <form action="{{ route('courses.update', $course->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Course Name -->
            <div style="margin-bottom: 1.5rem;">
                <label for="name" style="display: block; color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">Course Name <span style="color: #ef4444;">*</span></label>
                <input type="text" name="name" id="name" placeholder="Enter course name" value="{{ old('name', $course->name) }}" style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 1rem; background-color: #f9fafb; color: #333; transition: border-color 0.2s ease;" onchange="this.style.borderColor='#ec4899';" onfocus="this.style.borderColor='#ec4899';" onblur="this.style.borderColor='#e5e7eb';" required>
                @error('name')
                    <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Course Description -->
            <div style="margin-bottom: 2rem;">
                <label for="description" style="display: block; color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">Description <span style="color: #ef4444;">*</span></label>
                <textarea name="description" id="description" placeholder="Enter course description" rows="6" style="width: 100%; padding: 0.75rem; border: 2px solid #e5e7eb; border-radius: 6px; font-size: 1rem; background-color: #f9fafb; color: #333; transition: border-color 0.2s ease; font-family: 'Segoe UI', sans-serif; resize: vertical;" onchange="this.style.borderColor='#ec4899';" onfocus="this.style.borderColor='#ec4899';" onblur="this.style.borderColor='#e5e7eb';" required>{{ old('description', $course->description) }}</textarea>
                @error('description')
                    <p style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 1rem;">
                <button type="submit" style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #fff; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: box-shadow 0.2s ease; box-shadow: 0 4px 6px rgba(245, 158, 11, 0.25);" onmouseover="this.style.boxShadow='0 8px 12px rgba(245, 158, 11, 0.4)';" onmouseout="this.style.boxShadow='0 4px 6px rgba(245, 158, 11, 0.25)';">Update Course</button>
                <a href="{{ route('courses.index') }}" style="padding: 0.75rem 1.5rem; background-color: #e5e7eb; color: #374151; text-decoration: none; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background-color 0.2s ease; display: inline-block;" onmouseover="this.style.backgroundColor='#d1d5db';" onmouseout="this.style.backgroundColor='#e5e7eb';">Cancel</a>
            </div>
        </form>
    </div>
@endsection
