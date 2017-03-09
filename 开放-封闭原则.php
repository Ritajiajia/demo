<?php
/**
 * Created by PhpStorm.
 * User: chenjia
 * Date: 2017/3/7
 * Time: 11:10
 */
$mp4 = new mp4();
$mp4->work();

//播放器接口
interface process
{
    public function process();
}

//播放器编码功能
class playencode implements process
{
    public function process() {
        echo "encode \r\n";
    }
}
class playoutput implements process
{
    public function process() {
        echo "output\r\n";
    }
}

//播放器的调度管理器
class playprocess
{
    private $message = null;
    public function __construct() {

    }
    public function callback(event $event) {
        $this->message = $event->click();
        if($this->message instanceof process) {
            $this->message->process();
        }
    }
}

//播放器的事件处理逻辑
class mp4
{
    public function work() {
        $playProcee = new playprocess();
        $playProcee->callback(new event('encode'));
        $playProcee->callback(new event('output'));
    }
}

//播放器的事件处理类
class event
{
    private $m;

    public function __construct($me) {
        $this->m = $me;
    }

    public function click() {
        switch ($this->m) {
            case 'encode':
                return new playencode();
                break;
            case 'output':
                return new playoutput();
                break;
        }
    }
}