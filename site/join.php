<!DOCTYPE html>
<html>
<head>
  <title>编号申请</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/mdui.min.css">
  <script src="js/mdui.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/bj.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    #verificationButton {
      background-color: #d32f2f !important;
    }
  </style>
</head>
<body>
<div class="mdui-container">
  <div class="mdui-card mdui-m-y-3">
    <div class="mdui-card-content mdui-p-a-2">
      <h2>编号申请</h2>
      <p>请输入您要申请的编号：</p>
      <div class="mdui-textfield">
        <input class="mdui-textfield-input" type="text" id="numberInput" placeholder="请输入编号">
      </div>
      <button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-indigo" onclick="generateID()">确认编号</button>
      <div id="captchaContainer" style="display: none;">
        <p>请输入验证码：</p>
        <div id="captchaImageContainer"></div>
        <button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-indigo" onclick="refreshCaptcha()">刷新验证码</button>
        <div class="mdui-textfield">
          <input class="mdui-textfield-input" type="text" id="captchaInput" placeholder="请输入验证码">
          <input type="hidden" id="hiddenCaptcha" name="captcha">
        </div>
      </div>
      <div id="applicationForm" style="display: none;">
        <form action="submit.php" method="POST" onsubmit="return validateForm()">
          <div class="mdui-textfield">
            <input class="mdui-textfield-input" type="text" name="siteName" placeholder="站点名称" required>
          </div>
          <div class="mdui-textfield">
            <input class="mdui-textfield-input" type="text" name="address" placeholder="站点网址" required>
          </div>
          <div class="mdui-textfield">
            <input class="mdui-textfield-input" type="text" name="description" placeholder="介绍" required>
          </div>
          <div class="mdui-textfield">
            <input class="mdui-textfield-input" type="text" name="owner" placeholder="所有者" required>
          </div>
          <div class="mdui-textfield">
            <input class="mdui-textfield-input" type="text" name="email" placeholder="联系邮箱" required>
          </div>
          <input type="hidden" name="number" id="hiddenNumber">
          <input type="submit" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-indigo" value="提交申请">
          <a id="verificationButton" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-indigo" href="" target="_blank">点击获取站底代码</a>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
function generateID() {
  const numberInput = document.getElementById('numberInput');
  const number = numberInput.value.trim();

  if (number.length !== 6 || !number.match(/^\d+$/) || number.startsWith('0')) {
    alert('请输入正确的6位数字编号，且不能以0开头');
    return;
  }

  const hiddenNumber = document.getElementById('hiddenNumber');
  hiddenNumber.value = number;

  const applicationForm = document.getElementById('applicationForm');
  applicationForm.style.display = 'block';

  const captchaContainer = document.getElementById('captchaContainer');
  captchaContainer.style.display = 'block';

  const captcha = generateImageCaptcha();
  const hiddenCaptcha = document.getElementById('hiddenCaptcha');
  hiddenCaptcha.value = captcha;

  updateVerificationButtonLink(number);
}

function generateImageCaptcha() {
  const captchaImageContainer = document.getElementById('captchaImageContainer');
  const captcha = Math.floor(Math.random() * 10000);
  const imageUrl = 'Captcha.php?captcha=' + captcha;
  const imageElement = document.createElement('img');
  imageElement.src = imageUrl;
  captchaImageContainer.innerHTML = '';
  captchaImageContainer.appendChild(imageElement);
  return captcha;
}

function refreshCaptcha() {
  generateID();
}

function updateVerificationButtonLink(number) {
  const verificationButton = document.getElementById('verificationButton');
  verificationButton.href = './text.php?text=' + number;
}

function validateForm() {
  const captchaInput = document.getElementById('captchaInput').value;
  const hiddenCaptcha = document.getElementById('hiddenCaptcha').value;
  
  if (captchaInput !== hiddenCaptcha) {
    alert('验证码错误，请重新输入');
    return false;
  }
  return true;
}
</script>
</body>
</html>
