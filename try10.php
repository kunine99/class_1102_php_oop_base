<?php
class DB
{

    private $dsn = "mysql:host=localhost;charset=utf8;dbname=db01";
    private $root = 'root';
    private $password = '';
    private $table;
    private $pdo;

    public function __construct($table)
    {
        $this->table = $table;
        $this->pdo = new PDO($this->dsn, $this->root, $this->password);
    }




    public function all(...$arg)
    {
        $sql = " SELECT * FROM  $this->table ";
        switch (count($arg)) {
            case 1:
                if (is_array($arg[0])) {
                    foreach ($arg[0] as $key => $value) {
                        $tmp[] = "`$key`='$value'";
                    }
                    $sql = " WHERE " . implode("AND", $tmp);
                } else {
                    $sql .=$arg[0];
                }

                break;

            case 2:
                foreach ($arg[0] as $key => $value) {
                    $tmp[] = "`$key`='$value'";
                }
                $sql = " WHERE " . implode("AND", $tmp) . " " . $arg[1];
                break;
        }
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    public function math($math, $col, ...$arg)
    {
        $sql = " SELECT $math($col) FROM  $this->table ";
        switch (count($arg)) {
            case 1:
                if (is_array($arg[0])) {
                    foreach ($arg[0] as $key => $value) {
                        $tmp[] = "`$key`='$value'";
                    }
                    $sql = " WHERE " . implode("AND", $tmp);
                } else {
                    $sql .=$arg[0];
                }

                break;

            case 2:
                foreach ($arg[0] as $key => $value) {
                    $tmp[] = "`$key`='$value'";
                }
                $sql = " WHERE " . implode("AND", $tmp) . " " . $arg[1];
                break;
        }
        return $this->pdo->query($sql)->fetchColumn();
    }


    public function find($id)
    {
        $sql = " SELECT * FROM  $this->table WHERE";
        if (is_array($id)) {
            foreach ($id as $key => $value) {
                $tmp[] = "`$key`='$value'";
            }
            $sql = implode("AND", $tmp);
        } else {
            $sql .= "`id`='$id'";

        }
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

    }

    public function del($id)
    {
        $sql = "DELETE FROM $this->table WHERE ";

        if (is_array($id)) {
            foreach ($id as $key => $value) {
                $tmp[] = "`$key`='$value'";
            }
            $sql = implode("&&", $tmp);
        } else {
            $sql .= "`id`='$id'";

        }
        return $this->pdo->exec($sql);
    }


    public function q($sql)
    {
        //$sql - SQL語句字串，取出符合SQL語句的全部資料
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


public function save($array)
{
    $sql = "SELECT * FROM $this->table ";
        if (isset($array['id'])) {
            foreach ($array['id'] as $key => $value) {
                if ($key != 'id') {

                    $tmp[] = "`$key`='$value'";
                }
                $sql = "UPDATE $this->table SET " . implode(',', $tmp) . "WHERE `id`='{$array['id']}'";
            }
        } else {
            $sql = "INSERT INTO $this->table (`" . implode("`,`", array_keys($array)) . "`)VALUES('" . implode("','", $array) . "')";
        }
        return $this->pdo->exec($sql);
}
}
function to($url)
{
    //$url - 要導向的檔案路徑及檔名
    header("location:" . $url);
}

function dd($array)
{
    echo "<pre>";
    print_r($array);
    echo "<pre>";
}


date_default_timezone_set("Asia/Taipei");

//有很多功能需要透過session來暫存狀態，因此我們可以在共用檔中先啟月session
//方便在各個頁面都可以操作session。
session_start();
//建議使用首字母大寫來代表這是資料表的變數，方便和全小寫的變數做出區隔
$User = new DB('user');
$Menu = new DB('menu');
