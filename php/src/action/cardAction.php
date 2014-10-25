<?php

    require_once '../entity/card.php';

    /**
     * カードを扱うアクション
     */
    class CardAction{

        
        var $cards;
        
        /**
         * （サービスですべき内容だ）
         */
        function getAllCards(){

            $this->selectAllCards();
            return $this->cards;
        }

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
        }

        function connectDatabase($link){

            $link = mysql_connect('localhost', 'housekeeper', 'housekeeper');

            if (!$link) {
                print('接続失敗です。'.mysql_error());
            }
            print('成功です。');

            $db_selected = mysql_select_db('housekeeping', $link);
            if (!$db_selected){
                die('データベース選択失敗です。'.mysql_error());
            }

            return $link;

        }

        function closeDatabase($link){

            mysql_close($link);

        }


    }

    $cardAction = new CardAction();
    $cards = $cardAction->getAllCards();
    var_dump($cards);


?>

<a href="../entity/card.php">phps</a>