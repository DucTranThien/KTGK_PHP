<?php
require __DIR__ . '/../config/db.php';

if (isset($_GET['id'])) {
    $maSV = $_GET['id'];
    $stmt = $conn->prepare("SELECT sv.*, nh.TenNganh FROM SinhVien sv 
                            LEFT JOIN NganhHoc nh ON sv.MaNganh = nh.MaNganh
                            WHERE sv.MaSV = ?");
    $stmt->execute([$maSV]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$student) {
    echo "<h2>Không tìm thấy sinh viên!</h2>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Sinh Viên</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav class="navbar">
    <ul>
        <li><a href="index.php">Sinh Viên</a></li>
        <li><a href="hocphan.php">Học Phần</a></li>
        <li><a href="dangky.php">Đăng Ký</a></li>
        <li><a href="login.php">Đăng Nhập</a></li>
    </ul>
</nav>

<div class="detail-container">
    <h1>Chi Tiết Sinh Viên</h1>
    
    <img class="avatar" src="../uploads/<?= htmlspecialchars($student['Hinh']) ?>" alt="Ảnh sinh viên">

    <p><strong>Mã SV:</strong> <?= htmlspecialchars($student['MaSV']) ?></p>
    <p><strong>Họ Tên:</strong> <?= htmlspecialchars($student['HoTen']) ?></p>
    <p><strong>Giới Tính:</strong> <?= htmlspecialchars($student['GioiTinh']) ?></p>
    <p><strong>Ngày Sinh:</strong> <?= date('d/m/Y', strtotime($student['NgaySinh'])) ?></p>
    <p><strong>Ngành:</strong> <?= htmlspecialchars($student['TenNganh']) ?></p>

    <a href="index.php" class="button">← Quay lại</a>
</div>

</body>
</html>
