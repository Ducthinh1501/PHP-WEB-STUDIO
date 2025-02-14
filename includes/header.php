<?php
// Kiểm tra kết nối database
require_once __DIR__ . '/../config/database.php';
$database = new Database();
$conn = $database->getConnection();

// Bắt đầu session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Xác định trang hiện tại
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle : 'Studio Chụp Ảnh' ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link href="assets/css/lightbox.min.css" rel="stylesheet">
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar bg-dark text-light py-2">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <i class="fas fa-phone me-2"></i> 090 123 4567
                    <i class="fas fa-envelope ms-3 me-2"></i> info@studio.com
                </div>
                <div class="col-md-6 text-end">
                    <?php if(isset($_SESSION['user'])): ?>
                        <span class="me-3">
                            <i class="fas fa-user me-1"></i>
                            Xin chào, <?= htmlspecialchars($_SESSION['user']['HoTen']) ?>
                        </span>
                        <a href="logout.php" class="text-light"><i class="fas fa-sign-out-alt me-1"></i>Đăng xuất</a>
                    <?php else: ?>
                        <a href="login.php" class="text-light me-3">
                            <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                        </a>
                        <a href="register.php" class="text-light">
                            <i class="fas fa-user-plus me-1"></i>Đăng ký
                        </a>
                    <?php endif; ?>
                    <a href="#" class="text-light ms-3"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-light ms-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-light ms-3"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="./index.php">
                <img src="./assets/images/logo.png" alt="Studio Logo" height="50">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage == 'index' ? 'active' : '' ?>" 
                           href="./index.php">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage == 'services' ? 'active' : '' ?>" 
                           href="./services.php">Dịch vụ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage == 'gallery' ? 'active' : '' ?>" 
                           href="./gallery.php">Bộ sưu tập</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage == 'booking' ? 'active' : '' ?>" 
                           href="./booking.php">Đặt lịch</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage == 'contact' ? 'active' : '' ?>" 
                           href="./contact.php">Liên hệ</a>
                    </li>
                </ul>
                
                <?php if(isset($_SESSION['user'])): ?>
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i>
                        <?= htmlspecialchars($_SESSION['user']['HoTen']) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="profile.php">
                            <i class="fas fa-user me-2"></i>Thông tin cá nhân
                        </a></li>
                        <li><a class="dropdown-item" href="my-bookings.php">
                            <i class="fas fa-calendar-check me-2"></i>Lịch đã đặt
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                        </a></li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <script src="assets/js/lightbox.min.js"></script>
    <script>
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'albumLabel': "Ảnh %1 / %2"
    });
    </script>
</body>
</html>
