<?php
require_once '../includes/header.php';
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $conn = $database->getConnection();
    
    try {
        // Xử lý upload ảnh
        $targetDir = "../assets/images/services/";
        $fileName = time() . '_' . basename($_FILES["hinhAnh"]["name"]);
        $targetFile = $targetDir . $fileName;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
        
        // Kiểm tra file ảnh
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["hinhAnh"]["tmp_name"]);
            if($check === false) {
                throw new Exception("File không phải là ảnh.");
            }
        }
        
        // Kiểm tra kích thước
        if ($_FILES["hinhAnh"]["size"] > 5000000) {
            throw new Exception("File quá lớn.");
        }
        
        // Cho phép các định dạng file
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            throw new Exception("Chỉ chấp nhận file JPG, JPEG, PNG.");
        }
        
        // Upload file
        if (!move_uploaded_file($_FILES["hinhAnh"]["tmp_name"], $targetFile)) {
            throw new Exception("Có lỗi khi upload file.");
        }
        
        // Lưu vào database
        $stmt = $conn->prepare("INSERT INTO DichVu (TenDichVu, MoTa, GiaTien, HinhAnh) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_POST['tenDichVu'],
            $_POST['moTa'],
            $_POST['giaTien'],
            'services/' . $fileName
        ]);
        
        $success = "Thêm dịch vụ thành công!";
        
    } catch(Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<div class="container mt-4">
    <h2>Thêm Dịch Vụ Mới</h2>
    
    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Tên dịch vụ</label>
            <input type="text" name="tenDichVu" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="moTa" class="form-control" rows="3" required></textarea>
        </div>
        
        <div class="mb-3">
            <label>Giá tiền</label>
            <input type="number" name="giaTien" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label>Hình ảnh</label>
            <input type="file" name="hinhAnh" class="form-control" required accept="image/*">
        </div>
        
        <button type="submit" class="btn btn-primary">Thêm dịch vụ</button>
    </form>
</div> 