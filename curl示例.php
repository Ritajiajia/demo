<?php
/**
 * Created by PhpStorm.
 * User: chenjia
 * Date: 2017/3/8
 * Time: 14:56
 */
//1、curl批处理
$ch1 = curl_init();
$ch2 = curl_init();

curl_setopt($ch1, CURLOPT_URL, "http://www.baidu.com");
curl_setopt($ch1, CURLOPT_HEADER, 0);

curl_setopt($ch2, CURLOPT_URL, "http://invo.cn");
curl_setopt($ch2, CURLOPT_HEADER, 0);

//创建curl批处理句柄
$mh = curl_multi_init();

//加上两个资源句柄
curl_multi_add_handle($mh, $ch1);
curl_multi_add_handle($mh, $ch2);
//预定一个状态变量
$active = null;
//执行批处理
do {
    $mrc = curl_multi_exec($mh, $active);
} while ($mrc == CURLM_CALL_MULTI_PERFORM);//CURLM_CALL_MULTI_PERFORM:代表有一些刻不容缓的工作要做

while ($active && $mrc == CURLM_OK) {
    if(curl_multi_select($mh) != -1) {
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    }
}

//关闭各个句柄
curl_multi_remove_handle($mh, $ch1);
curl_multi_remove_handle($mh, $ch2);
curl_multi_close($mh);
exit;

//2、curl上传文件
$url = "http://invo.cn/";
$ch = curl_init();

$dir = getcwd();
$cfile = curl_file_create($dir.'/test.gif');

$data = ['test' => $cfile];
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_INFILESIZE, filesize($dir.'/test.gif'));
$output = curl_exec($ch);
curl_close($ch);
echo $output;exit;


//3、curl模拟手机UA
@header('Content - type: text/html; charset = utf - 8');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://3g.qq.com");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$h = [
    'HTTP_VIA:HTTP/1.1 SNXA - PS - WAP GW21 (infoX - WISG, Huawei Technologies)',
    'HTTP_ACCEPT:application/vnd.wap.wmlscriptc,text/vnd.wap.wml,
            application/vnd.wap.xhtml + xml,
            application/xhtml + xml, text/html,multipart/mixed,*/*',
    'HTTP_ACCEPT_CHARSET:ISO-8859-1,US-ASCII,UTF-8;Q=0.8,ISO-8859-15;Q=0.8,ISO-10646-UCS-2;Q=0.6,UTF-16;Q=0.6'
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $h);

$output = curl_exec($ch);
curl_close($ch);

//第二次跳转
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://info.3g.qq.com/g/s?aid=index&amp;s_it=3&amp;g_from=3gindex&amp;g_f=1283");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
$output = curl_exec($ch);
echo $output;exit;



//4、curl抓取图片
@header('Content - type: image/png');
//初始化
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "http://www.baidu.com/img/38270_b8c2a909848579fb12dc61f16ea9cde2.gif");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$output = curl_exec($ch);
$info = curl_getinfo($ch);

curl_close($ch);

file_put_contents("./test.gif", $output);
$size = filesize("./test.gif");

if($size != $info['size_download']) {
    echo "下载数据不完整";
} else {
    echo "下载数据完整";
}
exit;


//5、基本操作
//初始化
$ch = curl_init();
//设置选项，包括url
curl_setopt($ch, CURLOPT_URL, "http://www.baidu.cn");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//获取的信息以文件流的形式返回，不直接输出
curl_setopt($ch, CURLOPT_HEADER, 0);//将头文件的信息作为数据流输出

//发送POST数据
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, ['name' => 'rita', 'email' => 'test@qq.com']);

$output = curl_exec($ch);//执行并获取html内容

$info = curl_getinfo($ch);
var_dump($info);

if($output === false) {
    echo "Curl Error: ".curl_error($ch);exit;
}
//释放curl句柄
curl_close($ch);
echo $output;exit;


//6、通过fsockopen函数发送请求
$post = ['name' => 'rita', 'email' => 'rita@qq.com', 'comment' => 'this is a test'];
$data = http_build_query($post);
$fp = fsockopen('invo.cn', 80, $errno, $errstr, 5);
$out = "POST http://invo.cn/contact/send HTTP/1.1\r\n";
$out .= "Host: invo.cn\r\n";
$out .= "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:49.0) Gecko/20100101 Firefox/49.0\r\n";
$out .= "Referer: http://invo.cn/contact/index\r\n";
$out .= "Cookie: PHPSESSID=91h85lkpvmsn68cu3jpkj1fkd5\r\n";
$out .= "Content - Length: ".strlen($data)."\r\n";
$out .= "Connection: close\r\n\r\n";
$out .= $data."\r\n\r\n";
fwrite($fp, $out);
while(!feof($fp)){
    echo fgets($fp, 1280);
}
fclose($fp);
exit;


//7、创建socket
$host = '192.168.0.23.128';
$port = 8001;

set_time_limit(0);//保证服务器不会超时

//创建socket,AF_INET:基于ipv4的internet协议;SOCK_STREAM:全双工链接（TCP）
$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("could not create socket\n");
//绑定socket到指定的地址和端口
$result = socket_bind($socket, $host, $port) or die("could not bind to socket\n");
//开始监听连接,3:允许最大的连接数为3
$result = socket_listen($socket, 3) or die("could not set up socket listener\n");
//接收连接请求并调用另一个子socket处理客户端--服务器间的信息
$spawn = socket_accept($socket) or die("could not accept incoming connection\n");
//读取客户端输入
$input = socket_read($spawn, 1024) or die("could not read input\n");
//清除input string
$input = trim($input);
//反转客户端输入数据，返回服务端
$output = strrev($input)."\n";
socket_write($spawn, $output, strlen($output)) or die("could not write output\n");
//关闭socket
socket_close($spawn);
socket_close($socket);