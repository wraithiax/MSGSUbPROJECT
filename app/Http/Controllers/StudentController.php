<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Degree;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with(['degree', 'user'])->latest()->get();
        $degrees = Degree::all();

        if (request()->expectsJson()) {
            return response()->json([
                'students' => $students->map(fn ($student) => $this->formatStudent($student)),
            ]);
        }

        return view('studentDetails')->with('students', $students)->with('degrees', $degrees);
        
        // $students = [
        //     ['name' => 'Mary Grace De Guzman',  'age' => 19, 'course' => 'BS Information Technology'],
        //     ['name' => 'Mia Shiela Grace Uson',  'age' => 20, 'course' => 'BS Computer Science'],
        //     ['name' => 'Karl Angelo Gamboa',  'age' => 21, 'course' => 'BS Information System'],
        //     ['name' => 'Geneva Uson',       'age' => 22, 'course' => 'BS Computer Engineering'],
        //     ['name' => 'Hervy Dela Cruz',    'age' => 19, 'course' => 'BS Information Technology'],
        //     ['name' => 'Arabelle Soriano',      'age' => 20, 'course' => 'BS Computer Science'],
        // ];

        // return view('studentsPage')->with('students', $students);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return "Showing form to create student";
        $degrees = Degree::all();
        return view('studentlayout.addstudent', ['degrees' => $degrees]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fname' => 'required|string|min:2|max:255|regex:/^[A-Za-z\s]+$/',
            'mname' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
            'lname' => 'required|string|min:2|max:255|regex:/^[A-Za-z\s]+$/',
            'email' => 'required|email|unique:users,email',
            'contact' => 'required|digits:11',
            'degree_id' => 'required|exists:degrees,id',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'fname.required' => 'First name is required.',
            'fname.min' => 'First name must be at least 2 letters.',
            'fname.regex' => 'First name must contain letters only.',

            'mname.required' => 'Middle name is required.',
            'mname.regex' => 'Middle name must contain letters only.',
            'lname.required' => 'Last name is required.',
            'lname.min' => 'Last name must be at least 2 letters.',
            'lname.regex' => 'Last name must contain letters only.',

            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email.',
            'email.unique' => 'Email already exists.',

            'contact.required' => 'Contact number is required.',
            'contact.digits' => 'Contact number must be exactly 11 digits.',

            'degree_id.required' => 'Degree is required.',
            'degree_id.exists' => 'Selected degree is invalid.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        $student = DB::transaction(function () use ($validated) {
            $username = explode('@', $validated['email'])[0];
            $user = User::create([
                'username' => $username,
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => 'student',
            ]);

            return Student::create([
                'fname' => $validated['fname'],
                'mname' => $validated['mname'],
                'lname' => $validated['lname'],
                'contact' => $validated['contact'],
                'degree_id' => $validated['degree_id'],
                'user_id' => $user->id,
            ])->load(['degree', 'user']);
        });

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Student added successfully.',
                'student' => $this->formatStudent($student),
            ], 201);
        }
        
        return redirect()->route('students.index')->with('success', 'Student added successfully.');
        // $request->validate([
        //     'name'    => 'required|string|max:255',
        //     'mname'   => 'required|string|max:255',
        //     'lname'   => 'required|string|max:255',
        //     'email'   => 'required|email|unique:students,email',
        //     'contact' => 'required|numeric',
        // ]);

        // Student::create($request->only(['name', 'mname', 'lname', 'email', 'contact']));

        // return redirect()->route('students.index')->with('success', 'Student added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // return "Displaying student with ID: $id";
        $student = Student::with(['degree', 'user'])->find($id);

        if (!$student) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Student not found.'], 404);
            }

            abort(404);
        }

        if (request()->expectsJson()) {
            return response()->json([
                'student' => $this->formatStudent($student),
            ]);
        }

        return view('studentlayout.show')->with("students", [$student]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // return "Showing form to edit student with ID: $id";
        $student = Student::with('user')->find($id);
        $degrees = Degree::all();
        return view('studentlayout.edit')->with('student', $student)->with('degrees', $degrees);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = Student::with('user')->findOrFail($id);

        $validated = $request->validate([
            'fname' => 'required|string|min:2|max:255|regex:/^[A-Za-z\s]+$/',
            'mname' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
            'lname' => 'required|string|min:2|max:255|regex:/^[A-Za-z\s]+$/',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($student->user_id),
            ],
            'contact' => 'required|digits:11',
            'degree_id' => 'required|exists:degrees,id',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'fname.required' => 'First name is required.',
            'fname.min' => 'First name must be at least 2 letters.',
            'fname.regex' => 'First name must contain letters only.',

            'mname.required' => 'Middle name is required.',
            'mname.regex' => 'Middle name must contain letters only.',

            'lname.required' => 'Last name is required.',
            'lname.min' => 'Last name must be at least 2 letters.',
            'lname.regex' => 'Last name must contain letters only.',

            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email.',
            'email.unique' => 'Email already exists.',

            'contact.required' => 'Contact number is required.',
            'contact.digits' => 'Contact number must be exactly 11 digits.',

            'degree_id.required' => 'Degree is required.',
            'degree_id.exists' => 'Selected degree is invalid.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        DB::transaction(function () use ($student, $validated, $request) {
            $student->update([
                'fname' => $validated['fname'],
                'mname' => $validated['mname'],
                'lname' => $validated['lname'],
                'contact' => $validated['contact'],
                'degree_id' => $validated['degree_id'],
            ]);

            if ($student->user) {
                $student->user->email = $validated['email'];
                $student->user->username = explode('@', $validated['email'])[0];

                if ($request->filled('password')) {
                    $student->user->password = bcrypt($validated['password']);
                }

                $student->user->save();
            }
        });

        $student->load(['degree', 'user']);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Student updated successfully.',
                'student' => $this->formatStudent($student),
            ]);
        }

        return redirect()->route('students.show', $student->id)->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // SessionCheck middleware already verified session exists
        // Additional check for safety
        if (!session('user_id')) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Session expired. Please log in again.'], 401);
            }

            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }
        
        $student = Student::find($id);
        if (!$student) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Student not found.'], 404);
            }

            return redirect()->route('students.index')->with('error', 'Student not found.');
        }
        
        // If logged-in user is being deleted, clear session first
        if (session('user_id') == $student->user_id) {
            session()->flush();
            auth()->logout();
            // Delete user and student
            if ($student->user_id) {
                User::destroy($student->user_id);
            }
            $student->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Student deleted successfully.',
                    'redirect' => route('login'),
                ]);
            }

            return redirect()->route('login')->with('success', 'Student deleted successfully.');
        }
        
        // Delete user and student
        if ($student->user_id) {
            User::destroy($student->user_id);
        }
        $student->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Student deleted successfully.',
                'id' => (int) $id,
            ]);
        }
        
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    private function formatStudent(Student $student): array
    {
        return [
            'id' => $student->id,
            'fname' => $student->fname,
            'mname' => $student->mname,
            'lname' => $student->lname,
            'full_name' => trim("{$student->fname} {$student->mname} {$student->lname}"),
            'email' => $student->user?->email,
            'contact' => $student->contact,
            'degree_id' => $student->degree_id,
            'degree' => $student->degree?->Degree,
            'show_url' => route('students.show', $student),
            'edit_url' => route('students.edit', $student),
            'update_url' => route('students.update', $student),
            'delete_url' => route('students.destroy', $student),
        ];
    }
}
 
