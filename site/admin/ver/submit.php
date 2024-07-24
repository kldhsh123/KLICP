<?php
// 引入数据库连接文件
require_once '../../../db_connection.php';

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取表单数据
    $number = $_POST['number'];
    $siteName = $_POST['siteName'];
    $address = $_POST['address'];
    $description = $_POST['description'];
    $owner = $_POST['owner'];
    $email = $_POST['email'];

    // 在这里对表单数据进行验证，可以使用正则表达式或其他验证方法，确保数据的有效性

    // 将数据插入 ApprovedSites 表中
    $query = "INSERT INTO ApprovedSites (number, siteName, address, description, owner, email) VALUES ('$number', '$siteName', '$address', '$description', '$owner', '$email')";
    mysqli_query($conn, $query);

    // 删除 PendingSites 表中的数据
    $query = "DELETE FROM PendingSites WHERE number = $number";
    mysqli_query($conn, $query);
}

// 关闭数据库连接
mysqli_close($conn);

// 重定向到后台管理页面
header("Location: admin.php");
exit();
?>
