<?php

date_default_timezone_set("Asia/Taipei");
session_start();

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
        $sql = "SELECT * FROM $this->table ";
        switch (count($arg)) {
            case 1:
                if (is_array($arg[0])) {
                    foreach ($arg[0] as $key => $value) {
                        $tmp[] = "`$key`='$value'";
                    }
                    $sql .= " where " . implode("AND", $tmp);
                } else {
                    $sql .= $arg[0];
                }

                break;
            case 2:
                foreach ($arg[0] as $key => $value) {
                    $tmp[] = "`$key`='$value'";
                }
                $sql .= " where " . implode("AND", $tmp) . " " . $arg[1];
                break;
        }

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function  math($math, $col, ...$arg)
    {
        $sql = "SELECT $math($col) FROM $this->table ";
        switch (count($arg)) {
            case 1:
                if (is_array($arg[0])) {
                    foreach ($arg[0] as $key => $value) {
                        $tmp[] = "`$key`='$value'";
                    }
                    $sql .= " where " . implode("AND", $tmp);
                } else {
                    $sql .= $arg[0];
                }

                break;
            case 2:
                foreach ($arg[0] as $key => $value) {
                    $tmp[] = "`$key`='$value'";
                }
                $sql .= " where " . implode("AND", $tmp) . " " . $arg[1];
                break;
        }
        return $this->pdo->query($sql)->fetchColumn();
    }
    public function find($id)
    {
        // $sql = "SELECT * FROM $this->table WHERE"; //考試時寫錯的 忘了加空白
        $sql = "SELECT * FROM $this->table WHERE ";


        if (is_array($id)) {
            foreach ($id as $key => $value) {
                $tmp[] = "`$key`='$value'";
            }
            // $sql .= implode("AND", $tmp);//考試時寫錯的
            $sql .= implode(" AND ", $tmp);
        } else {
            $sql .= "`id`='$id'";
        }

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function del($id)
    {
        $sql = "DELETE FROM $this->table WHERE ";

        // $sql = "DELETE FROM $this->table WHERE"; //考試時寫錯的

        if (is_array($id)) {
            foreach ($id as $key => $value) {
                $tmp[] = "`$key`='$value'";
            }
            // $sql .= implode("AND", $tmp); //考試時寫錯的
            $sql .= implode(" && ", $tmp);

        } else {
            $sql .= "`id`='$id'";
        }

        return $this->pdo->exec($sql);
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
    public function q($sql)
    {
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}

function to($url)
{
    // header("location:", $url); //考試時寫錯的
    header("location:" . $url);
}

function dd($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

$User = new DB('user');
