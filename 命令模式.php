<?php
/**
 * Created by PhpStorm.
 * User: chenjia
 * Date: 2017/3/7
 * Time: 10:57
 */
$controller = new cookController();//顾客
$cook = new cook();//厨师

$mealcommand = new MealCommand($cook);
$drinkcommand = new DrinkCommand($cook);

$controller->addCommand($mealcommand, $drinkcommand);
$controller->callmeal();
$controller->calldrink();

//厨师类，命令接收者与执行者
class cook
{
    public function meal() {
        echo "番茄炒鸡蛋",PHP_EOL;
    }

    public function drink() {
        echo "番茄鸡蛋汤",PHP_EOL;
    }

    public function ok() {
        echo "完成",PHP_EOL;
    }
}

//命令接口
interface command
{
    public function execute();
}

//服务员与厨师的过程
class MealCommand implements command
{
    private $cook;

    //绑定命令接收者
    public function __construct(cook $cook) {
        $this->cook = $cook;
    }

    public function execute() {
        $this->cook->meal();//把消息传递给厨师，让厨师做菜
    }
}
class DrinkCommand implements command
{
    private $cook;

    //绑定命令接收者
    public function __construct(cook $cook) {
        $this->cook = $cook;
    }

    public function execute() {
        $this->cook->drink();//把消息传递给厨师，让厨师做汤
    }
}

//顾客与服务员的过程
class cookController
{
    private $mealcommand;
    private $drinkcommand;

    public function addCommand(command $mealcommand, command $drinkcommand) {
        $this->mealcommand = $mealcommand;
        $this->drinkcommand = $drinkcommand;
    }

    public function callmeal() {
        $this->mealcommand->execute();
    }
    public function calldrink() {
        $this->drinkcommand->execute();
    }
}