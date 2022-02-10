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
        $sql = "SELECT * FROM $this->table ";
        
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
        $sql = "SELECT $math($col) FROM $this->table ";
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
        return $this->pdo->query($sql)->fetchColumn();
    }
    public function find($id)
    {
        $sql = "SELECT * FROM $this->table where ";
        if (is_array($id)) {
            foreach ($id as $key => $value) {
                $tmp[] = "`$key`='$value'";
            }
            $sql .= implode(" AND ", $tmp);
        } else {
            $sql .= " `id`='$id'";
        }
            return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        
    }
    public function del($id)
    {
        $sql = "DELETE FROM $this->table where ";
        if (is_array($id)) {
            foreach ($id as $key => $value) {
                $tmp[] = "`$key`='$value'";
            }
            $sql .= implode(" AND ", $tmp);
        } else {
            $sql .= "`id`='$id'";
        }
            return $this->pdo->exec($sql);
    }
    public function save($array)
    {
        if (isset($array['id'])) {
            foreach ($array as $key => $value) {
                if ($key != 'id') {
                    $tmp[] = "`$key`='$value'";

                }
            }
            $sql = "UPDATE $this->table set " . implode(',', $tmp) . " where `id`='{$array['id']}'";
        } else {
$sql = "INSERT INTO $this->table (`" . implode("`,`", array_keys($array)) . "`)values('" . implode("','", $array) . "')";
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
$User = new DB('user');
$Menu = new DB('menu');
