<?php
/**
 * Created by PhpStorm.
 * User: chenjia
 * Date: 2017/3/7
 * Time: 14:06
 */
$message = new message();
$message->name = 'rita';
$message->email = 'rita@qq.com';
$message->content = 'this is a message';

$gb = new authorController();//新建一个留言相关的控制器
$pen = new leaveModel();//拿出笔
$book = new gbookModel();//翻出笔记本

$book->setBookPath("./test.txt");
//$gb->message($pen, $book, $message);

echo $gb->view($book);
echo $gb->viewByPage($book);
$gb->delete($book);


//留言本实体类
class message
{
    public $name;//留言者姓名
    public $email;//留言者邮箱
    public $content;//留言内容

    public function __set($name,$value) {
        if(!isset($this->$name)) {
            $this->$name = NUll;
        }
    }
}

//留言本模型,负责管理留言本
class gbookModel
{
    private $bookpath;//留言本文件
    private $data;//留言数据

    public function setBookPath($bookPath) {
        $this->bookpath = $bookPath;
    }
    public function getBooPath() {
        return $this->bookpath;
    }
    public function open() {

    }
    public function close() {

    }
    public function read() {
        return file_get_contents($this->bookpath);
    }
    public function write($data) {
        $this->data = self::safe($data)->name."&".self::safe($data)->email."\r\n said: \r\n".
            self::safe($data)->content;
        return file_put_contents($this->bookpath, $this->data, FILE_APPEND);
    }

    //模拟数据的安全处理，先拆包在打包
    public static function safe($data) {
        $relect = new ReflectionObject($data);
        $props = $relect->getProperties();
        $messagebox = new stdClass();
        foreach($props as $prop) {
            $ival = $prop->getName();
            $messagebox->$ival = trim($prop->getValue($data));
        }
        return $messagebox;
    }

    public function delete() {
        file_put_contents($this->bookpath, null);
    }

    public function readByPage() {
        $handle = file($this->bookpath);
        $count = count($handle);

        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        if($page < 1 || $page > $count) $page = 1;

        $pnum = 5;

        $begin = ($page-1)*$pnum;
        $end = ($begin+$pnum) > $count ? $count : $begin+$pnum;
        for($i=$begin;$i<$end;$i++){
            echo "<strong>",$i+1,"</strong> ",$handle[$i],"<br/>";
        }
        for($i=1;$i<ceil($count/$pnum);$i++){
            echo "<a href='/about/index/?page=$i'>$i</a>";
        }
    }
}

//留言本业务逻辑处理
class leaveModel
{
    public function write(gbookModel $gb, $data) {
        $book = $gb->getBooPath();
        $gb->write($data);
    }
}

//作者操作类
class authorController
{
    public function message(leaveModel $l, gbookModel $gb, message $data) {
        $l->write($gb, $data);
    }
    public function view(gbookModel $gb) {
        return $gb->read();
    }

    public function delete(gbookModel $gb) {
        $gb->delete();
        echo self::view($gb);
    }

    public function viewByPage(gbookModel $gb) {
        return $gb->readByPage();
    }
}