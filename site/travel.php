<?php
// 引入数据库连接文件
require_once '../db_connection.php';

// 查询 ApprovedSites 表中的数据，并随机选择一行记录
$query = "SELECT * FROM ApprovedSites ORDER BY RAND() LIMIT 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// 关闭数据库连接
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>开朗ICP备旅行</title>
    <link rel="stylesheet" href="css/mdui.min.css">
    <link rel="stylesheet" type="text/css" href="css/bj.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 400px;
            text-align: center;
        }

        h1 {
            margin-top: 0;
            color: #333;
        }

        h2 {
            margin-top: 20px;
            color: #666;
        }

        p {
            margin: 10px 0;
            color: #777;
        }

        .link {
            margin-top: 20px;
        }

        .link a {
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .link a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>开朗ICP备旅行</h1>
        <h2>站点名称：<?php echo $row['siteName']; ?></h2>
        <p>编号：<?php echo $row['number']; ?></p>
        <p>地址：<?php echo $row['address']; ?></p>
        <p>描述：<?php echo $row['description']; ?></p>
        <p>所有者：<?php echo $row['owner']; ?></p>
        <p>邮箱：<?php echo $row['email']; ?></p>
        <p>验证通过时间：<?php echo $row['verification_date']; ?></p>
        <p>5秒后将重定向至该网站地址...</p>
        <!-- 添加立刻前往按钮 -->
        <div class="link">
            <button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-blue-700" onclick="goToWebsite()">立刻前往</button>
        </div>
    </div>

    <script>
        // 获取网站地址
        var address = "<?php echo $row['address']; ?>";
        if (!address.startsWith("http://") && !address.startsWith("https://")) {
            address = "http://" + address;
        }

        // 立刻前往网站
        function goToWebsite() {
            window.location.href = address;
        }

        // 5秒后重定向至网站地址
        setTimeout(function() {
            window.location.href = address;
        }, 5000);
    </script>
</body>
</html>