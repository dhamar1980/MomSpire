<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseSyncController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Firebase Real-time Sync Routes
|--------------------------------------------------------------------------
|
| Routes for syncing chat data with Firebase Realtime Database
| These routes handle real-time messaging between pengguna, bidan, and dokter
|
*/

Route::middleware(['auth'])->group(function () {

    // Sync message to Firebase
    Route::post('/firebase/sync-message', [FirebaseSyncController::class, 'syncMessage'])
        ->name('firebase.sync_message');

    // Sync conversation to Firebase
    Route::post('/firebase/sync-conversation', [FirebaseSyncController::class, 'syncConversation'])
        ->name('firebase.sync_conversation');

    // Mark messages as read in Firebase
    Route::post('/firebase/mark-read', [FirebaseSyncController::class, 'markAsRead'])
        ->name('firebase.mark_read');

    // Get online users from Firebase
    Route::get('/firebase/online-users', [FirebaseSyncController::class, 'getOnlineUsers'])
        ->name('firebase.online_users');

});