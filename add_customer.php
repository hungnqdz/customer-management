<?php
include 'database/db.php';
include 'include/Session.php';
require 'include/User.php';
include 'include/escape.php';
include 'include/count_unread_feedbacks.php';
Session::init();
Session::CheckSession();
Session::CheckAuthority(1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $type_id = $_POST['type_id'];
    $role_id = 2;

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($full_name) || empty($phone) || empty($address) || empty($gender) || empty($type_id)) {
        $error = "Vui lòng điền đầy đủ thông tin.";
    } else if ($password !== $confirm_password) {
        $error = "Mật khẩu và xác nhận mật khẩu không khớp!";
    } else {
        $check_query = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $check_query->bind_param('ss', $username, $email);
        $check_query->execute();
        $result = $check_query->get_result();

        if ($result->num_rows > 0) {
            $error = "Username hoặc email đã tồn tại!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Câu lệnh INSERT thêm các trường mới
            $insert_query = $conn->prepare("INSERT INTO users (username, password, email, full_name, phone_number, address, gender, type_id, role_id) 
                                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_query->bind_param('ssssssiii', $username, $hashed_password, $email, $full_name, $phone, $address, $gender, $type_id, $role_id);

            if ($insert_query->execute()) {
                $success = "Thêm người dùng thành công";
            } else {
                $error = "Lỗi: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
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
                    <h2>Thêm khách hàng mới</h2>
                    <form method="POST" action="add_customer.php">
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <input type="text" name="username" id="username" class="form-control" required
                                   placeholder="Nhập tên đăng nhập">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" name="password" id="password" class="form-control" required
                                   placeholder="Nhập mật khẩu">
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                                   required placeholder="Xác nhận mật khẩu">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required
                                   placeholder="Nhập địa chỉ email">
                        </div>
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Họ và tên</label>
                            <input type="text" name="full_name" id="full_name" class="form-control" required
                                   placeholder="Nhập họ và tên">
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="number" name="phone" id="phone" class="form-control" required
                                   placeholder="Nhập số điện thoại">
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <input type="text" name="address" id="address" class="form-control" required
                                   placeholder="Nhập địa chỉ">
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Giới tính</label>
                            <select name="gender" id="gender" class="form-select" required>
                                <option value="Male">Nam</option>
                                <option value="Female">Nữ</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="loyalty_points" class="form-label">Điểm thưởng</label>
                            <input type="number" name="loyalty_points" id="loyalty_points" class="form-control" required
                                   placeholder="Nhập điểm thưởng ban đầu">
                        </div>
                        <div class="mb-3">
                            <label for="type_id" class="form-label">Loại khách hàng</label>
                            <select name="type_id" id="type_id" class="form-select" required>
                                <option value="1">VIP</option>
                                <option value="2">Thường</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <input type="hidden" name="action" id="action" class="form-control" required
                                   value="add_customer">
                        </div>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger mt-3" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success mt-3" role="alert">
                                <?php echo $success; ?>
                            </div>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-primary">Thêm khách hàng</button>
                        <a href="index.php" class="btn btn-secondary">Hủy</a>
                    </form>
                </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="assets/scripts.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.querySelector("form");
            const password = document.getElementById("password");
            const confirmPassword = document.getElementById("confirm_password");

            form.addEventListener("submit", function (event) {
                if (password.value !== confirmPassword.value) {
                    event.preventDefault();
                    alert("Mật khẩu và xác nhận mật khẩu không khớp. Vui lòng kiểm tra lại.");
                }
            });
        });
    </script>
</body>
</html>
