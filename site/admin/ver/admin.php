<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台管理</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn-group {
            margin-top: 10px;
            float: right;
        }

        .btn-danger, .btn-success {
            margin-right: 10px;
        }

        .no-sites {
            text-align: center;
            color: #666;
            margin-top: 50px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mb-4">待审批的站点</h2>
    <div class="btn-group">
        <a href="../../admin/" class="btn btn-primary">管理</a>
    </div>

    <?php
    // 引入数据库连接文件
    require_once '../../../db_connection.php';

    // 查询 PendingSites 表中的数据
    $query = "SELECT * FROM PendingSites";
    $result = mysqli_query($conn, $query);

    // 如果没有待审批的站点，则显示提示消息
    if (mysqli_num_rows($result) === 0) {
        echo '<p class="no-sites">当前没有待审批的站点。</p>';
    } else {
        echo '<table class="table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>编号</th>';
        echo '<th>站点名称</th>';
        echo '<th>地址</th>';
        echo '<th>描述</th>';
        echo '<th>所有者</th>';
        echo '<th>邮箱</th>';
        echo '<th>操作</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        // 遍历查询结果
        while ($row = mysqli_fetch_assoc($result)) {
            $number = $row['number'];
            $siteName = $row['siteName'];
            $address = $row['address'];
            $description = $row['description'];
            $owner = $row['owner'];
            $email = $row['email'];

            // 显示每行的数据和操作按钮
            echo '<tr>';
            echo '<td>' . $number . '</td>';
            echo '<td>' . $siteName . '</td>';
            echo '<td>' . $address . '</td>';
            echo '<td>' . $description . '</td>';
            echo '<td>' . $owner . '</td>';
            echo '<td>' . $email . '</td>';
            echo '<td class="btn-group">';
            echo '<button class="btn btn-danger" onclick="deleteRow(' . $number . ')">未通过</button>';
            echo '<button class="btn btn-success" onclick="approveRow(' . $number . ')">通过</button>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }

    // 关闭数据库连接
    mysqli_close($conn);
    ?>
</div>

<script>
    // 删除行的函数
    function deleteRow(number) {
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
        xhr.send("number=" + number);
    }

    // 通过行的函数
    function approveRow(number) {
        // 发送 AJAX 请求来通过行
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "approve.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // 显示来自服务器的响应
                alert(xhr.responseText);
                // 刷新页面
                location.reload();
            }
        };
        xhr.send("number=" + number);
    }
</script>

</body>
</html>
