<?php
// Kết nối đến cơ sở dữ liệu MySQL
$servername = "localhost"; // Địa chỉ máy chủ MySQL
$username = "root"; // Tên đăng nhập MySQL
$password = ""; // Mật khẩu MySQL
$dbname = "qlsv_dohaduyen"; // Tên cơ sở dữ liệu

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra khi người dùng nhấn nút "Lưu"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $hometown = $_POST['hometown'];
    $level = $_POST['level'];
    $group_id = $_POST['group_id'];

    // Sử dụng prepared statement để lưu thông tin sinh viên vào cơ sở dữ liệu
    $stmt = $conn->prepare("INSERT INTO table_student (fullname, dob, gender, hometown, level, group_id) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisii", $fullname, $dob, $gender, $hometown, $level, $group_id);

    // Thực thi câu lệnh
    if ($stmt->execute()) {
        echo "<script>alert('Thêm sinh viên thành công!'); window.location.href = 'index.php';</script>";
        exit();
    } else {
        echo "Lỗi: " . $stmt->error;
    }

    $stmt->close(); // Đóng statement
}

// Đóng kết nối
$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sinh viên</title>
    <link rel="stylesheet" href="style2.css"> <!-- Liên kết đến file CSS -->
</head>
<body>

    <h1>Thêm Sinh viên</h1>

    <form action="them.php" method="POST">
        <!-- Trường nhập Họ và tên -->
        <label for="fullname">Họ và tên:</label>
        <input type="text" id="fullname" name="fullname" required>

        <!-- Trường nhập Ngày sinh -->
        <label for="dob">Ngày sinh:</label>
        <input type="date" id="dob" name="dob" required>

        <!-- Trường chọn Giới tính -->
        <label>Giới tính:</label><br>
        <input type="radio" id="male" name="gender" value="1" required> Nam
        <input type="radio" id="female" name="gender" value="0" required> Nữ

        <!-- Trường nhập Quê quán -->
        <label for="hometown">Quê quán:</label>
        <input type="text" id="hometown" name="hometown" required>

        <!-- Trường chọn Trình độ học vấn -->
        <label for="level">Trình độ học vấn:</label>
        <select id="level" name="level" required>
            <option value="0">Cử nhân</option>
            <option value="1">Thạc sĩ</option>
            <option value="2">Tiến sĩ</option>
            <option value="3">Khác</option>
        </select>

        <!-- Trường nhập Nhóm -->
        <label for="group_id">Nhóm:</label>
        <input type="number" id="group_id" name="group_id" required>

        <!-- Nút gửi form -->
        <button type="submit" class="button">Lưu</button>
    </form>

</body>
</html>