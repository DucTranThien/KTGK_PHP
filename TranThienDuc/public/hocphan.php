<?php
require __DIR__ . '/../config/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


$maSV = $_SESSION['user_id']; // Lấy mã sinh viên từ session

// Lấy danh sách học phần
$query = "SELECT * FROM HocPhan";
$stmt = $conn->prepare($query);
$stmt->execute();
$hocPhanList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Xử lý đăng ký học phần
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['MaHP'])) {
    $maHP = $_POST['MaHP'];

    // Kiểm tra xem sinh viên đã có đăng ký chưa
    $checkQuery = "SELECT MaDK FROM DangKy WHERE MaSV = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->execute([$maSV]);
    $dangKy = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dangKy) {
        // Nếu chưa có, tạo mới
        $insertDK = "INSERT INTO DangKy (MaSV, NgayDK) VALUES (?, CURDATE())";
        $stmt = $conn->prepare($insertDK);
        $stmt->execute([$maSV]);

        $maDK = $conn->lastInsertId();
    } else {
        $maDK = $dangKy['MaDK'];
    }

    // Kiểm tra xem học phần đã được đăng ký chưa
    $checkHP = "SELECT * FROM ChiTietDangKy WHERE MaDK = ? AND MaHP = ?";
    $stmt = $conn->prepare($checkHP);
    $stmt->execute([$maDK, $maHP]);

    if ($stmt->rowCount() == 0) {
        // Thêm vào ChiTietDangKy
        $insertCTDK = "INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (?, ?)";
        $stmt = $conn->prepare($insertCTDK);
        $stmt->execute([$maDK, $maHP]);
        header("Location: dangky.php");
        exit;
    } else {
        echo "Bạn đã đăng ký học phần này!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Học Phần</title>
    <link rel="stylesheet" href="../css/style2.css">
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

    <h1>DANH SÁCH HỌC PHẦN</h1>
    <table>
        <thead>
            <tr>
                <th>Mã Học Phần</th>
                <th>Tên Học Phần</th>
                <th>Số Tín Chỉ</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($hocPhanList as $hocPhan) : ?>
                <tr>
                    <td><?= $hocPhan['MaHP']; ?></td>
                    <td><?= $hocPhan['TenHP']; ?></td>
                    <td><?= $hocPhan['SoTinChi']; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="MaHP" value="<?= $hocPhan['MaHP']; ?>">
                            <button type="submit" class="button">Đăng Ký</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
