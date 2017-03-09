<?php
/**
 * Created by PhpStorm.
 * User: chenjia
 * Date: 2017/3/7
 * Time: 10:16
 */
doPrint(new teacher());
doPrint(new coder());
doPrint(new employee());

//多态
class employee
{
    protected function working() {
        echo '本方法需重载';
    }
}
class teacher extends employee
{
    public function working() {
        echo 'teacher',PHP_EOL;
    }
}
class coder extends employee
{
    public function working() {
        echo 'coder',PHP_EOL;
    }
}
function doPrint($obj)
{
    if(get_class($obj) == 'employee') {
        echo 'error',PHP_EOL;
    } else {
        $obj->working();
    }
}