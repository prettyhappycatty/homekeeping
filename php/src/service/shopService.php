<?php
    
    require_once '../entity/shop.php';
    require_once '../setting/setting_database.php';

    class ShopService{
        
    //echo "test";
        
    var $shops;
        
        //echo "test";
    
    function getAllShops(){
        
        $this->selectAllShops();
        return $this->shops;
    }
    
    function selectAllShops(){
            
        $link = $this->connectDatabase();
        
        $result = mysql_query('SELECT * FROM shop');
        if (!$result) {
            die('クエリーが失敗しました。'.mysql_error());
        }
        
        while ($row = mysql_fetch_assoc($result)) {
            $shop = new Shop();
            
            $shop->id = $row['id'];
            $shop->name = $row['name'];
            $shop->default_category_id = $row['default_category_id'];
            
            $shops[] = $shop;
        }
        
        $this->shops = $shops;
        //var_dump($cards);
        
        $this->closeDatabase($link);
        
        return $this->shops;
        
    }
        
        function getById($shop_id){
            
            //echo "searchword:" . $sname . "<br>";
            
            $link = $this->connectDatabase();
            
            $query = 'SELECT * FROM shop where id = "' . $shop_id . '"';
            //echo $query;
            
            $result = mysql_query($query);
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }
            
            while ($row = mysql_fetch_assoc($result)) {
                $shop = new Shop();
                
                $shop->id = $row['id'];
                $shop->name = $row['name'];
                $shop->default_category_id = $row['default_category_id'];
                
                $shops[] = $shop;
            }
            
            $this->shops = $shops;
            //var_dump($row);
            
            
            $this->closeDatabase($link);        
            return $this->shops;
            
        }
    
    function selectByName($sname){
        
        //echo "searchword:" . $sname . "<br>";
        
        $link = $this->connectDatabase();
        
        $query = 'SELECT * FROM shop where name = "' . $sname . '"';
        //echo $query;
        
        $result = mysql_query($query);
        if (!$result) {
            die('クエリーが失敗しました。'.mysql_error());
        }
        
        while ($row = mysql_fetch_assoc($result)) {
            $shop = new Shop();
            
            $shop->id = $row['id'];
            $shop->name = $row['name'];
            $shop->default_category_id = $row['default_category_id'];
            
            $shops[] = $shop;
        }
        
        $this->shops = $shops;
        //var_dump($row);
        

        $this->closeDatabase($link);        
        return $this->shops;
        
    }
        
        function addShop($shopname){
            
            $link = $this->connectDatabase();
            
            //INSERT INTO shop (name,default_category_id) VALUES ('コストコ　ホールセール　ジャパン', NULL);
            $shopname = mb_convert_encoding($shopname, 'UTF-8', 'auto');
            
            $query = "INSERT INTO shop (name) VALUES ('" . $shopname . "')";
            //$query = "INSERT INTO shop (name,default_category_id) VALUES ('コストコ　ホールセール　ジャパン', NULL)";
            echo $query . "-" . $shopname;
            
            $result = mysql_query($query);
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }
            
            $this->closeDatabase($link);        
        }
        
        function setDefaultCategory($shop_id, $category_id){
            
            //echo "setCategory!<br>";
            
            $link = $this->connectDatabase();
            
            $query = "UPDATE shop set default_category_id = " . $category_id . " where id = " . $shop_id;
            //echo $query;
            
            $result = mysql_query($query);
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }
            
            $this->closeDatabase($link);
             
        }
        
    
    function connectDatabase($link){

	    $databaseSetting = new DatabaseSetting();

            $link = mysql_connect($databaseSetting->setting_database_host, 
$databaseSetting->setting_database_user, $databaseSetting->setting_database_pass);
	    mysql_query('SET NAMES utf8');


            if (!$link) {
                print('接続失敗です。'.mysql_error());
            }
            //print('成功です。');

            $db_selected = mysql_select_db($databaseSetting->setting_database_scheme, $link);
            if (!$db_selected){
                die('データベース選択失敗です。'.mysql_error());
            }

            return $link;

        }
    
    function closeDatabase($link){
        
        mysql_close($link);
        
    }
    
    
}
    
    
/*
$shopService = new ShopService();
$shops = $shopService->selectByName("コストコ　ホールセール　ジャパン");
$shopService->setDefaultCategory(1,1);
var_dump($shops);

*/
    


?>
