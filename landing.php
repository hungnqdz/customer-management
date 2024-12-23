<?php session_start();

include 'include/Session.php';
include 'database/db.php';
include 'include/escape.php';

?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content="Trang giới thiệu siêu thị Fresh Market"/>
    <meta name="author" content="Fresh Market"/>
    <title>Siêu Thị Fresh Market</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico"/>
    <!-- Bootstrap Icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet"/>
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="assets/styles_landing.css" rel="stylesheet"/>
</head>
<body id="page-top">
<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="#page-top">Siêu Thị Fresh Market</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false"
                aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto my-2 my-lg-0">
                <li class="nav-item"><a class="nav-link" href="#about">Về Chúng Tôi</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Dịch Vụ</a></li>
                <li class="nav-item"><a class="nav-link" href="#portfolio">Sản Phẩm</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Góp ý</a></li>

                <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
                    <li class="nav-item">
                        <span class="nav-link">
                            Xin chào, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!
                            (Điểm: <?php echo htmlspecialchars($_SESSION['loyalty_points']); ?>)
                        </span>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Đăng xuất</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="register.php">Đăng ký</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Đăng nhập</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>


<header class="masthead">
    <div class="container px-4 px-lg-5 h-100">
        <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-8 align-self-end">
                <h1 class="text-white font-weight-bold">Nơi Đáp Ứng Mọi Nhu Cầu Mua Sắm</h1>
                <hr class="divider"/>
            </div>
            <div class="col-lg-8 align-self-baseline">
                <p class="text-white-75 mb-5">Siêu thị Fresh Market mang đến những sản phẩm tươi ngon và chất lượng mỗi
                    ngày.</p>
                <a class="btn btn-primary btn-xl" href="#about">Tìm Hiểu Ngay</a>
            </div>
        </div>
    </div>
</header>
<!-- About Section-->
<section class="page-section bg-primary" id="about">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="text-white mt-0">Về Fresh Market</h2>
                <hr class="divider divider-light"/>
                <p class="text-white-75 mb-4">Fresh Market là siêu thị hàng đầu cung cấp thực phẩm tươi sống, hàng tiêu
                    dùng và nhiều sản phẩm khác với chất lượng tốt nhất.</p>
                <a class="btn btn-light btn-xl" href="#services">Khám Phá Dịch Vụ</a>
            </div>
        </div>
    </div>
</section>
<!-- Services Section-->
<section class="page-section" id="services">
    <div class="container px-4 px-lg-5">
        <h2 class="text-center mt-0">Dịch Vụ Của Chúng Tôi</h2>
        <hr class="divider"/>
        <div class="row gx-4 gx-lg-5">
            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <div class="mb-2"><i class="bi-basket fs-1 text-primary"></i></div>
                    <h3 class="h4 mb-2">Sản Phẩm Đa Dạng</h3>
                    <p class="text-muted mb-0">Hàng trăm loại sản phẩm phù hợp với mọi nhu cầu gia đình.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <div class="mb-2"><i class="bi-truck fs-1 text-primary"></i></div>
                    <h3 class="h4 mb-2">Giao Hàng Tận Nơi</h3>
                    <p class="text-muted mb-0">Dịch vụ giao hàng nhanh chóng và tiện lợi.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <div class="mb-2"><i class="bi-patch-check fs-1 text-primary"></i></div>
                    <h3 class="h4 mb-2">Chất Lượng Cao</h3>
                    <p class="text-muted mb-0">Đảm bảo chất lượng an toàn vệ sinh thực phẩm.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="mt-5">
                    <div class="mb-2"><i class="bi-credit-card fs-1 text-primary"></i></div>
                    <h3 class="h4 mb-2">Thanh Toán Dễ Dàng</h3>
                    <p class="text-muted mb-0">Nhiều phương thức thanh toán linh hoạt.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Products Section-->
<div id="portfolio">
    <div class="container-fluid p-0">
        <h2 class="text-center mt-0">Sản Phẩm Nổi Bật</h2>
        <hr class="divider"/>
        <div class="row g-0">
            <div class="col-lg-4 col-sm-6">
                <img class="img-fluid" src="assets/img/product_1.jpg" alt="Sản phẩm 1"/>
            </div>
            <div class="col-lg-4 col-sm-6">
                <img class="img-fluid" src="assets/img/product_2.png" alt="Sản phẩm 2"/>
            </div>
            <div class="col-lg-4 col-sm-6">
                <img class="img-fluid" src="assets/img/product_3.png" alt="Sản phẩm 3"/>
            </div>
        </div>
    </div>
</div>
<!-- Call to action-->
<?php if (!isset($_SESSION['login']) || $_SESSION['login'] === false):
    echo '<section class="page-section bg-dark text-white">
    <div class="container px-4 px-lg-5 text-center">
        <h2 class="mb-4">Đăng ký thành viên để nhận ưu đãi ngay hôm nay!</h2>
        <a class="btn btn-light btn-xl" href="register.php">Đăng ký thành viên</a>
    </div>
</section>';
endif;
?>

<section class="page-section" id="contact">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-lg-8 col-xl-6 text-center">
                <h2 class="mt-0">Góp ý về chất lượng dịch vụ!</h2>
                <hr class="divider"/>
                <p class="text-muted mb-5">Mọi góp ý của khách hàng là tiền đề để chúng tôi ngày một phát triển!</p>
            </div>
        </div>
        <div class="row gx-4 gx-lg-5 justify-content-center mb-5">
            <div class="col-lg-6">

                <div class="form-floating mb-3">
                    <input class="form-control" id="name" name="name" type="text"
                           placeholder="Nhập họ và tên của bạn..." required/>
                    <label for="name">Họ và tên</label>
                </div>
                <!-- Email address input-->
                <div class="form-floating mb-3">
                    <input class="form-control" id="email" name="email" type="email" placeholder="name@example.com"
                           required/>
                    <label for="email">Địa chỉ email</label>
                </div>
                <!-- Phone number input-->
                <div class="form-floating mb-3">
                    <input class="form-control" id="phone" name="phone" type="tel" placeholder="(123) 456-7890"
                           required/>
                    <label for="phone">Số điện thoại</label>
                </div>
                <!-- Message input-->
                <div class="form-floating mb-3">
                    <textarea class="form-control" id="message" name="message"
                              placeholder="Nhập tin nhắn của bạn ở đây..." style="height: 10rem" required></textarea>
                    <label for="message">Tin nhắn</label>
                </div>
                <?php if (!empty($msg)): ?>
                    <div class="alert alert-info text-center" role="alert">
                        <?php echo htmlspecialchars($msg); ?>
                    </div>
                <?php endif; ?>
                <div class="d-grid">
                    <button class="btn btn-primary btn-xl" id="submitFeedback" type="button">Gửi</button>
                </div>


            </div>
        </div>
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-lg-4 text-center mb-5 mb-lg-0">
                <i class="bi-phone fs-2 mb-3 text-muted"></i>
                <div>+84 3985 43225</div>
            </div>
        </div>
    </div>
</section>

<!-- Footer-->
<footer class="bg-light py-5">
    <div class="container px-4 px-lg-5">
        <div class="small text-center text-muted">Copyright &copy; 2023 - Company Name</div>
    </div>
</footer>
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SimpleLightbox plugin JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
<!-- Core theme JS-->
<script src="assets/scripts.js"></script>
<script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
<script>
    document.getElementById('submitFeedback').addEventListener('click', function () {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const message = document.getElementById('message').value.trim();

        if (!name || !email || !phone || !message) {
            alert("Vui lòng điền đầy đủ thông tin.");
            return;
        }

        fetch('send_feedback.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ name, email, phone, message }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    alert(data.message);
                    document.getElementById('name').value = '';
                    document.getElementById('email').value = '';
                    document.getElementById('phone').value = '';
                    document.getElementById('message').value = '';
                } else {
                    alert(data.message);
                }
            })
            .catch((error) => {
                console.error('Lỗi:', error);
                alert('Đã xảy ra lỗi, vui lòng thử lại.');
            });
    });
</script>
</body>
</html>
