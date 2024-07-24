<?php
// 引入数据库连接文件
require_once '../../db_connection.php';

// 处理删除请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取要删除的行的 ID
    $id = $_POST['id'];

    // 从数据库中删除行
    $query = "DELETE FROM ApprovedSites WHERE id = $id"; // 使用站点的 ID 来删除行
    mysqli_query($conn, $query);
}

// 关闭数据库连接
mysqli_close($conn);
?>
