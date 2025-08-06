<?php

// Test login API to understand password structure
// Run: php test_login_api.php

$baseUrl = 'https://tellink-backend-2-b916417fa9aa.herokuapp.com';

echo "Testing Tellink Login API\n";
echo "==========================\n\n";

// Test with different password combinations
$testCases = [
    ['nim' => '6706223068', 'password' => 'password123'],
    ['nim' => '6706223068', 'password' => 'Password123'],
    ['nim' => '6706223068', 'password' => '123456'],
    ['nim' => '6706223068', 'password' => '12345678'],
    ['nim' => '6706223068', 'password' => 'tellink'],
    ['nim' => '6706223068', 'password' => 'tellink123'],
    ['nim' => '6706223068', 'password' => 'mahasiswa'],
    ['nim' => '6706223068', 'password' => 'mahasiswa123'],
    ['nim' => '6706223068', 'password' => 'admin'],
    ['nim' => '6706223068', 'password' => 'admin123'],
    ['nim' => '6706223068', 'password' => '6706223068'], // NIM as password
];

echo "Testing login with various passwords for NIM: 6706223068\n";
echo "---------------------------------------------------------\n";

foreach ($testCases as $test) {
    $ch = curl_init($baseUrl . '/api/login');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($test));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "Password: '" . $test['password'] . "' -> ";
    
    if ($httpCode == 200) {
        $data = json_decode($response, true);
        if (isset($data['success']) && $data['success']) {
            echo "✅ SUCCESS! Login successful\n";
            echo "   Response: " . json_encode($data) . "\n";
            break;
        } else {
            echo "❌ Failed (Invalid credentials)\n";
        }
    } else {
        echo "❌ HTTP Error: $httpCode\n";
    }
}

echo "\n==========================\n";
echo "Testing register endpoint to understand password creation:\n";
echo "---------------------------------------------------------\n";

// Check if we can see how passwords are created
$registerData = [
    'nim' => '9999999999',
    'nama' => 'Test User',
    'jurusan' => 'D3 Informatika',
    'password' => 'testpassword123'
];

$ch = curl_init($baseUrl . '/api/register');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($registerData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Register test response (HTTP $httpCode):\n";
$responseData = json_decode($response, true);
if ($responseData) {
    echo json_encode($responseData, JSON_PRETTY_PRINT) . "\n";
} else {
    echo "Raw response: " . substr($response, 0, 500) . "\n";
}

echo "\n==========================\n";
echo "Note: BCrypt passwords cannot be decrypted.\n";
echo "The only way to know the password is:\n";
echo "1. Test with known passwords (brute force)\n";
echo "2. Check documentation or default passwords\n";
echo "3. Reset the password to a new one\n";
echo "4. Contact the system administrator\n";
