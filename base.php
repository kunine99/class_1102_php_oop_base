<?php


class DB{
    protected $table;
    protected $dsn="mysql:host=localhost;charset=uft8;dbname=students";
    protected $pdo;

    public function __construct($table){
        $this->pdo=new PDO($this->dsn,'root','');
        $this->table=$table;
    }


public function all(){
    $rows=$this->pdo->query("select * from $this->table")->fetchALL(PDO::FETCH_ASSOC);
    return $rows;
}
}


$db=new DB('user');
echo "<pre>";
print_r($db->all());
echo $db->getTable();

?>