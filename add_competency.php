<?php
include 'db_connect.php';
header('Content-Type: application/json');

$name = $_POST['competency_name'] ?? '';
$description = $_POST['description'] ?? '';
$job_role_id = $_POST['job_role_id'] ?? 0;

try {
    $stmt = $conn->prepare("INSERT INTO competencies (name, description, job_role_id) VALUES (?, ?, ?)");
    
    // Bind parameters: s = string, s = string, i = integer
    $stmt->bind_param("ssi", $name, $description, $job_role_id);
    
    $stmt->execute();
    
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
