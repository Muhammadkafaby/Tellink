<?php

// Test script untuk memverifikasi API password endpoint
// Jalankan dengan: php test_password_api.php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

$baseUrl = 'https://tellink-backend-2-b916417fa9aa.herokuapp.com';

echo "Testing Tellink API Endpoints...\n";
echo "================================\n\n";

// Test 1: Get all users
echo "1. Testing GET /api/mahasiswa:\n";
$ch = curl_init($baseUrl . '/api/mahasiswa');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "   HTTP Status: $httpCode\n";
if ($httpCode == 200) {
    $data = json_decode($response, true);
    if (isset($data['data']) && is_array($data['data'])) {
        echo "   Found " . count($data['data']) . " users\n";
        if (count($data['data']) > 0) {
            $firstUser = $data['data'][0];
            echo "   First user NIM: " . ($firstUser['nim'] ?? 'N/A') . "\n";
            echo "   Has password field: " . (isset($firstUser['password']) ? 'Yes' : 'No') . "\n";
            
            // Test 2: Get single user detail
            if (isset($firstUser['nim'])) {
                echo "\n2. Testing GET /api/mahasiswa/{nim}:\n";
                $ch2 = curl_init($baseUrl . '/api/mahasiswa/' . $firstUser['nim']);
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
                $response2 = curl_exec($ch2);
                $httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
                curl_close($ch2);
                
                echo "   HTTP Status: $httpCode2\n";
                if ($httpCode2 == 200) {
                    $userData = json_decode($response2, true);
                    echo "   User found: " . ($userData['nama'] ?? 'N/A') . "\n";
                    echo "   Has password field: " . (isset($userData['password']) ? 'Yes' : 'No') . "\n";
                    if (isset($userData['password'])) {
                        echo "   Password: " . $userData['password'] . "\n";
                    }
                } else {
                    echo "   Failed to get user detail\n";
                }
            }
        }
    }
} else {
    echo "   Failed to get users list\n";
}

// Test 3: Check alternative login endpoint
echo "\n3. Testing POST /api/login/validate:\n";
$testNim = '6706223068'; // NIM dari screenshot
$postData = json_encode(['nim' => $testNim]);

$ch3 = curl_init($baseUrl . '/api/login/validate');
curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch3, CURLOPT_POST, true);
curl_setopt($ch3, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch3, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($postData)
]);
$response3 = curl_exec($ch3);
$httpCode3 = curl_getinfo($ch3, CURLINFO_HTTP_CODE);
curl_close($ch3);

echo "   HTTP Status: $httpCode3\n";
if ($httpCode3 == 200) {
    $loginData = json_decode($response3, true);
    echo "   Response: " . json_encode($loginData) . "\n";
} else {
    echo "   Endpoint not available or returned error\n";
}

echo "\n================================\n";
echo "Test completed!\n";
