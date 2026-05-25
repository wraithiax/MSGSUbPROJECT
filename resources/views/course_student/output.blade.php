@extends('format.layout')

@section('title', 'Courses')

@section('content')
<style>
    .course-output {
        color: #111827;
    }

    .course-output-hero {
        align-items: center;
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        border-radius: 8px;
        box-shadow: 0 12px 26px rgba(5, 150, 105, 0.22);
        color: #fff;
        display: flex;
        gap: 1.5rem;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        padding: 1.5rem;
    }

    .course-output-hero h1 {
        font-size: 1.85rem;
        font-weight: 800;
        letter-spacing: 0;
        margin: 0 0 0.35rem;
    }

    .course-output-hero p {
        font-size: 0.96rem;
        margin: 0;
        opacity: 0.92;
    }

    .course-output-summary {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }

    .summary-pill {
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.35);
        border-radius: 8px;
        min-width: 112px;
        padding: 0.75rem 0.9rem;
    }

    .summary-pill span {
        display: block;
        font-size: 0.74rem;
        font-weight: 700;
        opacity: 0.82;
        text-transform: uppercase;
    }

    .summary-pill strong {
        display: block;
        font-size: 1.25rem;
        margin-top: 0.2rem;
    }

    .course-output-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        overflow: hidden;
    }

    .course-output-card-header {
        align-items: center;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        gap: 1rem;
        justify-content: space-between;
        padding: 1rem 1.15rem;
    }

    .course-output-card-header h2 {
        color: #111827;
        font-size: 1rem;
        font-weight: 800;
        margin: 0;
    }

    .course-output-card-header a {
        background: #f3f4f6;
        border-radius: 6px;
        color: #374151;
        font-size: 0.9rem;
        font-weight: 700;
        padding: 0.55rem 0.8rem;
        text-decoration: none;
    }

    .course-output-table-wrap {
        overflow-x: auto;
    }

    .course-output-table {
        border-collapse: collapse;
        min-width: 760px;
        width: 100%;
    }

    .course-output-table th {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        color: #64748b;
        font-size: 0.72rem;
        font-weight: 800;
        padding: 0.9rem 1rem;
        text-align: left;
        text-transform: uppercase;
    }

    .course-output-table td {
        border-bottom: 1px solid #edf2f7;
        color: #374151;
        padding: 0.95rem 1rem;
        vertical-align: middle;
    }

    .course-output-table tbody tr:hover {
        background: #f9fafb;
    }

    .course-index {
        color: #64748b;
        font-weight: 700;
    }

    .course-name {
        color: #1e3a8a;
        font-weight: 800;
        text-decoration: none;
    }

    .enrolled-badge {
        align-items: center;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        border-radius: 999px;
        color: #047857;
        display: inline-flex;
        font-size: 0.86rem;
        font-weight: 800;
        min-width: 108px;
        padding: 0.42rem 0.7rem;
    }

    .enrolled-badge.empty {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #64748b;
    }

    .course-actions {
        align-items: center;
        display: flex;
        gap: 0.55rem;
        white-space: nowrap;
    }

    .action-link {
        border-radius: 6px;
        display: inline-block;
        font-size: 0.82rem;
        font-weight: 800;
        padding: 0.48rem 0.72rem;
        text-decoration: none;
    }

    .action-link.view {
        background: #e0f2fe;
        color: #0369a1;
    }

    .action-link.enroll {
        background: #059669;
        color: #fff;
    }

    .action-link.unenroll {
        background: #ef4444;
        color: #fff;
    }

    .empty-state {
        color: #64748b;
        padding: 2.25rem;
        text-align: center;
    }

    @media (max-width: 768px) {
        .course-output-hero,
        .course-output-card-header {
            align-items: stretch;
            flex-direction: column;
        }

        .course-output-summary {
            justify-content: flex-start;
        }
    }
</style>

@php
    $totalCourses = $courses->count();
    $totalEnrolled = $courses->sum('students_count');
@endphp

<div class="course-output">
    <div class="course-output-hero">
        <div>
            <h1>Courses</h1>
            <p>Available courses with enrolled students.</p>
        </div>

        <div class="course-output-summary">
            <div class="summary-pill">
                <span>Courses</span>
                <strong>{{ $totalCourses }}</strong>
            </div>
            <div class="summary-pill">
                <span>Enrolled</span>
                <strong>{{ $totalEnrolled }}</strong>
            </div>
        </div>
    </div>

    <div class="course-output-card">
        <div class="course-output-card-header">
            <h2>Course Enrollment Summary</h2>
            <a href="{{ route('course_students.index') }}">Manage Assignments</a>
        </div>

        <div class="course-output-table-wrap">
            <table class="course-output-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Course Name</th>
                        <th>Total Enrolled</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td class="course-index">{{ $loop->iteration }}</td>
                            <td>
                                <a href="{{ route('courses.show', $course->id) }}" class="course-name">
                                    {{ $course->name }}
                                </a>
                            </td>
                            <td>
                                <span @class(['enrolled-badge', 'empty' => $course->students_count === 0])>
                                    {{ $course->students_count }} {{ $course->students_count === 1 ? 'Student' : 'Students' }}
                                </span>
                            </td>
                            <td>
                                {{ $course->created_at ? $course->created_at->format('M d, Y') : 'N/A' }}
                            </td>
                            <td>
                                <div class="course-actions">
                                    <a href="{{ route('courses.show', $course->id) }}" class="action-link view">View</a>

                                    @if($course->students_count > 0)
                                        <a href="{{ route('courses.show', $course->id) }}#enrolled-students" class="action-link unenroll">Unenroll</a>
                                    @else
                                        <a href="{{ route('course_students.create', ['course_id' => $course->id]) }}" class="action-link enroll">Enroll</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">No courses available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
