<?php

// Test BCrypt password verification
// Run: php test_bcrypt_password.php

echo "Testing BCrypt Password Verification\n";
echo "=====================================\n\n";

// Sample BCrypt hash from the API (from user 6706223068)
$sampleHash = '$2b$10$BlTAaMeJU8a0H3tGjBj3COAQJ2Mq12b2JclsrTXLt088x6AOwbQd.';

echo "Sample BCrypt Hash:\n";
echo $sampleHash . "\n\n";

// Common passwords to test
$testPasswords = [
    'password123',
    'Password123',
    'password',
    '12345678',
    '123456',
    '6706223068', // NIM as password
    'tellink123',
    'tellink2024',
    'mahasiswa123',
    'admin123',
    'tellink',
    'Tellink123',
    'mahasiswa',
    'Mahasiswa123'
];

echo "Testing common passwords against the hash:\n";
echo "-----------------------------------------\n";

$found = false;
foreach ($testPasswords as $password) {
    // Note: BCrypt hashes starting with $2b$ need to be converted to $2y$ for PHP
    $phpCompatibleHash = str_replace('$2b$', '$2y$', $sampleHash);
    
    if (password_verify($password, $phpCompatibleHash)) {
        echo "✅ MATCH FOUND: '$password'\n";
        $found = true;
        break;
    } else {
        echo "❌ Not match: '$password'\n";
    }
}

if (!$found) {
    echo "\n⚠️ No match found. The actual password might be different.\n";
}

echo "\n=====================================\n";
echo "Additional BCrypt Information:\n";
echo "- Algorithm: BCrypt (2b variant)\n";
echo "- Cost/Rounds: 10\n";
echo "- This is a one-way hash - cannot be decrypted\n";
echo "- Only way to verify is by testing known passwords\n";

// Test creating a new BCrypt hash
echo "\n=====================================\n";
echo "Creating sample BCrypt hashes:\n";
echo "-----------------------------------------\n";

$samplePasswords = ['password123', 'tellink123', 'mahasiswa123'];
foreach ($samplePasswords as $pwd) {
    $hash = password_hash($pwd, PASSWORD_BCRYPT, ['cost' => 10]);
    echo "Password: '$pwd'\n";
    echo "Hash: $hash\n\n";
}
