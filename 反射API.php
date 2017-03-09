<?php
//反射API
$obj = new ReflectionClass('person');
$className = $obj->getName();
$methods = $properties = [];
foreach($obj->getProperties() as $v) {
    $properties[$v->getName()] = $v;
}
foreach($obj->getMethods() as $v) {
    $methods[$v->getName()] = $v;
}
echo "class $className\n\n";
is_array($properties) && ksort($properties);

foreach($properties as $k => $v) {
    echo "\t";
    echo $v->isPublic() ?  'public' : '',$v->isPrivate() ? 'private' : '',
    $v->isProtected() ? 'protected' : '',$v->isStatic() ? ' static' : '';
    echo "\t $k \n";
}
echo "\n";
if(is_array($methods)) ksort($methods);
foreach($methods as $k => $v) {
    echo "\t function $k (){}\n";
}
echo "} \n";

class person
{
    public $name;
    public $gender;
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