<?php
//异常处理
set_error_handler('customError', E_ALL|E_STRICT);

$data = '2017-03-07';
if(ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $data, $regs)) {
    echo "$regs[3].$regs[2].$regs[1]";
} else {
    echo "无效日期";
}

if($i > 5) {
    echo "没有初始化";
}

$a = ['o' => 2,4,6,8];
echo $a[o];
$result = array_sum($a, 3);
echo fun();
echo 'error';
exit;

//自定义异常错误函数
function customError($errno,$errstr,$errfile,$errline)
{
    echo "<b>错误代码：</b>[$errno] $errstr \r\n";
    echo "错误所在的代码行：$errline 文件 $errfile \r\n";
    echo "PHP版本 ",PHP_VERSION, "(",PHP_OS,")\r\n";
}