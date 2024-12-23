<?php
include 'include/Session.php';
require 'include/User.php';
include 'include/escape.php';
include 'include/count_unread_feedbacks.php';
Session::init();
Session::CheckSession();
Session::CheckAuthority(1);
$errorMsg = '';
$successMsg = '';
$userHandler = new User($conn);
$customer = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $email = trim($_POST['email']);
    $fullName = trim($_POST['full_name']);
    $typeId = intval($_POST['type_id']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $gender = trim($_POST['gender']);
    $loyalty_point = intval(trim($_POST['loyalty_points']));
    $id = intval($_POST['customer_id']);

    if ($password !== $confirmPassword && !empty($password) && !empty($confirmPassword)) {
        $errorMsg = "Mật khẩu và xác nhận mật khẩu không khớp.";
    }

    if (empty($errorMsg)) {
        $result = $userHandler->updateUser($id, $username, $password, $email, $fullName, $phone, $address, $gender, $loyalty_point, $typeId);

        if ($result['status'] === 'success') {
            $successMsg = 'Cập nhật thành công ';
            $customer['id'] = $id;
            $customer['username'] = $username;
            $customer['email'] = $email;
            $customer['full_name'] = $fullName;
            $customer['phone_number'] = $phone;
            $customer['address'] = $address;
            $customer['gender'] = $gender;
            $customer['loyalty_points'] = $loyalty_point;
            $customer['type_id'] = $typeId;
        } else {
            $errorMsg = $result['message'];
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $customerId = intval($_GET['id']);
        $customer = $userHandler->getUserDetails($customerId);

        if (!$customer) {
            header("Location: index.php");
            exit;
        }
    } else {
        header("Location: index.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa khách hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet"/>

</head>
<body class="sb-nav-fixed">
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand ps-3" href="index.php">Quản lý khách hàng</a>
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
</nav>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <a class="nav-link" href="index.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Quản lý khách hàng
                    </a>
                    <a class="nav-link" href="feedback_management.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-comments"></i></div>
                        Góp ý
                        <?php if (isset($unread_count) && $unread_count > 0): ?>
                            <span class="badge bg-danger ms-2"><?php echo $unread_count; ?></span>
                        <?php endif; ?>
                    </a>
                    <a class="nav-link" href="logout.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                        Đăng xuất
                    </a>
                </div>
            </div>
        </nav>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <div class="container mt-5">
                    <h2>Sửa thông tin khách hàng</h2>
                    <form method="POST" action="edit_customer.php">
                        <input type="hidden" name="customer_id" value="<?php echo $customer['id']; ?>">

                        <div class="mb-3">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <input type="text" name="username" id="username" class="form-control"
                                   value="<?php echo $customer['username']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" name="password" id="password" class="form-control"
                                   placeholder="Nhập mật khẩu nếu muốn thay đổi">
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                                   placeholder="Xác nhận mật khẩu">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                   value="<?php echo $customer['email']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="full_name" class="form-label">Họ và tên</label>
                            <input type="text" name="full_name" id="full_name" class="form-control"
                                   value="<?php echo $customer['full_name']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="number" name="phone" id="phone" class="form-control"
                                   value="<?php echo $customer['phone_number']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <input type="text" name="address" id="address" class="form-control"
                                   value="<?php echo $customer['address']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="gender" class="form-label">Giới tính</label>
                            <select name="gender" id="gender" class="form-select" required>
                                <option value="Male" <?php echo ($customer['gender'] == 'Male') ? 'selected' : ''; ?>>
                                    Nam
                                </option>
                                <option value="Female" <?php echo ($customer['gender'] == 'Female') ? 'selected' : ''; ?>>
                                    Nữ
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="loyalty_points" class="form-label">Điểm thưởng</label>
                            <input type="number" name="loyalty_points" id="loyalty_points" class="form-control"
                                   value="<?php echo $customer['loyalty_points']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="type_id" class="form-label">Loại khách hàng</label>
                            <select name="type_id" id="type_id" class="form-select" required>
                                <option value="1" <?php echo ($customer['type_id'] == 1) ? 'selected' : ''; ?>>VIP
                                </option>
                                <option value="2" <?php echo ($customer['type_id'] == 2) ? 'selected' : ''; ?>>Thường
                                </option>
                            </select>
                        </div>

                        <?php if ($errorMsg): ?>
                            <div class="alert alert-danger mt-3" role="alert">
                                <?php echo $errorMsg; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($successMsg): ?>
                            <div class="alert alert-success mt-3" role="alert">
                                <?php echo $successMsg; ?>
                            </div>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-primary">Cập nhật khách hàng</button>
                        <a href="index.php" class="btn btn-secondary">Hủy</a>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

</body>
</
