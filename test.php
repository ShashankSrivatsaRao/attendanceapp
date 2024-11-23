<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path."/attendanceapp/database/database.php";

header('Content-Type: application/json');
$response = array();

// Log incoming request
error_log("Login attempt - POST data: " . print_r($_POST, true));

if(isset($_POST['action']) && $_POST['action'] == "verifyUser") {
    $username = $_POST['user_name'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if(empty($username) || empty($password)) {
        $response['status'] = "Username and password are required";
        echo json_encode($response);
        exit;
    }
    
    try {
        $db = new Database();
        $query = "SELECT id, name FROM faculty_details 
                 WHERE user_name = :username AND password = :password";
        $stmt = $db->conn->prepare($query);
        $stmt->execute([
            ':username' => $username,
            ':password' => $password
        ]);
        
        // Log query results
        error_log("Query executed - Rows found: " . $stmt->rowCount());
        
        if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            session_start();
            $_SESSION['faculty_id'] = $row['id'];
            $_SESSION['faculty_name'] = $row['name'];
            $response['status'] = "ALL OK";
            error_log("Login successful for user: " . $username);
        } else {
            $response['status'] = "Invalid username or password";
            error_log("Login failed for user: " . $username);
        }
    } catch(PDOException $e) {
        $response['status'] = "Database error occurred";
        error_log("Database error: " . $e->getMessage());
    }
} else {
    $response['status'] = "Invalid request";
    error_log("Invalid request received");
}

echo json_encode($response);
