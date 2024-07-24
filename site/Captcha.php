<?php
session_start();

// 从 GET 参数中获取验证码字符串，如果不存在则生成一个新的验证码字符串
$captchaStr = isset($_GET['captcha']) ? $_GET['captcha'] : substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);

// 将验证码字符串保存到 session 中
$_SESSION['captcha'] = $captchaStr;

// 创建空白图片
$image = imagecreatetruecolor(120, 40);

// 设置背景色为白色
$bgColor = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $bgColor);

// 设置验证码颜色
$textColor = imagecolorallocate($image, 0, 0, 0);

// 在图片上绘制验证码字符串
imagestring($image, 5, 10, 10, $captchaStr, $textColor);

// 添加干扰线
for ($i = 0; $i < 5; $i++) {
    $lineColor = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
    imageline($image, rand(0, 120), rand(0, 40), rand(0, 120), rand(0, 40), $lineColor);
}

// 添加干扰点
for ($i = 0; $i < 50; $i++) {
    $pointColor = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
    imagesetpixel($image, rand(0, 120), rand(0, 40), $pointColor);
}

// 设置 Content-Type 为图片
header('Content-Type: image/png');

// 输出图片
imagepng($image);

// 销毁图片资源
imagedestroy($image);
?>
