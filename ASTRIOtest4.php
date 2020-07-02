<?php
interface metods{
    public function setData($key,$value);
    public function getData();
    public function save();
    public function load();
}
abstract class AbstractBox implements metods{
    public $key;
    public $value;
    public function setData($key,$value){
        $this->key = $key;
        $this->value = $value;
    }
    public function getData(){
        return "{$this->key}".","."{$this->value}";
    }
    public function save(){
        $key = $this->key;
        $value = $this->value;
    }
    public function load(){
        $key = $this->key;
        $value = $this->value;
    }
}
class FileBox extends AbstractBox {
    private static $_instance = null;
    private function __construct() {
    }
    protected function __clone() {
    }
	public static function getInstance()
	{
		if (self::$_instance != null) {
			return self::$_instance;
		}

		return new self;
	}
    public function save(){
        parent::save();
        $file1 = fopen('file.txt', 'a+');
        $file = file('file.txt');
        $key = $this->key;
        $value = $this->value."\n";
        $replacements = array($key => $value);
        $result = array_replace($file,$replacements);
        fclose($file1);
        $file1 = fopen('file.txt', 'w');
        foreach ($result as $key => $value) {
            fwrite($file1,$value);
        }
        fclose($file1);
    }
    public function load(){
        parent::load();
        $key = $this->key;
        $takeFile = file('file.txt');
        return $takeFile[$key];
    }
}
class DbBox extends AbstractBox {
    private static $_instance = null;
    private function __construct() {
    }
    protected function __clone() {
    }
	public static function getInstance()
	{
		if (self::$_instance != null) {
			return self::$_instance;
		}

		return new self;
	}
    public function save(){
        parent::save();
        $key = $this->key;
        $value = $this->value;
        $mybase = mysqli_connect('localhost',' ',' ',' ') or die("Ошибка подключения к БД");
        $query = "SELECT * FROM `base` WHERE `id` = '$key'";
        $result = mysqli_query($mybase,$query);
        if($row = mysqli_fetch_array($result)){
            $query = "UPDATE `base` SET `value` = '$value' WHERE `id` = '$key'";
            mysqli_query($mybase,$query);
        }
        else {
            $query = "INSERT INTO `base`(`id`,`value`) VALUES('$key','$value')";
            mysqli_query($mybase,$query);
        }
    }
    public function load(){
        parent::load();
        $key = $this->key;
        $mybase = mysqli_connect('localhost',' ',' ',' ') or die("Ошибка подключения к БД");
        $query = "SELECT `value` FROM `base` WHERE `id` = '$key'";
        $result = mysqli_query($mybase,$query);
        $row = mysqli_fetch_row($result);
        return $row[0];
    }
}
$obj = FileBox::getInstance();
$obj->setData(1,'Новая запись в файл');
$obj->save();
echo $obj->load();
?>
