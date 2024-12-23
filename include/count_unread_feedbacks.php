<?php
// Include database connection
include 'database/db.php';

// Prepare the SQL query to count unread feedbacks
$query = "SELECT COUNT(*) AS unread_count FROM feedbacks WHERE is_read = 0";
$stmt = $conn->prepare($query);

$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();
$unread_count = $row['unread_count'];
?>
