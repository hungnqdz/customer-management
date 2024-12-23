<?php
session_start();
include 'include/Session.php';
include 'database/db.php';
include 'include/escape.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $_POST['action'] ?? '';
    $name = $input['name'] ?? '';
    $email = $input['email'] ?? '';
    $phone = $input['phone'] ?? '';
    $message = $input['message'] ?? '';

    if (empty($name) || empty($email) || empty($phone) || empty($message)) {
        $response['message'] = 'Vui lòng điền đầy đủ thông tin.';
    } else {
        $stmt = $conn->prepare("INSERT INTO feedbacks (name, email, phone, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $message);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Cảm ơn bạn đã gửi góp ý!';
        } else {
            $response['message'] = 'Đã xảy ra lỗi, vui lòng thử lại.';
        }

        $stmt->close();
    }

    $conn->close();
} else {
    $response['message'] = 'Phương thức không được hỗ trợ.';
}

echo json_encode($response);
exit;
?>
