<?php
    
    require_once '../entity/user.php';
    require_once '../setting/setting_database.php';
    
    class UserService{
        
        var $users;
        
        function getAllUsers(){
            
            $this->selectAllUsers();
            return $this->users;
        }
        
        function addUser($user){
            
            $link = $this->connectDatabase();
            $query = 'INSERT INTO user (name,default_payment,additional_ratio) VALUES ("' . $user->name . '",' . $user->default_payment . ',"' . $user->additional_ratio . '")';
            
            $result = mysql_query($query);
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }
            
            $this->closeDatabase($link);
            
            return $this->users;
             
        }
        
        function selectAllUsers(){
            
            $link = $this->connectDatabase();
            
            $result = mysql_query('SELECT * FROM user');
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }
            
            
            while ($row = mysql_fetch_assoc($result)) {
                $user = new User();
                
                $user->id = $row['id'];
                $user->name = $row['name'];
                $user->default_payment = $row['default_payment'];
                $user->additional_ratio = $row['additional_ratio'];
                
                $users[] = $user;
            }
            
            $this->users = $users;
            //var_dump($users);
            
            $this->closeDatabase($link);
            
            return $this->users;
        }
        
        
        function selectById($id){
            
            $link = $this->connectDatabase();
            
            $result = mysql_query('SELECT * FROM users where id=' . $id);
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }
            
            while ($row = mysql_fetch_assoc($result)) {
                $user = new User();
                
                $user->id = $row['id'];
                $user->name = $row['name'];
                $user->default_payment = $row['default_payment'];
                $user->additional_ratio = $row['additional_ratio'];
                
                $users[] = $user;
            }
            
            $this->users = $users;
            //var_dump($users);
            
            $this->closeDatabase($link);
            
            return $this->users;
        }
        
        
        function selectByUserPrefixAndMonth($month, $prefix){
            
            //echo "searchword:" . $name . "<br>";
            
            $link = $this->connectDatabase();
            
            $query = 'SELECT * FROM users where file_prefix = "' . $prefix . '" AND month = "' . $month . '"';
            //echo $query;
            
            $result = mysql_query($query);
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }
            
            while ($row = mysql_fetch_assoc($result)) {
                $user = new User();
                
                $user->id = $row['id'];
                $user->name = $row['name'];
                $user->default_payment = $row['default_payment'];
                $user->additional_ratio = $row['additional_ratio'];
                
                $users[] = $user;
            }
            
            $this->users = $users;
            //var_dump($row);
            
            $this->closeDatabase($link);
            
            return $this->users;
            
        }
        
        function selectByMonth($month){
            
            //echo "searchword:" . $name . "<br>";
            
            $link = $this->connectDatabase();
            
            $query = 'SELECT * FROM user where month = "' . $month . '"';
            //echo $query;
            
            $result = mysql_query($query);
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }
            
            while ($row = mysql_fetch_assoc($result)) {
                $user = new User();
                
                $user->id = $row['id'];
                $user->name = $row['name'];
                $user->default_payment = $row['default_payment'];
                $user->additional_ratio = $row['additional_ratio'];
                
                $users[] = $user;
            }
            
            $this->users = $users;
            //var_dump($row);
            
            $this->closeDatabase($link);
            
            return $this->users;
            
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
    $userService = new UserService();
    $user = new User();
    $user->month = "201008";
    $user->sum = 20000;
    $user->file_prefix = "COSTCO";
    $userService->addUser($user);
    //var_dump($users);
    //echo count($users);
     */
    
    
    ?>