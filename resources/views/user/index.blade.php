@extends('format.layout')

@section('title', 'Users')

@section('content')
<style>
    .user-toolbar {
        align-items: center;
        display: flex;
        gap: 1rem;
        justify-content: space-between;
        margin-bottom: 40px;
        flex-wrap: wrap;
    }

    .user-alert {
        border-radius: 8px;
        display: none;
        margin-bottom: 1.5rem;
        padding: 1rem;
    }

    .user-alert.success {
        background: #d1fae5;
        border: 1px solid #6ee7b7;
        color: #065f46;
    }

    .user-alert.error {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        color: #991b1b;
    }

    .user-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(236, 72, 153, 0.15);
        overflow-x: auto;
        padding: 2rem;
    }

    .user-table {
        border-collapse: collapse;
        width: 100%;
    }

    .user-table tr {
        border-bottom: 1px solid #f3f4f6;
    }

    .user-table th {
        background: #fdf2f8;
        border-bottom: 2px solid #ec4899;
        color: #ec4899;
        padding: 1rem;
        text-align: left;
    }

    .user-table td {
        padding: 1rem;
    }

    .user-table th:last-child,
    .user-table td:last-child {
        text-align: center;
    }

    .user-btn {
        border: none;
        border-radius: 6px;
        color: #fff;
        cursor: pointer;
        display: inline-block;
        font-size: 0.95rem;
        font-weight: 600;
        margin-right: 0.5rem;
        padding: 0.5rem 1rem;
        text-decoration: none;
    }

    .user-btn.primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
    }

    .user-btn.view {
        background: #3b82f6;
    }

    .user-btn.edit {
        background: #f59e0b;
    }

    .user-btn.delete {
        background: #ef4444;
    }

    .user-btn.secondary {
        background: #e5e7eb;
        color: #374151;
    }

    .user-modal {
        align-items: center;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        height: 100%;
        justify-content: center;
        left: 0;
        overflow-y: auto;
        padding: 7rem 1rem 2rem;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 999;
    }

    .user-modal.active {
        display: flex;
    }

    .user-modal-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        margin: auto;
        max-height: calc(100vh - 9rem);
        max-width: 760px;
        overflow-y: auto;
        padding: 2rem;
        width: 100%;
    }

    .user-modal-card h2 {
        background: #fff;
        margin-top: 0;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .user-form-grid,
    .user-detail-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .user-form-section {
        border-top: 2px solid #fce7f3;
        grid-column: 1 / -1;
        margin-top: 0.5rem;
        padding-top: 1rem;
    }

    .user-form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .user-form-group.full {
        grid-column: 1 / -1;
    }

    .user-form-group label {
        color: #ec4899;
        font-weight: 600;
    }

    .user-form-group input,
    .user-form-group select {
        border: 2px solid #fce7f3;
        border-radius: 8px;
        font-size: 1rem;
        padding: 0.75rem;
        width: 100%;
    }

    .user-form-error {
        color: #ef4444;
        font-size: 0.85rem;
        min-height: 1rem;
    }

    .user-detail-item {
        background: #fdf2f8;
        border: 1px solid #fbcfe8;
        border-radius: 8px;
        padding: 1rem;
    }

    .user-detail-item span {
        color: #ec4899;
        display: block;
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 0.35rem;
    }

    .user-detail-item strong {
        color: #374151;
        display: block;
        word-break: break-word;
    }

    .user-modal-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }

    @media (max-width: 768px) {
        .user-form-grid,
        .user-detail-grid {
            grid-template-columns: 1fr;
        }

        .user-modal-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .user-modal {
            align-items: flex-start;
            padding-top: 8rem;
        }

        .user-modal-card {
            max-height: calc(100vh - 10rem);
            padding: 1.25rem;
        }

        .user-btn {
            margin-bottom: 0.5rem;
        }
    }
</style>

<div
    id="user-page"
    data-index-url="{{ route('users.index') }}"
    data-store-url="{{ route('users.store') }}"
>
    <div class="user-toolbar">
        <div>
            <h1 style="color: #ec4899; font-size: 2.5rem; font-weight: 700; margin: 0;">Users</h1>
            <p style="color: #9f1239; font-size: 1rem; margin-top: 0.5rem;">Manage user accounts connected to profiles and posts.</p>
        </div>
        <button type="button" class="user-btn primary" id="open-create-user">+ New User</button>
    </div>

    @if (session('success'))
        <div class="user-alert success" style="display: block;">{{ session('success') }}</div>
    @endif

    <div class="user-alert" id="user-alert"></div>

    <div class="user-card">
        <table class="user-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Profile</th>
                    <th>Posts</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="users-table-body">
                <tr>
                    <td colspan="7" style="padding: 2rem; text-align: center; color: #6b7280;">Loading users...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="user-modal" id="user-form-modal">
        <div class="user-modal-card">
            <h2 id="user-form-title" style="color:#ec4899; margin-bottom: 1rem;">New User</h2>

            <div id="user-form">
                @csrf
                <input type="hidden" name="user_id" id="user_id">

                <div class="user-form-grid">
                    <div class="user-form-group">
                        <label for="user_role">Role</label>
                        <select name="role" id="user_role" required>
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                            <option value="admin">Admin</option>
                        </select>
                        <span class="user-form-error" data-user-error-for="role"></span>
                    </div>

                    <div class="user-form-group">
                        <label for="user_username">Username</label>
                        <input type="text" name="username" id="user_username" required>
                        <span class="user-form-error" data-user-error-for="username"></span>
                    </div>

                    <div class="user-form-group full">
                        <label for="user_email">Email</label>
                        <input type="email" name="email" id="user_email" required>
                        <span class="user-form-error" data-user-error-for="email"></span>
                    </div>

                    <div class="user-form-section" id="user-role-info-section">
                        <h3 id="user-role-info-title" style="color:#ec4899; margin-bottom: 1rem;">Student Information</h3>
                        <div id="user-role-info-note" style="padding: 1rem; background: #fff1f2; border: 1px solid #fbcfe8; border-radius: 8px; color: #9f1239; margin-bottom: 1rem; display: none;">
                            This account type uses the Role, Username, and Email fields above.
                        </div>
                        <div class="user-form-grid" id="user-person-fields">
                            <div class="user-form-group">
                                <label for="user_fname">First Name</label>
                                <input type="text" name="fname" id="user_fname">
                                <span class="user-form-error" data-user-error-for="fname"></span>
                            </div>

                            <div class="user-form-group">
                                <label for="user_mname">Middle Name</label>
                                <input type="text" name="mname" id="user_mname">
                                <span class="user-form-error" data-user-error-for="mname"></span>
                            </div>

                            <div class="user-form-group">
                                <label for="user_lname">Last Name</label>
                                <input type="text" name="lname" id="user_lname">
                                <span class="user-form-error" data-user-error-for="lname"></span>
                            </div>

                            <div class="user-form-group">
                                <label for="user_contact">Contact Number</label>
                                <input type="text" name="contact" id="user_contact" placeholder="11 digits">
                                <span class="user-form-error" data-user-error-for="contact"></span>
                            </div>

                            <div class="user-form-group full" id="user-degree-group">
                                <label for="user_degree_id">Degree/Course</label>
                                <select name="degree_id" id="user_degree_id">
                                    <option value="">Select a degree</option>
                                    @foreach($degrees as $degree)
                                        <option value="{{ $degree->id }}">{{ $degree->Degree }}</option>
                                    @endforeach
                                </select>
                                <span class="user-form-error" data-user-error-for="degree_id"></span>
                            </div>
                        </div>
                    </div>

                    <div class="user-form-group full" id="temporary-password-note">
                        <label>Temporary Password</label>
                        <div style="width: 100%; padding: 0.75rem; border: 2px dashed #f9a8d4; border-radius: 8px; background: #fff1f2; color: #9f1239; font-weight: 600;">
                            Password123
                        </div>
                    </div>
                </div>

                <div class="user-modal-actions">
                    <button type="button" class="user-btn secondary" id="close-user-form">Cancel</button>
                    <button type="submit" class="user-btn primary" id="save-user">Save User</button>
                </div>
            </form>
        </div>
    </div>

    <div class="user-modal" id="user-details-modal">
        <div class="user-modal-card">
            <h2 style="color:#ec4899; margin-bottom: 0.35rem;">User Details</h2>
            <p id="detail-user-title" style="color:#831843; font-size: 1.15rem; font-weight: 700; margin-bottom: 1.5rem;"></p>

            <div class="user-detail-grid">
                <div class="user-detail-item">
                    <span>Username</span>
                    <strong id="detail-user-username"></strong>
                </div>
                <div class="user-detail-item">
                    <span>Email</span>
                    <strong id="detail-user-email"></strong>
                </div>
                <div class="user-detail-item">
                    <span>Role</span>
                    <strong id="detail-user-role"></strong>
                </div>
                <div class="user-detail-item">
                    <span>Profile Status</span>
                    <strong id="detail-user-profile"></strong>
                </div>
                <div class="user-detail-item">
                    <span>Posts Created</span>
                    <strong id="detail-user-posts"></strong>
                </div>
                <div class="user-detail-item">
                    <span>Joined</span>
                    <strong id="detail-user-joined"></strong>
                </div>
                <div class="user-detail-item user-student-detail">
                    <span>Student Name</span>
                    <strong id="detail-user-student-name"></strong>
                </div>
                <div class="user-detail-item user-student-detail">
                    <span>Student Contact</span>
                    <strong id="detail-user-student-contact"></strong>
                </div>
                <div class="user-detail-item user-student-detail">
                    <span>Degree/Course</span>
                    <strong id="detail-user-student-degree"></strong>
                </div>
            </div>

            <div class="user-modal-actions">
                <button type="button" class="user-btn secondary" id="close-user-details">Close</button>
            </div>
        </div>
    </div>

    <div class="user-modal" id="delete-user-modal">
        <div class="user-modal-card" style="max-width: 380px; text-align: center;">
            <h2 style="color:#ec4899;">Confirm Delete</h2>
            <p style="margin:15px 0; color:#555;">Delete this user? Related profile and posts may also be removed.</p>

            <div class="user-modal-actions" style="justify-content: center;">
                <button type="button" class="user-btn delete" id="confirm-delete-user">Yes</button>
                <button type="button" class="user-btn secondary" id="cancel-delete-user">No</button>
            </div>
        </div>
    </div>
</div>
@endsection
