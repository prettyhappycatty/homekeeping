<?php
    
    require_once '../entity/category.php';
    require_once '../setting/setting_database.php';

    class CategoryService{
        
        var $categories;
        
        function getAllCategories(){
	    

            $this->selectAllCategories();
            return $this->categories;
        }
        
        function selectAllCategories(){
            
            $link = $this->connectDatabase();
            
            
            $result = mysql_query('SELECT * FROM category');
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }
            
            while ($row = mysql_fetch_assoc($result)) {
                $category = new Category();
                
                $category->id = $row['id'];
                $category->name = $row['name'];
                $category->additional_flg = $row['additional_flg'];
                
                $categories[] = $category;
            }
            
            $this->categories = $categories;
            //var_dump($cards);
            
            $this->closeDatabase($link);
            
            
            return $this->categories;
            
        }
        
        function getById($shop_id){
            
            //echo "searchword:" . $sname . "<br>";
            
            $link = $this->connectDatabase();
            
            
            
            $query = 'SELECT * FROM category where id = "' . $shop_id . '"';
            //echo $query;
            
            $result = mysql_query($query);
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }
            
            while ($row = mysql_fetch_assoc($result)) {
                $category = new Category();
                
                $category->id = $row['id'];
                $category->name = $row['name'];
                $category->additional_flg = $row['additional_flg'];
                
                $categories[] = $category;
            }
            
            $this->categories = $categories;
            //var_dump($row);
            
            
            $this->closeDatabase($link);    
             
            return $this->categories;
            
        }
        
        function getByAdditionalFlg($additional_flg){
            
            $link = $this->connectDatabase();
            
            $query = 'SELECT * FROM category where additional_flg = ' . $additional_flg . '';
            
            $result = mysql_query($query);
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }
            
            while ($row = mysql_fetch_assoc($result)) {
                $category = new Category();
                
                $category->id = $row['id'];
                $category->name = $row['name'];
                $category->additional_flg = $row['additional_flg'];
                
                $categories[] = $category;
            }
            
            $this->categories = $categories;
            //var_dump($row);
            
            
            $this->closeDatabase($link);    
            
            return $this->categories;
            
        }
         
        function addCategory($cname){
            
            
            
            $link = $this->connectDatabase();
            
            //INSERT INTO  (name,default_category_id) VALUES ('コストコ　ホールセール　ジャパン', NULL);
            
            $query = 'INSERT INTO category (name) VALUES ("' . $cname . '")';
            //$query = "INSERT INTO  (name,default_category_id) VALUES ('コストコ　ホールセール　ジャパン', NULL)";
            //echo $query;
            
            $result = mysql_query($query);
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }
            
            $this->closeDatabase($link);     
             
        }
        
        function updateCategory($category){
            
            $link = $this->connectDatabase();
            
            //INSERT INTO  (name,default_category_id) VALUES ('コストコ　ホールセール　ジャパン', NULL);
            
            $query = 'UPDATE category set name ="' . $category->name . '", additional_flg=' . $category->additional_flg . ' where id=' . $category->id;
            //$query = "INSERT INTO  (name,default_category_id) VALUES ('コストコ　ホールセール　ジャパン', NULL)";
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
     $categoryService = new CategoryService();
     $categories = $categoryService->selectAllCategories();
     //$categories = categoryService->a();
     //echo $categories;
     //var_dump($categories);
     */
     
    
    
    
?>
