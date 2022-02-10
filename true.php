<?php
//老師hackmd筆記+課堂上的筆記+自己看老師其他的hackmd筆記做出來的註解大全
//https://mackliu.github.io/php-book/2021/09/21/php-lesson-03/
//https://mackliu.github.io/php-book/categories/%E7%B6%B2%E9%A0%81%E6%8A%80%E8%A1%93/%E8%B3%87%E6%96%99%E5%BA%AB/

// 一般自訂函式宣告方式
// function name($var1,$var2){
//      //程式碼內容}
// 有幾點特性要注意：
// function中的變數有區域性
// 要取用function外的全域變數時使用global關鍵字
// 回傳值使用return
// 可設定參數的預設值
class DB
{

    //宣告成員屬性
    // 未宣告權限則預設為public
    // Public -> 外部可以自由存取
    // Protect -> 內部和有繼承關係的可以存取
    // Private -> 僅限類別內部取用

    // * 建立資料庫基本資料，主要是資料庫系統名稱，主機名稱，使用的資料庫等等資訊
    // *  host => 主機名稱或是IP
    // *  charset => 使用的字元集，一般選utf8即可
    // *　dbname => 使用的資料庫名稱
    private $dsn = "mysql:host=localhost;charset=utf8;dbname=db01";
    private $root = 'root';
    private $password = '';
    private $table;
    private $pdo;

    // 先宣告全部函式都會用到的資料庫連線設定及建立PDO資料庫物件
    // $dsn="mysql:host=localhost;charset=utf8;dbname=dbname";
    // $pdo=new PDO($dsn,'root','password');


    //建立建構式，在建構時帶入table名稱會建立資料庫的連線
    public function __construct($table)
    {
        // 預設值只能在construct這裡面做
        // * 使用new 語法來建立一個PDO連線物件，並將這個物件指定給一個變數，
        // * 方便接下來的操作
        // * 第一個參數位置是資料庫的設定資料
        // * 第二個參數是資料庫的使用者帳號
        // * 第三個參數是資料庫的使用者密碼
        // * 第四個參數是附加設定資料，以陣列方式呈現，這個參數可不填
        $this->table = $table;
        $this->pdo = new PDO($this->dsn, $this->root, $this->password);
    }

    //$table->all()-查詢符合條件的全部資料
    //此方法可能會有不帶參數，一個參數及二個參數的用法，因此使用不定參數的方式來宣告
    //all()-給定資料表名和條件後，會回傳符合條件的所有資料
    public function all(...$arg)
    {
        /**
         * $table - 資料表名稱 字串型式
         * ...$arg - 參數型態
         *           1. 沒有參數，撈出資料表全部資料
         *           2. 一個參數：
         *              a. 陣列 - 撈出符合陣列key = value 條件的全部資料
         *              b. 字串 - 撈出符合SQL字串語句的全部資料
         *           3. 二個參數：
         *              a. 第一個參數必須為陣列，同2-a描述
         *              b. 第二個參數必須為字串，同2-b描述
         */


        //在class中要引用內部的成員使用$this->成員名稱或方法
        //當參數數量不為1或2時，那麼此方法就只會執行選取全部資料這一句SQL語法
        ////建立共有的基本SQL語法

        $sql = "SELECT * FROM $this->table ";


        //依參數數量來決定進行的動作因此使用switch...case
        switch (count($arg)) {
            case 1:

                //判斷參數是否為陣列
                if (is_array($arg[0])) {

                    //使用迴圈來建立條件語句的字串型式，並暫存在陣列中
                    foreach ($arg[0] as $key => $value) {

                        $tmp[] = "`$key`='$value'";
                    }

                    //使用implode()來轉換陣列為字串並和原本的$sql字串再結合
                    $sql .= " WHERE " . implode(" AND ", $tmp);
                } else {

                    //如果參數不是陣列，那應該是SQL語句字串，因此直接接在原本的$sql字串之後即可
                    $sql .= $arg[0];
                }
                break;
            case 2:

                //第一個參數必須為陣列，使用迴圈來建立條件語句的陣列
                foreach ($arg[0] as $key => $value) {

                    $tmp[] = "`$key`='$value'";
                }

                //將條件語句的陣列使用implode()來轉成字串，最後再接上第二個參數(必須為字串)
                $sql .= " WHERE " . implode(" AND ", $tmp) . " " . $arg[1];
                break;

                //執行連線資料庫查詢並回傳sql語句執行的結果
        }


        //echo $sql;  //保留echo $sql 除錯時可用

        //fetchAll()加上常數參數FETCH_ASSOC是為了讓取回的資料陣列中只有欄位名稱
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    //$table->math($math,…$arg)-使用聚合函式來計算某個欄位的結果
    //math()-使用指定的聚合函數進行資料表的計算或取值
    public function math($math, $col, ...$arg)
    {
        $sql = "SELECT $math($col) FROM $this->table ";

        //依參數數量來決定進行的動作因此使用switch...case
        switch (count($arg)) {
            case 1:

                //判斷參數是否為陣列
                if (is_array($arg[0])) {

                    //使用迴圈來建立條件語句的字串型式，並暫存在陣列中
                    foreach ($arg[0] as $key => $value) {

                        $tmp[] = "`$key`='$value'";
                    }

                    //使用implode()來轉換陣列為字串並和原本的$sql字串再結合
                    $sql .= " WHERE " . implode(" AND ", $tmp);
                } else {

                    //如果參數不是陣列，那應該是SQL語句字串，因此直接接在原本的$sql字串之後即可
                    $sql .= $arg[0];
                }
                break;
            case 2:

                //第一個參數必須為陣列，使用迴圈來建立條件語句的陣列
                foreach ($arg[0] as $key => $value) {

                    $tmp[] = "`$key`='$value'";
                }

                //將條件語句的陣列使用implode()來轉成字串，最後再接上第二個參數(必須為字串)
                $sql .= " WHERE " . implode(" AND ", $tmp) . " " . $arg[1];
                break;

                //執行連線資料庫查詢並回傳sql語句執行的結果
        }


        //echo $sql;  //保留echo $sql 除錯時可用

        //fetchColumn()只會取回的指定欄位資料預設是查詢結果的第1欄位的值
        return $this->pdo->query($sql)->fetchColumn();
    }

    //只取一筆
    //$table->find($id)-查詢符合條件的單筆資料
    //find()-會回傳資料表指定條件的單筆資料
    public function find($id)
    {
        /**
         * $table - 資料表名稱 字串型式
         * $arg 參數型態
         *      1. 陣列 - 撈出符合陣列key = value 條件的單筆資料
         *      2. 字串 - 必須是資料表的id，數字型態，且資料表有id這個欄位
         */
        $sql = "SELECT * FROM $this->table where ";

        if (is_array($id)) {

            foreach ($id as $key => $value) {

                $tmp[] = "`$key`='$value'";
            }

            $sql .= implode(" AND ", $tmp);
        } else {

            $sql .= " `id`='$id'";
        }

        //echo $sql;
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    //刪除資料
    //$table->del($id)-刪除資料
    //del()-給定條件後，會去刪除指定的資料
    public function del($id)
    {
        /**
         * $table - 資料表名稱 字串型式
         * $arg 參數型態
         *      1. 陣列 - 刪除符合陣列key = value 條件的所有資料
         *      2. 字串 - 必須是資料表的id，數字型態，且資料表有id這個欄位
         */

        $sql = "DELETE from $this->table where ";

        if (is_array($id)) {

            foreach ($id as $key => $value) {

                $tmp[] = "`$key`='$value'";
            }

            $sql .= implode(" && ", $tmp);
        } else {

            $sql .= "`id`='$id'";
        }

        //echo $sql;
        return $this->pdo->exec($sql);
    }

    //新增或更新資料,僅限一次一筆資料
    //利用新增和更新語法的特點整合兩個動作為一個，簡化函式的數量並提高函式的通用性；
    //由於是針對特定目的設計的自訂函式，所以使用此函時，資料表必須有 id 這個欄位。
    public function save($array)
    {

        //判斷資料陣列中是否有帶有 'id' 這個欄位，有則表示為既有資料的更新
        //沒有 'id' 這個欄位則表示為新增的資料
        if (isset($array['id'])) {
            //update
            foreach ($array as $key => $value) {

                if ($key != 'id') {

                    $tmp[] = "`$key`='$value'";
                }
            }

            //建立更新資料(update)的sql語法
            $sql = "UPDATE $this->table set " . implode(',', $tmp) . " where `id`='{$array['id']}'";
        } else {
            //insert

            //建立新增資料(insert)的sql語法
            $sql = "INSERT into $this->table (`" . implode("`,`", array_keys($array)) . "`)values('" . implode("','", $array) . "')";

            /* 覺得一行式寫法太複雜可以利用變數把語法拆成多行再組合
             * $cols=implode("`,`",array_keys($array));
             * $values=implode("','",$array);
             * $sql="INSERT INTO $table (`$cols`) VALUES('$values')";        
             */
        }
        //echo $sql;
        return $this->pdo->exec($sql);
    }

    //萬用的查詢
    //$table->q($sql)-複雜語法回傳全部資料
    //q()-可以用來撰寫較為複雜的SQL語句，並回傳查詢結果的全部資料
    public function q($sql)
    {
        //$sql - SQL語句字串，取出符合SQL語句的全部資料
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}

//$to($url)-頁面導向輔助函式
//此函式會獨立在 DB 這個類別外，但是會和共用檔放在一起，然後include到所有的頁面去使用
//主要目的是簡化header指令的語法，避免拚字錯誤之類的事發生。
//to()-頁面導向，取代header(‘location:url’)
function to($url)
{
    //$url - 要導向的檔案路徑及檔名
    header("location:" . $url);
}

//有些題組會使用到時間，直接修改apache設定檔或php.ini都可以
//但如果是一般坊間的server，可能沒有提供使用者去更改全域設定的功能
//此時可以簡單的加上一個動態時區設定的語法，讓我們的程式在執行期間可以使用我們自行設定的時區：
date_default_timezone_set("Asia/Taipei");

//有很多功能需要透過session來暫存狀態，因此我們可以在共用檔中先啟月session
//方便在各個頁面都可以操作session。
session_start();
//建議使用首字母大寫來代表這是資料表的變數，方便和全小寫的變數做出區隔
$User = new DB('user');
$Menu = new DB('menu');
//etc......