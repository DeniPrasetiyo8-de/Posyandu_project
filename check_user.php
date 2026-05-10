<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::where('email', 'sipandu@gmail.com')->first();

if ($user) {
    echo "User ditemukan!\n";
    echo "Email: " . $user->email . "\n";
    echo "Name: " . $user->name . "\n";
    echo "Role: " . $user->role . "\n";
    
    // Test password
    $testPassword = 'sipandu6';
    if (Hash::check($testPassword, $user->password)) {
        echo "Password BENAR!\n";
    } else {
        echo "Password SALAH!\n";
    }
} else {
    echo "User TIDAK ditemukan!\n";
}
