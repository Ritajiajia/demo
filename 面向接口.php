<?php
/**
 * Created by PhpStorm.
 * User: chenjia
 * Date: 2017/3/7
 * Time: 10:15
 */
$a = new teacher2();
$b = new coder2();
doPrint2($a);
doPrint2($b);

//接口
interface employee2
{
    public function working();
}
class teacher2 implements employee2
{
    public function working() {
        echo 'teacher',PHP_EOL;
    }
}
class coder2 implements employee2
{
    public function working() {
        echo 'coder',PHP_EOL;
    }
}
function doPrint2(employee2 $obj)
{
    $obj->working();
}