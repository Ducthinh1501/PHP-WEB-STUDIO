<?php
require_once '../includes/header.php';
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $conn = $database->getConnection();
    
    try {
        // Xử lý upload nhiều ảnh
        $uploadedFiles = [];
        $targetDir = "../assets/images/gallery/";
        
        // Duyệt qua từng file được upload
        foreach($_FILES['hinhAnh']['tmp_name'] as $key => $tmp_name) {
            $fileName = time() . '_' . basename($_FILES["hinhAnh"]["name"][$key]);
            $targetFile = $targetDir . $fileName;
            
            // Kiểm tra file
            $check = getimagesize($tmp_name);
            if($check === false) {
                throw new Exception("File không phải là ảnh.");
            }
            
            // Kiểm tra kích thước
            if ($_FILES["hinhAnh"]["size"][$key] > 5000000) {
                throw new Exception("File quá lớn.");
            }
            
            // Upload file
            if (move_uploaded_file($tmp_name, $targetFile)) {
                $uploadedFiles[] = 'gallery/' . $fileName;
            }
        }
        
        // Lưu vào database
        $stmt = $conn->prepare("INSERT INTO ThuVienAnh (TieuDe, MoTa, DuongDanAnh, MaDanhMuc) VALUES (?, ?, ?, ?)");
        
        foreach($uploadedFiles as $file) {
            $stmt->execute([
                $_POST['tieuDe'],
                $_POST['moTa'],
                $file,
                $_POST['maDanhMuc']
            ]);
        }
        
        $success = "Upload ảnh thành công!";
        
    } catch(Exception $e) {
        $error = $e->getMessage();
    }
}

// Lấy danh sách danh mục
$stmt = $conn->query("SELECT * FROM DanhMuc WHERE TrangThai = 1");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h2>Thêm Ảnh Vào Gallery</h2>
    
    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Tiêu đề</label>
            <input type="text" name="tieuDe" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="moTa" class="form-control" rows="3"></textarea>
        </div>
        
        <div class="mb-3">
            <label>Danh mục</label>
            <select name="maDanhMuc" class="form-control" required>
                <?php foreach($categories as $category): ?>
                    <option value="<?= $category['MaDanhMuc'] ?>">
                        <?= htmlspecialchars($category['TenDanhMuc']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="mb-3">
            <label>Hình ảnh</label>
            <input type="file" name="hinhAnh[]" class="form-control" multiple required accept="image/*">
            <small class="text-muted">Có thể chọn nhiều ảnh</small>
        </div>
        
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div> 