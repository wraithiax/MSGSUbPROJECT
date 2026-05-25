@extends('format.layout')

@section('title')
    Degree Details
@endsection

@section('content')
    <div style="margin-bottom: 40px;">
        <h1 style="color: #ec4899; font-size: 2.5rem; font-weight: 700; margin: 0;">Degree Management</h1>
        <p style="color: #9f1239; font-size: 1rem; margin-top: 0.5rem;">View and manage degree programs</p>
    </div>
    
    @if(session('success'))
    <div style="padding: 1rem; background-color: #fce7f3; border-left: 4px solid #ec4899; border-radius: 4px; margin-bottom: 1.5rem; color: #9f1239;">
        {{ session('success') }}
    </div>
    @endif
    
    <table style="width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(236, 72, 153, 0.15);">
        <thead>
            <tr style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); color: #fff;">
                <th style="padding: 1.25rem 1rem; text-align: left; font-weight: 600;">#</th>
                <th style="padding: 1.25rem 1rem; text-align: left; font-weight: 600;">Degree Name</th>
                <th style="padding: 1.25rem 1rem; text-align: left; font-weight: 600;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($degrees as $degree)
            <tr style="border-bottom: 1px solid #fce7f3; transition: background-color 0.2s ease; {{ $loop->even ? 'background-color: #fdf2f8;' : 'background-color: #fff;' }}" 
                onmouseover="this.style.backgroundColor='#fbecf8';" 
                onmouseout="this.style.backgroundColor='{{ $loop->even ? '#fdf2f8' : '#fff' }}';">
                
                <td style="padding: 1rem; color: #831843; font-weight: 600;">{{ $loop->iteration }}</td>
                <td style="padding: 1rem; color: #333;">{{ $degree->Degree }}</td>

                <td style="padding: 1rem;">
                    <!-- View -->
                    <a href="/degrees/{{ $degree->id }}" 
                       style="padding: 0.5rem 0.75rem; background-color: #06b6d4; color: #fff; text-decoration: none; border-radius: 6px; margin-right: 0.5rem; display: inline-block; font-size: 0.875rem; font-weight: 500; transition: background-color 0.2s ease;" 
                       onmouseover="this.style.backgroundColor='#0891b2';" 
                       onmouseout="this.style.backgroundColor='#06b6d4';">
                       View
                    </a>

                    <!-- Edit -->
                    <a href="/degrees/{{ $degree->id }}/edit" 
                       style="padding: 0.5rem 0.75rem; background-color: #f59e0b; color: #fff; text-decoration: none; border-radius: 6px; margin-right: 0.5rem; display: inline-block; font-size: 0.875rem; font-weight: 500; transition: background-color 0.2s ease;" 
                       onmouseover="this.style.backgroundColor='#d97706';" 
                       onmouseout="this.style.backgroundColor='#f59e0b';">
                       Edit
                    </a>

                    <!-- Delete (with modal trigger) -->
                    <form id="delete-form-{{ $degree->id }}" action="/degrees/{{ $degree->id }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            onclick="openModal({{ $degree->id }})"
                            style="padding: 0.5rem 0.75rem; background-color: #ef4444; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 0.875rem; font-weight: 500; transition: background-color 0.2s ease;"
                            onmouseover="this.style.backgroundColor='#dc2626';" 
                            onmouseout="this.style.backgroundColor='#ef4444';">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <a href="/degrees/create" 
       style="display: inline-block; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); color: #fff; text-decoration: none; border-radius: 8px; margin-top: 2rem; font-weight: 600; transition: box-shadow 0.2s ease; box-shadow: 0 4px 6px rgba(236, 72, 153, 0.25);" 
       onmouseover="this.style.boxShadow='0 8px 12px rgba(236, 72, 153, 0.4)';" 
       onmouseout="this.style.boxShadow='0 4px 6px rgba(236, 72, 153, 0.25)';">
       + Add Degree
    </a>

    <!-- MODAL -->
    <div id="deleteModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:999;">
        
        <div style="background:#fff; padding:30px; border-radius:12px; text-align:center; width:300px;">
            <h2 style="color:#ec4899;">Confirm Delete</h2>
            <p style="margin:15px 0; color:#555;">Are you sure you want to delete this degree?</p>

            <div style="margin-top:20px;">
                <button onclick="confirmDelete()" 
                    style="padding:8px 15px; background:#ef4444; color:#fff; border:none; border-radius:6px; margin-right:10px;">
                    Yes
                </button>

                <button onclick="closeModal()" 
                    style="padding:8px 15px; background:#ccc; border:none; border-radius:6px;">
                    No
                </button>
            </div>
        </div>
    </div>

    <!-- SCRIPT -->
    <script>
        let deleteId = null;

        function openModal(id) {
            deleteId = id;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none';
            deleteId = null;
        }

        function confirmDelete() {
            if(deleteId){
                document.getElementById('delete-form-' + deleteId).submit();
            }
        }
    </script>
@endsection
