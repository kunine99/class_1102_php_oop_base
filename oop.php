<?php

class Animal{
    // 指定成員時後面只能是xxx 或不指定，不建議改成piblic，這樣以後會不知道誰存取它
    public $name='';

    protected $age=0;
    private $heartbeat=0;

    public function __construct(){
// 預設值只能在construct這裡面做
        $this->age=rand(10,20);
        $this->name='john';
        $this->heartbeat=rand(20,60);
    }


public function getName(){
    return $this->name;
}

public function getHeartbeat(){
    return $this->heartbeat;
}

public function setName($name){
    $this->name=$name;
}



}

$animal=new Animal;

// 外部不能去存取內部
// echo $animal->name;
// echo $animal->age;
echo $animal->getName();
echo "<br>";
$animal->setName('mack');
echo $animal->getName();
echo $animal->getHeartbeat();
 $dog->setName('herry');
 echo $dog->getName();
 //echo $animal->heartbeat;


?>




