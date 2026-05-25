<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Degree;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    private const DEFAULT_USER_PASSWORD = 'Password123';

    public function index()
    {
        $users = User::with(['profile', 'posts', 'student.degree'])->latest()->get();
        $degrees = Degree::all();

        if (request()->expectsJson()) {
            return response()->json([
                'users' => $users->map(fn ($user) => $this->formatUser($user)),
            ]);
        }

        return view('user.index', compact('users', 'degrees'));
    }

    public function create()
    {
        $degrees = Degree::all();
        return view('user.create', compact('degrees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role' => ['required', 'string', Rule::in(['admin', 'teacher', 'student'])],
            'fname' => ['required_if:role,student,teacher', 'nullable', 'string', 'min:2', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
            'mname' => ['required_if:role,student,teacher', 'nullable', 'string', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
            'lname' => ['required_if:role,student,teacher', 'nullable', 'string', 'min:2', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
            'contact' => ['required_if:role,student,teacher', 'nullable', 'digits:11'],
            'degree_id' => ['required_if:role,student', 'nullable', 'exists:degrees,id'],
            'username' => ['required', 'string', 'min:3', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        ], [
            'fname.required' => 'First name is required.',
            'fname.min' => 'First name must be at least 2 letters.',
            'fname.regex' => 'First name must contain letters only.',
            'mname.required' => 'Middle name is required.',
            'mname.regex' => 'Middle name must contain letters only.',
            'lname.required' => 'Last name is required.',
            'lname.min' => 'Last name must be at least 2 letters.',
            'lname.regex' => 'Last name must contain letters only.',
            'contact.required' => 'Contact number is required.',
            'contact.digits' => 'Contact number must be exactly 11 digits.',
            'degree_id.required' => 'Degree is required.',
            'degree_id.exists' => 'Selected degree is invalid.',
            'username.required' => 'Username is required.',
            'username.min' => 'Username must be at least 3 characters.',
            'username.unique' => 'Username already exists.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email.',
            'email.unique' => 'Email already exists.',
            'role.required' => 'Role is required.',
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make(self::DEFAULT_USER_PASSWORD),
                'role' => $validated['role'],
                'fname' => $validated['role'] === 'teacher' ? $validated['fname'] : null,
                'mname' => $validated['role'] === 'teacher' ? $validated['mname'] : null,
                'lname' => $validated['role'] === 'teacher' ? $validated['lname'] : null,
                'contact' => $validated['role'] === 'teacher' ? $validated['contact'] : null,
                'force_password_change' => true,
            ]);

            if ($user->isStudent()) {
                Student::create([
                    'fname' => $validated['fname'],
                    'mname' => $validated['mname'],
                    'lname' => $validated['lname'],
                    'contact' => $validated['contact'],
                    'degree_id' => $validated['degree_id'],
                    'user_id' => $user->id,
                ]);
            }

            return $user->load(['profile', 'posts', 'student.degree']);
        });

        if ($request->expectsJson()) {
            return response()->json([
                'message' => ucfirst($user->normalizedRole()) . ' account created successfully. Temporary password: ' . self::DEFAULT_USER_PASSWORD,
                'user' => $this->formatUser($user),
            ], 201);
        }

        return redirect()->route('users.index')->with(
            'success',
            ucfirst($user->normalizedRole()) . ' account created successfully. Temporary password: ' . self::DEFAULT_USER_PASSWORD
        );
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Show user's own profile for editing
     */
    public function editProfile()
    {
        $user = User::findOrFail(session('user_id'));
        return view('user.profile-edit', compact('user'));
    }

    /**
     * Update user's own profile
     */
    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(session('user_id'));

        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'current_password' => [
                'nullable',
                'required_with:password',
                function ($attribute, $value, $fail) use ($user, $request) {
                    if ($request->filled('password') && !Hash::check((string) $value, $user->password)) {
                        $fail('Current password is incorrect.');
                    }
                },
            ],
            'password' => ['nullable', 'string', 'min:8', 'required_with:password_confirmation', 'confirmed'],
            'password_confirmation' => ['nullable', 'required_with:password', 'string'],
        ], [
            'username.required' => 'Username is required.',
            'username.min' => 'Username must be at least 3 characters.',
            'username.unique' => 'Username already exists.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email.',
            'email.unique' => 'Email already exists.',
            'current_password.required_with' => 'Current password is required when changing your password.',
            'password.required_with' => 'Please fill in both password fields or leave both blank.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
            'password_confirmation.required_with' => 'Please confirm your new password.',
        ]);

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
            unset($validated['password_confirmation']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
            $validated['force_password_change'] = false;
        }

        unset($validated['current_password']);

        $user->update($validated);

        Session::put('user', $user->fresh());
        Session::put('user_email', $user->email);
        Session::put('force_password_change', $user->force_password_change);

        return back()->with('success', 'Your profile has been updated successfully.');
    }

    public function update(Request $request, string $id)
    {
        $user = User::with('student')->findOrFail($id);

        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in(['admin', 'teacher', 'student'])],
            'fname' => ['required_if:role,student,teacher', 'nullable', 'string', 'min:2', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
            'mname' => ['required_if:role,student,teacher', 'nullable', 'string', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
            'lname' => ['required_if:role,student,teacher', 'nullable', 'string', 'min:2', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
            'contact' => ['required_if:role,student,teacher', 'nullable', 'digits:11'],
            'degree_id' => ['required_if:role,student', 'nullable', 'exists:degrees,id'],
        ], [
            'username.required' => 'Username is required.',
            'username.min' => 'Username must be at least 3 characters.',
            'username.unique' => 'Username already exists.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email.',
            'email.unique' => 'Email already exists.',
            'role.required' => 'Role is required.',
            'fname.required' => 'First name is required.',
            'fname.min' => 'First name must be at least 2 letters.',
            'fname.regex' => 'First name must contain letters only.',
            'mname.required' => 'Middle name is required.',
            'mname.regex' => 'Middle name must contain letters only.',
            'lname.required' => 'Last name is required.',
            'lname.min' => 'Last name must be at least 2 letters.',
            'lname.regex' => 'Last name must contain letters only.',
            'contact.required' => 'Contact number is required.',
            'contact.digits' => 'Contact number must be exactly 11 digits.',
            'degree_id.required' => 'Degree is required.',
            'degree_id.exists' => 'Selected degree is invalid.',
        ]);

        DB::transaction(function () use ($user, $validated) {
            $user->update([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'fname' => $validated['role'] === 'teacher' ? $validated['fname'] : null,
                'mname' => $validated['role'] === 'teacher' ? $validated['mname'] : null,
                'lname' => $validated['role'] === 'teacher' ? $validated['lname'] : null,
                'contact' => $validated['role'] === 'teacher' ? $validated['contact'] : null,
            ]);

            if ($validated['role'] === 'student') {
                Student::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'fname' => $validated['fname'],
                        'mname' => $validated['mname'],
                        'lname' => $validated['lname'],
                        'contact' => $validated['contact'],
                        'degree_id' => $validated['degree_id'],
                    ]
                );
            }
        });

        if ($request->expectsJson()) {
            $user->load(['profile', 'posts', 'student.degree']);

            return response()->json([
                'message' => 'User updated successfully.',
                'user' => $this->formatUser($user),
            ]);
        }

        return redirect()->route('users.show', $user->id)->with('success', 'User updated successfully.');
    }

    public function updateDashboardPassword(Request $request)
    {
        $user = User::findOrFail(session('user_id'));

        if (!$user->force_password_change) {
            return redirect()->route('home')->with('error', 'Your password has already been updated.');
        }

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check((string) $value, $user->password)) {
                        $fail('Temporary password is incorrect.');
                    }
                },
            ],
        ], [
            'current_password.required' => 'Temporary password is required.',
            'password.required' => 'New password is required.',
            'password.min' => 'New password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
            'password.different' => 'New password must be different from your temporary password.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
            'force_password_change' => false,
        ]);

        $freshUser = $user->fresh();
        Session::put('user', $freshUser);
        Session::put('user_email', $freshUser->email);
        Session::put('force_password_change', false);

        return redirect()->route('home')->with('success', 'Your password has been changed successfully.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'User deleted successfully.',
                'id' => (int) $id,
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function show(string $id)
    {
        if (session('user_role') !== 'admin' && session('user_id') != $id) {
            return redirect()->route('profile.edit')->with('error', 'You can only view your own profile.');
        }

        $user = User::with(['profile', 'posts', 'student.degree'])->findOrFail($id);

        if (request()->expectsJson()) {
            return response()->json([
                'user' => $this->formatUser($user),
            ]);
        }

        return view('user.show', compact('user'));
    }

    private function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->normalizedRole(),
            'role_label' => ucfirst($user->normalizedRole()),
            'profile_status' => $user->profile ? 'Created' : 'Not yet',
            'profile_message' => $user->profile ? 'This user already has a profile.' : 'This user does not have a profile yet.',
            'posts_count' => $user->relationLoaded('posts') ? $user->posts->count() : $user->posts()->count(),
            'joined' => $user->created_at?->format('F d, Y h:i A'),
            'student' => $user->student ? [
                'fname' => $user->student->fname,
                'mname' => $user->student->mname,
                'lname' => $user->student->lname,
                'contact' => $user->student->contact,
                'degree_id' => $user->student->degree_id,
                'degree' => $user->student->degree?->Degree,
            ] : null,
            'teacher' => $user->isTeacher() ? [
                'fname' => $user->fname,
                'mname' => $user->mname,
                'lname' => $user->lname,
                'contact' => $user->contact,
            ] : null,
            'show_url' => route('users.show', $user),
            'update_url' => route('users.update', $user),
            'delete_url' => route('users.destroy', $user),
        ];
    }
}
