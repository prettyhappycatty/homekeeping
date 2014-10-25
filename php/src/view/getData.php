<?php

    header("Content-Type: application/xml; charset=UTF-8");
    /*
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    */

    require_once '../service/billlineService.php';
    require_once '../service/billService.php';
    require_once '../service/categoryService.php';
    require_once '../entity/billline.php';
    //とりあえず直近２年分を出すことにする。


    //$year[]="2011";
    $year[]="2012";


    //該当する月のデータの検索
    $billService = new BillService();
    $bill_months = $billService->selectDistinctMonth();
    $categoryService = new categoryService();
    $categories = $categoryService->selectAllCategories();

    //配列の初期化
    for($i=0;$i < count($bill_months);$i++){
        for($j=0;$j < count($categories);$j++){
            $data[$i][$j] = 0;
        }
    }

echo "<months>";
for($i = 0; $i < count($bill_months); $i++){
	echo "<month_data>";
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

        //var_dump($billlineSums);

        echo "<month>" .  $month . "</month>";

        for($k=0; $k<count($categories);$k++){
            $flg = false;
        	for($j=0;$j < count($billlineSums);$j++){
                if($categories[$k]->id == $billlineSums[$j]->category_id){
                	echo "<category_data>";
                	echo "<category_id>" . $billlineSums[$j]->category_id ."</category_id>";
                    echo "<category_sum>" . $billlineSums[$j]->sum . "</category_sum>";
                    echo "</category_data>";
                    $flg = true;
                }
            }
            //金額が０のカテゴリがあってもデータを返す
            if($flg == false){
                	echo "<category_data>";
                	echo "<category_id>" . $categories[$k]->id ."</category_id>";
                	echo "<category_sum>0</category_sum>";
                	echo "</category_data>";
            }
        }

	echo "</month_data>";

    }

    echo "</months>";


?>