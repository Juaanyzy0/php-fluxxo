<?php

use App\Http\Controllers\AuthClienteController;
use App\Http\Controllers\AuthStaffController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Flujo Cliente Final (Registro automático + OTP SMS simulado)
Route::get('/', [AuthClienteController::class, 'showLogin'])->name('cliente.login');
Route::post('/cliente/solicitar-otp', [AuthClienteController::class, 'solicitarOtp'])->name('cliente.solicitar-otp');
Route::get('/cliente/verificar-otp', [AuthClienteController::class, 'showVerifyOtp'])->name('cliente.verificar-otp');
Route::post('/cliente/verificar-otp', [AuthClienteController::class, 'verifyOtp'])->name('cliente.verificar-otp.post');
Route::post('/cliente/reenviar-otp', [AuthClienteController::class, 'resendOtp'])->name('cliente.reenviar-otp');

// Flujo Staff / Admin (Correo + Contraseña -> OTP vía Mailtrap Sandbox)
Route::get('/staff/login', [AuthStaffController::class, 'showLogin'])->name('staff.login');
Route::post('/staff/login', [AuthStaffController::class, 'login'])->name('staff.login.post');
Route::get('/staff/verificar-otp', [AuthStaffController::class, 'showVerifyOtp'])->name('staff.verificar-otp');
Route::post('/staff/verificar-otp', [AuthStaffController::class, 'verifyOtp'])->name('staff.verificar-otp.post');
Route::post('/staff/reenviar-otp', [AuthStaffController::class, 'resendOtp'])->name('staff.reenviar-otp');

// Dashboard Único Dinámico por Rol y Logout Unificado
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');
