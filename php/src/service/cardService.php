<?php
    
    require_once '../entity/card.php';
    require_once '../setting/setting_database.php';
    
    /**
     * カードを扱うアクション
     */
    class CardService{
        
        /**
         * データベースから取得したデータをここに入れる
         */
        var $cards;
        
        
        /**
         * すべてのCardのデータをデータベースから取得する
         * @return card $cards すべてのカードのデータ
         */
        function getAllCards(){
            
            $this->selectAllCards();
            return $this->cards;
        }
        
        /**
         * すべてのCardのデータをデータベースから取得する
         * @return card $cards すべてのカードのデータ
         */
        function selectAllCards(){
            
            $link = $this->connectDatabase();
            
            $result = mysql_query('SELECT * FROM card');
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }
            
            
            while ($row = mysql_fetch_assoc($result)) {
                $card = new Card();
                
                $card->name = $row['name'];
                $card->start_row_num = $row['start_row_num'];
                $card->date_column_num = $row['date_column_num'];
                $card->shop_column_num = $row['shop_column_num'];
                $card->file_prefix = $row['file_prefix'];
                $card->sum_column_num = $row['sum_column_num'];
                $card->sum_row_num = $row['sum_row_num'];
                
                $cards[] = $card;
            }
            
            $this->cards = $cards;
            //var_dump($cards);
            
            $this->closeDatabase($link);
            
            return $this->cards;
        }
        
        /**
         * あるプリフィクスのCardのデータをデータベースから取得す
         * @return card $cards 該当するカードのデータ（１件のはず、、、、）
         */
        function selectByCardPrefix($name){
            
            //echo "searchword:" . $name . "<br>";
            
            $link = $this->connectDatabase();
            
            $query = 'SELECT * FROM card where file_prefix = "' . $name . '"';
            //echo $query;
            
            $result = mysql_query($query);
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }
            
            while ($row = mysql_fetch_assoc($result)) {
                $card = new Card();
                
                $card->name = $row['name'];
                $card->start_row_num = $row['start_row_num'];
                $card->date_column_num = $row['date_column_num'];
                $card->shop_column_num = $row['shop_column_num'];
                $card->payment_column_num = $row['payment_column_num'];
                $card->file_prefix = $row['file_prefix'];
                $card->sum_column_num = $row['sum_column_num'];
                $card->sum_row_num = $row['sum_row_num'];
                
                $cards[] = $card;
            }
            
            $this->cards = $cards;
            //var_dump($row);
            
            $this->closeDatabase($link);
            
            return $this->cards;
            
        }
        /**
         * 接続を開くメソッド
         * @params ? $link 型が分からんが開くデータベース
         * @return ? $link 型が分からんが開くデータベース
         */
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
        
        /**
         * 接続を閉じるメソッド
         * @params ? $link 型が分からんが開くデータベース
         */
        function closeDatabase($link){
            
            mysql_close($link);
            
        }
        
        
    }
    
    //$cardService = new CardService();
    //$cards = $cardService->getAllCards();
    //var_dump($cards);
    
    
    ?>