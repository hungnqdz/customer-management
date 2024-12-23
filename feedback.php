<?php
include 'include/Session.php';
include 'database/db.php';
include 'include/escape.php';

$action = $_POST['action'] ?? '';

if ($action == 'list') {
    $start = $_POST['start'];
    $length = $_POST['length'];
    $draw = $_POST['draw'];

    $totalQuery = "SELECT COUNT(*) AS total FROM feedbacks";
    $totalResult = $conn->query($totalQuery);
    $totalRow = $totalResult->fetch_assoc();
    $recordsTotal = $totalRow['total'];

    $query = "SELECT * FROM feedbacks ORDER BY id DESC LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $start, $length);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode([
        'draw' => $draw,
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsTotal,
        'data' => $data
    ]);
} elseif ($action == 'view') {
    $id = $_POST['id'];
    $queryMarkAsRead = "UPDATE feedbacks SET is_read = 1 WHERE id = ?";
    $stmt = $conn->prepare($queryMarkAsRead);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $query = "SELECT * FROM feedbacks WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $feedback = $result->fetch_assoc();
    echo json_encode($feedback);
} elseif ($action == 'markAsRead') {
    $id = $_POST['id'];

    $query = "UPDATE feedbacks SET is_read = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
