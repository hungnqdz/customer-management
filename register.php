<?php
session_start();
include 'include/Session.php';
include 'database/db.php';
include 'include/User.php';
include 'include/escape.php';
Session::CheckLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $role_id = 2; 

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($full_name) || empty($phone) || empty($address) || empty($gender)) {
        $error = "Vui lòng điền đầy đủ thông tin.";
    } else if ($password !== $confirm_password) {
        $error = "Mật khẩu và xác nhận mật khẩu không khớp!";
    } else {
        // Kiểm tra username hoặc email có tồn tại trong cơ sở dữ liệu không
        $check_query = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $check_query->bind_param('ss', $username, $email);
        $check_query->execute();
        $result = $check_query->get_result();

        if ($result->num_rows > 0) {
            $error = "Username hoặc email đã tồn tại!";
        } else {
            $user = new User($conn);
            $result = $user->addCustomer($username, $password, $email, $full_name, $phone, $address, $gender, 0, 1, $role_id); // Giả sử điểm thưởng = 0 và loại khách hàng = 1
            if ($result['status'] == 'success') {
                $success = "Đăng ký thành công! <a href='login.php'>Đăng nhập ngay</a>.";
            } else {
                $error = "Lỗi: " . $result['message'];
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
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            height: 100vh;
            margin: 0;
        }

        .register-container {
            max-width: 500px;
            background: #ffffff;
            border-radius: 10px;
            margin: 50px auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
        }

        footer {
            margin-top: auto;
            text-align: center;
            background-color: #f1f1f1;
        }

        .register-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

    </style>
</head>
<body>
<div class="register-container">
    <h2>Đăng ký</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="full_name" class="form-label">Họ và tên</label>
            <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Nhập họ và tên" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Tên đăng nhập</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Nhập tên đăng nhập" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Số điện thoại</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Nhập số điện thoại" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Địa chỉ</label>
            <input type="text" class="form-control" id="address" name="address" placeholder="Nhập địa chỉ" required>
        </div>
        <div class="mb-3">
            <label for="gender" class="form-label">Giới tính</label>
            <select class="form-control" id="gender" name="gender" required>
                <option value="Male">Nam</option>
                <option value="Female">Nữ</option>
                <option value="Other">Khác</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Đăng ký</button>
        </div>
        <div class="text-center mt-3">
            <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
        </div>
    </form>
</div>
<?php include 'include/footer.php';?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
