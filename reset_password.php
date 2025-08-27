<?php
// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$database = 'keydera';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $user, $pass);
    
    // Generate new password hash
    $new_password = 'Keydera.com';
    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update the user
    $stmt = $pdo->prepare("UPDATE auth_users SET au_password = ?, au_username = ? WHERE au_id = 1");
    $result = $stmt->execute([$password_hash, 'Craadly']);
    
    if ($result) {
        echo "✅ Password updated successfully!\n";
        echo "New credentials:\n";
        echo "Username: Craadly\n";
        echo "Password: Keydera.com\n";
    } else {
        echo "❌ Failed to update password\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
