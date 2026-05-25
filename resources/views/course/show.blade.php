@extends('format.layout')

@section('title', 'Course Details')

@section('content')
<style>
    .course-show {
        color: #111827;
    }

    .course-show-header {
        align-items: center;
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        border-radius: 8px;
        box-shadow: 0 12px 26px rgba(5, 150, 105, 0.2);
        color: #fff;
        display: flex;
        gap: 1.5rem;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        padding: 1.5rem;
    }

    .course-show-header h1 {
        font-size: 1.8rem;
        font-weight: 800;
        letter-spacing: 0;
        margin: 0 0 0.35rem;
    }

    .course-show-header p {
        margin: 0;
        opacity: 0.92;
    }

    .course-count {
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.34);
        border-radius: 8px;
        min-width: 132px;
        padding: 0.85rem 1rem;
        text-align: center;
    }

    .course-count span {
        display: block;
        font-size: 0.74rem;
        font-weight: 800;
        opacity: 0.84;
        text-transform: uppercase;
    }

    .course-count strong {
        display: block;
        font-size: 1.45rem;
        margin-top: 0.2rem;
    }

    .course-show-grid {
        display: grid;
        gap: 1.25rem;
        grid-template-columns: minmax(260px, 0.75fr) minmax(360px, 1.25fr);
    }

    .course-panel {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        overflow: hidden;
    }

    .course-panel-header {
        border-bottom: 1px solid #e5e7eb;
        padding: 1rem 1.15rem;
    }

    .course-panel-header h2 {
        color: #111827;
        font-size: 1rem;
        font-weight: 800;
        margin: 0;
    }

    .course-panel-body {
        padding: 1.15rem;
    }

    .detail-row {
        margin-bottom: 1rem;
    }

    .detail-row:last-child {
        margin-bottom: 0;
    }

    .detail-row span {
        color: #64748b;
        display: block;
        font-size: 0.75rem;
        font-weight: 800;
        margin-bottom: 0.35rem;
        text-transform: uppercase;
    }

    .detail-row strong,
    .detail-row p {
        color: #374151;
        display: block;
        font-size: 0.98rem;
        line-height: 1.55;
        margin: 0;
    }

    .student-table-wrap {
        overflow-x: auto;
    }

    .student-table {
        border-collapse: collapse;
        min-width: 680px;
        width: 100%;
    }

    .student-table th {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 0.72rem;
        font-weight: 800;
        padding: 0.9rem 1rem;
        text-align: left;
        text-transform: uppercase;
    }

    .student-table td {
        border-bottom: 1px solid #edf2f7;
        color: #374151;
        padding: 0.95rem 1rem;
    }

    .student-table tbody tr:hover {
        background: #f9fafb;
    }

    .student-name {
        color: #1e3a8a;
        font-weight: 800;
        text-decoration: none;
    }

    .student-row-actions {
        align-items: center;
        display: flex;
        gap: 0.5rem;
        white-space: nowrap;
    }

    .student-row-action {
        border: none;
        border-radius: 6px;
        cursor: pointer;
        display: inline-block;
        font-size: 0.82rem;
        font-weight: 800;
        padding: 0.48rem 0.72rem;
        text-decoration: none;
    }

    .student-row-action.view {
        background: #e0f2fe;
        color: #0369a1;
    }

    .student-row-action.unenroll {
        background: #ef4444;
        color: #fff;
    }

    .empty-students {
        color: #64748b;
        padding: 2rem;
        text-align: center;
    }

    .course-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1.25rem;
    }

    .course-action {
        border-radius: 6px;
        color: #fff;
        display: inline-block;
        font-size: 0.9rem;
        font-weight: 800;
        padding: 0.65rem 0.9rem;
        text-decoration: none;
    }

    .course-action.enroll {
        background: #059669;
    }

    .course-action.back {
        background: #e5e7eb;
        color: #374151;
    }

    @media (max-width: 900px) {
        .course-show-header,
        .course-show-grid {
            display: block;
        }

        .course-count,
        .course-panel {
            margin-top: 1rem;
        }
    }
</style>

<div class="course-show">
    <div class="course-show-header">
        <div>
            <h1>{{ $course->name }}</h1>
            <p>Course details and enrolled students.</p>
        </div>

        <div class="course-count">
            <span>Enrolled</span>
            <strong>{{ $course->students->count() }}</strong>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div style="padding: 1rem; background: #d1fae5; border: 1px solid #6ee7b7; border-radius: 8px; color: #065f46; margin-bottom: 1.5rem;" role="alert">
            <strong>Success!</strong> {{ $message }}
        </div>
    @endif

    <div class="course-show-grid">
        <div class="course-panel">
            <div class="course-panel-header">
                <h2>Course Information</h2>
            </div>
            <div class="course-panel-body">
                <div class="detail-row">
                    <span>Course Name</span>
                    <strong>{{ $course->name }}</strong>
                </div>
                <div class="detail-row">
                    <span>Description</span>
                    <p>{{ $course->description }}</p>
                </div>
                <div class="detail-row">
                    <span>Created</span>
                    <strong>{{ $course->created_at ? $course->created_at->format('M d, Y') : 'N/A' }}</strong>
                </div>
                <div class="detail-row">
                    <span>Last Updated</span>
                    <strong>{{ $course->updated_at ? $course->updated_at->format('M d, Y') : 'N/A' }}</strong>
                </div>

                <div class="course-actions">
                    <a href="{{ route('course_students.create', ['course_id' => $course->id]) }}" class="course-action enroll">Enroll Student</a>
                    <a href="{{ route('course_students.output') }}" class="course-action back">Back</a>
                </div>
            </div>
        </div>

        <div class="course-panel" id="enrolled-students">
            <div class="course-panel-header">
                <h2>Enrolled Students</h2>
            </div>

            <div class="student-table-wrap">
                <table class="student-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Degree/Course</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($course->students as $student)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a href="{{ route('students.show', $student->id) }}" class="student-name">
                                        {{ $student->fname }} {{ $student->mname }} {{ $student->lname }}
                                    </a>
                                </td>
                                <td>{{ $student->user?->email ?? 'N/A' }}</td>
                                <td>{{ $student->contact ?? 'N/A' }}</td>
                                <td>{{ $student->degree?->Degree ?? 'N/A' }}</td>
                                <td>
                                    <div class="student-row-actions">
                                        <a href="{{ route('students.show', $student->id) }}" class="student-row-action view">View</a>
                                        <form action="{{ route('course_students.unenroll', [$course->id, $student->id]) }}" method="POST" onsubmit="return confirm('Unenroll this student from {{ $course->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="student-row-action unenroll">Unenroll</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-students">No students enrolled in this course yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
