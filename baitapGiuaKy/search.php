search.php
<?php
// Kết nối đến cơ sở dữ liệu MySQL
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "qlsv_dohaduyen"; 

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Khai báo biến để chứa từ khóa tìm kiếm
$search_name = '';
$search_hometown = '';

// Kiểm tra nếu có yêu cầu tìm kiếm
if (isset($_POST['search'])) {
    $search_name = $_POST['search_name'];
    $search_hometown = $_POST['search_hometown'];
}

// Tạo câu truy vấn tìm kiếm
$sql = "SELECT * FROM table_student WHERE fullname LIKE '%$search_name%' AND hometown LIKE '%$search_hometown%'";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm kiếm Sinh viên</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Danh sách Sinh viên - Tìm kiếm</h1>

<!-- Form tìm kiếm -->
<form method="POST" action="">
    <input type="text" name="search_name" placeholder="Tìm theo tên" value="<?php echo $search_name; ?>">
    <input type="text" name="search_hometown" placeholder="Tìm theo quê quán" value="<?php echo $search_hometown; ?>">
    <button type="submit" name="search">Tìm kiếm</button>
</form>

<!-- Nút quay lại danh sách sinh viên -->
<a href="index.php" class="btn-back">Quay lại danh sách sinh viên</a>

<?php
// Kiểm tra nếu có dữ liệu sinh viên
if ($result->num_rows > 0) {
    echo "<table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Họ và tên</th>
                    <th>Ngày sinh</th>
                    <th>Giới tính</th>
                    <th>Quê quán</th>
                    <th>Trình độ học vấn</th>
                    <th>Nhóm</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>";

    $index = 1;
    // Lặp qua các sinh viên và hiển thị thông tin
    while($row = $result->fetch_assoc()) {
        // Chuyển đổi giá trị giới tính
        $gender = ($row['gender'] == 1) ? 'Nam' : 'Nữ';

        // Chuyển đổi trình độ học vấn
        switch ($row['level']) {
            case 0:
                $level = "Cử nhân";
                break;
            case 1:
                $level = "Thạc sĩ";
                break;
            case 2:
                $level = "Tiến sĩ";
                break;
            default:
                $level = "Khác";
                break;
        }

        // Hiển thị dữ liệu sinh viên
        echo "<tr>
                <td>" . $index++ . "</td>
                <td>" . htmlspecialchars($row['fullname']) . "</td>
                <td>" . htmlspecialchars($row['dob']) . "</td>
                <td>" . htmlspecialchars($gender) . "</td>
                <td>" . htmlspecialchars($row['hometown']) . "</td>
                <td>" . htmlspecialchars($level) . "</td>
                <td>" . htmlspecialchars($row['group_id']) . "</td>
                <td>
                    <a href='edit_student.php?id=" . $row['id'] . "' class='button edit'>Sửa</a>
                    <a href='?delete_id=" . $row['id'] . "' class='button delete' onclick='return confirm(\"Bạn có chắc muốn xóa sinh viên này không?\")'>Xóa</a>
                </td>
              </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p class='no-data'>Không có sinh viên nào phù hợp với yêu cầu tìm kiếm!</p>";
    // Thực thi câu lệnh
    if ($stmt->execute()) {
        echo "<script>alert('Thêm sinh viên thành công!'); window.location.href = 'index.php';</script>";
        exit();
    } else {
        echo "Lỗi: " . $conn->error;
    }

    $stmt->close(); // Đóng statement
}

// Đóng kết nối
$conn->close();
?>

</body>
</html>