<?php
// 允许访问的域名列表
$allowed_domains = array('icp.kldhsh.top');

// 获取当前请求的域名
$request_domain = $_SERVER['HTTP_HOST'];

// 检查当前请求的域名是否在允许列表中
if (!in_array($request_domain, $allowed_domains)) {
    header('HTTP/1.0 403 Forbidden');
    die('');
}

// 这里是你的数据库连接代码
$servername = "localhost"; // 数据库服务器的地址
$username = "icp_kldhsh_top"; // 你的数据库用户名
$password = "kldhsh123/Aa123412345./icp.kldhsh.top"; // 你的数据库密码
$dbname = "icp_kldhsh_top"; // 你想要连接的数据库名

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

?>
