<?php 
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

$database = new Database();
$conn = $database->getConnection();

$currentPage = 'services';
$pageTitle = 'Dịch vụ';

// Lấy danh sách danh mục
$stmt = $conn->query("SELECT * FROM DanhMuc WHERE TrangThai = 1");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Banner Section -->
<div class="page-banner" style="background-image: url('assets/images/banners/service-bg.jpg');">
    <div class="container">
        <h1>Dịch vụ của chúng tôi</h1>
    </div>  
</div>

<!-- Services Section -->
<section class="services-section py-5">
    <div class="container">
        <?php foreach($categories as $category): ?>
            <div class="category-section mb-5">
                <h2 class="text-center mb-4"><?= $category['TenDanhMuc'] ?></h2>
                <div class="row">
                    <?php 
                    $stmt = $conn->prepare("SELECT * FROM DichVu WHERE MaDanhMuc = ? AND TrangThai = 1");
                    $stmt->execute([$category['MaDanhMuc']]);
                    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach($services as $service):
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="service-card card h-100 shadow-sm">
                            <a href="assets/images/services/<?= getServiceImage($service) ?>" 
                               data-lightbox="services" 
                               data-title="<?= htmlspecialchars($service['TenDichVu']) ?>">
                                <img src="assets/images/services/<?= getServiceImage($service) ?>" 
                                     class="card-img-top"
                                     alt="<?= htmlspecialchars($service['TenDichVu']) ?>"
                                     onerror="this.src='assets/images/services/default.jpg'">
                            </a>
                            
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($service['TenDichVu']) ?></h5>
                                <p class="card-text"><?= nl2br(htmlspecialchars($service['MoTa'])) ?></p>
                                <ul class="list-unstyled mb-4">
                                    <li class="mb-2">
                                        <i class="fas fa-clock text-primary"></i> 
                                        <?= $service['ThoiGianChup'] ?> phút
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-images text-primary"></i> 
                                        <?= $service['SoLuongAnh'] ?> ảnh
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-tag text-primary"></i> 
                                        <?= number_format($service['GiaTien'], 0, ',', '.') ?>đ
                                    </li>
                                </ul>
                                <a href="booking.php?service=<?= $service['MaDichVu'] ?>" 
                                   class="btn btn-primary w-100">
                                    <i class="fas fa-calendar-alt me-2"></i>Đặt lịch ngay
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Why Choose Us -->
<section class="why-choose-us py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Tại sao chọn chúng tôi?</h2>
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <i class="fas fa-camera fa-3x mb-3 text-primary"></i>
                <h4>Thiết bị hiện đại</h4>
                <p>Sử dụng các thiết bị chụp ảnh chuyên nghiệp, hiện đại nhất</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <i class="fas fa-user-tie fa-3x mb-3 text-primary"></i>
                <h4>Đội ngũ chuyên nghiệp</h4>
                <p>Nhiếp ảnh gia giàu kinh nghiệm, được đào tạo bài bản</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <i class="fas fa-heart fa-3x mb-3 text-primary"></i>
                <h4>Dịch vụ tận tâm</h4>
                <p>Cam kết mang đến trải nghiệm tốt nhất cho khách hàng</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section py-5">
    <div class="container">
        <h2 class="text-center mb-5">Câu hỏi thường gặp</h2>
        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        Quy trình đặt lịch như thế nào?
                    </button>
                </h3>
                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Quy trình đặt lịch đơn giản: Chọn gói dịch vụ → Chọn ngày giờ → Điền thông tin → Xác nhận đặt lịch
                    </div>
                </div>
            </div>
            <!-- Thêm các câu hỏi khác -->
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
