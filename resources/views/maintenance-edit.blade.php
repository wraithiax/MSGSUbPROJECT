@extends('format.admin-layout')

@section('title')
    Admin
@endsection

@section('content')
    <div style="margin-bottom: 40px;">
        <h1 style="color: #ec4899; font-size: 2.5rem; font-weight: 700; margin: 0;">Admin</h1>
    </div>

    @if ($errors->any())
        <div style="padding: 1rem; background-color: #fee2e2; border-left: 4px solid #dc2626; border-radius: 4px; margin-bottom: 1.5rem; color: #991b1b;">
            <strong>Please fix the following errors:</strong>
            <ul style="margin-top: 0.5rem; margin-left: 1.5rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('maintenance.update', $maintenance) }}" method="POST" style="max-width: 700px;">
        @csrf
        @method('PUT')

        <div style="background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(236, 72, 153, 0.15);">
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; color: #831843; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.95rem;">
                    Title <span style="color: #dc2626;">*</span>
                </label>
                <input type="text" 
                       name="title" 
                       value="{{ old('title', $maintenance->title) }}"
                       style="width: 100%; padding: 0.75rem; border: 2px solid #fce7f3; border-radius: 6px; font-family: inherit; font-size: 1rem; transition: border-color 0.3s ease;"
                       onfocus="this.style.borderColor='#ec4899';"
                       onblur="this.style.borderColor='#fce7f3';">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; color: #831843; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.95rem;">
                    Description
                </label>
                <textarea name="description" 
                          rows="4"
                          style="width: 100%; padding: 0.75rem; border: 2px solid #fce7f3; border-radius: 6px; font-family: inherit; font-size: 1rem; transition: border-color 0.3s ease; resize: vertical;"
                          onfocus="this.style.borderColor='#ec4899';"
                          onblur="this.style.borderColor='#fce7f3';">{{ old('description', $maintenance->description) }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; color: #831843; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.95rem;">
                        Type <span style="color: #dc2626;">*</span>
                    </label>
                    <select name="maintenance_type" 
                            style="width: 100%; padding: 0.75rem; border: 2px solid #fce7f3; border-radius: 6px; font-family: inherit; font-size: 1rem; transition: border-color 0.3s ease;"
                            onfocus="this.style.borderColor='#ec4899';"
                            onblur="this.style.borderColor='#fce7f3';">
                        <option value="system" {{ old('maintenance_type', $maintenance->maintenance_type) === 'system' ? 'selected' : '' }}>System</option>
                        <option value="database" {{ old('maintenance_type', $maintenance->maintenance_type) === 'database' ? 'selected' : '' }}>Database</option>
                        <option value="infrastructure" {{ old('maintenance_type', $maintenance->maintenance_type) === 'infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                        <option value="security" {{ old('maintenance_type', $maintenance->maintenance_type) === 'security' ? 'selected' : '' }}>Security</option>
                        <option value="other" {{ old('maintenance_type', $maintenance->maintenance_type) === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; color: #831843; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.95rem;">
                        Status <span style="color: #dc2626;">*</span>
                    </label>
                    <select name="status" 
                            style="width: 100%; padding: 0.75rem; border: 2px solid #fce7f3; border-radius: 6px; font-family: inherit; font-size: 1rem; transition: border-color 0.3s ease;"
                            onfocus="this.style.borderColor='#ec4899';"
                            onblur="this.style.borderColor='#fce7f3';">
                        <option value="scheduled" {{ old('status', $maintenance->status) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="active" {{ old('status', $maintenance->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ old('status', $maintenance->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $maintenance->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div>
                    <label style="display: block; color: #831843; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.95rem;">
                        Start Time <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="datetime-local" 
                           name="started_at"
                           value="{{ old('started_at', $maintenance->started_at?->format('Y-m-d\TH:i')) }}"
                           style="width: 100%; padding: 0.75rem; border: 2px solid #fce7f3; border-radius: 6px; font-family: inherit; font-size: 1rem; transition: border-color 0.3s ease;"
                           onfocus="this.style.borderColor='#ec4899';"
                           onblur="this.style.borderColor='#fce7f3';">
                </div>

                <div>
                    <label style="display: block; color: #831843; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.95rem;">
                        Estimated End Time <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="datetime-local" 
                           name="estimated_end_at"
                           value="{{ old('estimated_end_at', $maintenance->estimated_end_at?->format('Y-m-d\TH:i')) }}"
                           style="width: 100%; padding: 0.75rem; border: 2px solid #fce7f3; border-radius: 6px; font-family: inherit; font-size: 1rem; transition: border-color 0.3s ease;"
                           onfocus="this.style.borderColor='#ec4899';"
                           onblur="this.style.borderColor='#fce7f3';">
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" 
                        style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);"
                        onmouseover="this.style.boxShadow='0 6px 20px rgba(236, 72, 153, 0.5)';"
                        onmouseout="this.style.boxShadow='0 4px 12px rgba(236, 72, 153, 0.3)';">
                    Update Maintenance
                </button>
                <a href="{{ route('maintenance.index') }}" 
                   style="padding: 0.75rem 1.5rem; background-color: #e5e7eb; color: #4b1f3a; text-decoration: none; border-radius: 8px; font-weight: 600; transition: background-color 0.3s ease; display: inline-block;"
                   onmouseover="this.style.backgroundColor='#d1d5db';"
                   onmouseout="this.style.backgroundColor='#e5e7eb';">
                    Cancel
                </a>
            </div>
        </div>
    </form>
@endsection
