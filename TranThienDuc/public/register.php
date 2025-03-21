<?php
require __DIR__ . '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maSV = $_POST['maSV'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Mã hóa mật khẩu

    // Xử lý upload ảnh
    $avatar = "default.png"; // Ảnh mặc định
    if (!empty($_FILES['avatar']['name'])) {
        $targetDir = "../uploads/";
        $avatar = time() . "_" . basename($_FILES["avatar"]["name"]);
        $targetFile = $targetDir . $avatar;
        move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFile);
    }

    // Kiểm tra email hoặc MaSV đã tồn tại chưa
    $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ? OR MaSV = ?");
    $stmt->execute([$email, $maSV]);
    if ($stmt->rowCount() > 0) {
        echo "Email hoặc Mã Sinh Viên đã tồn tại!";
    } else {
        // Thêm user vào database
        $query = "INSERT INTO Users (MaSV, fullname, email, password, avatar) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$maSV, $fullname, $email, $password, $avatar]);

        echo "Đăng ký thành công! <a href='login.php'>Đăng nhập</a>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <!-- Thanh điều hướng -->
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Sinh Viên</a></li>
            <li><a href="hocphan.php">Học Phần</a></li>
            <li><a href="dangky.php">Đăng Ký</a></li>
            <li><a href="login.php">Đăng Nhập</a></li>
        </ul>
    </nav>

    <div class="form-container">
        <h2>Đăng Ký</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="maSV" placeholder="Mã Sinh Viên" required>
            <input type="text" name="fullname" placeholder="Họ và Tên" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <input type="file" name="avatar">
            <button type="submit">Đăng Ký</button>
        </form>
        <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
    </div>

</body>
</html>
