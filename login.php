<?php
session_start();
require_once 'config/database.php';

$currentPage = 'login';
$pageTitle = 'Đăng nhập';

if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $conn = $database->getConnection();
    
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Trong thực tế nên dùng password_hash
    
    $stmt = $conn->prepare("SELECT * FROM KhachHang WHERE Email = ? AND MatKhau = ?");
    $stmt->execute([$email, $password]);
    
    if ($user = $stmt->fetch()) {
        $_SESSION['user'] = $user;
        
        // Nếu có trang redirect
        if (isset($_SESSION['redirect_url'])) {
            $redirect = $_SESSION['redirect_url'];
            unset($_SESSION['redirect_url']);
            header("Location: $redirect");
        } else {
            header('Location: index.php');
        }
        exit;
    } else {
        $error = "Email hoặc mật khẩu không đúng!";
    }
}

require_once 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="text-center mb-4">Đăng nhập</h2>
                    
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">
                                Vui lòng nhập email hợp lệ
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="invalid-feedback">
                                Vui lòng nhập mật khẩu
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">Đăng nhập</button>
                        
                        <div class="text-center">
                            <a href="forgot-password.php" class="text-decoration-none">Quên mật khẩu?</a>
                            <hr>
                            <p class="mb-0">Chưa có tài khoản? <a href="register.php" class="text-decoration-none">Đăng ký ngay</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validation form
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>

<?php require_once 'includes/footer.php'; ?> 