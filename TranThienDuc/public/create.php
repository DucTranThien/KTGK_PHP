<?php
require __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $maSV = $_POST['MaSV'];
    $hoTen = $_POST['HoTen'];
    $gioiTinh = $_POST['GioiTinh'];
    $ngaySinh = $_POST['NgaySinh'];
    $maNganh = $_POST['MaNganh'];

    // Xử lý upload ảnh
    $hinh = "default.png"; // Ảnh mặc định nếu không chọn
    if (!empty($_FILES['Hinh']['name'])) {
        $targetDir = "../uploads/";
        $hinh = time() . "_" . basename($_FILES["Hinh"]["name"]);
        $targetFile = $targetDir . $hinh;
        move_uploaded_file($_FILES["Hinh"]["tmp_name"], $targetFile);
    }

    // Thêm dữ liệu vào bảng SinhVien
    $query = "INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$maSV, $hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh]);

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sinh Viên</title>
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
        <h1>Thêm Sinh Viên</h1>

        <form method="POST" enctype="multipart/form-data">
            <label for="MaSV">Mã Sinh Viên</label>
            <input type="text" name="MaSV" id="MaSV" required>

            <label for="HoTen">Họ và Tên</label>
            <input type="text" name="HoTen" id="HoTen" required>

            <label for="GioiTinh">Giới Tính</label>
            <select name="GioiTinh" id="GioiTinh">
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
            </select>

            <label for="NgaySinh">Ngày Sinh</label>
            <input type="date" name="NgaySinh" id="NgaySinh">

            <label for="Hinh">Ảnh Đại Diện</label>
            <input type="file" name="Hinh" id="Hinh" accept="image/*">

            <label for="MaNganh">Mã Ngành</label>
            <input type="text" name="MaNganh" id="MaNganh">

            <button type="submit">Thêm</button>
        </form>
    </div>

</body>
</html>
