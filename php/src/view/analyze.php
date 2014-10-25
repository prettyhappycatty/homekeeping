<?php
    
    require_once '../service/billlineService.php';
    require_once '../service/billService.php';
    require_once '../entity/billline.php';
    //とりあえず直近２年分を出すことにする。
    
    //$year[]="2011";
    $year[]="2012";
    
            
    //該当する月のデータの検索
    $billService = new BillService();
    $bill_months = $billService->selectDistinctMonth();
    
    for($i = 0; $i < count($bill_months); $i++){
        $month = $bill_months[$i]->month;
        $bills = $billService->selectByMonth($month);
            
        //見つかったbill_idを配列にする。
        $bill_ids = array();
        for($k=0; $k < count($bills); $k++){
            $bill_ids[] = $bills[$k]->id;
        }
        //探す。
        $billlineService = new BilllineService();
        $billlineSums = $billlineService->getCategorySumsByBillIds($bill_ids);
        echo $month . "<br>";
        echo "<table border=1><tbody>";
        if(count($billlineSums) > 0){
            echo $billlineSums[0]->getTableHeaderHtml();
        }
        for($k = 0 ; $k < count($billlineSums); $k++){
            echo $billlineSums[$k]->getTableLineHtml();
        }
        echo "</tbody></table>";
        echo "<hr>";
    }
    



?>