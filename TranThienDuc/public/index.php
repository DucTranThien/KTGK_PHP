<?php
require __DIR__ . '/../config/db.php';

$query = "SELECT sv.*, nh.TenNganh FROM SinhVien sv 
          LEFT JOIN NganhHoc nh ON sv.MaNganh = nh.MaNganh";
$stmt = $conn->prepare($query);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sinh viên</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <!-- Thanh điều hướng -->
    <nav class="navbar">
    <ul>
        <li><a href="index.php">Sinh Viên</a></li>
        <li><a href="hocphan.php">Học Phần</a></li>
        <li><a href="dangky.php">Đăng Ký</a></li>

        <?php if (isset($_SESSION['user_id'])): ?>
            <li>
                <img src="../uploads/<?= $_SESSION['avatar'] ?>" alt="Avatar" width="40" height="40" style="border-radius: 50%;">
                <a href="#">👤 <?= $_SESSION['fullname']; ?></a>
            </li>
            <li><a href="logout.php">Đăng Xuất</a></li>
        <?php else: ?>
            <li><a href="login.php">Đăng Nhập</a></li>
        <?php endif; ?>
    </ul>
</nav>



    <h1>TRANG SINH VIÊN</h1>
    <a href="create.php" class="button">Thêm sinh viên</a>

    <table>
        <thead>
            <tr>
                <th>Mã SV</th>
                <th>Họ Tên</th>
                <th>Giới Tính</th>
                <th>Ngày Sinh</th>
                <th>Hình</th>
                <th>Ngành</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student) : ?>
                <tr>
                    <td><?= $student['MaSV']; ?></td>
                    <td><?= $student['HoTen']; ?></td>
                    <td><?= $student['GioiTinh']; ?></td>
                    <td><?= date('d/m/Y', strtotime($student['NgaySinh'])); ?></td>
                    <td>
    <img src="../uploads/<?= htmlspecialchars($student['Hinh']); ?>" alt="Ảnh SV" class="student-img">
</td>

                    <td><?= $student['TenNganh']; ?></td>
                    <td>
                        <a href="edit.php?id=<?= $student['MaSV']; ?>">Sửa</a> |
                        <a href="detail.php?id=<?= $student['MaSV']; ?>">Chi tiết</a> |
                        <a href="delete.php?id=<?= $student['MaSV']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
