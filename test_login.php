<?php
// Quick test file to verify login redirect

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create request for login form
$request = \Illuminate\Http\Request::create('/login', 'POST', [
    'email' => 'ajeng@gmail.com',
    'password' => 'ajeng123',
    '_token' => 'test'
]);

$request->setLaravelSession(app('session.store'));

// Test with Fortify login
try {
    \Illuminate\Support\Facades\Auth::guard('web')->attempt([
        'email' => 'ajeng@gmail.com',
        'password' => 'ajeng123'
    ]);
    
    if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
        $user = \Illuminate\Support\Facades\Auth::user();
        echo "✓ Login successful!\n";
        echo "  User: " . $user->name . "\n";
        echo "  Role: " . $user->role . "\n";
        
        // Test redirect response
        $loginResponse = app(\Laravel\Fortify\Contracts\LoginResponse::class);
        $response = $loginResponse->toResponse($request);
        
        echo "  Redirect target: " . $response->getTargetUrl() . "\n";
        
        if ($user->role === 'bidan' && strpos($response->getTargetUrl(), '/bidan/dashboard') !== false) {
            echo "\n✓ BIDAN REDIRECT WORKING!\n";
        } else {
            echo "\n✗ Redirect might be incorrect\n";
        }
    } else {
        echo "✗ Login failed\n";
    }
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
