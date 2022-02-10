<?php
//第三次邊看邊打練習，花了45分鐘
class DB
{
    private $dsn = "mysql:host=localhost;charset=utf8;dbname=db01";
    private $root = 'root';
    private $password = '';
    private $table;
    private $pdo;

    //是table
    public function __construct($table)
    {
        $this->table = $table;
        $this->pdo = new PDO($this->dsn, $this->root, $this->password);
        
    }

    public function all(...$arg) 
    {
        $sql = "select * from $this->table ";
        switch (count($arg)) {
            case 1:
                if (is_array($arg[0])) {
                    foreach ($arg[0] as $key => $value) {
                        $tmp[] = "`$key`='$value'";
                    }
                    $sql .= " WHERE " . implode(" AND ", $tmp);
                } else {
                    $sql .= $arg[0]; 
                }
                
            break;
            case 2:
                foreach ($arg[0] as $key => $value) {
                    $tmp[] = "`$key`='$value'";
                }
                $sql .= " WHERE " . implode(" AND ", $tmp) . " " . $arg[1];
            break;
        }
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function math($math, $col, ...$arg) 
    {
        //從all複製來的，*刪掉改成$math(`$col`)
        $sql = "SELECT $math(`$col`) FROM $this->table ";
        //加一句這個判斷式，其他跟all一樣
        if (!empty($arg[0])) {
            foreach ($arg[0] as $key => $value) {
                $tmp[] = "`$key`='$value'";
            }
            $sql .= " WHERE " . implode(" AND ", $tmp);
        }

        return $this->pdo->query($sql)->fetchColumn();
    }

    public function find($id) 
    {
        //all複製來的，要加上where
        $sql = "select * from $this->table where ";
        if (is_array($id)) {
            foreach ($id as $key => $value) {
                $tmp[] = "`$key`='$value'";
            }
            $sql .= implode(" AND ", $tmp);
        } else {
            $sql .= "`id`='$id'"; 
        }
        //echo $sql;  記住不是fetchall
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

    }

    public function del($id)
    {
        //find複製來，刪掉select * 改成delete
        $sql = "delete from $this->table where ";
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
        //從all複製來的,改成isset
        if (isset($array['id'])) {
            foreach ($array as $key => $value) {
                if ($key != 'id') {

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



    function to($url){
        header("location:" . $url);
    }

    date_default_timezone_set("Asia/Taipei");
    session_start();
    $User = new DB('user');
    $Menu = new DB('menu');

