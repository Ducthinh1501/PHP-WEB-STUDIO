<?php
require_once '../config/database.php';
$database = new Database();
$conn = $database->getConnection();

// Lấy thống kê
$totalBookings = $conn->query("SELECT COUNT(*) FROM DatLich")->fetchColumn();
$totalServices = $conn->query("SELECT COUNT(*) FROM DichVu")->fetchColumn();
$totalGallery = $conn->query("SELECT COUNT(*) FROM ThuVienAnh")->fetchColumn();
$pendingBookings = $conn->query("SELECT COUNT(*) FROM DatLich WHERE TrangThai = 'ChoXacNhan'")->fetchColumn();

// Lấy đơn đặt lịch mới nhất
$recentBookings = $conn->query("SELECT dl.*, kh.HoTen, dv.TenDichVu 
                               FROM DatLich dl 
                               JOIN KhachHang kh ON dl.MaKhachHang = kh.MaKhachHang
                               JOIN DichVu dv ON dl.MaDichVu = dv.MaDichVu
                               ORDER BY dl.NgayDat DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="content">
            <!-- Navbar -->
            <?php include 'includes/navbar.php'; ?>

            <!-- Dashboard Content -->
            <div class="container-fluid p-4">
                <h2 class="mb-4">Dashboard</h2>

                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card stat-card bg-primary text-white">
                            <div class="card-body">
                                <h5>Tổng đơn đặt lịch</h5>
                                <h2><?= $totalBookings ?></h2>
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card stat-card bg-success text-white">
                            <div class="card-body">
                                <h5>Dịch vụ</h5>
                                <h2><?= $totalServices ?></h2>
                                <i class="fas fa-camera"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card stat-card bg-info text-white">
                            <div class="card-body">
                                <h5>Thư viện ảnh</h5>
                                <h2><?= $totalGallery ?></h2>
                                <i class="fas fa-images"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card stat-card bg-warning text-white">
                            <div class="card-body">
                                <h5>Chờ xác nhận</h5>
                                <h2><?= $pendingBookings ?></h2>
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Đơn đặt lịch mới nhất</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Khách hàng</th>
                                        <th>Dịch vụ</th>
                                        <th>Ngày chụp</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($recentBookings as $booking): ?>
                                    <tr>
                                        <td><?= $booking['MaDatLich'] ?></td>
                                        <td><?= htmlspecialchars($booking['HoTen']) ?></td>
                                        <td><?= htmlspecialchars($booking['TenDichVu']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($booking['NgayChup'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $booking['TrangThai'] == 'ChoXacNhan' ? 'warning' : 'success' ?>">
                                                <?= $booking['TrangThai'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="booking-detail.php?id=<?= $booking['MaDatLich'] ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/admin.js"></script>
</body>
</html>
