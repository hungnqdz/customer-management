<?php
session_start();
include 'include/Session.php';
include 'database/db.php';
include 'include/escape.php';
Session::CheckLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Vui lòng nhập đầy đủ thông tin.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $query = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $query->bind_param('s', $username);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if ($user['is_active'] == 0){
                $error = "Tài khoản này đã bị khóa";
            }
            else if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role_id'] = $user['role_id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['login'] = true;
                $_SESSION['gender'] = $user['gender'];
                $_SESSION['phone'] = $user['phone'];
                $_SESSION['address'] = $user['address'];
                $_SESSION['loyalty_points'] = $user['loyalty_points'];
                $_SESSION['is_active'] = $user['is_active'];
                $_SESSION['type_id'] = $user['type_id'];
                if ($user['role_id'] == 1) header("Location: index.php");
                else header("Location: landing.php");
                exit;
            } else {
                $error = "Tên đăng nhập hoặc mật khẩu không chính xác.";
            }
        } else {
            $error = "Tên đăng nhập hoặc mật khẩu không chính xác.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            display: flex;
            flex-direction: column; /* Stack items vertically */
            justify-content: flex-start; /* Align content at the top */
            height: 100vh; /* Full height of the viewport */
            margin: 0;
        }

        .login-container {
            max-width: 400px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%; /* Ensure it adapts to smaller screens */
            margin: auto;
        }

        footer {
            margin-top: auto; /* Push footer to the bottom */
            text-align: center;
            background-color: #f1f1f1;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Đăng nhập</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Tên đăng nhập</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Nhập tên đăng nhập" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Đăng nhập</button>
        </div>
        <div class="text-center mt-3">
            <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
        </div>
    </form>
</div>
<?php include 'include/footer.php';?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
