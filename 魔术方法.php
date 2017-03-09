<?php
/**
 * Created by PhpStorm.
 * User: chenjia
 * Date: 2017/3/7
 * Time: 10:19
 */
//toString魔术方法
$student = new person();
echo $student;exit;

//callStatic魔术方法
$res = Customer::findById(123)->select;
$res2 = Customer::findByName('jiajia')->select;
exit;

//实例化一个person类，将数据存在在$student对象中
$student = new person();

//调用一个不存在方法
$student->dance(2,76);

//给一个私有属性赋值(主要使用了__set魔术方法)
$student->age = 11;

//给name属性进行赋值
$student->name = 'rita';
//给gender属性进行赋值
$student->gender = 'female';
//执行类中的say方法
$student->say();

//实例化family类
$rita = new family($student, 'hangzhou');
echo serialize($student);

//定义一个数组
$student_arr = ['name' => 'rita', 'gender' => 'female'];
echo "\r\n";

echo serialize($student_arr);
echo "\r\n";
print_r($rita);
echo "\r\n";

echo serialize($rita);exit;

abstract class ActiveRecord
{
    protected static $table;
    protected $filedValues;
    public $select;

    public function __get($filedName) {
        return $this->filedValues[$filedName];
    }

    private static function createDomain($query) {
        $kclass = get_called_class();//获取静态绑定后的类名
        $domain = new $kclass();
        $domain->filedValues = [];
        $domain->select = $query;
        foreach($kclass::$fields as $field => $type) {
            $domain->filedValues[$field] = 'TODO: set from sql result';
        }
        return $domain;
    }

    static function findById($id) {
        $query = "select * from " .static::$table. " where id = $id";
        return self::createDomain($query);
    }

    static function __callStatic($method, $args)
    {
        $filed = preg_replace('/^findBy(\w*)$/', '${1}', $method);
        $query = "select * from " . static::$table. " where $filed = $args[0]";

        return self::createDomain($query);
    }
}

class Customer extends ActiveRecord
{
    protected static $table = 'users';
    protected static $fields = [
        'id' => 'int',
        'username' => 'varchar'
    ];
}

class person
{
    public $name;
    public $gender;
    private $age;

    public function say()
    {
        echo $this->name,"\t is ",$this->gender,"\t age is ",$this->age,"\r\n";
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
class family
{
    public $people;
    public $location;
    public function __construct($p, $loc)
    {
        $this->people = $p;
        $this->location = $loc;
    }
}
