<?php
// 引入 PHPMailer 的自动加载文件
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/Exception.php';
require 'PHPMailer/SMTP.php';

// 引入数据库连接文件，确保路径正确
require_once '../../../db_connection.php';

// 处理删除请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取要删除的行的编号
    $number = $_POST['number'];

    // 查询待审批的行的数据
    $query = "SELECT * FROM PendingSites WHERE number = $number";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // 确保从 PendingSites 表中正确获取 email
        $email = $row['email'];

        // 发送邮件给申请人
        try {
            // 实例化 PHPMailer 对象
            $mail = new PHPMailer(true);
            
            // 设置 SMTP 服务器配置
            $mail->isSMTP();
            $mail->Host = 'text';  // SMTP 服务器地址
            $mail->SMTPAuth = true;            // 启用 SMTP 认证
            $mail->Username = 'text'; // SMTP 用户名
            $mail->Password = 'text'; // SMTP 密码
            $mail->SMTPSecure = 'ssl';         // 使用 SSL 加密
            $mail->Port = 465;                 // SMTP 端口号，一般为 587 或 465
            
            // 设置发件人信息
            $mail->setFrom('text', '开朗虚拟ICP站点');
            
            // 设置收件人信息
            $mail->addAddress($email);
            
            // 设置邮件内容
            $mail->isHTML(true);
            $mail->Subject = '您的ICP请求已被拒绝';
            $mail->Body    = "尊敬的用户，很抱歉地通知您，您提交的站点申请未能通过审批。<br>原因包括但不限于: 未正确添加站底链接，站点无法访问，违规站点等。<br>请按要求修正后重新提交申请。<br>如有异议，请加群330316577或发送邮件至1022140881@qq.com以获取帮助<br>本邮件由自动系统发出，请不要直接回复。";
            
            // 发送邮件
            $mail->send();
            
            // 输出成功消息
            echo '站点审批已拒绝，并已发送拒绝邮件。';
        } catch (Exception $e) {
            // 输出错误消息
            echo '邮件发送失败: ' . $mail->ErrorInfo;
        }
    }

    // 从数据库中删除行
    $query = "DELETE FROM PendingSites WHERE number = $number";
    mysqli_query($conn, $query);
}

// 关闭数据库连接
mysqli_close($conn);
?>
