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
        return [$this->key => $this->value];
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
    private $data = 'file.txt';
    private static $_instance = null;
    private function __construct() {
        $this->_instance = $data;
    }
    protected function __clone() {
    }
	public static function getInstance()
	{
		if (self::$_instance) {
			return self::$_instance;
		}

		return new self;
	}
    public function save(){
        parent::save();
        $data = $this->data;
        $key = strval($this->key);
        $value = $this->value;
        $array = [$key => $value];
        if(filesize($data) == 0){
            $seriaArray = serialize($array);
            file_put_contents($data, $seriaArray);
        }
        else {
            $fileGet = file_get_contents($data);
            $unserFileGet = unserialize($fileGet);
            print_r($unserFileGet);
            if(array_key_exists($key,$unserFileGet) === true){
                $replacemantArray = array_replace($unserFileGet,$array);
                $seriaArray = serialize($replacemantArray);
                file_put_contents($data, $seriaArray);
            }
            else {
                $newArray = $unserFileGet + $array;
                $seriaArray = serialize($newArray);
                file_put_contents($data, $seriaArray);
            }
        }
    }
    public function load(){
        parent::load();
        $data = $this->data;
        $takeFile = file($data);
        return $takeFile;
    }
}
class DbBox extends AbstractBox {
    private $host = '127.0.0.1';
    private $user = 'root';
    private $password = 'root';
    private $dbname = 'dbtest';
    private static $mysqli;
    private function __construct() {
        $host = $this->host;
        $user = $this->user;
        $password = $this->password;
        $dbname = $this->dbname;
        $this->mysqli = new mysqli($host,$user,$password,$dbname);
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
    }
    protected function __clone() {
    }
	public static function getInstance() {
		if (self::$mysqli) {
			return self::$mysqli;
		}
		return new self;
	}
    public function save() {
        parent::save();
        $key = $this->key;
        $value = $this->value;
        $mysqli = $this->mysqli;
        if(!($select = $mysqli->prepare("SELECT * FROM test WHERE id = ?"))){
            echo "Не удалось подготовить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        if (!$select->bind_param("i", $key)) {
            echo "Не удалось привязать параметры: (" . $select->errno . ") " . $select->error;
        }
        if(!($select->execute())){
            echo "Не удалось выполнить запрос MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        $select->store_result();
        if($select->num_rows() != 0){
            $select->close();
            if(!($update = $mysqli->prepare("UPDATE test SET value = ? WHERE id = ?"))){
                echo "Не удалось подготовить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
            }
            if (!$update->bind_param("si", $value,$key)) {
                echo "Не удалось привязать параметры: (" . $update->errno . ") " . $update->error;
            }
            if(!($update->execute())){
                echo "Не удалось выполнить запрос MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            }
        }
        else {
            $select->close();
            if (!($insert = $mysqli->prepare("INSERT INTO test VALUES (?,?)"))) {
                echo "Не удалось подготовить запрос: (" . $mysqli->errno . ") " . $mysqli->error;
            }
            if (!$insert->bind_param("is", $key,$value)) {
                echo "Не удалось привязать параметры: (" . $insert->errno . ") " . $insert->error;
            }
            if (!$insert->execute()) {
                echo "Не удалось выполнить запрос: (" . $stmt->errno . ") " . $stmt->error;
            }
        }
    }
    public function load(){
        parent::load();
        $mysqli = $this->mysqli;
        $res = $mysqli->query("SELECT * FROM test");
        return $res->fetch_all();
    }
}
// $obj = FileBox::getInstance();
// $obj->setData(1,'Новая запись в файл');
// $obj->save();
$obj1 = DbBox::getInstance();
print_r($obj1->load());
?>
