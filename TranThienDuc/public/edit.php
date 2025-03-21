<?php
require __DIR__ . '/../config/db.php';

if (isset($_GET['id'])) {
    $maSV = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM SinhVien WHERE MaSV = ?");
    $stmt->execute([$maSV]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        die("Không tìm thấy sinh viên!");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hoTen = $_POST['HoTen'];
    $gioiTinh = $_POST['GioiTinh'];
    $ngaySinh = $_POST['NgaySinh'];
    $maNganh = $_POST['MaNganh'];

    // Xử lý upload ảnh
    $hinh = $student['Hinh']; // Giữ ảnh cũ nếu không chọn ảnh mới
    if (!empty($_FILES['Hinh']['name'])) {
        $targetDir = "../uploads/";
        $hinh = time() . "_" . basename($_FILES["Hinh"]["name"]);
        $targetFile = $targetDir . $hinh;
        move_uploaded_file($_FILES["Hinh"]["tmp_name"], $targetFile);
    }

    // Cập nhật dữ liệu sinh viên
    $query = "UPDATE SinhVien SET HoTen=?, GioiTinh=?, NgaySinh=?, Hinh=?, MaNganh=? WHERE MaSV=?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$hoTen, $gioiTinh, $ngaySinh, $hinh, $maNganh, $maSV]);

    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh Sửa Sinh Viên</title>
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

    <h1>Chỉnh Sửa Thông Tin Sinh Viên</h1>

    <form method="POST" enctype="multipart/form-data">
        <label for="HoTen">Họ và Tên</label>
        <input type="text" name="HoTen" id="HoTen" value="<?= $student['HoTen']; ?>" required>

        <label for="GioiTinh">Giới Tính</label>
        <select name="GioiTinh" id="GioiTinh">
            <option value="Nam" <?= $student['GioiTinh'] == 'Nam' ? 'selected' : ''; ?>>Nam</option>
            <option value="Nữ" <?= $student['GioiTinh'] == 'Nữ' ? 'selected' : ''; ?>>Nữ</option>
        </select>

        <label for="NgaySinh">Ngày Sinh</label>
        <input type="date" name="NgaySinh" id="NgaySinh" value="<?= $student['NgaySinh']; ?>">

        <label for="Hinh">Ảnh Đại Diện</label>
        <img src="../uploads/<?= $student['Hinh']; ?>" alt="Avatar" width="100" height="100" style="border-radius: 50%; margin-bottom: 10px;">
        <input type="file" name="Hinh" id="Hinh" accept="image/*">

        <label for="MaNganh">Ngành Học</label>
<select name="MaNganh" id="MaNganh" required>
    <?php
    $stmt = $conn->prepare("SELECT * FROM NganhHoc");
    $stmt->execute();
    $nganhList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($nganhList as $nganh) {
        echo "<option value='{$nganh['MaNganh']}'>{$nganh['TenNganh']} ({$nganh['MaNganh']})</option>";
    }
    ?>
</select>


        <button type="submit">Cập Nhật</button>
    </form>

</body>
</html>
