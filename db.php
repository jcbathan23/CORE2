<?php
// Simple DB connector for CORE II
// Adjust credentials as needed for your WAMP/MySQL setup

$dbHost = 'localhost';
$dbUser = 'root';
$dbPassword = '';
$dbName = 'core2';

// Connect to MySQL server (without specifying DB first, so we can auto-create it)
$mysqli = new mysqli($dbHost, $dbUser, $dbPassword);
if ($mysqli->connect_errno) {
	http_response_code(500);
	die(json_encode(['error' => 'Database connection failed: ' . $mysqli->connect_error]));
}

// Ensure database exists
$mysqli->query("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
if ($mysqli->errno) {
	http_response_code(500);
	die(json_encode(['error' => 'Failed creating database: ' . $mysqli->error]));
}

// Select database
$mysqli->select_db($dbName);
if ($mysqli->errno) {
	http_response_code(500);
	die(json_encode(['error' => 'Failed selecting database: ' . $mysqli->error]));
}

// Auto-create tariffs table if it does not exist
$createTariffsSql = <<<SQL
CREATE TABLE IF NOT EXISTS tariffs (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL,
	category VARCHAR(100) NOT NULL,
	base_rate DECIMAL(10,2) NOT NULL DEFAULT 0.00,
	per_km_rate DECIMAL(10,2) NOT NULL DEFAULT 0.00,
	per_hour_rate DECIMAL(10,2) NOT NULL DEFAULT 0.00,
	priority_multiplier DECIMAL(4,2) NOT NULL DEFAULT 1.00,
	status VARCHAR(50) NOT NULL DEFAULT 'Draft',
	effective_date DATE NOT NULL,
	expiry_date DATE NOT NULL,
	notes TEXT NULL,
	created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

$mysqli->query($createTariffsSql);
if ($mysqli->errno) {
	// Do not hard fail the request, but expose the error for diagnostics
	// Caller endpoints may still handle this gracefully
}

// Auto-create providers table
$createProvidersSql = <<<SQL
CREATE TABLE IF NOT EXISTS providers (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL,
	type VARCHAR(100) NOT NULL,
	contact_person VARCHAR(255) NOT NULL,
	contact_email VARCHAR(255) NOT NULL,
	contact_phone VARCHAR(50) NOT NULL,
	service_area VARCHAR(255) NOT NULL,
	monthly_rate DECIMAL(10,2) NOT NULL DEFAULT 0.00,
	status VARCHAR(50) NOT NULL,
	contract_start DATE NOT NULL,
	contract_end DATE NOT NULL,
	notes TEXT NULL,
	created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
$mysqli->query($createProvidersSql);

// Auto-create sops table
$createSopsSql = <<<SQL
CREATE TABLE IF NOT EXISTS sops (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(255) NOT NULL,
	category VARCHAR(100) NOT NULL,
	department VARCHAR(100) NOT NULL,
	version VARCHAR(20) NOT NULL,
	status VARCHAR(50) NOT NULL,
	review_date DATE NOT NULL,
	purpose TEXT NOT NULL,
	scope TEXT NOT NULL,
	responsibilities TEXT NOT NULL,
	procedures TEXT NOT NULL,
	equipment TEXT NULL,
	safety_notes TEXT NULL,
	notes TEXT NULL,
	created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
$mysqli->query($createSopsSql);

// Auto-create routes table
$createRoutesSql = <<<SQL
CREATE TABLE IF NOT EXISTS routes (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL,
	type VARCHAR(100) NOT NULL,
	start_point VARCHAR(255) NOT NULL,
	end_point VARCHAR(255) NOT NULL,
	distance DECIMAL(10,1) NOT NULL DEFAULT 0.0,
	frequency VARCHAR(100) NOT NULL,
	status VARCHAR(50) NOT NULL,
	estimated_time INT NOT NULL,
	notes TEXT NULL,
	created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
$mysqli->query($createRoutesSql);

// Auto-create service_points table
$createServicePointsSql = <<<SQL
CREATE TABLE IF NOT EXISTS service_points (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL,
	type VARCHAR(100) NOT NULL,
	location VARCHAR(255) NOT NULL,
	services VARCHAR(255) NOT NULL,
	status VARCHAR(50) NOT NULL,
	notes TEXT NULL,
	created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
$mysqli->query($createServicePointsSql);

// Auto-create schedules table
$createSchedulesSql = <<<SQL
CREATE TABLE IF NOT EXISTS schedules (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL,
	route VARCHAR(255) NOT NULL,
	vehicle_type VARCHAR(100) NOT NULL,
	departure TIME NOT NULL,
	arrival TIME NOT NULL,
	frequency VARCHAR(100) NOT NULL,
	status VARCHAR(50) NOT NULL,
	start_date DATE NOT NULL,
	end_date DATE NOT NULL,
	capacity INT NOT NULL,
	notes TEXT NULL,
	created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
$mysqli->query($createSchedulesSql);

// Auto-create users table for authentication
$createUsersSql = <<<SQL
CREATE TABLE IF NOT EXISTS users (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(50) NOT NULL UNIQUE,
	password_hash VARCHAR(255) NOT NULL,
	email VARCHAR(255) NOT NULL,
	role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
	is_active BOOLEAN NOT NULL DEFAULT TRUE,
	last_login TIMESTAMP NULL,
	created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
$mysqli->query($createUsersSql);

// Insert default admin user if not exists
$checkAdminSql = "SELECT id FROM users WHERE username = 'admin' LIMIT 1";
$result = $mysqli->query($checkAdminSql);

if ($result && $result->num_rows == 0) {
    // Create default admin user (password: admin123)
    $defaultPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $insertAdminSql = "INSERT INTO users (username, password_hash, email, role) VALUES ('admin', '$defaultPassword', 'admin@slate.com', 'admin')";
    $mysqli->query($insertAdminSql);
}

// Helper to send JSON
function send_json($data, int $status = 200): void {
	header('Content-Type: application/json');
	http_response_code($status);
	echo json_encode($data);
}

// Helper to read JSON body
function read_json_body(): array {
	$raw = file_get_contents('php://input');
	$decoded = json_decode($raw, true);
	return is_array($decoded) ? $decoded : [];
}

?>


