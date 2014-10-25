<?php

    require_once '../entity/bill.php';
    require_once '../setting/setting_database.php';

    class BillService{

        var $bills;

        function getAllBills(){

            $this->selectAllBills();
            return $this->bills;
        }

        function addBill($bill){

            $link = $this->connectDatabase();
            $query = 'INSERT INTO bills (month,sum,file_prefix) VALUES ("' . $bill->month . '",' . $bill->sum . ',"' . $bill->file_prefix . '")';

            $result = mysql_query($query);
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }

            $this->closeDatabase($link);

            return $this->bills;

        }
        function updateBill($bill){


            $link = $this->connectDatabase();
            $query = 'UPDATE bills set sum="' . $bill->sum . '" where id=' .$bill->id;
			echo $query;
            $result = mysql_query($query);
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }

            $this->closeDatabase($link);



        }

        function selectAllBills(){

            $link = $this->connectDatabase();

            $result = mysql_query('SELECT * FROM bills');
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }


            while ($row = mysql_fetch_assoc($result)) {
                $bill = new Bill();

                $bill->month = $row['month'];
                $bill->sum = $row['sum'];
                $bill->file_prefix = $row['file_prefix'];

                $bills[] = $bill;
            }

            $this->bills = $bills;
            //var_dump($bills);

            $this->closeDatabase($link);

            return $this->bills;
        }


        function selectById($id){

            $link = $this->connectDatabase();

            $result = mysql_query('SELECT * FROM bills where id=' . $id);
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }

            while ($row = mysql_fetch_assoc($result)) {
                $bill = new Bill();

                $bill->month = $row['month'];
                $bill->sum = $row['sum'];
                $bill->file_prefix = $row['file_prefix'];

                $bills[] = $bill;
            }

            $this->bills = $bills;
            //var_dump($bills);

            $this->closeDatabase($link);

            return $this->bills;
        }


        function selectByBillPrefixAndMonth($month, $prefix){

            //echo "searchword:" . $name . "<br>";

            $link = $this->connectDatabase();

            $query = 'SELECT * FROM bills where file_prefix = "' . $prefix . '" AND month = "' . $month . '"';
            //echo $query;

            $result = mysql_query($query);
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }

            while ($row = mysql_fetch_assoc($result)) {
                $bill = new Bill();

                $bill->id = $row['id'];
                $bill->month = $row['month'];
                $bill->sum = $row['sum'];
                $bill->file_prefix = $row['file_prefix'];

                $bills[] = $bill;
            }

            $this->bills = $bills;
            //var_dump($row);

            $this->closeDatabase($link);

            return $this->bills;

        }

        function selectByMonth($month){

            //echo "searchword:" . $name . "<br>";

            $link = $this->connectDatabase();

            $query = 'SELECT * FROM bills where month = "' . $month . '"';
            //echo $query;

            $result = mysql_query($query);
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }

            while ($row = mysql_fetch_assoc($result)) {
                $bill = new Bill();

                $bill->id = $row['id'];
                $bill->month = $row['month'];
                $bill->sum = $row['sum'];
                $bill->file_prefix = $row['file_prefix'];

                $bills[] = $bill;
            }

            $this->bills = $bills;
            //var_dump($row);

            $this->closeDatabase($link);

            return $this->bills;

        }

        function selectDistinctMonth(){

            //echo "searchword:" . $name . "<br>";

            $link = $this->connectDatabase();

            $query = 'SELECT distinct month from bills order by month desc';
            //echo $query;

            $result = mysql_query($query);
            if (!$result) {
                die('クエリーが失敗しました。'.mysql_error());
            }

            while ($row = mysql_fetch_assoc($result)) {
                $bill = new Bill();

                $bill->month = $row['month'];

                $bills[] = $bill;
            }

            $this->bills = $bills;
            //var_dump($row);

            $this->closeDatabase($link);

            return $this->bills;
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
    $billService = new BillService();
    $bill = new Bill();
    $bill->month = "201008";
    $bill->sum = 20000;
    $bill->file_prefix = "COSTCO";
    $billService->addBill($bill);
    //var_dump($bills);
    //echo count($bills);
     */


    ?>