<?php
require __DIR__ . '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['MaSV']; // Lưu MaSV vào session
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['avatar'] = $user['avatar']; // Lưu ảnh đại diện
        header("Location: index.php");
        exit;
    } else {
        echo "<p style='color:red;'>Sai email hoặc mật khẩu!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <div class="auth-container">
        <h2>Đăng Nhập</h2>

        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit">Đăng Nhập</button>
        </form>

        <p>Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
    </div>

</body>
</html>
