<?php
//老師githube-opp專案的base檔，駐解全刪掉

class DB{

    protected $table;
    protected $dsn="mysql:host=localhost;charset=utf8;dbname=students";
    protected $pdo;

    public function __construct($table){


        $this->pdo=new PDO($this->dsn,'root','');
        $this->table=$table;
    }


    public function all(...$arg){
        $sql="SELECT * FROM $this->table ";

       
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
        

            return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

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


        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

   
    public function math($math,$col,...$arg){
        $sql="SELECT $math($col) FROM $this->table ";

       
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
        
 
            return $this->pdo->query($sql)->fetchColumn();
    }

 
    public function save($array){
        if(isset($array['id'])){
          
            foreach($array as $key => $value){
         
                if($key!='id'){
                    $tmp[]="`$key`='$value'";
                }
            }

            $sql="UPDATE $this->table SET ".implode(" , ",$tmp);
            $sql .= " WHERE `id`='{$array['id']}'";
           
        }else{
           

            $sql="INSERT INTO $this->table (`".implode("`,`",array_keys($array))."`) 
                                     VALUES('".implode("','",$array)."')";

        }

     

        return $this->pdo->exec($sql);
    }


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

      

        return $this->pdo->exec($sql);
    }

    
    public function q($sql){
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

}



$Journal=new DB('journal');

echo "<pre>";
print_r($Journal->save(['money'=>210,
                        'place'=>'義尤味勁',
                        'item'=>'午餐',
                        'type'=>'飲食']));
echo "</pre>"; 

?>