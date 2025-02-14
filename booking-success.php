<?php
session_start();
require_once 'includes/header.php';

// Kiểm tra xem có thông tin đặt lịch không
if (!isset($_SESSION['booking_success'])) {
    header('Location: booking.php');
    exit;
}

$bookingInfo = $_SESSION['booking_info'];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-check-circle text-success fa-5x mb-3"></i>
                    <h2 class="card-title mb-4">Đặt lịch thành công!</h2>
                    
                    <div class="booking-details text-start mb-4">
                        <h4>Thông tin đặt lịch:</h4>
                        <p><strong>Họ tên:</strong> <?= htmlspecialchars($bookingInfo['hoTen']) ?></p>
                        <p><strong>Ngày chụp:</strong> <?= date('d/m/Y', strtotime($bookingInfo['ngayChup'])) ?></p>
                        <p><strong>Giờ chụp:</strong> <?= $bookingInfo['gioChup'] ?></p>
                        <p><strong>Tổng tiền:</strong> <?= number_format($bookingInfo['tongTien']) ?>đ</p>
                    </div>
                    
                    <p class="alert alert-info">
                        Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận lịch chụp.<br>
                        Vui lòng kiểm tra email để xem chi tiết.
                    </p>
                    
                    <div class="mt-4">
                        <a href="index.php" class="btn btn-primary">Về trang chủ</a>
                        <a href="gallery.php" class="btn btn-outline-primary ms-2">Xem bộ sưu tập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Xóa thông tin đặt lịch khỏi session
unset($_SESSION['booking_success']);
unset($_SESSION['booking_info']);

require_once 'includes/footer.php';
?> 