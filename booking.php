<?php 
require_once 'config/database.php';
require_once 'includes/header.php';

$database = new Database();
$conn = $database->getConnection();

// Lấy thông tin dịch vụ được chọn (nếu có)
$selectedService = null;
if(isset($_GET['service'])) {
    $stmt = $conn->prepare("SELECT * FROM DichVu WHERE MaDichVu = ?");
    $stmt->execute([$_GET['service']]);
    $selectedService = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Lấy danh sách dịch vụ
$stmt = $conn->query("SELECT * FROM DichVu WHERE TrangThai = 1");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Xử lý form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn->beginTransaction();
        
        // Thêm thông tin khách hàng
        $stmtKH = $conn->prepare("INSERT INTO KhachHang (HoTen, Email, SoDienThoai, DiaChi) VALUES (?, ?, ?, ?)");
        $stmtKH->execute([
            $_POST['hoTen'],
            $_POST['email'],
            $_POST['soDienThoai'],
            $_POST['diaChi']
        ]);
        $maKhachHang = $conn->lastInsertId();
        
        // Thêm đơn đặt lịch online
        $stmtDL = $conn->prepare("INSERT INTO DatLichOnline (MaKhachHang, MaDichVu, NgayChup, GioChup, TongTien, TrangThai, GhiChu) 
                                 VALUES (?, ?, ?, ?, ?, 'ChoXacNhan', ?)");
        $stmtDL->execute([
            $maKhachHang,
            $_POST['maDichVu'],
            $_POST['ngayChup'],
            $_POST['gioChup'],
            $_POST['tongTien'],
            $_POST['ghiChu']
        ]);
        
        // Gửi email thông báo
        $to = $_POST['email'];
        $subject = "Xác nhận đặt lịch - Studio";
        $message = "
        <html>
        <head>
            <title>Xác nhận đặt lịch</title>
        </head>
        <body>
            <h2>Cảm ơn bạn đã đặt lịch tại Studio!</h2>
            <p>Thông tin đặt lịch của bạn:</p>
            <ul>
                <li>Họ tên: {$_POST['hoTen']}</li>
                <li>Ngày chụp: {$_POST['ngayChup']}</li>
                <li>Giờ chụp: {$_POST['gioChup']}</li>
                <li>Tổng tiền: " . number_format($_POST['tongTien']) . "đ</li>
            </ul>
            <p>Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận lịch chụp.</p>
            <p>Mọi thắc mắc xin vui lòng liên hệ: 090 123 4567</p>
        </body>
        </html>
        ";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: Studio <no-reply@studio.com>' . "\r\n";

        mail($to, $subject, $message, $headers);
        
        $conn->commit();
        
        // Lưu thông tin vào session
        $_SESSION['booking_success'] = true;
        $_SESSION['booking_info'] = [
            'hoTen' => $_POST['hoTen'],
            'ngayChup' => $_POST['ngayChup'],
            'gioChup' => $_POST['gioChup'],
            'tongTien' => $_POST['tongTien']
        ];
        
        // Chuyển hướng đến trang thành công
        header('Location: booking-success.php');
        exit;
        
    } catch(Exception $e) {
        $conn->rollBack();
        $error = "Có lỗi xảy ra, vui lòng thử lại!";
    }
}
?>

<!-- Booking Section -->
<div class="booking-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="booking-form card">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Đặt lịch chụp ảnh</h2>
                        
                        <?php if(isset($success)): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php endif; ?>
                        
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" id="bookingForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Họ tên</label>
                                    <input type="text" name="hoTen" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Số điện thoại</label>
                                    <input type="tel" name="soDienThoai" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label>Địa chỉ</label>
                                <input type="text" name="diaChi" class="form-control">
                            </div>
                            
                            <div class="mb-3">
                                <label>Chọn dịch vụ</label>
                                <select name="maDichVu" class="form-control" required>
                                    <option value="">-- Chọn dịch vụ --</option>
                                    <?php foreach($services as $service): ?>
                                        <option value="<?= $service['MaDichVu'] ?>" 
                                                <?= ($selectedService && $selectedService['MaDichVu'] == $service['MaDichVu']) ? 'selected' : '' ?>
                                                data-price="<?= $service['GiaTien'] ?>">
                                            <?= $service['TenDichVu'] ?> - <?= number_format($service['GiaTien']) ?>đ
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Ngày chụp</label>
                                    <input type="date" name="ngayChup" class="form-control" required
                                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Giờ chụp</label>
                                    <input type="time" name="gioChup" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label>Ghi chú</label>
                                <textarea name="ghiChu" class="form-control" rows="3"></textarea>
                            </div>
                            
                            <input type="hidden" name="tongTien" id="tongTien">
                            
                            <button type="submit" class="btn btn-primary w-100">Đặt lịch ngay</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="booking-sidebar card">
                    <div class="card-body">
                        <h4 class="card-title">Thông tin đặt lịch</h4>
                        <div id="bookingSummary">
                            <p>Vui lòng chọn dịch vụ</p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-info card mt-4">
                    <div class="card-body">
                        <h4 class="card-title">Liên hệ hỗ trợ</h4>
                        <p><i class="fas fa-phone"></i> 090 123 4567</p>
                        <p><i class="fas fa-envelope"></i> booking@studio.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bookingForm');
    const serviceSelect = form.querySelector('[name="maDichVu"]');
    const summaryDiv = document.getElementById('bookingSummary');
    const tongTienInput = document.getElementById('tongTien');
    const phoneInput = form.querySelector('[name="soDienThoai"]');
    
    // Validation số điện thoại
    phoneInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        if(this.value.length > 10) {
            this.value = this.value.slice(0, 10);
        }
    });

    // Giới hạn thời gian đặt lịch
    const timeInput = form.querySelector('[name="gioChup"]');
    timeInput.addEventListener('change', function() {
        const time = this.value;
        const hour = parseInt(time.split(':')[0]);
        if(hour < 8 || hour > 17) {
            alert('Vui lòng chọn giờ từ 8:00 đến 17:00');
            this.value = '';
        }
    });

    // Cập nhật summary khi thay đổi thông tin
    function updateSummary() {
        const selectedOption = serviceSelect.selectedOptions[0];
        const ngayChup = form.querySelector('[name="ngayChup"]').value;
        const gioChup = form.querySelector('[name="gioChup"]').value;
        
        if(selectedOption.value) {
            const price = parseInt(selectedOption.dataset.price);
            tongTienInput.value = price;
            
            summaryDiv.innerHTML = `
                <div class="summary-item">
                    <p><strong>Dịch vụ:</strong> ${selectedOption.text}</p>
                    <p><strong>Ngày chụp:</strong> ${formatDate(ngayChup)}</p>
                    <p><strong>Giờ chụp:</strong> ${gioChup}</p>
                    <p><strong>Tổng tiền:</strong> ${price.toLocaleString()}đ</p>
                </div>
            `;
        }
    }
    
    // Format date
    function formatDate(dateString) {
        if(!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('vi-VN');
    }
    
    // Theo dõi thay đổi của các trường
    form.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('change', updateSummary);
    });
    
    // Validation trước khi submit
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if(!validateForm()) {
            return;
        }
        
        // Xác nhận đặt lịch
        if(confirm('Xác nhận đặt lịch?')) {
            this.submit();
        }
    });
    
    function validateForm() {
        const hoTen = form.querySelector('[name="hoTen"]').value.trim();
        const soDienThoai = phoneInput.value.trim();
        const email = form.querySelector('[name="email"]').value.trim();
        
        if(hoTen.length < 2) {
            alert('Vui lòng nhập họ tên hợp lệ');
            return false;
        }
        
        if(soDienThoai.length !== 10) {
            alert('Vui lòng nhập số điện thoại 10 số');
            return false;
        }
        
        if(!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
            alert('Vui lòng nhập email hợp lệ');
            return false;
        }
        
        return true;
    }
    
    updateSummary();
});
</script>

<?php require_once 'includes/footer.php'; ?>