<?php


    require_once '../entity/bill.php';
    require_once '../entity/billline.php';
    require_once '../entity/card.php';
    require_once '../service/cardService.php';
    require_once '../service/billService.php';

    require_once '../service/billlineService.php';

    require_once '../service/excelService.php';

    require_once '../service/shopService.php';
    
    /**
     * Billを操作するアクションクラス
     */
    class BillsAction{

        var $filepath;
        var $month;
        var $cardprefix;
        
        /**
         * クラス変数に値をセットするメソッド
         * @param string $filepath ファイルパス
         * @param string $month 月（201209のような文字列）
         * @param string $cardprefix カードプリフィクス
         */
        public function setGetValue($filepath, $month, $cardprefix){

            $this->filepath = $filepath;
            $this->month = $month;
            $this->cardprefix = $cardprefix;

        }
        
        /**
         * Billに含まれるShopをデータベースに追加するメソッド
         * 既にデータベースにショップ名があれば追加しないが、ないものは追加される
         * @return shop[] $addShops 追加されたShopの配列
         */
        public function importShops(){

            $excelService = new ExcelService();
            $sheetData = $excelService->readExcel($this->filepath);

            $shopService = new ShopService();

            $cardService = new CardService();
            $cards = $cardService->selectByCardPrefix($this->cardprefix);
            $card = new Card();
            $card = $cards[0];

            $addedShops = array();

            for($i = $card->start_row_num ; $i < count($sheetData)+1 ; $i++){

                $shopname =$sheetData[$i][$card->shop_column_num];

                if ( 1 > count( $shopService->selectByName($shopname) )){
                    $shopService->addShop($shopname);
                    $addedShops[] = $shopname;

                }
            }

            return $addedShops;

        }
        
        /**
         * Billに含まれるBilllineをデータベースに追加するメソッド
         * Shopに、デフォルトカテゴリがないと失敗する
         */
        public function importBills(){


            // 'Import<br>billid->' . $bills->id;

            $excelService = new ExcelService();
            $sheetData = $excelService->readExcel($this->filepath);

            $cardService = new CardService();
            $cards = $cardService->selectByCardPrefix($this->cardprefix);
            $card = new Card();
            $card = $cards[0];

            $sum = $sheetData[$card->sum_row_num][$card->sum_column_num];
            //echo '合計' . $sum . "<br>";

            $bill = new Bill();
            $bill->sum = $this->format_payment($sum);
            $bill->month = $this->format_month($card->file_prefix, $this->filepath);
            $bill->file_prefix = $card->file_prefix;

            $billService = new BillService();
            $bills = $billService->selectByBillPrefixAndMonth($bill->month, $bill->file_prefix);

            if(count($bills) == 0){
            	//billのレコードがない場合は作る
                $billService->addBill($bill);
                $bills = $billService->selectByBillPrefixAndMonth($bill->month, $bill->file_prefix);
            }else{
            	//ある場合はUpdate
            	$bill->id = $bills[0]->id;
            	$billService->updateBill($bill);
            	echo "UPDATE";
            }

            //echo 'billid->' . $bills->id;

            $shopService = new ShopService();

            for($i = $card->start_row_num ; $i < count($sheetData)+1 ; $i++){

                $shopname =$sheetData[$i][$card->shop_column_num];

                if ( 1 > count( $shopService->selectByName($shopname) )){
                    $shopService->addShop($shopname);
                }
                $shopService = new ShopService();
                $shops = $shopService->selectByName($shopname);

                $billline = new BillLine();
                $billline->date = $sheetData[$i][$card->date_column_num];
                $billline->shop_id = $shops[0]->id;
                $billline->category_id = $shops[0]->default_category_id;
                $billline->payment = $this->format_payment($sheetData[$i][$card->payment_column_num]);
                $billline->row_num = $i - $card->start_row_num;
                $billline->memo = '';
                $billline->bill_id = $bills[0]->id;

                $billlineService = new BilllineService();
                $billlines = $billlineService->selectByBilllineBillIdAndRowNum($billline->bill_id, $billline->row_num);
                if ( 1 > count($billlines)){
                    $billlineService->addBillline($billline);
                    $billlines = $billlineService->selectByBilllineBillIdAndRowNum($billline->bill_id, $billline->row_num);
                }


            }


        }

        
        /**
         * csvファイルを表示するメソッド
         * @return $result 表示用のHTML
         */
        public function showBillsFromCsv(){

            //
            $result = '';

            $cardService = new CardService();
            $cards = $cardService->selectByCardPrefix($this->cardprefix);
            //var_dump($cards[0]);



            $excelService = new ExcelService();
            $sheetData = $excelService->readExcel($this->filepath);

            $shopService = new ShopService();

            $card = new Card();
            $card = $cards[0];


            //var_dump($sheetData);
            $row_num = $card->sum_row_num;
            $column_num = $card->sum_column_num;
            $sum = $sheetData[$row_num][$column_num];
	

            // "total:" . $sum;

            $bill = new Bill();
            $bill->sum = $this->format_payment($sum);
            $bill->month = $this->format_month($card->file_prefix, $this->filepath);
            $bill->file_prefix = $card->file_prefix;

            //$bill
            //echo $bill->getStandardHtml();
            //$result .= $bill->getStandardHtml();
            $result .= "<h1>" . $bill->month . "-" . $bill->file_prefix . "</h1>";
            $result .= "<hr>";
            $result .= '<table border=1>';

            $billline = new BillLine();
            $result .= $billline->getTableHeaderHtml();

            $billService = new BillService();


            $bills = $billService->selectByBillPrefixAndMonth($bill->month, $bill->file_prefix);

            //echo $bill->getStandardHtml();
            if(count($bills) == 0){

                $billService->addBill($bill);

                $bills = $billService->selectByBillPrefixAndMonth($bill->month, $bill->file_prefix);
            }

            //var_dump($bills);
            //echo count($bills);


            $billlines = array();
            for($i = $card->start_row_num ; $i < count($sheetData)+1 ; $i++){

                $shopname =$sheetData[$i][$card->shop_column_num];
                //echo $shopname;
                //echo count( $shopService->selectByName($shopname) );

                if ( 1 > count( $shopService->selectByName($shopname) )){
                    $shopService->addShop($shopname);
                }
                $shopService = new ShopService();
                $shops = $shopService->selectByName($shopname);
                //echo 'shops:';
                //echo $shops[0]->id

                $billline->date = $sheetData[$i][$card->date_column_num];
                $billline->shop_id = $shops[0]->id;

                $billline->category_id = $shops[0]->default_category_id;
                $billline->payment = $this->format_payment($sheetData[$i][$card->payment_column_num]);
                $billline->row_num = $i - $card->start_row_num;
                $billline->memo = '';
                $billline->bill_id = $bills[0]->id;

                $billlines[]=$billline;
                $result .= $billline->GetTableLineHtml();

            }

            $result .= '</table>';

            return $result;


        }

        private function format_payment($payment){

            $p = $payment;

            //¥と、,を削除する
            $p = str_replace(',', '', $p);
            $p = str_replace('¥', '', $p);

            $ret = $p;

            return $ret;

        }

        private function format_month($fileprefix, $filepath){

            $m = split($fileprefix . '_', $filepath);
            $m = str_replace('.csv', '', $m[1]);

            $ret = $m;

            return $ret;
        }



    }



    ?>
<html>
<body>

<?php
/*
	$filepath = $_GET['filepath'];
    $month = $_GET['month'];
    $cardprefix = $_GET['cardprefix'];

    //echo 'filepath-billaction-html:' . $filepath . '<br>';
    //echo 'filepath-billaction-pre-html:' . $cardprefix . '<br>';

    $billsAction = new BillsAction();
    $billsAction->setGetValue($filepath, $month, $cardprefix);
    //$shops = $a->importShops();
    $result = $billsAction->showBillsFromCsv();

    echo $result;

    //var_dump($shops);
*/
    ?>

</body>
</html>