<?php
header('Content-Type: application/json');

// Check if this is a test connection request
if (!isset($_POST['test_connection']) || $_POST['test_connection'] !== 'true') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

// Get database credentials from POST
$host = isset($_POST['host']) ? strip_tags(trim($_POST['host'])) : '';
$port = isset($_POST['port']) ? strip_tags(trim($_POST['port'])) : '3306';
$user = isset($_POST['user']) ? strip_tags(trim($_POST['user'])) : '';
$pass = isset($_POST['pass']) ? strip_tags(trim($_POST['pass'])) : '';
$dbname = isset($_POST['name']) ? strip_tags(trim($_POST['name'])) : '';

// Validate required fields
if (empty($host) || empty($user) || empty($dbname)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing required database configuration fields.'
    ]);
    exit;
}

// Test database connection
try {
    // Attempt to connect to MySQL server (without selecting database)
    $dsn = "mysql:host=$host;port=$port;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_TIMEOUT => 5 // 5 second timeout
    ];

    $pdo = new PDO($dsn, $user, $pass, $options);

    // Check MySQL version
    $stmt = $pdo->query('SELECT VERSION() as version');
    $result = $stmt->fetch();
    $mysql_version = $result['version'];

    // Extract version number
    preg_match('/^(\d+\.\d+\.\d+)/', $mysql_version, $matches);
    $version_number = isset($matches[1]) ? $matches[1] : '0.0.0';

    // Check if MySQL version is 5.6 or higher
    if (version_compare($version_number, '5.6.0', '<')) {
        echo json_encode([
            'status' => 'error',
            'message' => "MySQL version $version_number detected. Minimum requirement is MySQL 5.6 or higher."
        ]);
        exit;
    }

    // Check if database exists
    $stmt = $pdo->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?");
    $stmt->execute([$dbname]);
    $database_exists = $stmt->fetch();

    if ($database_exists) {
        // Database already exists - check if it's empty
        $stmt = $pdo->prepare("SELECT COUNT(*) as table_count FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ?");
        $stmt->execute([$dbname]);
        $result = $stmt->fetch();
        $table_count = $result['table_count'];

        if ($table_count > 0) {
            echo json_encode([
                'status' => 'warning',
                'message' => "Database '$dbname' already exists and contains $table_count table(s). Creating database will overwrite existing data."
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'message' => "Connection successful! Database '$dbname' exists but is empty. Ready for installation."
            ]);
        }
    } else {
        // Database doesn't exist - will be created
        echo json_encode([
            'status' => 'success',
            'message' => "Connection successful! Database '$dbname' will be created during installation."
        ]);
    }

} catch (PDOException $e) {
    // Determine the type of error
    $error_code = $e->getCode();
    $error_message = $e->getMessage();

    // Provide user-friendly error messages
    if ($error_code == 1045) {
        // Access denied
        echo json_encode([
            'status' => 'error',
            'message' => 'Access denied: Invalid username or password.'
        ]);
    } elseif ($error_code == 2002) {
        // Connection refused
        echo json_encode([
            'status' => 'error',
            'message' => 'Connection failed: Could not connect to database server. Please check host and port.'
        ]);
    } elseif ($error_code == 1049) {
        // Unknown database (shouldn't happen in our test, but handle it)
        echo json_encode([
            'status' => 'error',
            'message' => "Database '$dbname' does not exist and user lacks CREATE privilege."
        ]);
    } elseif (strpos($error_message, 'SQLSTATE[HY000] [2002]') !== false) {
        // Connection timeout or host not found
        echo json_encode([
            'status' => 'error',
            'message' => 'Connection failed: Host not found or connection timeout. Please verify the host address.'
        ]);
    } else {
        // Generic error with details
        echo json_encode([
            'status' => 'error',
            'message' => 'Database connection failed: ' . $error_message
        ]);
    }
} catch (Exception $e) {
    // Other unexpected errors
    echo json_encode([
        'status' => 'error',
        'message' => 'Unexpected error: ' . $e->getMessage()
    ]);
}
?>
