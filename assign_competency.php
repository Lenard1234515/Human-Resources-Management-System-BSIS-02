<?php
// assign_competency.php
header('Content-Type: application/json');
require_once 'config.php';

try {
    // Expect employee_id (NOT personal_info_id)
    $employee_id     = filter_input(INPUT_POST, 'employee_id', FILTER_VALIDATE_INT);
    $competency_id   = filter_input(INPUT_POST, 'competency_id', FILTER_VALIDATE_INT);
    $cycle_id        = filter_input(INPUT_POST, 'cycle_id', FILTER_VALIDATE_INT);
    $rating          = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
    $comments        = trim($_POST['notes'] ?? $_POST['comments'] ?? '');
    $assessment_date = $_POST['assessment_date'] ?? date('Y-m-d');

    if (!$employee_id || !$competency_id || !$cycle_id || !$rating) {
        echo json_encode([
            'success' => false,
            'message' => 'Employee ID, Competency, Cycle, and Rating are required.'
        ]);
        exit;
    }

    // Check if this employee + competency + cycle already exists
    $checkSql = "SELECT 1 
                 FROM employee_competencies 
                 WHERE employee_id = :employee_id 
                   AND competency_id = :competency_id 
                   AND cycle_id = :cycle_id
                 LIMIT 1";
    $stmt = $conn->prepare($checkSql);
    $stmt->execute([
        ':employee_id'   => $employee_id,
        ':competency_id' => $competency_id,
        ':cycle_id'      => $cycle_id
    ]);

    if ($stmt->rowCount() > 0) {
        // Update existing record
        $updateSql = "UPDATE employee_competencies
                      SET rating = :rating,
                          comments = :comments,
                          assessment_date = :assessment_date,
                          updated_at = CURRENT_TIMESTAMP
                      WHERE employee_id = :employee_id
                        AND competency_id = :competency_id
                        AND cycle_id = :cycle_id";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->execute([
            ':rating'          => $rating,
            ':comments'        => $comments,
            ':assessment_date' => $assessment_date,
            ':employee_id'     => $employee_id,
            ':competency_id'   => $competency_id,
            ':cycle_id'        => $cycle_id
        ]);
    } else {
        // Insert new record
        $insertSql = "INSERT INTO employee_competencies
                      (employee_id, competency_id, cycle_id, rating, assessment_date, comments)
                      VALUES (:employee_id, :competency_id, :cycle_id, :rating, :assessment_date, :comments)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->execute([
            ':employee_id'     => $employee_id,
            ':competency_id'   => $competency_id,
            ':cycle_id'        => $cycle_id,
            ':rating'          => $rating,
            ':assessment_date' => $assessment_date,
            ':comments'        => $comments
        ]);
    }

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
