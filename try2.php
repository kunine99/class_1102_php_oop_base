<?php
class DB
{
    //是charset
    private $dsn = "mysql:host=localhost;charset=utf8;dbname=db01"; 
    private $root = 'root';
    private $password = '';
    private $table;
    private $pdo;

    public function __construct($table)
    {
        //table要注意有沒有加錯變數
        $this->table = $table;
        $this->pdo = new PDO($this->dsn, $this->root, $this->password);
    }

    public function all(...$arg)
    {

    }

    public function math($math, $col, ...$arg)
    {

    }
    public function find($id)
    {
        $sql = "select * from $this->table where ";

            if(isset($id)) {
                foreach ($id as $key => $value) {
                    $tmp[] = "`$key`='$value'";
                }
                $sql .= implode(" AND ", $tmp); 
            } else {
                $sql .= "`id`='$id'";
            }
            return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function del($id)
    {
        //find複製來，刪掉select * 改成delete
        $sql = "delete from $this->table where ";

        if(isset($id)) {
            foreach ($id as $key => $value) {
                $tmp[] = "`$key`='$value'";
            }
            //把 AND 改成&&
            $sql .= implode(" && ", $tmp); 
        } else {
            $sql .= "`id`='$id'";
        }
        //exec 執行的意思  query是拿取的意思
        return $this->pdo->exec($sql);
    }

    public function save($array)
    {

    }

    public function q($sql)
    {
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

}

function to($url)
{
    header("location:" . $url); 
}

date_default_timezone_set("Asia/Taipei");
session_start();
// $User = new DB($user);  這樣打是錯的
$User = new DB('user');
$Menu = new DB('menu');
