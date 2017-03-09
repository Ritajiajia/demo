<?php
/**
 * Created by PhpStorm.
 * User: chenjia
 * Date: 2017/3/7
 * Time: 11:31
 */
$worka = new workA;
$worka->work();
$workb = new workB();
$workb->set(new teacher());
$workb->work();

interface employee
{
    public function working();
}

class teacher implements employee
{
    public function working() {
        echo "teacher\r\n";
    }
}
class coder implements employee
{
    public function working() {
        echo "coder\r\n";
    }
}
class workA
{
    public function work() {
        $teacher = new teacher;
        $teacher->working();
    }
}
class workB
{
    private $e;

    public function set(employee $e) {
        $this->e = $e;
    }

    public function work() {
        $this->e->working();
    }
}