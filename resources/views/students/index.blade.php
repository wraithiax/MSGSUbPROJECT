@extends('layouts.app')

@section('title', 'Students')

@section('content')

<h2 class="mb-3">Student List</h2>

<table class="table table-bordered table-striped">

    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Age</th>
            <th>Course</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>

    @forelse($students as $student)

        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $student['name'] }}</td>
            <td>{{ $student['age'] }}</td>
            <td>{{ $student['course'] }}</td>

            <td>

                @if($student['age'] == 19)
                    Freshman Student
                @elseif($student['age'] == 20)
                    Sophomore Student
                @elseif($student['age'] == 21)
                    Junior Student
                @elseif($student['age'] == 22)
                    Senior Student
                @else
                    Unknown
                @endif

            </td>

        </tr>

    @empty

        <tr>
            <td colspan="5" class="text-center text-danger">
                No students available.
            </td>
        </tr>

    @endforelse

    </tbody>

</table>

@endsection