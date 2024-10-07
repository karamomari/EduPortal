<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login',[LoginController::class, 'index'])->name('login.index');

Route::get('/login/show',[LoginController::class, 'show'])->name('login.show');

Route::get('/login/registration',[LoginController::class, 'registration'])->name('login.registration');

Route::post('/registration',[LoginController::class, 'store'])->name('login.registration');

Route::get('/messages/{senderId}/{receiverId}', [StudentController::class, 'show']);

Route::post('/messages/store', [StudentController::class, 'store']);

Route::delete('/student/{student_id}',[AdminController::class,'destroy'])->name('student.destroy');

Route::put('/student/{student_id}',[AdminController::class,'update'])->name('student.update');

Route::put('/student/edit/{student_id}', [AdminController::class, 'edit'])->name('student.edit');

Route::post('/subject/create/student',[AdminController::class, 'create'])->name('student.create');

Route::post('/subjects', [AdminController::class, 'create_subject'])->name('subject.create');

Route::get('/students/not-registered', [AdminController::class, 'getNotRegisteredStudents'])->name('students.not.registered');

Route::post('/subject/register', [AdminController::class, 'registerSubject'])->name('subject.register');

Route::get('/students/registered', [AdminController::class, 'getRegisteredStudents'])->name('students.registered');

Route::post('/marks/add', [AdminController::class, 'addMark'])->name('marks.add');

