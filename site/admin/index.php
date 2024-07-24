<!DOCTYPE html>
<html>
<head>
    <title>后台管理</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/mdui.min.css">
    <script src="../js/mdui.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* 添加自定义样式 */
        .pagination {
            margin-top: 10px;
            display: inline-block;
        }

        .pagination a {
            margin-right: 5px;
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            text-decoration: none;
            color: #333;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #f0f0f0;
        }

        /* 美化跳转表单 */
        .jump-form {
            margin-top: 10px;
            display: flex;
            align-items: center;
        }

        .jump-input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            width: 60px;
        }

        .jump-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 3px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-left: 5px;
        }

        .jump-btn:hover {
            background-color: #0056b3;
        }

        /* 显示总页数 */
        .total-pages {
            margin-top: 10px;
            color: #666;
            font-size: 14px;
        }
        
        /* 美化表格 */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<div class="mdui-container">
    <div class="mdui-card mdui-m-y-3">
        <div class="mdui-card-content mdui-p-a-2">
            <h2 style="display: inline-block;">已批准的站点</h2>
            <a href="./ver/admin.php" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-indigo" style="float: right; margin-top: 10px;">验证</a>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>编号</th>
                        <th>站点名称</th>
                        <th>地址</th>
                        <th>描述</th>
                        <th>所有者</th>
                        <th>邮箱</th>
                        <th>验证日期</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // 引入数据库连接文件
                    require_once '../../db_connection.php';

                    // 分页相关参数
                    $perPage = isset($_GET['perPage']) ? $_GET['perPage'] : 10; // 每页显示的条目数
                    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1; // 当前页码
                    $start = ($currentPage - 1) * $perPage; // 数据查询的起始索引

                    // 查询 ApprovedSites 表中的数据并按照 id 字段从大到小排序
                    $query = "SELECT * FROM ApprovedSites ORDER BY id DESC LIMIT $start, $perPage";
                    $result = mysqli_query($conn, $query);

                    // 遍历查询结果
                    while ($row = mysqli_fetch_assoc($result)) {
                        $id = $row['id'];
                        $number = $row['number'];
                        $siteName = $row['siteName'];
                        $address = $row['address'];
                        $description = $row['description'];
                        $owner = $row['owner'];
                        $email = $row['email'];
                        $verificationDate = $row['verification_date'];

                        // 显示每行的数据和删除按钮
                        echo '<tr>';
                        echo '<td>' . $id . '</td>';
                        echo '<td>' . $number . '</td>';
                        echo '<td>' . $siteName . '</td>';
                        echo '<td>' . $address . '</td>';
                        echo '<td>' . $description . '</td>';
                        echo '<td>' . $owner . '</td>';
                        echo '<td>' . $email . '</td>';
                        echo '<td>' . $verificationDate . '</td>';
                        echo '<td>';
                        echo '<button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-red" onclick="deleteRow(' . $id . ')">删除</button>';
                        echo '</td>';
                        echo '</tr>';
                    }

                    // 计算总页数
                    $query = "SELECT COUNT(*) AS total FROM ApprovedSites";
                    $result = mysqli_query($conn, $query);
                    $row = mysqli_fetch_assoc($result);
                    $totalPages = ceil($row['total'] / $perPage);

                    // 显示分页链接和总页数
                    echo '<div class="pagination">';
                    for ($i = 1; $i <= $totalPages; $i++) {
                        echo '<a href="?page=' . $i . '&perPage=' . $perPage . '">' . $i . '</a>';
                    }
                    echo '</div>';
                    echo '<div class="total-pages">共 ' . $totalPages . ' 页</div>';

                    // 关闭数据库连接
                    mysqli_close($conn);
                    ?>
                    
                    <!-- 添加美化后的跳转表单 -->
                    <form action="" method="GET" class="jump-form">
                        <input type="text" name="page" placeholder="跳转到页码" class="jump-input">
                        <input type="hidden" name="perPage" value="<?php echo $perPage; ?>">
                        <button type="submit" class="jump-btn">跳转</button>
                    </form>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // 删除行的函数
    function deleteRow(id) {
    // 发送 AJAX 请求来删除行
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "delete.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // 刷新页面
            location.reload();
        }
    };
    xhr.send("id=" + id); // 传递站点的 ID 而不是编号
   }

</script>

</body>
</html>
