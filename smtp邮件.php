<?php
/**
 * Created by PhpStorm.
 * User: chenjia
 * Date: 2017/3/8
 * Time: 17:44
 */
$host = "smtp.qq.com";
$port = 25;
$user = 'username';
$pass = 'password';

$from = 'send@qq.com';
$to = '732678792@qq.com';
$subject = 'Hello Body';
$content = "This is example email for you";

$mail = new Mail($host, $port, $user, $pass);
$mail->send_mail($from, $to, $subject, $content);

class Mail
{
    private $host;//保存要连接的smtp服务器
    private  $port = 25;//要绑定的端口，默认25
    private  $user;//要登录smtp服务器的用户名
    private $pass;//要登录smtp服务器的密码
    private $debug = false;//是否开启调试模式，默认为关闭
    private $sock;//保存与smtp服务器连接的句柄
    private $mail_format = 0;//发送的邮件格式，0：普通文本，1：html邮件

    public function __construct($host, $port, $user, $pass, $format=1, $debug = 0) {
        $this->host = $host;
        $this->port = $port;
        $this->user = base64_encode($user);
        $this->pass = base64_encode($pass);
        $this->mail_format = $format;
        $this->debug = $debug;

        $this->sock = fsockopen($this->host, $this->port, & $errno, & $errstr, 10);
        if(!$this->sock) {
            exit("Error number: $errno, Error message: $errstr\n");
        }

        $response = fgets($this->sock);//获取服务器的信息
        if(strstr($response, '220') === false) {
            exit("Server error: $response\n");
        }
    }

    private function show_debug($message) {
        if($this->debug) {
            echo "<p>Debug: $message</p>\n";
        }
    }

    private function do_command($cmd, $return_code) {
        fwrite($this->sock, $cmd);

        $response = fgets($this->sock);
        if(strstr($response, $return_code) === false) {
            $this->debug($response);
            return false;
        }

        return true;
    }

    private function is_email($email) {
        $pattren = "/^[^_][\w]* @[\w.]+[\w]*[^_]$/";
        if(preg_match($pattren, $email, $matches)) {
            return true;
        } else {
            return false;
        }
    }

    public function send_mail($from, $to, $subject, $body) {
        if(!$this->is_email($from) || !$this->is_email($to)) {
            $this->show_debug("Please enter vaild from/to email");
            return false;
        }

        if(empty($subject) || empty($body)) {
            $this->show_debug("Please enter subject/content");
            return false;
        }

        $detail = "From:".$from."\r\n";
        $detail .= "To:".$to."\r\n";
        $detail .= "Subject:".$subject."\r\n";

        if($this->mail_format == 1) {
            $detail .= "Content - Type: text/html;\r\n";
        } else {
            $detail .= "Content - Type: text/plain;\r\n";
        }

        $detail .= "charset=gb2312\r\n\r\n";
        $detail .= $body;

        $this->do_command('HELLO smtp.qq.com\r\n', 250);
        $this->do_command("AUTH LOGIN\r\n", 334);
        $this->do_command($this->user."\r\n", 234);
        $this->do_command($this->pass."\r\n", 235);
        $this->do_command("MAIL FROM:<".$from.">\r\n", 250);
        $this->do_command("RCPT TO:<".$to.">\r\n", 250);
        $this->do_command("DATA\r\n", 354);
        $this->do_command($detail."\r\n.\r\n", 250);
        $this->do_command("QUIT\r\n", 221);

        return true;
    }
}