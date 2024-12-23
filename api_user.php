<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'include/Session.php';
include 'include/escape.php';
Session::init();
Session::CheckSession();
Session::CheckAuthority(1);

require 'include/User.php';
$userHandler = new User($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $action = $_POST['action'] ?? null;

    switch ($action) {
        case 'list':
            header('Content-Type: application/json');
            $start = intval($_POST['start'] ?? 0);
            $length = intval($_POST['length'] ?? 10);
            $searchValue = $_POST['searchValue'] ?? '';
            $draw = intval($_POST['draw'] ?? 0);
            $orderColumn = $_POST['orderColumn'] ?? 'id';
            $orderDirection = $_POST['orderDirection'] ?? 'desc';
            $response = $userHandler->getUserList($start, $length, $searchValue, $draw, $orderColumn, $orderDirection);
            echo json_encode($response);
            break;

        case 'view':
            $id = intval($_POST['id']);
            $response = $userHandler->getUserDetails($id);
            echo json_encode($response);
            break;

        case 'delete':
            header('Content-Type: application/json');
            $id = intval($_POST['id']);
            $response = $userHandler->deleteUser($id);
            echo $response;
            break;

        case 'disable':
            header('Content-Type: application/json');
            $id = intval($_POST['id']);
            $response = $userHandler->disableUser($id);
            echo $response;
            break;

        case 'enable':
            header('Content-Type: application/json');
            $id = intval($_POST['id']);
            $response = $userHandler->enableUser($id);
            echo $response;
            break;

        case 'add_customer':
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $confirmPassword = trim($_POST['confirm_password']);
            $email = trim($_POST['email']);
            $fullName = trim($_POST['full_name']);
            $typeId = intval($_POST['type_id']);
            $phone = trim($_POST['phone']);
            $address = trim($_POST['address']);
            if ($password !== $confirmPassword) {
                die("Mật khẩu và xác nhận mật khẩu không khớp.");
            }

            $result = $userHandler->addCustomer($username, $password, $email, $fullName, $phone, $address, $typeId);

            if ($result['status'] === 'success') {
                header('Location: index.php');
                exit;
            } else {
                die($result['message']);
            }

        default:
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Hành động không hợp lệ.']);
            break;
    }
}
