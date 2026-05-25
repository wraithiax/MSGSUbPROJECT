@extends('format.layout')

@section('title')
    Edit Degree
@endsection

@section('content')
    <div style="margin-bottom: 40px;">
        <h1 style="color: #ec4899; font-size: 2.5rem; font-weight: 700; margin: 0;">Edit Degree</h1>
        <p style="color: #9f1239; font-size: 1rem; margin-top: 0.5rem;">Update degree program information</p>
    </div>

    <form action="{{ route('degrees.update', $degree->id) }}" method="POST" style="max-width: 600px; background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(236, 72, 153, 0.15);">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 2rem;">
            <label for="Degree" style="display: block; color: #ec4899; font-weight: 600; margin-bottom: 0.5rem;">Degree Name <span style="color: #ef4444;">*</span></label>
            <input type="text" name="Degree" id="Degree" placeholder="Enter degree name" value="{{ $degree->Degree }}" required style="width: 100%; padding: 0.75rem; border: 2px solid #fce7f3; border-radius: 8px; font-size: 1rem; transition: border-color 0.2s ease;" onfocus="this.style.borderColor='#ec4899';" onblur="this.style.borderColor='#fce7f3';">
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); color: #fff; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: box-shadow 0.2s ease; box-shadow: 0 4px 6px rgba(236, 72, 153, 0.25);" onmouseover="this.style.boxShadow='0 8px 12px rgba(236, 72, 153, 0.4)';" onmouseout="this.style.boxShadow='0 4px 6px rgba(236, 72, 153, 0.25)';">Update Degree</button>
            <a href="{{ route('degrees.index') }}" style="padding: 0.75rem 1.5rem; background-color: #e5e7eb; color: #374151; text-decoration: none; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background-color 0.2s ease; display: inline-block;" onmouseover="this.style.backgroundColor='#d1d5db';" onmouseout="this.style.backgroundColor='#e5e7eb';">Cancel</a>
        </div>
    </form>

@endsection
