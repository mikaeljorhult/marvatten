<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NetworkAdapterController;
use App\Http\Controllers\VersionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkstationController;
use App\Http\Controllers\WorkstationApplicationController;
use App\Http\Controllers\WorkstationVersionController;
use App\Models\Application;
use App\Models\Workstation;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('applications', ApplicationController::class);
    Route::resource('applications.versions', VersionController::class)->only(['store', 'edit', 'update', 'destroy']);
    Route::resource('workstations', WorkstationController::class);
    Route::resource('workstations.applications', WorkstationApplicationController::class)->only(['store', 'edit', 'destroy']);
    Route::resource('workstations.versions', WorkstationVersionController::class)->only(['store', 'destroy']);
    Route::resource('users', UserController::class);
    Route::resource('attachments', AttachmentController::class)->only(['store', 'show', 'edit', 'update', 'destroy']);
    Route::resource('comments', CommentController::class)->only(['store', 'edit', 'update', 'destroy']);
    Route::resource('network-adapters', NetworkAdapterController::class)->only(['store', 'edit', 'update', 'destroy']);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    $attentionApplications = Application::with(['versions'])->needsAttention()->get();
    $attentionWorkstations = Workstation::with(['applications.versions', 'versions'])->needsAttention()->get();

    return view('dashboard', compact('attentionApplications', 'attentionWorkstations'));
})->name('dashboard');
