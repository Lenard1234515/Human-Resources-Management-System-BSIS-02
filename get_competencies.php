<?php
header('Content-Type: application/json');
require_once 'config.php'; // provides $conn (PDO)

try {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($id > 0) {
        // Fetch single competency without cycle_id
        $sql = "
            SELECT competency_id, name, description, job_role_id
            FROM competencies
            WHERE competency_id = :id
            LIMIT 1
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            echo json_encode($row);
        } else {
            echo json_encode(['error' => true, 'message' => 'Competency not found']);
        }

    } else {
        // Fetch all competencies without cycle_id
        $sql = "SELECT competency_id, name, description, job_role_id FROM competencies ORDER BY name";
        $stmt = $conn->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($rows);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => true, 'message' => $e->getMessage()]);
}
?>
