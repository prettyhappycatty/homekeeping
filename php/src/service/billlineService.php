<?php

    require_once '../entity/billline.php';
    require_once '../setting/setting_database.php';


    class BilllineService{

        var $billlines;

        function getAllBilllines(){

            $this->selectAllBilllines();
            return $this->billlines;
        }

        function updateBillline($billline){

            $link = $this->connectDatabase();
            $query = 'UPDATE bill_lines SET date="'. $billline->date . '",'
                    . 'shop_id=' . $billline->shop_id . ','
                    . 'category_id=' . $billline->category_id . ','
                    . 'payment=' .$billline->payment . ','
                    . 'row_num=' .$billline->row_num . ','
                    . 'memo="' . $billline->memo . '",'
                    . 'bill_id=' . $billline->bill_id . ' where id=' . $billline->id;
            //echo $query;

            $result = mysql_query($query);
            if (!$result) {
                die('billineserviceクエリーが失敗しました。'.mysql_error());
            }

            $this->closeDatabase($link);

            return $this->billlines;

        }

        function getCategorySumPaymentOfYear($year){

        	$link = $this->connectDatabase();
        	$query = "select category.id, category.name, sum(bill_lines.payment)
        	from category inner join bill_lines on category.id = bill_lines.category_id
        	inner join bills on bills.id = bill_lines.bill_id where bills.month
        	like '" . $year . "%' group by category_id;";
        	//echo $query;

        	$result = mysql_query($query);
        	if (!$result) {
        		die('billineserviceクエリーが失敗しました。'.mysql_error());
        	}

        	while ($row = mysql_fetch_assoc($result)) {

        		//var_dump($row);
        		$billlineSum = new BilllineSum();

        		$billlineSum->category_id = $row['category.id'];
        		$billlineSum->category_name = $row['category.name'];
        		$billlineSum->sum = $row['sum(bill_lines.payment)'];

        		$billlineSums[] = $billlineSum;
        	}

        	$this->closeDatabase($link);

        	return $this->billlines;

        }


        function addBillline($billline){

            $link = $this->connectDatabase();
            $query = 'INSERT INTO bill_lines (date, shop_id ,category_id, payment, row_num, memo, bill_id ) VALUES ("' . $billline->date . '",' . $billline->shop_id . ',' . $billline->category_id . ',' . $billline->payment . ',' . $billline->row_num . ',"'. $billline->memo . '",' . $billline->bill_id . ')';
            //echo $query;

            $result = mysql_query($query);
            if (!$result) {
                die('billineserviceクエリーが失敗しました。'.mysql_error());
            }

            $this->closeDatabase($link);

            return $this->billlines;
        }

        function selectByBillId($bill_id){

            $link = $this->connectDatabase();

            $query = 'SELECT * FROM bill_lines where bill_id = ' . $bill_id;
            //echo $query;
            $result = mysql_query($query);
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }


            while ($row = mysql_fetch_assoc($result)) {
                $billline = new Billline();

                $billline->id = $row['id'];
                $billline->date = $row['date'];
                $billline->shop_id = $row['shop_id'];
                $billline->category_id = $row['category_id'];
                $billline->payment = $row['payment'];
                $billline->row_num = $row['row_num'];
                $billline->memo = $row['memo'];
                $billline->bill_id = $row['bill_id'];


                $billlines[] = $billline;
            }

            $this->billlines = $billlines;
            //var_dump($billlines);

            $this->closeDatabase($link);

            return $this->billlines;

        }

        function selectAllBilllines(){

            $link = $this->connectDatabase();

            $result = mysql_query('SELECT * FROM billlines');
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }


            while ($row = mysql_fetch_assoc($result)) {
                $billline = new Billline();
                /*
                $billline->month = $row['month'];
                $billline->sum = $row['sum'];
                $billline->file_prefix = $row['file_prefix'];
                 */

                 $billline->date = $row['date'];
                 $billline->shop_id = $row['shop_id'];
                 $billline->category_id = $row['category_id'];
                 $billline->payment = $row['payment'];
                 $billline->row_num = $row['row_num'];
                 $billline->memo = $row['memo'];
                 $billline->billline_id = $row['billline_id'];


                $billlines[] = $billline;
            }

            $this->billlines = $billlines;
            //var_dump($billlines);

            $this->closeDatabase($link);

            return $this->billlines;
        }

        function selectByBilllineBillIdAndRowNum($bill_id, $row_num){

            //echo "searchword:" . $name . "<br>";

            $link = $this->connectDatabase();

            $query = 'SELECT * FROM bill_lines where bill_id = ' . $bill_id . ' AND row_num = ' . $row_num . '';
            //echo $query;

            $result = mysql_query($query);
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }

            while ($row = mysql_fetch_assoc($result)) {


                $billline->id = $row['id'];
                $billline->month = $row['month'];
                $billline->sum = $row['sum'];
                $billline->file_prefix = $row['file_prefix'];

                $billlines[] = $billline;

            }

            //var_dump($row);
            $this->billlines = $billlines;

            $this->closeDatabase($link);

            return $this->billlines;

        }


        function getCategorySumsByBillIds($bill_ids){


            $link = $this->connectDatabase();

            if(count($bill_ids) < 1){
                return;
            }

            $bill_id_query = 'bill_id = "' . $bill_ids[0]. '" ';
            for($i = 1; $i < count($bill_ids); $i++){
                $add_query = '|| bill_id = "' . $bill_ids[$i] . '" ';
                $bill_id_query .= $add_query;
            }

            $query = 'select category_id, count(*), sum(payment) from bill_lines where ' . $bill_id_query . 'group by category_id';
            //echo $query;

            $result = mysql_query($query);
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }

            while ($row = mysql_fetch_assoc($result)) {

                //var_dump($row);
                $billlineSum = new BilllineSum();

                $billlineSum->category_id = $row['category_id'];
                $billlineSum->count = $row['count(*)'];
                $billlineSum->sum = $row['sum(payment)'];

                $billlineSums[] = $billlineSum;
            }


            $this->closeDatabase($link);

            return $billlineSums;


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
     $billlineService = new BilllineService();
     $billline = new Billline();
     $billline->date = "20100811";
     $billline->payment = 20000;
     $billline->shop_id = 1;
     $billline->category_id = 1;
    $billline->row_num = 1;
    //$billline->memo ="memo";
    $billline->bill_id = 1;
     $billlineService->addBillline($billline);
     //var_dump($billlines);
     //echo count($billlines);
    */


    ?>
