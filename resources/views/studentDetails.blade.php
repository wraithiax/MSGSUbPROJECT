@extends('format.layout')

@section('title')
    Student Details
@endsection

@section('content')
<style>
    .student-toolbar {
        align-items: center;
        display: flex;
        gap: 1rem;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .student-alert {
        border-left: 4px solid #ec4899;
        border-radius: 4px;
        color: #9f1239;
        display: none;
        margin-bottom: 1.5rem;
        padding: 1rem;
    }

    .student-alert.success {
        background-color: #fce7f3;
    }

    .student-alert.error {
        background-color: #fee2e2;
        border-left-color: #ef4444;
        color: #7f1d1d;
    }

    .student-table {
        background: #fff;
        border-collapse: collapse;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(236, 72, 153, 0.15);
        overflow: hidden;
        width: 100%;
    }

    .student-table thead tr {
        background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
        color: #fff;
    }

    .student-table th,
    .student-table td {
        padding: 1rem;
        text-align: left;
    }

    .student-table th {
        font-weight: 600;
        padding-bottom: 1.25rem;
        padding-top: 1.25rem;
    }

    .student-table tbody tr {
        border-bottom: 1px solid #fce7f3;
    }

    .student-table tbody tr:nth-child(even) {
        background-color: #fdf2f8;
    }

    .student-table tbody tr:hover {
        background-color: #fbecf8;
    }

    .student-btn {
        border: none;
        border-radius: 6px;
        color: #fff;
        cursor: pointer;
        display: inline-block;
        font-size: 0.875rem;
        font-weight: 500;
        margin-right: 0.5rem;
        padding: 0.5rem 0.75rem;
        text-decoration: none;
    }

    .student-btn.primary {
        background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(236, 72, 153, 0.25);
        font-size: 1rem;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
    }

    .student-btn.view {
        background-color: #06b6d4;
    }

    .student-btn.edit {
        background-color: #f59e0b;
    }

    .student-btn.delete {
        background-color: #ef4444;
    }

    .student-modal {
        align-items: center;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        height: 100%;
        justify-content: center;
        left: 0;
        padding: 1rem;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 999;
    }

    .student-modal.active {
        display: flex;
    }

    .student-modal-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        max-height: 90vh;
        max-width: 720px;
        overflow-y: auto;
        padding: 2rem;
        width: 100%;
    }

    .student-form-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .student-form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .student-form-group.full {
        grid-column: 1 / -1;
    }

    .student-form-group label {
        color: #ec4899;
        font-weight: 600;
    }

    .student-form-group input,
    .student-form-group select {
        border: 2px solid #fce7f3;
        border-radius: 8px;
        font-size: 1rem;
        padding: 0.75rem;
        width: 100%;
    }

    .student-form-error {
        color: #ef4444;
        font-size: 0.85rem;
        min-height: 1rem;
    }

    .student-modal-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }

    .student-btn.secondary {
        background-color: #e5e7eb;
        color: #374151;
    }

    .student-detail-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        margin-top: 1.5rem;
    }

    .student-detail-item {
        background: #fdf2f8;
        border: 1px solid #fbcfe8;
        border-radius: 8px;
        padding: 1rem;
    }

    .student-detail-item span {
        color: #ec4899;
        display: block;
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 0.35rem;
    }

    .student-detail-item strong {
        color: #374151;
        display: block;
        font-size: 1rem;
        font-weight: 600;
        word-break: break-word;
    }

    @media (max-width: 768px) {
        .student-toolbar,
        .student-modal-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .student-form-grid {
            grid-template-columns: 1fr;
        }

        .student-detail-grid {
            grid-template-columns: 1fr;
        }

        .student-table {
            display: block;
            overflow-x: auto;
        }
    }
</style>

<div
    id="student-page"
    data-index-url="{{ route('students.index') }}"
    data-store-url="{{ route('students.store') }}"
    data-degrees='@json($degrees->map(fn ($degree) => ["id" => $degree->id, "name" => $degree->Degree])->values())'
>
    <div class="student-toolbar">
        <div>
            <h1 style="color: #ec4899; font-size: 2.5rem; font-weight: 700; margin: 0;">Student Details</h1>
            <p style="color: #9f1239; font-size: 1rem; margin-top: 0.5rem;">View and manage student information</p>
        </div>

        <button type="button" class="student-btn primary" id="open-create-student">+ Add Student</button>
    </div>

    @if(session('success'))
        <div class="student-alert success" style="display: block;">{{ session('success') }}</div>
    @endif

    <div class="student-alert" id="student-alert"></div>

    <table class="student-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contacts</th>
                <th>Course</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="students-table-body">
            <tr>
                <td colspan="6" style="color: #831843; font-weight: 600; text-align: center;">Loading students...</td>
            </tr>
        </tbody>
    </table>

    <div class="student-modal" id="student-form-modal">
        <div class="student-modal-card">
            <h2 id="student-form-title" style="color: #ec4899; margin-bottom: 1rem;">Add Student</h2>

            <form id="student-form">
                @csrf
                <input type="hidden" name="student_id" id="student_id">

                <div class="student-form-grid">
                    <div class="student-form-group">
                        <label for="fname">First Name</label>
                        <input type="text" name="fname" id="fname" required>
                        <span class="student-form-error" data-error-for="fname"></span>
                    </div>

                    <div class="student-form-group">
                        <label for="mname">Middle Name</label>
                        <input type="text" name="mname" id="mname" required>
                        <span class="student-form-error" data-error-for="mname"></span>
                    </div>

                    <div class="student-form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" name="lname" id="lname" required>
                        <span class="student-form-error" data-error-for="lname"></span>
                    </div>

                    <div class="student-form-group">
                        <label for="contact">Contact</label>
                        <input type="text" name="contact" id="contact" required>
                        <span class="student-form-error" data-error-for="contact"></span>
                    </div>

                    <div class="student-form-group">
                        <label for="degree_id">Course</label>
                        <select name="degree_id" id="degree_id" required>
                            <option value="">Select a course</option>
                            @foreach($degrees as $degree)
                                <option value="{{ $degree->id }}">{{ $degree->Degree }}</option>
                            @endforeach
                        </select>
                        <span class="student-form-error" data-error-for="degree_id"></span>
                    </div>

                    <div class="student-form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" required>
                        <span class="student-form-error" data-error-for="email"></span>
                    </div>

                    <div class="student-form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" required>
                        <span class="student-form-error" data-error-for="password"></span>
                    </div>

                    <div class="student-form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required>
                        <span class="student-form-error" data-error-for="password_confirmation"></span>
                    </div>
                </div>

                <div class="student-modal-actions">
                    <button type="button" class="student-btn secondary" id="close-student-form">Cancel</button>
                    <button type="submit" class="student-btn primary" id="save-student">Save Student</button>
                </div>
            </form>
        </div>
    </div>

    <div class="student-modal" id="student-details-modal">
        <div class="student-modal-card">
            <h2 style="color:#ec4899; margin-bottom: 0.35rem;">Student Information</h2>
            <p id="detail-name" style="color:#831843; font-size: 1.15rem; font-weight: 700;"></p>

            <div class="student-detail-grid">
                <div class="student-detail-item">
                    <span>First Name</span>
                    <strong id="detail-first-name"></strong>
                </div>
                <div class="student-detail-item">
                    <span>Middle Name</span>
                    <strong id="detail-middle-name"></strong>
                </div>
                <div class="student-detail-item">
                    <span>Last Name</span>
                    <strong id="detail-last-name"></strong>
                </div>
                <div class="student-detail-item">
                    <span>Email</span>
                    <strong id="detail-email"></strong>
                </div>
                <div class="student-detail-item">
                    <span>Contact</span>
                    <strong id="detail-contact"></strong>
                </div>
                <div class="student-detail-item">
                    <span>Course</span>
                    <strong id="detail-course"></strong>
                </div>
            </div>

            <div class="student-modal-actions">
                <button type="button" class="student-btn secondary" id="close-student-details">Close</button>
            </div>
        </div>
    </div>

    <div class="student-modal" id="deleteModal">
        <div class="student-modal-card" style="max-width: 360px; text-align: center;">
            <h2 style="color:#ec4899;">Confirm Delete</h2>
            <p style="margin:15px 0; color:#555;">Are you sure you want to delete this student?</p>

            <div class="student-modal-actions" style="justify-content: center;">
                <button type="button" class="student-btn delete" id="confirm-delete-student">Yes</button>
                <button type="button" class="student-btn secondary" id="cancel-delete-student">No</button>
            </div>
        </div>
    </div>
</div>
@endsection
