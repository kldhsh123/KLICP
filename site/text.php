<?php

// 获取 URL 中的 "text" 参数
$text = $_GET['text'];

// 替换代码中的 "text" 占位符
$imageCode = '<img style="width:20px;height:20px;margin-bottom:-4px" src="https://krseoul.imgtbl.com/i/2024/07/01/66825b0a53d19.jpg">';
$linkCode = '<a href="https://这是域名/?id=' . $text . '" target="_blank">开朗ICP备案 ' . $text . '号</a>';

// 输出最终结果
echo "请将以下代码插入站底\n";
echo htmlentities($imageCode) . "\n";
echo htmlentities($linkCode) . "\n";

?>
