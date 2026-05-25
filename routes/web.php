<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DegreeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\MaintenanceController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Login routes (accessible without session)
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'submitLogin'])->name('login.submit');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.forgot');
Route::post('/forgot-password', [AuthController::class, 'submitForgotPassword'])->name('password.forgot.submit');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'submitResetPassword'])->name('password.reset.submit');

// Protected routes (require session)
Route::middleware('session-check')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Home page
    Route::get('/home', function () {
        $user = User::with('student.degree')->findOrFail(session('user_id'));

        return view('homePage', compact('user'));
    })->name("home");

    Route::get('/about', function () {
        return view('about');
    })->name("about");

    Route::get('/greetings', [ClientController::class, 'displayGreetings'])->name('greetings');
    Route::get('/clientProfile', [ClientController::class, 'displayProfile']);
    Route::get('/clientDashboard', [ClientController::class, 'displayDashboard']);
    Route::get('/clientAboutUs', [ClientController::class, 'displayAboutUs']);

    Route::middleware('role:admin,teacher')->resource('/students', StudentController::class);
    Route::middleware('role:admin,teacher')->get('/studentDetails', [StudentController::class, 'index'])->name('students.details');
    Route::middleware('role:admin,teacher')->resource('/degrees', DegreeController::class);

    Route::middleware('role:admin,teacher')->resource('/courses', CourseController::class);
    Route::middleware('role:admin,teacher')->get('/course-student-output', [CourseStudentController::class, 'output'])->name('course_students.output');
    Route::middleware('role:admin,teacher')->delete('/courses/{course}/students/{student}', [CourseStudentController::class, 'unenroll'])->name('course_students.unenroll');
    Route::middleware('role:admin,teacher')->resource('/course_students', CourseStudentController::class);
    Route::resource('/profiles', ProfileController::class);
    Route::resource('/posts', PostController::class);
    
    // User management routes (admin only)
    Route::middleware('admin-only')->resource('/users', UserController::class);

    // User profile routes - for users to edit their own profile
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('/dashboard/password', [UserController::class, 'updateDashboardPassword'])->name('dashboard.password.update');

    // Maintenance management routes
    Route::prefix('admin')->middleware('admin-only')->group(function () {
        Route::resource('/maintenance', MaintenanceController::class);
        Route::post('/maintenance/{maintenance}/activate', [MaintenanceController::class, 'activate'])->name('maintenance.activate');
        Route::post('/maintenance/{maintenance}/deactivate', [MaintenanceController::class, 'deactivate'])->name('maintenance.deactivate');
    });
});
// Route::middleware('group_middleware')->group(function(){
// 
// 










// Route::get('/addition', [CalculateController::class,'addition']);

// Route::get('/subtract', [CalculateController::class,'subtract']);
// Route::get('/multiply', [CalculateController::class,'multiply']);
// Route::get('/division', [CalculateController::class,'division']);
// Route::get('/remainder', [CalculateController::class,'remainder']);
// Route::get('/welcome', [PSUController::class,'welcome'])->name('welcome');
// Route::get('/mission', [PSUController::class,'mission'])->name('mission');
// Route::get('/vision', [PSUController::class,'vision'])->name('vision');
// Route::get('/EOMSPolicy', [PSUController::class,'EOMSPolicy'])->name('EOMSPolicy');
// Route::get('/student/{name}/{course}', [PSUController::class,'student'])->name('psu.student');

// Route::resource('/client',ClientController::class);

// Route::resource('/student',StudentController::class);




//Route::prefix('admin')->group(
   // function () {
   // Route::get('/dashboard', function () {
   //     return "This is the dashboard page for admin";
   // });
   // Route::get('/profile', function () {
   //     return "This is the profile page for admin";
   // });
   // Route::get('/configuration', function () {
   //     return "This is the configuration page for admin";
   // });
//});

//task 1:
// Route::get('/home', function () {
//     return "Iam john carlo. welcome to the home page";
// })->name('home.page');

// // task 2
// Route::get('/redirect-home', function () {
//     return redirect()->route('home.page');
// });

// // task 3
// Route::get('/greet/{name}', function ($name) {
//     return "Hello: " . $name;
// })->name('user.page');

// // task 4
// Route::get('/student/{name?}', function ($name = "John Carlo") {
//     return "Hello: {$name} ";
// });


// //task 5
// Route::prefix('administrator')->group(
//  function () {
//    Route::get('/dashboard', function () {
//         return "Dashboards";
//     })->name('dashboard.page');
//     Route::get('/profile', function () {
//         return "Welcome to my Profile";
//     });
//     Route::get('/settings', function () {
//         return "setting page";
//     });
// });

// //task 6
// Route::get('/redirectAdminDashboard', function () {
//     return redirect()->route('dashboard.page');
// });
// routing and resource controller
//when inputing data or CRUD its better to use resource controller rather than routing

//php artisan migration make:model Student -m or --migration //automatic to
//create migration

//next php artisan migrate after mo sya gawan ng attributes
