<?php

    header("Content-Type: application/text; charset=UTF-8");

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
    $_html = "";
$_html .=  "{";
$_html .=  '"total":"';
$_html .=  count($bill_months);
$_html .=  '", "month_data" : [';
for($i = 0; $i < count($bill_months); $i++){
	$_html .=  '{';
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

        $_html .=  '"month" : "' .  $month . '"';

        for($k=0; $k<count($categories);$k++){
        $_html .=  ',';
            $flg = false;
        	for($j=0;$j < count($billlineSums);$j++){
                if($categories[$k]->id == $billlineSums[$j]->category_id){
                	$_html .=  '"category' . $billlineSums[$j]->category_id .'":"';
                    $_html .=  $billlineSums[$j]->sum;
                	$_html .=  '"';
                    $flg = true;
                }
            }
            //金額が０のカテゴリがあってもデータを返す
            if($flg == false){
                	$_html .=  '"category' .$categories[$k]->id .'":"';
                	$_html .=  "0";
                	$_html .=  '"';
            }
        }

	$_html .=  "},";

    }
    $_html = rtrim($_html, ",");
    $_html .=  "]}";
    
    echo $_html;


?>