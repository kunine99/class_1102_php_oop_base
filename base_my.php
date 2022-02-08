<?php
// 我自己的github原base檔

class DB{
    protected $table;
    protected $dsn="mysql:host=localhost;charset=utf8;dbname=students";
    protected $pdo;

    public function __construct($table){
        $this->pdo=new PDO($this->dsn,'root','');
        $this->table=$table;
    }


// public function all(){
//     $rows=$this->pdo->query("select * from $this->table")->fetchALL(PDO::FETCH_ASSOC);
//     return $rows;

public function all(...$arg){
    $sql="SELECT * FROM $this->table ";

    //依參數數量來決定進行的動作因此使用switch...case
    switch(count($arg)){
        case 1:
    
            //判斷參數是否為陣列
            if(is_array($arg[0])){
    
                //使用迴圈來建立條件語句的字串型式，並暫存在陣列中
                foreach($arg[0] as $key => $value){
    
                    $tmp[]="`$key`='$value'";
    
                }
    
                //使用implode()來轉換陣列為字串並和原本的$sql字串再結合
                $sql.=" WHERE ". implode(" AND " ,$tmp);
            }else{
                
                //如果參數不是陣列，那應該是SQL語句字串，因此直接接在原本的$sql字串之後即可
                $sql.=$arg[0];
            }
        break;
        case 2:
    
            //第一個參數必須為陣列，使用迴圈來建立條件語句的陣列
            foreach($arg[0] as $key => $value){
    
                $tmp[]="`$key`='$value'";
    
            }
    
            //將條件語句的陣列使用implode()來轉成字串，最後再接上第二個參數(必須為字串)
            $sql.=" WHERE ". implode(" AND " ,$tmp) . $arg[1];
        break;
    
        //執行連線資料庫查詢並回傳sql語句執行的結果
        }
    
        //fetchAll()加上常數參數FETCH_ASSOC是為了讓取回的資料陣列中
        //只有欄位名稱,而沒有數字的索引值
       // echo $sql;
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    
    

}

// class Journal extends DB{
// protected $table='journal';
// public function __construct(){
//     parent::__construct($this->table);

// public function find(id){ 我希望我只丟id的話會得到這筆資料


//我們要只取一筆
public function find($id){
    $sql="SELECT * FROM $this->table WHERE ";
    if(is_array($id)){
        
            foreach($id as $key => $value){

                $tmp[]="`$key`='$value'";

            }
            
            $sql .= implode(' AND ',$tmp);

    }else{

        $sql .= " id='$id'";

    }

    //echo $sql;

    return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
}
      //計算某個欄位或是計算符合條件的筆數
    //max,min,sum,count,avg
    public function math($math,$col,...$arg){
        $sql="SELECT $math($col) FROM $this->table ";

        //依參數數量來決定進行的動作因此使用switch...case
        switch(count($arg)){
            case 1:
  
                if(is_array($arg[0])){

                    foreach($arg[0] as $key => $value){
        
                        $tmp[]="`$key`='$value'";

}

$sql.=" WHERE ". implode(" AND " ,$tmp);
}else{
    
    $sql.=$arg[0];
}
break;
case 2:

foreach($arg[0] as $key => $value){

    $tmp[]="`$key`='$value'";

}

$sql.=" WHERE ". implode(" AND " ,$tmp) . $arg[1];
break;

}

// echo $sql;
return $this->pdo->query($sql)->fetchColumn();
}
/*     public function sum($col,...$arg){
$sql="SELECT sum({$col}) FROM $this->table ";

//依參數數量來決定進行的動作因此使用switch...case
switch(count($arg)){
case 1:

if(is_array($arg[0])){

    foreach($arg[0] as $key => $value){

        $tmp[]="`$key`='$value'";

    }

    $sql.=" WHERE ". implode(" AND " ,$tmp);
}else{
    
    $sql.=$arg[0];
}
break;
case 2:

foreach($arg[0] as $key => $value){

    $tmp[]="`$key`='$value'";

}

$sql.=" WHERE ". implode(" AND " ,$tmp) . $arg[1];
break;

}

echo $sql;
return $this->pdo->query($sql)->fetchColumn(0);
}


public function min($col,...$arg){
$sql="SELECT min({$col}) FROM $this->table ";

//依參數數量來決定進行的動作因此使用switch...case
switch(count($arg)){
case 1:

if(is_array($arg[0])){

    foreach($arg[0] as $key => $value){

        $tmp[]="`$key`='$value'";

    }

    $sql.=" WHERE ". implode(" AND " ,$tmp);
}else{
    
    $sql.=$arg[0];
}
break;
case 2:

foreach($arg[0] as $key => $value){

    $tmp[]="`$key`='$value'";

}

$sql.=" WHERE ". implode(" AND " ,$tmp) . $arg[1];
break;

}

echo $sql;
return $this->pdo->query($sql)->fetchColumn(0);
} *


///新增或更新資料











//刪除資料
public function del($id){
    $sql="DELETE FROM $this->table WHERE ";
    if(is_array($id)){

        foreach($id as $key => $value){
    
            $tmp[]="`$key`='$value'";

        }

        $sql .= implode(' AND ',$tmp);

    }else{

        $sql .= " id='$id'";

    }
    
        //echo $sql;

        return $this->pdo->exec($sql);
    }
//萬用的查詢
public function q($sql){
    return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

}

$Journal=new DB('journal');
/* echo "<pre>";
print_r($Journal->del(2));
echo "</pre>";  */
echo "<pre>";
print_r($Journal->q("select * from `journal` where `item`='早餐' && `money` < 200"));

// print_r($Journal->all());
// print_r($Journal->find(填什麼));
// print_r($Journal->find(['item'=>'早餐']));
// 請幫我把這個陣列的第0個位置的count的*印出來  [0]['count(*)']; 
// print_r($Journal->math('count','*',['item'=>'早餐']));

echo "</pre>"; 
/* echo "<pre>";
print_r($Journal->math('sum','money',['item'=>'早餐']));
echo "</pre>"; 
echo "<pre>";
print_r($Journal->math('min','money',['item'=>'早餐']));
echo "</pre>";  */
/* echo "<pre>";
print_r($Journal->all(['item'=>'早餐']));
echo "</pre>";  */



// $db=new DB('user');
/* $db=new DB('journal');
echo "<pre>";
print_r($db->all(['item'=>'早餐']," ORDER by `money` desc"));
echo "</pre>";
echo "<pre>";
print_r($db->all());
echo "</pre>"; */

?>





<!-- 這個function會去拿第一個第0個資料 -->
