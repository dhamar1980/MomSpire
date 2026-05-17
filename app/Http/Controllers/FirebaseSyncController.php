<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseSyncController extends Controller
{
    /**
     * Sync message to Firebase Realtime Database
     * Called by frontend after successful Laravel API message send
     */
    public function syncMessage(Request $request)
    {
        $validated = $request->validate([
            'conversation_key' => ['required', 'string'],
            'sender_type' => ['required', 'in:pengguna,bidan,dokter'],
            'sender_id' => ['required', 'integer'],
            'sender_name' => ['nullable', 'string'],
            'message' => ['required', 'string', 'max:5000'],
            'message_id' => ['nullable', 'integer'],
        ]);

        // Firebase Database URL
        $firebaseUrl = config('services.firebase.database_url');

        if (!$firebaseUrl) {
            return response()->json([
                'success' => false,
                'message' => 'Firebase not configured'
            ], 400);
        }

        try {
            // Push message to Firebase
            $response = Http::post("{$firebaseUrl}/messages/{$validated['conversation_key']}.json", [
                'sender_type' => $validated['sender_type'],
                'sender_id' => $validated['sender_id'],
                'sender_name' => $validated['sender_name'] ?? '',
                'message' => $validated['message'],
                'timestamp' => now()->timestamp,
                'is_read' => false,
                'laravel_message_id' => $validated['message_id'],
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'firebase_key' => $response->json('name'),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to sync to Firebase'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Firebase sync error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Firebase sync error'
            ], 500);
        }
    }

    /**
     * Sync conversation to Firebase
     */
    public function syncConversation(Request $request)
    {
        $validated = $request->validate([
            'conversation_key' => ['required', 'string'],
            'pengguna_id' => ['required', 'integer'],
            'professional_type' => ['required', 'in:bidan,dokter'],
            'professional_id' => ['required', 'integer'],
        ]);

        $firebaseUrl = config('services.firebase.database_url');

        if (!$firebaseUrl) {
            return response()->json([
                'success' => false,
                'message' => 'Firebase not configured'
            ], 400);
        }

        try {
            $response = Http::put("{$firebaseUrl}/conversations/{$validated['conversation_key']}.json", [
                'pengguna_id' => $validated['pengguna_id'],
                'professional_type' => $validated['professional_type'],
                'professional_id' => $validated['professional_id'],
                'created_at' => now()->timestamp,
                'last_message' => '',
                'last_message_at' => now()->timestamp,
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to sync conversation'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Firebase conversation sync error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Firebase sync error'
            ], 500);
        }
    }

    /**
     * Mark messages as read in Firebase
     */
    public function markAsRead(Request $request)
    {
        $validated = $request->validate([
            'conversation_key' => ['required', 'string'],
            'message_keys' => ['nullable', 'array'],
        ]);

        $firebaseUrl = config('services.firebase.database_url');

        if (!$firebaseUrl) {
            return response()->json(['success' => false], 400);
        }

        try {
            $keys = $validated['message_keys'] ?? [];

            if (empty($keys)) {
                // Mark all unread messages as read
                $response = Http::get("{$firebaseUrl}/messages/{$validated['conversation_key']}.json");

                if ($response->successful() && $data = $response->json()) {
                    foreach ($data as $key => $msg) {
                        if (empty($msg['is_read'])) {
                            Http::patch("{$firebaseUrl}/messages/{$validated['conversation_key']}/{$key}.json", [
                                'is_read' => true,
                            ]);
                        }
                    }
                }
            } else {
                foreach ($keys as $key) {
                    Http::patch("{$firebaseUrl}/messages/{$validated['conversation_key']}/{$key}.json", [
                        'is_read' => true,
                    ]);
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Firebase mark as read error: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Get online users from Firebase
     */
    public function getOnlineUsers(Request $request)
    {
        $firebaseUrl = config('services.firebase.database_url');

        if (!$firebaseUrl) {
            return response()->json(['success' => false], 400);
        }

        try {
            $response = Http::get("{$firebaseUrl}/online.json");

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'online_users' => $response->json(),
                ]);
            }

            return response()->json(['success' => false], 500);
        } catch (\Exception $e) {
            Log::error('Firebase online users error: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }
}