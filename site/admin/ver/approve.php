<?php
// 引入 PHPMailer 的自动加载文件
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/Exception.php';
require 'PHPMailer/SMTP.php';

// 引入数据库连接文件，确保路径正确
require_once '../../../db_connection.php';

// 处理通过请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取要处理的行的编号
    $number = $_POST['number'];

    // 获取当前日期
    $currentDate = date("Y-m-d");

    // 设置 SMTP 配置
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = 'text'; // SMTP 服务器地址
    $mail->SMTPAuth = true; // 启用 SMTP 认证
    $mail->Username = 'text'; // SMTP 用户名
    $mail->Password = 'text'; // SMTP 密码
    $mail->SMTPSecure = 'ssl'; // 使用 SSL 加密
    $mail->Port = 465; // SMTP 端口号

    try {
        // 将当前日期插入到 ApprovedSites 表中的 verification_date 字段
        $query = "UPDATE ApprovedSites SET verification_date = '$currentDate' WHERE number = '$number'";
        $result = mysqli_query($conn, $query);

        // 检查是否更新成功
        if ($result) {
            // 查询待审批的行的数据
            $query = "SELECT * FROM PendingSites WHERE number = $number";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);

                // 确保从 PendingSites 表中正确获取 email 和 ip_address
                $email = $row['email'];
                $ipAddress = mysqli_real_escape_string($conn, $row['ip_address']); // 新增字段

                // 将行插入到 ApprovedSites 表中
                $siteName = mysqli_real_escape_string($conn, $row['siteName']);
                $address = mysqli_real_escape_string($conn, $row['address']);
                $description = mysqli_real_escape_string($conn, $row['description']);
                $owner = mysqli_real_escape_string($conn, $row['owner']);

                $query = "INSERT INTO ApprovedSites (number, siteName, address, description, owner, email, ip_address, verification_date) VALUES ('$number', '$siteName', '$address', '$description', '$owner', '$email', '$ipAddress', '$currentDate')";
                $result = mysqli_query($conn, $query);

                if ($result) {
                    // 删除 PendingSites 表中的行
                    $query = "DELETE FROM PendingSites WHERE number = $number";
                    $result = mysqli_query($conn, $query);

                    // 设置发件人信息
                    $mail->setFrom('text', '开朗虚拟ICP站点');
                    // 设置收件人信息
                    $mail->addAddress($email);
                    // 设置邮件内容
                    $mail->isHTML(true);
                    $mail->Subject = '您的ICP请求已处理';
                    $mail->Body    = "尊敬的用户，您提交的站点申请已经通过审批。您的ICP备案号已被激活。<br>感谢您的使用。<br>本邮件由自动系统发出，请不要直接回复。";
                    
                    // 发送邮件
                    $mail->send();
                    
                    // 输出成功消息
                    echo '站点已通过，并且验证日期已更新为：' . $currentDate;
                } else {
                    // 输出失败消息
                    echo "通过站点失败，请重试！";
                }
            }
        }
    } catch (Exception $e) {
        // 输出错误消息
        echo '邮件发送失败: ' . $mail->ErrorInfo;
    }
}

// 关闭数据库连接
mysqli_close($conn);
?>
