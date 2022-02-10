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
        //是$this->table
        $sql = "select * from $this->table ";
        switch (count($arg)) {
            case 1:
                if (is_array($arg[0])) {
                    foreach ($arg[0] as $key => $value) {
                        //"`$key`='$value'"
                        $tmp[] = "`$key`='$value'";
                    }
                    //WHERE前面要空白
                    $sql .= " WHERE " . implode(" AND ", $tmp);
                } else {
                    $sql .= $arg[0];
                }
                break;
            case 2:
                foreach ($arg[0] as $key => $value) {
                    ////"`$key`='$value'"
                    $tmp[] = "`$key`='$value'";
                }
                //不要忘記簡寫的.
                $sql .= " WHERE " . implode(" AND ", $tmp) . " " . $arg[1];
                //不要忘記
                break;
        }
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function math($math, $col, ...$arg)
    {
        
        $sql = "SELECT $math(`$col`) FROM $this->table ";
        if (!empty($arg[0])) {
            //複製all開始
            foreach ($arg[0] as $key => $value) {
                //"`$key`='$value'"
                $tmp[] = "`$key`='$value'";
            }
            //WHERE前面要空白
            $sql .= " WHERE " . implode(" AND ", $tmp);
        }
        return $this->pdo->query($sql)->fetchColumn();
        ////複製all結束，fetchAll(PDO::FETCH_ASSOC)改成fetchColumn()

    }
    public function find($id)
    {
        $sql = "select * from $this->table where ";
        //判斷是不是陣列
        if (is_array($id)) {
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
        //判斷是不是陣列
        if (is_array($id)) {
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
        if (isset($array['id'])) {
            foreach ($array as $key => $value) {
                if ($key != 'id') {
                    //"`$key`='$value'"
                    $tmp[] = "`$key`='$value'";
                    
                
         }
        }
            $sql = "update $this->table set " . implode(',', $tmp) . " where `id`='{$array['id']}'";

        } else {
            $sql = "insert into $this->table (`" . implode("`,`", array_keys($array)) . "`)values('" . implode("','", $array) . "')";
            }
            return $this->pdo->exec($sql);
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
