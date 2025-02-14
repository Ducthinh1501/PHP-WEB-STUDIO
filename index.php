<?php 
require_once 'config/database.php';
require_once 'includes/header.php';

$database = new Database();
$conn = $database->getConnection();

$currentPage = 'home';
$pageTitle = 'Trang chủ - Studio Chụp Ảnh';

// Lấy dịch vụ nổi bật
$stmt = $conn->query("SELECT * FROM DichVu WHERE TrangThai = 1 LIMIT 6");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy ảnh gallery mới nhất
$stmt = $conn->query("SELECT * FROM ThuVienAnh WHERE TrangThai = 1 ORDER BY NgayDang DESC LIMIT 8");
$gallery = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Hero Section -->
<section class="hero-section" style="background-image: url('assets/images/banners/hero-bg.jpg');">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <h1>Chụp ảnh chuyên nghiệp</h1>
            <p class="lead">Lưu giữ khoảnh khắc đẹp nhất của bạn</p>
            <a href="booking.php" class="btn btn-primary btn-lg">Đặt lịch ngay</a>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Dịch vụ của chúng tôi</h2>
        <div class="row">
            <?php foreach($services as $service): ?>
            <div class="col-md-4 mb-4">
                <div class="card service-card h-100">
                    <?php
                    // Xác định tên file ảnh dựa vào MaDichVu
                    $imageFile = '';
                    switch($service['MaDichVu']) {
                        // Dịch vụ cưới
                        case 1: $imageFile = 'anh-cuoi-co -ban.jpg'; break;
                        case 2: $imageFile = 'anh-cuoi-cao-cap.jpg'; break;
                        case 3: $imageFile = 'anh-cuoi-dac-biet.jpg'; break;
                        
                        // Dịch vụ gia đình
                        case 4: $imageFile = 'gia-dinh-co-ban.jpg'; break;
                        case 5: $imageFile = 'gia-dinh-cao-cap.jpg'; break;
                        case 6: $imageFile = 'gia-dinh-dac-biet.jpg'; break;
                        
                        // Dịch vụ sự kiện
                        case 7: $imageFile = 'su-kien-2h.jpg'; break;
                        case 8: $imageFile = 'su-kien-4h.jpg'; break;
                        case 9: $imageFile = 'su-kien-nguyen-ngay.jpg'; break;
                        
                        // Dịch vụ kỷ yếu
                        case 10: $imageFile = 'ky-yeu-co-ban.jpg'; break;
                        case 11: $imageFile = 'ky-yeu-nang-cao.jpg'; break;
                        case 12: $imageFile = 'ky-yeu-cao-cap.jpg'; break;
                    }
                    ?>
                    <a href="assets/images/services/<?= $imageFile ?>" 
                       data-lightbox="services" 
                       data-title="<?= htmlspecialchars($service['TenDichVu']) ?>">
                        <img src="assets/images/services/<?= $imageFile ?>" 
                             class="card-img-top"
                             alt="<?= htmlspecialchars($service['TenDichVu']) ?>"
                             onerror="this.src='assets/images/services/default.jpg'">
                    </a>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($service['TenDichVu']) ?></h5>
                        <p class="card-text"><?= nl2br(htmlspecialchars($service['MoTa'])) ?></p>
                        <div class="mt-auto">
                            <p class="text-primary fw-bold mb-3">
                                <?= number_format($service['GiaTien'], 0, ',', '.') ?>đ
                            </p>
                            <a href="booking.php?service=<?= $service['MaDichVu'] ?>" 
                               class="btn btn-primary w-100">
                                <i class="fas fa-calendar-alt me-2"></i>Đặt lịch
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="services.php" class="btn btn-outline-primary">Xem tất cả dịch vụ</a>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Bộ sưu tập ảnh</h2>
        <div class="row">
            <?php foreach($gallery as $image): ?>
            <div class="col-md-3">
                <div class="gallery-item">
                    <img src="<?= $image['DuongDanAnh'] ?>" alt="<?= $image['TieuDe'] ?>">
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="gallery.php" class="btn btn-primary">Xem thêm</a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Khách hàng nói gì về chúng tôi</h2>
        <div class="row">
            <?php
            $stmt = $conn->query("SELECT d.*, k.HoTen FROM DanhGia d 
                                 JOIN KhachHang k ON d.MaKhachHang = k.MaKhachHang 
                                 WHERE d.TrangThai = 1 
                                 ORDER BY d.NgayDanhGia DESC LIMIT 3");
            $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach($testimonials as $testimonial):
            ?>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="stars mb-3">
                        <?php for($i = 0; $i < $testimonial['SoSao']; $i++): ?>
                            <i class="fas fa-star text-warning"></i>
                        <?php endfor; ?>
                    </div>
                    <p><?= $testimonial['NhanXet'] ?></p>
                    <strong><?= $testimonial['HoTen'] ?></strong>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
