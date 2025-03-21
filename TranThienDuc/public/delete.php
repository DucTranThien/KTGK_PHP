<?php
require __DIR__ . '/../config/db.php';


if (isset($_GET['id'])) {
    $maSV = $_GET['id'];
    $query = "DELETE FROM SinhVien WHERE MaSV = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$maSV]);
}

header("Location: index.php");
?>
