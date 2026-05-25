@extends('format.layout')

@section('title')
    CLIENT PAGE
@endsection

@section('header')
    @parent
@endsection

@section('content')
    <section class="card">
        <h1>Client Page</h1>
        <p>Welcome to your client information page.</p>

        <h3>Client Details</h3>
        <p><strong>Name:</strong> {{ $name ?? 'N/A' }}</p>
        <p><strong>Sex:</strong> {{ $sex ?? 'N/A' }}</p>
        <p><strong>Address:</strong> {{ $address ?? 'N/A' }}</p>
        <p><strong>Grade:</strong> {{ $grade }}</p>

        <h3>Numbers (1 to 10)</h3>
        @for($a = 1; $a <= 10; $a++)
            <p>{{ $a }}</p>
        @endfor

        @if ($grade % 2 == 0)
            <p><strong>Status:</strong> Your grade {{ $grade }} is even.</p>
        @else
            <p><strong>Status:</strong> Your grade {{ $grade }} is odd.</p>
        @endif

        @if ($grade >= 75 && $grade <= 100)
            <p><strong>Result:</strong> Your grade {{ $grade }} is passed.</p>
        @elseif ($grade < 75 && $grade >= 0)
            <p><strong>Result:</strong> Your grade {{ $grade }} is failed.</p>
        @endif
    </section>

@endsection

@section('footer')
    @parent
@endsection