<?php 
require_once 'config/database.php';
require_once 'includes/header.php';

// Xử lý form liên hệ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Thêm code xử lý gửi email hoặc lưu vào database
    $success = "Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất!";
}
?>

<!-- Contact Section -->
<div class="contact-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="mb-4">Liên hệ với chúng tôi</h2>
                
                <?php if(isset($success)): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>
                
                <form method="POST" class="contact-form">
                    <div class="mb-3">
                        <label>Họ tên</label>
                        <input type="text" name="hoTen" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label>Số điện thoại</label>
                        <input type="tel" name="soDienThoai" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label>Tiêu đề</label>
                        <input type="text" name="tieuDe" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label>Nội dung</label>
                        <textarea name="noiDung" class="form-control" rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Gửi tin nhắn</button>
                </form>
            </div>
            
            <div class="col-md-6">
                <div class="contact-info">
                    <h3>Thông tin liên hệ</h3>
                    <p><i class="fas fa-map-marker-alt"></i> 123 Đường ABC, Quận XYZ, TP.HCM</p>
                    <p><i class="fas fa-phone"></i> 090 123 4567</p>
                    <p><i class="fas fa-envelope"></i> info@studio.com</p>
                    
                    <h4 class="mt-4">Giờ làm việc</h4>
                    <p>Thứ 2 - Thứ 6: 8:00 - 20:00</p>
                    <p>Thứ 7 - Chủ nhật: 9:00 - 18:00</p>
                    
                    <div class="map mt-4">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!..." 
                            width="100%" 
                            height="300" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 