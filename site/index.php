<?php
// 连接数据库
include '../db_connection.php';

// 初始化变量
$site = [];
$pending = false;

// 如果有GET请求
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
  // 获取查询编号
  $id = $_GET['id'];

  // 创建预处理查询，防止SQL注入攻击
  $stmt = $conn->prepare("SELECT * FROM ApprovedSites WHERE number = ?");
  $stmt->bind_param("s", $id);

  // 执行查询并获取结果
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $site = $result->fetch_assoc();
  } else {
    // 如果在ApprovedSites表单中没有找到编号，尝试在PendingSites表单中查询
    $stmt = $conn->prepare("SELECT * FROM PendingSites WHERE number = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      // 设置标志以显示提示信息
      $pending = true;
    }
  }

  // 关闭连接
  $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>开朗ICP备</title>
  <meta charset="utf-8">
  <meta name="keywords" content="icp备案,网站装饰,虚拟备案,虚拟icp备案,备案,icp">
  <link rel="stylesheet" href="css/mdui.min.css">
  <script src="js/mdui.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/bj.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
    }
    .mdui-toolbar {
      padding: 16px 24px;
    }
    .title-container {
      width: 30%;
    }
    .content-container {
      margin: 20px auto;
      max-width: 600px;
      background-color: #ffffff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .mdui-textfield {
      margin-bottom: 16px;
    }
    .mdui-btn {
      margin-right: 8px;
    }
    .result-container {
      margin-top: 20px;
    }
    .result-info {
      margin-bottom: 16px;
    }
  </style>
</head>
<!DOCTYPE html>
<html>
<head>
  <title>开朗ICP备</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/mdui.min.css">
  <script src="js/mdui.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/bj.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
    }
    .mdui-toolbar {
      padding: 16px 24px;
    }
    .title-container {
      width: 30%;
    }
    .content-container {
      margin: 20px auto;
      max-width: 600px;
      background-color: #ffffff;
      padding: 20px; 
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .mdui-textfield {
      margin-bottom: 16px;
    }
    .mdui-btn {
      margin-right: 8px;
    }
    .result-container {
      margin-top: 20px;
    }
    .result-info {
      margin-bottom: 16px;
    }
  </style>
</head>
<body>
<div class="mdui-toolbar mdui-color-blue-700">
  <div class="title-container">
    <span class="mdui-typo-title mdui-text-color-white">开朗ICP备</span>
  </div>
</div>
<div class="content-container">
  <h3>公告：</h3>
  <p>举报违规站点或无效站点请加Q群或发送邮件至1022140881@qq.com</p>
</div>
<div class="content-container">
  <h1 class="mdui-text-color-blue-700">开朗ICP备</h1>
  <form action="index.php" method="GET">
    <div class="mdui-textfield">
      <input class="mdui-textfield-input" type="text" name="id" placeholder="输入编号" />
    </div>
    <input type="submit" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-teal" value="查询">
    <a href="travel.php" target="_blank" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-blue-700">开朗旅行</a>
    <a href="join.php" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-pink">加入ICP备</a>
    <a href="http://qm.qq.com/cgi-bin/qm/qr?_wv=1027&k=vFOh8aTsWPbQsv7ckU8-Rih9w-N6PFom&authKey=uzWNwmUSGD32aKVGINOAcDrx0BC2I7uPz1mIMsM%2B5yygh8FIrDh7DMkRFQV2j4x8&noverify=0&group_code=330316577" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-purple">加入交流群</a>
    <a href="about.html" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-teal">介绍</a>
  </form>
  <?php if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])): ?>
    <div class="result-container">
      <h2 class="mdui-text-color-blue-700">查询结果：</h2>
      <?php if (!empty($site)): ?>
        <div class="result-info">
          <p>编号：<?php echo $site['number']; ?></p>
          <p>站点名称：<?php echo $site['siteName']; ?></p>
          <p>地址：<?php echo $site['address']; ?></p>
          <p>介绍：<?php echo $site['description']; ?></p>
          <p>所有者：<?php echo $site['owner']; ?></p> 
          <p>联系邮箱：<?php echo $site['email']; ?></p>
          <p>验证通过时间：<?php echo $site['verification_date']; ?></p>
        </div>
      <?php elseif ($pending): ?>
        <p>当前编号正在审核，您可以加群330316577催促！</p>
      <?php else: ?>
        <p>没有找到匹配的站点信息。</p>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
