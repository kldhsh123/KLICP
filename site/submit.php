<?php
// 连接数据库
include '../db_connection.php';

// 初始化错误消息
$error_messages = [];

// 检查请求是否来自指定的URL
$referer = $_SERVER['HTTP_REFERER'];
$allowed_url = 'https://这是域名/join.php';
if($referer !== $allowed_url) {
  $error_messages[] = "错误：非法的请求来源！";
}

// 检索表单数据
$number = $_POST['number'];
$siteName = $_POST['siteName'];
$address = $_POST['address'];
$description = $_POST['description'];
$owner = $_POST['owner'];
$email = $_POST['email'];

// 检查表单数据是否为空
if (empty($number) || empty($siteName) || empty($address) || empty($description) || empty($owner) || empty($email)) {
  $error_messages[] = "错误：请确保所有字段均已填写！";
}

// 验证编号格式
if (!preg_match('/^[1-9]\d{5}$/', $number)) {
  $error_messages[] = "错误：编号必须是开头不为0的6位数字！";
}

// 验证 email 是否包含 @ 符号
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $error_messages[] = "错误：请输入有效的电子邮件地址！";
}

// 验证 address 是否包含 . 符号
if (strpos($address, '.') === false) {
  $error_messages[] = "错误：请输入有效网站的地址！";
}

// 检查编号是否已存在于PendingSites表中
$checkNumberStmtPending = $conn->prepare("SELECT COUNT(*) FROM PendingSites WHERE number = ?");
$checkNumberStmtPending->bind_param("s", $number);
$checkNumberStmtPending->execute();
$checkNumberStmtPending->bind_result($existingNumberCountPending);
$checkNumberStmtPending->fetch();
$checkNumberStmtPending->close();

// 检查编号是否已存在于ApprovedSites表中
$checkNumberStmtApproved = $conn->prepare("SELECT COUNT(*) FROM ApprovedSites WHERE number = ?");
$checkNumberStmtApproved->bind_param("s", $number);
$checkNumberStmtApproved->execute();
$checkNumberStmtApproved->bind_result($existingNumberCountApproved);
$checkNumberStmtApproved->fetch();
$checkNumberStmtApproved->close();

// 如果编号已存在于任一表中，则显示错误消息
if ($existingNumberCountPending > 0 || $existingNumberCountApproved > 0) {
  $error_messages[] = "错误：编号已存在验证队列或已验证名单，请重新输入并确认ID！";
}

// 检查请求频率限制的缓存文件
$cache_file = __DIR__ . '/request_cache.txt';
$current_time = time();
$request_data = [];

// 加载缓存文件中的数据
if (file_exists($cache_file)) {
  $request_data = unserialize(file_get_contents($cache_file));
}

// 清理过期数据（5小时前的数据）
$limit_hours = 5;
$expired_time = $current_time - ($limit_hours * 3600);
foreach ($request_data as $key => $timestamp) {
  if ($timestamp < $expired_time) {
    unset($request_data[$key]);
  }
}

// 检查是否超过请求限制
$max_requests = 8;
if (count($request_data) >= $max_requests) {
  $error_messages[] = "错误：当前时间段接受的请求已到达上限，请等待5小时或加群寻求帮助！";
} else {
  // 添加当前请求的时间戳到数据中
  $request_data[] = $current_time;
  // 将更新后的数据写入缓存文件
  file_put_contents($cache_file, serialize($request_data));

  // 获取提交者的IP地址
  $ip_address = $_SERVER['REMOTE_ADDR'];

  // 检查PendingSites表中相同IP地址的数量
  $checkIpCountStmt = $conn->prepare("SELECT COUNT(*) FROM PendingSites WHERE ip_address = ?");
  $checkIpCountStmt->bind_param("s", $ip_address);
  $checkIpCountStmt->execute();
  $checkIpCountStmt->bind_result($ipCount);
  $checkIpCountStmt->fetch();
  $checkIpCountStmt->close();

  // 如果IP地址已经有三个以上的申请，则拒绝提交
  if ($ipCount >= 3) {
    $error_messages[] = "错误：您的提交已到达上限，在管理员审批后才能继续提交！";
  } else {
    // 如果没有其他错误消息，则将申请信息插入待审核站点表中
    if (empty($error_messages)) {
      $stmt = $conn->prepare("INSERT INTO PendingSites (number, siteName, address, description, owner, email, ip_address) VALUES (?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("sssssss", $number, $siteName, $address, $description, $owner, $email, $ip_address);

      // 检查语句执行是否成功
      if ($stmt->execute()) {
        $submit_message = "申请提交成功！请耐心等待，不要重复提交！";
      } else {
        $error_messages[] = "申请提交失败，请重试或加群进行反馈！";
      }

      $stmt->close();
    }
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="UTF-8">
<!-- 元数据和样式表链接 -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>申请提交结果</title>
<link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <!-- 主体内容 -->
  <div class="container">
    <!-- 结果标题 -->
    <h1>申请提交结果</h1>
    <!-- 成功或错误消息显示 -->
    <?php if(isset($submit_message)) echo "<p class='success-message'>$submit_message</p>"; ?>
    <?php foreach($error_messages as $error_message) echo "<p class='error-message'>$error_message</p>"; ?>
    <!-- 返回消息 -->
    <p class="redirect-message">页面将在5秒钟后返回主页面...</p>
  </div>
  <!-- JavaScript 脚本用于重定向 -->
  <script>
    setTimeout(function() {
      // 获取当前页面的根路径
      var rootUrl = window.location.protocol + "//" + window.location.host + "/";
      window.location.href = rootUrl;
    }, 5000); // 5000毫秒后跳转
  </script>
</body>
</html>
