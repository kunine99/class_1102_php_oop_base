<?php
interface Sound{
    public function sound();
}

class Animal implements sound{

    // 指定成員時後面只能是xxx 或不指定，不建議改成piblic，這樣以後會不知道誰存取它
    protected $name='';


    protected $age=0;
    protected $heartbeat=0;

    public function __construct(){
// 預設值只能在construct這裡面做
        $this->age=rand(10,20);
        $this->name='john';
        $this->heartbeat=rand(20,60);
    }
    public function sound(){
        return "gi";
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


/* $animal=new Animal;
$dog=new Animal; */
//echo $animal->name;
//$animal->name="mack";
// 外部不能去存取內部
//echo $animal->name;
//echo $animal->age;

// $animal->setName('mack');
// echo $animal->getName();
// echo $animal->getHeartbeat();
// echo "<hr>";
// $dog->setName('herry');
// echo $dog->getName(); */
//echo $animal->heartbeat;

 class Dog extends Animal{
    protected $hair_color="black";


    public function getColor(){
        return $this->hair_color;
    }
    public function sound(){
        return "汪";
    }
    public function setColor($color){
        return $this->hair_color=$color;
    }

    public function getName(){
        return 'my name is '.$this->name;
    }

}
class Cat extends Animal{
    protected $hair_color="black";


    public function getColor(){
        return $this->hair_color;
    }
    public function setColor($color){
        return $this->hair_color=$color;
    }

    public function getName(){
        return 'I am '.$this->name;
    }
    public function sound(){
        return "喵";
    }

}

echo "<h1>狗</h1>";
$dog=new Dog;
echo $dog->getName();
echo "<br>";
$dog->setName('mack');
echo "<br>";
echo $dog->getName();
echo "<br>";
echo $dog->getColor();
echo "<br>";
echo $dog->sound();
echo "<br>";

echo "sdfsdfs";
echo "<h1>貓</h1>";
$cat=new Cat;
echo $cat->getName();
echo "<br>";
$cat->setName('judy');
echo "<br>";
echo $cat->getName();
echo "<br>";
echo $cat->getColor();
echo "<br>";
echo $cat->sound();
echo "<br>";

?>





