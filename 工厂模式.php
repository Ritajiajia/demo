<?php
/**
 * Created by PhpStorm.
 * User: chenjia
 * Date: 2017/3/7
 * Time: 10:40
 */
$db1 = sqlFactory::factory('Mysql');
$db2 = sqlFactory::factory('Sqlite');
$obj = new $db1;
print_r($obj->query('1111','3333'));

interface Db_Adapter {
    //数据库连接，$config数据库配置
    public function connect($config);

    /**
     * 执行数据库查询
     * @param string $query 数据库查询sql字符串
     * @param mixed $handle 连接对象
     * @return resource
     **/
    public function query($query, $handle);
}

//mysql数据库操作类
class Db_Adapter_Mysql implements Db_Adapter
{
    private $_dblink;//数据库连接标示

    public function connect($config) {
        if($this->_dblink = @mysql_connect($config->host . (empty($config->port) ? '' : $config->port),
            $config->user,$config->password, true)) {
            if(@mysql_select_db($config->database, $this->_dblink)) {
                if($config->chasrset) {
                    mysql_query("SET NAMES '$config->charset'", $this->_dblink);
                }
                return $this->_dblink;
            }
        }
    }
    public  function query($query, $handle) {
        echo 111;exit;
        if($resource = @mysql_query($query, $handle));
        return $resource;
    }
}

//SQLite数据库操作类
class Db_Adapter_Sqlite implements Db_Adapter
{
    private $_dblink;

    public function connect($config) {
        if($this->_dblink = sqlite_open($config->file, 0666, $error)) {
            return $this->_dblink;
        }

        throw new Db_Exception($error);
    }

    public function query($query, $handle) {
        if($resource = @sqlite_query($query, $handle)) {
            return $resource;
        }
    }
}

//定义一个工厂类
class sqlFactory
{
    public static function factory($type) {
        $classname = 'Db_Adapter_'.$type;
        return $classname;
    }
}