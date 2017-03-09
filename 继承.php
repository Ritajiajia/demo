<?php
/**
 * Created by PhpStorm.
 * User: chenjia
 * Date: 2017/3/7
 * Time: 10:17
 */
$poor = new family2();
$poor->name = 'rita';
$poor->gender = 'female';
$poor->age = 20;
$poor->say();
$poor->cry();

class person
{
    public $name;
    public $gender;
    private $age;
    static $money = 1000;

    public function __construct()
    {
        echo "这是父类",PHP_EOL;
    }

    public function say()
    {
        echo $this->name,"\t is ",$this->gender,"\r\n";
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        if(isset($this->$name)) {
            $this->$name = 'default';//默认值
        }
        return $this->$name;
    }

    public function __call($name, $arguments) {
        echo $name,"\r\n";//方法名
        print_r($arguments);//参数
    }

    public function __toString()
    {
        return "当前对象是测试魔术方法";
    }
}

//继承
class family2 extends person
{
    public $name;
    public $gender;
    public $age;
    static $money = 100000;

    public function __construct() {
        parent::__construct();//调用父类的构造方法
        echo "这是子类",PHP_EOL;
    }

    public function say() {
        parent::say();
        echo $this->name," \t is \t",$this->gender,",and is \t",$this->age,PHP_EOL;
    }

    public function cry() {
        echo parent::$money,PHP_EOL;
        echo  self::$money,PHP_EOL;
    }
}