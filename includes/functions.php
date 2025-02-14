<?php
function getDichVuByDanhMuc($conn, $maDanhMuc) {
    $query = "SELECT * FROM DichVu WHERE MaDanhMuc = ? AND TrangThai = 1";
    $stmt = $conn->prepare($query);
    $stmt->execute([$maDanhMuc]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getLatestGallery($conn, $limit = 8) {
    $query = "SELECT a.*, d.TenDanhMuc 
              FROM ThuVienAnh a 
              JOIN DanhMuc d ON a.MaDanhMuc = d.MaDanhMuc 
              WHERE a.TrangThai = 1 
              ORDER BY a.NgayDang DESC 
              LIMIT ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createBooking($conn, $data) {
    try {
        $conn->beginTransaction();
        
        // Thêm thông tin khách hàng
        $queryKH = "INSERT INTO KhachHang (HoTen, Email, SoDienThoai) VALUES (?, ?, ?)";
        $stmtKH = $conn->prepare($queryKH);
        $stmtKH->execute([$data['hoTen'], $data['email'], $data['soDienThoai']]);
        $maKhachHang = $conn->lastInsertId();
        
        // Thêm đơn đặt lịch
        $queryDL = "INSERT INTO DatLich (MaKhachHang, MaDichVu, NgayChup, GioChup, TongTien, TrangThai) 
                    VALUES (?, ?, ?, ?, ?, 'ChoXacNhan')";
        $stmtDL = $conn->prepare($queryDL);
        $stmtDL->execute([
            $maKhachHang,
            $data['maDichVu'],
            $data['ngayChup'],
            $data['gioChup'],
            $data['tongTien']
        ]);
        
        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollBack();
        return false;
    }
}

function getServiceImage($service) {
    switch($service['MaDichVu']) {
        // Dịch vụ cưới
        case 1: return 'anh-cuoi-co-ban.jpg';
        case 2: return 'anh-cuoi-cao-cap.jpg';
        case 3: return 'anh-cuoi-dac-biet.jpg';
        
        // Dịch vụ gia đình
        case 4: return 'gia-dinh-co-ban.jpg';
        case 5: return 'gia-dinh-cao-cap.jpg';
        case 6: return 'gia-dinh-dac-biet.jpg';
        
        // Dịch vụ sự kiện
        case 7: return 'su-kien-2h.jpg';
        case 8: return 'su-kien-4h.jpg';
        case 9: return 'su-kien-ca-ngay.jpg';
        
        // Dịch vụ kỷ yếu
        case 10: return 'ky-yeu-co-ban.jpg';
        case 11: return 'ky-yeu-nang-cao.jpg';
        case 12: return 'ky-yeu-cao-cap.jpg';
        
        default: return 'default.jpg';
    }
}