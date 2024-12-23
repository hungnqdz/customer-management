<?php
include 'include/Session.php';
require 'include/User.php';
include 'include/escape.php';
Session::init();
Session::CheckSession();
Session::CheckAuthority(1);

$customerId = $_GET['id'];
$userHandler = new User($conn);
$customer = $userHandler->getUserDetails($customerId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Khách Hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet"/>
</head>
<body class="sb-nav-fixed">
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand ps-3" href="index.php">Quản lý khách hàng</a>
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
               aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="profile.html">Profile</a></li>
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
        </li>
    </ul>
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
                    <h2>Chi Tiết Khách Hàng</h2>
                    <form>
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <input type="text" name="username" id="username" class="form-control" value="<?= $customer['username'] ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="<?= $customer['email'] ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Họ và tên</label>
                            <input type="text" name="full_name" id="full_name" class="form-control" value="<?= $customer['full_name'] ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="number" name="phone" id="phone" class="form-control" value="<?= $customer['phone_number'] ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <input type="text" name="address" id="address" class="form-control" value="<?= $customer['address'] ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Giới tính</label>
                            <input type="text" name="gender" id="gender" class="form-control"
                                   value="<?= $customer['gender'] === 'Male' ? 'Nam' : 'Nữ' ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="loyalty_points" class="form-label">Điểm thưởng</label>
                            <input type="number" name="loyalty_points" id="loyalty_points" class="form-control"
                                   value="<?= $customer['loyalty_points'] ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="type_id" class="form-label">Loại khách hàng</label>
                            <select name="type_id" id="type_id" class="form-select" disabled>
                                <option value="1" <?= $customer['type_id'] == 1 ? 'selected' : '' ?>>VIP</option>
                                <option value="2" <?= $customer['type_id'] == 2 ? 'selected' : '' ?>>Thường</option>
                            </select>
                        </div>
                        <a href="index.php" class="btn btn-secondary">Quay lại</a>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

</body>
</html>
