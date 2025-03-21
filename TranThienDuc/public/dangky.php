<?php
require __DIR__ . '/../config/db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$maSV = $_SESSION['user_id']; // Lấy mã sinh viên

// Lấy thông tin đăng ký của sinh viên
$query = "SELECT dk.MaDK, ctdk.MaHP, hp.TenHP, hp.SoTinChi 
          FROM DangKy dk
          JOIN ChiTietDangKy ctdk ON dk.MaDK = ctdk.MaDK
          JOIN HocPhan hp ON ctdk.MaHP = hp.MaHP
          WHERE dk.MaSV = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$maSV]);
$dangKyList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Xóa học phần đã đăng ký
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['MaHP'])) {
    $maHP = $_POST['MaHP'];

    $deleteQuery = "DELETE FROM ChiTietDangKy WHERE MaHP = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->execute([$maHP]);

    header("Location: dangky.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Ký Học Phần</title>
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

    <h1>Đăng Kí Học Phần</h1>
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
            <?php $totalCredits = 0; ?>
            <?php foreach ($dangKyList as $hocPhan) : ?>
                <tr>
                    <td><?= $hocPhan['MaHP']; ?></td>
                    <td><?= $hocPhan['TenHP']; ?></td>
                    <td><?= $hocPhan['SoTinChi']; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="MaHP" value="<?= $hocPhan['MaHP']; ?>">
                            <button type="submit" class="button red">Xóa</button>
                        </form>
                    </td>
                </tr>
                <?php $totalCredits += $hocPhan['SoTinChi']; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p><strong>Số học phần:</strong> <?= count($dangKyList); ?></p>
    <p><strong>Tổng số tín chỉ:</strong> <?= $totalCredits; ?></p>

</body>
</html>
