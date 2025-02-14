<?php 
require_once 'config/database.php';
require_once 'includes/header.php';

$database = new Database();
$conn = $database->getConnection();

$currentPage = 'gallery';
$pageTitle = 'Bộ sưu tập';

require_once 'includes/header.php';

// Lấy danh mục
$categories = $conn->query("SELECT * FROM DanhMuc WHERE TrangThai = 1")->fetchAll(PDO::FETCH_ASSOC);

// Lọc theo danh mục
$category_id = isset($_GET['category']) ? $_GET['category'] : null;
$where = $category_id ? "WHERE a.MaDanhMuc = " . intval($category_id) : "";

// Lấy ảnh
$query = "SELECT a.*, d.TenDanhMuc 
          FROM ThuVienAnh a 
          JOIN DanhMuc d ON a.MaDanhMuc = d.MaDanhMuc 
          $where 
          ORDER BY a.NgayDang DESC";
$images = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Gallery Header -->
<div class="page-banner" style="background-image: url('assets/images/banners/gallery-bg.jfif');">
    <div class="container">
        <h1>Bộ sưu tập ảnh</h1>
    </div>
</div>

<!-- Gallery Filter -->
<section class="gallery-section py-5">
    <div class="container">
        <div class="gallery-filter mb-5">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="category-filter">
                        <a href="gallery.php" class="btn <?= !$category_id ? 'btn-primary' : 'btn-outline-primary' ?>">
                            Tất cả
                        </a>
                        <?php foreach($categories as $cat): ?>
                            <a href="gallery.php?category=<?= $cat['MaDanhMuc'] ?>" 
                               class="btn <?= $category_id == $cat['MaDanhMuc'] ? 'btn-primary' : 'btn-outline-primary' ?>">
                                <?= $cat['TenDanhMuc'] ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div class="row gallery-grid" id="galleryGrid">
            <?php foreach($images as $image): ?>
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="gallery-item">
                    <a href="<?= htmlspecialchars($image['DuongDanAnh']) ?>" 
                       data-lightbox="gallery" 
                       data-title="<?= htmlspecialchars($image['TieuDe']) ?>">
                        <img src="<?= htmlspecialchars($image['DuongDanAnh']) ?>" 
                             alt="<?= htmlspecialchars($image['TieuDe']) ?>" 
                             class="img-fluid">
                        <div class="gallery-overlay">
                            <div class="gallery-info">
                                <h5><?= $image['TieuDe'] ?></h5>
                                <p><?= $image['TenDanhMuc'] ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Thêm Lightbox JS và CSS -->
<link href="assets/css/lightbox.min.css" rel="stylesheet">
<script src="assets/js/lightbox.min.js"></script>

<script>
lightbox.option({
    'resizeDuration': 200,
    'wrapAround': true,
    'albumLabel': "Ảnh %1 / %2"
})
</script>

<?php require_once 'includes/footer.php'; ?>
