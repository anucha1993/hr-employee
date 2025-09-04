<?php
// ตัวอย่างการใช้ middleware ใน Route

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/admin', fn() => view('admin'))->middleware('role:Admin');
Route::get('/users/create', [UserController::class, 'create'])->middleware('permission:create user');
