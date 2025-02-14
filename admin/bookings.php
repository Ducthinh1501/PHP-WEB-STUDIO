<?php
session_start();
require_once '../config/database.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$conn = $database->getConnection();

// Xử lý cập nhật trạng thái
if (isset($_POST['update_status'])) {
    $stmt = $conn->prepare("UPDATE DatLichOnline SET TrangThai = ? WHERE MaDatLich = ?");
    $stmt->execute([$_POST['status'], $_POST['booking_id']]);
    
    // Gửi email thông báo cho khách
    // ... code gửi email ...
}

// Lấy danh sách đặt lịch
$stmt = $conn->query("
    SELECT dl.*, kh.HoTen, kh.Email, kh.SoDienThoai, dv.TenDichVu 
    FROM DatLichOnline dl
    JOIN KhachHang kh ON dl.MaKhachHang = kh.MaKhachHang
    JOIN DichVu dv ON dl.MaDichVu = dv.MaDichVu
    ORDER BY dl.NgayDat DESC
");
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>

<div class="container-fluid py-4">
    <h2 class="mb-4">Quản lý đặt lịch</h2>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Dịch vụ</th>
                        <th>Ngày chụp</th>
                        <th>Giờ chụp</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($bookings as $booking): ?>
                    <tr>
                        <td><?= $booking['MaDatLich'] ?></td>
                        <td>
                            <?= htmlspecialchars($booking['HoTen']) ?><br>
                            <small><?= $booking['SoDienThoai'] ?></small>
                        </td>
                        <td><?= htmlspecialchars($booking['TenDichVu']) ?></td>
                        <td><?= date('d/m/Y', strtotime($booking['NgayChup'])) ?></td>
                        <td><?= $booking['GioChup'] ?></td>
                        <td><?= number_format($booking['TongTien']) ?>đ</td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="booking_id" value="<?= $booking['MaDatLich'] ?>">
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="ChoXacNhan" <?= $booking['TrangThai'] == 'ChoXacNhan' ? 'selected' : '' ?>>
                                        Chờ xác nhận
                                    </option>
                                    <option value="DaXacNhan" <?= $booking['TrangThai'] == 'DaXacNhan' ? 'selected' : '' ?>>
                                        Đã xác nhận
                                    </option>
                                    <option value="DaHuy" <?= $booking['TrangThai'] == 'DaHuy' ? 'selected' : '' ?>>
                                        Đã hủy
                                    </option>
                                    <option value="HoanThanh" <?= $booking['TrangThai'] == 'HoanThanh' ? 'selected' : '' ?>>
                                        Hoàn thành
                                    </option>
                                </select>
                                <input type="hidden" name="update_status" value="1">
                            </form>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info" 
                                    onclick="viewDetails(<?= $booking['MaDatLich'] ?>)">
                                Chi tiết
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
