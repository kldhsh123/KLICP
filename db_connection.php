<?php
$servername = "localhost"; // 数据库服务器的地址
$username = "text"; // 你的数据库用户名
$password = "text"; // 你的数据库密码
$dbname = "text"; // 你想要连接的数据库名

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

?>
