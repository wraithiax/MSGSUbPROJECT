@extends('format.layout')

@section('title')
    Degree Details
@endsection

@section('content')
    <div style="margin-bottom: 40px;">
        <h1 style="color: #ec4899; font-size: 2.5rem; font-weight: 700; margin: 0;">Admin</h1>
    </div>

    <div style="display: flex; justify-content: flex-end; margin-bottom: 2rem;">
        <a href="{{ route('maintenance.create') }}" 
           style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);"
           onmouseover="this.style.boxShadow='0 6px 20px rgba(236, 72, 153, 0.5)';"
           onmouseout="this.style.boxShadow='0 4px 12px rgba(236, 72, 153, 0.3)';">
            + New Maintenance
        </a>
    </div>

    @if (session('success'))
        <div style="padding: 1rem; background-color: #fce7f3; border-left: 4px solid #ec4899; border-radius: 4px; margin-bottom: 1.5rem; color: #9f1239;">
            ✓ {{ session('success') }}
        </div>
    @endif

    <!-- Active Maintenance Alert -->
    @if($active)
        <div style="padding: 1.5rem; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-left: 4px solid #dc2626; border-radius: 8px; margin-bottom: 2rem; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: #7f1d1d; margin-bottom: 0.5rem;">
                        🔴 ACTIVE MAINTENANCE MODE
                    </h3>
                    <p style="color: #991b1b; font-weight: 600; font-size: 1.1rem; margin-bottom: 0.5rem;">{{ $active->title }}</p>
                    <p style="color: #b91c1c; margin-bottom: 1rem;">{{ $active->description }}</p>
                    @if($active->estimated_end_at)
                        <p style="color: #b91c1c; font-size: 0.95rem;">
                            <strong>Estimated End:</strong> {{ $active->estimated_end_at->format('F j, Y \a\t g:i A') }}
                        </p>
                    @endif
                </div>
                <form action="{{ route('maintenance.deactivate', $active) }}" method="POST" style="margin-left: 1rem;">
                    @csrf
                    <button type="submit" 
                            style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);"
                            onmouseover="this.style.boxShadow='0 6px 20px rgba(16, 185, 129, 0.5)';"
                            onmouseout="this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.3)';">
                        ✓ End Maintenance
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- Maintenance Records Table -->
    <table style="width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(236, 72, 153, 0.15);">
        <thead>
            <tr style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); color: #fff;">
                <th style="padding: 1.25rem 1rem; text-align: left; font-weight: 600;">Title</th>
                <th style="padding: 1.25rem 1rem; text-align: left; font-weight: 600;">Type</th>
                <th style="padding: 1.25rem 1rem; text-align: left; font-weight: 600;">Status</th>
                <th style="padding: 1.25rem 1rem; text-align: left; font-weight: 600;">Start Time</th>
                <th style="padding: 1.25rem 1rem; text-align: left; font-weight: 600;">Est. End</th>
                <th style="padding: 1.25rem 1rem; text-align: left; font-weight: 600;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($maintenances as $maintenance)
                <tr style="border-bottom: 1px solid #fce7f3; transition: background-color 0.2s ease; {{ $loop->even ? 'background-color: #fdf2f8;' : 'background-color: #fff;' }}"
                    onmouseover="this.style.backgroundColor='#fbecf8';"
                    onmouseout="this.style.backgroundColor='{{ $loop->even ? '#fdf2f8' : '#fff' }}';">
                    
                    <td style="padding: 1rem; color: #831843; font-weight: 600;">{{ $maintenance->title }}</td>
                    
                    <td style="padding: 1rem;">
                        <span style="padding: 0.375rem 0.75rem; background-color: #ffe4f1; color: #ec4899; border-radius: 20px; font-size: 0.875rem; font-weight: 500;">
                            {{ ucfirst($maintenance->maintenance_type) }}
                        </span>
                    </td>
                    
                    <td style="padding: 1rem;">
                        <span style="padding: 0.375rem 0.75rem; border-radius: 20px; font-size: 0.875rem; font-weight: 500;
                            @if($maintenance->status === 'active') background-color: #fee2e2; color: #dc2626;
                            @elseif($maintenance->status === 'completed') background-color: #dbeafe; color: #0284c7;
                            @elseif($maintenance->status === 'scheduled') background-color: #fef3c7; color: #d97706;
                            @else background-color: #e5e7eb; color: #6b7280;
                            @endif">
                            {{ ucfirst($maintenance->status) }}
                        </span>
                    </td>
                    
                    <td style="padding: 1rem; color: #666; font-size: 0.9rem;">
                        {{ $maintenance->started_at ? $maintenance->started_at->format('M d, Y H:i') : '-' }}
                    </td>
                    
                    <td style="padding: 1rem; color: #666; font-size: 0.9rem;">
                        {{ $maintenance->estimated_end_at ? $maintenance->estimated_end_at->format('M d, Y H:i') : '-' }}
                    </td>
                    
                    <td style="padding: 1rem;">
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            @if($maintenance->status !== 'active')
                                <form action="{{ route('maintenance.activate', $maintenance) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" 
                                            style="padding: 0.5rem 0.75rem; background-color: #fbbf24; color: #78350f; text-decoration: none; border: none; border-radius: 6px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: background-color 0.2s ease;"
                                            onmouseover="this.style.backgroundColor='#f59e0b';"
                                            onmouseout="this.style.backgroundColor='#fbbf24';">
                                        Activate
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('maintenance.edit', $maintenance) }}" 
                               style="padding: 0.5rem 0.75rem; background-color: #06b6d4; color: #fff; text-decoration: none; border-radius: 6px; font-size: 0.875rem; font-weight: 500; transition: background-color 0.2s ease; display: inline-block;"
                               onmouseover="this.style.backgroundColor='#0891b2';"
                               onmouseout="this.style.backgroundColor='#06b6d4';">
                                Edit
                            </a>
                            
                            <form action="{{ route('maintenance.destroy', $maintenance) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this maintenance record?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        style="padding: 0.5rem 0.75rem; background-color: #f87171; color: #fff; border: none; border-radius: 6px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: background-color 0.2s ease;"
                                        onmouseover="this.style.backgroundColor='#ef4444';"
                                        onmouseout="this.style.backgroundColor='#f87171';">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding: 2rem; text-align: center; color: #666;">
                        No maintenance records found. <a href="{{ route('maintenance.create') }}" style="color: #ec4899; text-decoration: none; font-weight: 600;">Create one</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
