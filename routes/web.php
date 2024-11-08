<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get("/login", [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get("/company-not-found", [CompanyController::class, 'companyNotFound'])->name('company-not-found');
// Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('change-password');
// Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');

Route::middleware(['isLoginValid'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile/{id}', [UserController::class, 'profile'])->name('profile');

    Route::get("/", [UserController::class, 'index'])->name('index');
//     Route::get("/user", [UserController::class, "showUser"])->name('user');
//     Route::get("/user/create", [UserController::class, "create"])->name('user.create');
//     Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
//     Route::get('/user/view/{id}', [UserController::class, 'showDetailUser'])->name('user.detail');
//     Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
//     Route::post('/user/update', [UserController::class, 'update'])->name('user.update');
//
//     Route::get('/role', [RoleController::class, 'showRole'])->name('role');
//     Route::get("/role/create", [RoleController::class, "create"])->name('role.create');
//     Route::post('/role/store', [RoleController::class, 'store'])->name('role.store');
//     Route::get('/role/view/{id}', [RoleController::class, 'showDetailRole'])->name('role.detail');
//     Route::get('/role/edit/{id}', [RoleController::class, 'edit'])->name('role.edit');
//     Route::post('/role/update', [RoleController::class, 'update'])->name('role.update');
//
//     Route::get('/upload', [UploadController::class, 'showUpload'])->name('upload');
//     Route::post('/upload/store', [UploadController::class, 'store'])->name('upload.store');


    Route::get("/company", [CompanyController::class, "showCompany"])->name('company');
    Route::get("/company/create", [CompanyController::class, "create"])->name('company.create');
    Route::post('/company/store', [CompanyController::class, 'store'])->name('company.store');
    Route::get('/company/view/{id}', [CompanyController::class, 'showDetailCompany'])->name('company.detail');
    Route::get('/company/edit/{id}', [CompanyController::class, 'edit'])->name('company.edit');
    Route::post('/company/update', [CompanyController::class, 'update'])->name('company.update');

});
