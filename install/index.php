<?php
    session_start(); // Start session at the very beginning
    
    // Check if already installed (only check for final installation marker)
    if (file_exists('install.keydera') && !isset($_GET['reinstall'])) {
        // Allow access to step 5 if admin account was just created but installation not finalized
        $showStep5 = isset($_SESSION['admin_created']) && $_SESSION['admin_created'] === true;
        
        if (!$showStep5) {
            echo "<div style='padding: 50px; text-align: center; font-family: Arial, sans-serif;'>";
            echo "<h2>Keydera is already installed!</h2>";
            echo "<p>The installation has already been completed.</p>";
            echo "<p><a href='../index.php'>Go to Keydera</a> | <a href='index.php?reinstall=1'>Reinstall</a></p>";
            echo "</div>";
            exit;
        }
    }
    
    // Clean up files when reinstalling
    if (isset($_GET['reinstall'])) {
        // Remove installation marker files
        if (file_exists('install.keydera')) {
            unlink('install.keydera');
        }
        if (file_exists('database.keydera')) {
            unlink('database.keydera');
        }
        
        // Redirect to clean installation wizard without reinstall parameter
        header('Location: index.php');
        exit;
    }
    
    // Handle AJAX requests for installation steps
    if (isset($_GET['step']) || isset($_POST['step'])) {
        $step = isset($_GET['step']) ? $_GET['step'] : $_POST['step'];
        
        switch ($step) {
            case '0':
                // Requirements check
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Requirements check passed',
                    'next_step' => 1
                ]);
                exit;
                break;
                
            case '1':
                // Database configuration
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Process database form submission
                    $host = isset($_POST['host']) ? $_POST['host'] : 'localhost';
                    $port = isset($_POST['port']) ? $_POST['port'] : '3306';
                    $user = isset($_POST['user']) ? $_POST['user'] : 'root';
                    $pass = isset($_POST['pass']) ? $_POST['pass'] : '';
                    $name = isset($_POST['name']) ? $_POST['name'] : '';
                    
                    // Save database config
                    $config_content = "<?php\n";
                    $config_content .= "\$db_host = '" . addslashes($host) . "';\n";
                    $config_content .= "\$db_username = '" . addslashes($user) . "';\n";
                    $config_content .= "\$db_password = '" . addslashes($pass) . "';\n";
                    $config_content .= "\$db_database = '" . addslashes($name) . "';\n";
                    
                    file_put_contents('../application/config/db.php', $config_content);
                    
                    // Import database schema
                    try {
                        // First connect without selecting database to create it if needed
                        $dsn_server = "mysql:host=$host;port=$port;charset=utf8";
                        $pdo_server = new PDO($dsn_server, $user, $pass);
                        $pdo_server->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        
                        // Create database if it doesn't exist
                        $pdo_server->exec("CREATE DATABASE IF NOT EXISTS `$name` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                        
                        // Now connect to the specific database
                        $dsn = "mysql:host=$host;port=$port;dbname=$name;charset=utf8";
                        $pdo = new PDO($dsn, $user, $pass);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        
                        // Read SQL file
                        $sql = file_get_contents('database.sql');
                        if ($sql !== false) {
                            // Check if database has existing tables
                            $stmt = $pdo->query("SHOW TABLES");
                            $existingTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                            
                            if (!empty($existingTables)) {
                                // Database has existing tables, drop them first
                                $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
                                foreach ($existingTables as $table) {
                                    $pdo->exec("DROP TABLE IF EXISTS `$table`");
                                }
                                $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
                            }
                            
                            // Execute the SQL import
                            $pdo->exec($sql);
                        }
                        
                        // Create temporary marker to track database completion
                        file_put_contents('database.keydera', 'Database configured on ' . date('Y-m-d H:i:s'));
                        
                        // Redirect to admin account creation step
                        header('Location: index.php#step-4');
                        exit;
                    } catch (PDOException $e) {
                        // Handle database error
                        header('Location: index.php?error=' . urlencode('Database error: ' . $e->getMessage()));
                        exit;
                    }
                }
                break;
                
            case '2':
                // Admin account creation
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $adminUsername = $_POST['admin_username'] ?? '';
                    $adminEmail = $_POST['admin_email'] ?? '';
                    $adminPassword = $_POST['admin_password'] ?? '';
                    $adminPasswordConfirm = $_POST['admin_password_confirm'] ?? '';
                    
                    // Validate inputs
                    if (empty($adminUsername) || empty($adminEmail) || empty($adminPassword)) {
                        header('Location: index.php?error=' . urlencode('All fields are required for admin account creation.'));
                        exit;
                    }
                    
                    if ($adminPassword !== $adminPasswordConfirm) {
                        header('Location: index.php?error=' . urlencode('Passwords do not match.'));
                        exit;
                    }
                    
                    if (strlen($adminPassword) < 8) {
                        header('Location: index.php?error=' . urlencode('Password must be at least 8 characters long.'));
                        exit;
                    }
                    
                    try {
                        // Check if database was configured first
                        if (!file_exists('database.keydera')) {
                            header('Location: index.php?error=' . urlencode('Please complete database setup first.'));
                            exit;
                        }
                        
                        // Get database connection info from config
                        if (file_exists('../application/config/db.php')) {
                            require_once '../application/config/db.php';
                            
                            $dsn = "mysql:host=$db_host;dbname=$db_database;charset=utf8";
                            $pdo = new PDO($dsn, $db_username, $db_password);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            
                            // Hash the password
                            $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
                            
                            // Update the default admin user in the auth_users table
                            $stmt = $pdo->prepare("UPDATE auth_users SET au_username = ?, au_email = ?, au_password = ?, au_date_created = CURDATE() WHERE au_id = 1");
                            $stmt->execute([$adminUsername, $adminEmail, $hashedPassword]);
                            
                            // DO NOT create final installation marker yet - let step 5 be shown first
                            // file_put_contents('install.keydera', 'Installation completed on ' . date('Y-m-d H:i:s'));
                            
                            // Set a session flag to indicate admin account was created successfully
                            $_SESSION['admin_created'] = true;
                            $_SESSION['admin_username'] = $adminUsername;
                            $_SESSION['admin_email'] = $adminEmail;
                            
                            // Redirect to completion step
                            header('Location: index.php#step-5');
                            exit;
                        } else {
                            header('Location: index.php?error=' . urlencode('Database configuration not found. Please complete database setup first.'));
                            exit;
                        }
                    } catch (PDOException $e) {
                        // Handle database error
                        header('Location: index.php?error=' . urlencode('Admin account creation failed: ' . $e->getMessage()));
                        exit;
                    }
                }
                break;
                
            case '5':
                // Final completion step - create installation marker and clear session
                if (isset($_SESSION['admin_created']) && $_SESSION['admin_created'] === true) {
                    // Create final installation marker
                    file_put_contents('install.keydera', 'Installation completed on ' . date('Y-m-d H:i:s'));
                    
                    // Clean up temporary files
                    if (file_exists('database.keydera')) {
                        unlink('database.keydera');
                    }
                    
                    // Clear session
                    unset($_SESSION['admin_created']);
                    unset($_SESSION['admin_username']);
                    unset($_SESSION['admin_email']);
                    
                    // Return success response
                    header('Content-Type: application/json');
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Installation completed successfully!',
                        'redirect' => '../index.php'
                    ]);
                    exit;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Installation not properly completed. Please start over.'
                    ]);
                    exit;
                }
                break;
                
            default:
                // Invalid step, redirect to beginning
                header('Location: index.php');
                exit;
                break;
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <title>Install - Keydera</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/css/material.css" />
  <link rel="stylesheet" href="../assets/css/custom.css" />
  <link rel="stylesheet" href="../assets/vendor/FontAwesome/css/all.min.css"/>
  <link rel="icon" type="image/png" href="../assets/images/favicon-32x32.png" sizes="32x32"/>
  <link rel="icon" type="image/png" href="../assets/images/favicon-16x16.png" sizes="16x16"/>
</head>
<body>
  <?php
    require "install.php";
  ?>
</body>
</html>
