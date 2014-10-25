<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- Rakuten Widget FROM HERE --><script type="text/javascript">rakuten_design="slide";rakuten_affiliateId="0d2db834.adb4071f.0d2db835.c76f546c";rakuten_items="ctsmatch";rakuten_genreId=0;rakuten_size="728x90";rakuten_target="_blank";rakuten_theme="gray";rakuten_border="on";rakuten_auto_mode="on";rakuten_genre_title="off";rakuten_recommend="on";</script><script type="text/javascript" src="http://xml.affiliate.rakuten.co.jp/widget/js/rakuten_widget.js"></script><!-- Rakuten Widget TO HERE -->
<title>Edit Bills</title>


</head>
<body>

<h1>Edit Bills </h1>
<?php
    require_once '../service/billService.php';
    require_once '../service/billlineService.php';
    require_once '../entity/billline.php';

    $billService = new BillService();
    $billlineService = new BilllineService();

    if($_GET['month'] != NULL){
        //表示用に遷移してきたとき
        //urlにmonth=201208などが渡される
        $month = $_GET['month'];

    }else if($_POST['id'] != NULL){
        //memoを編集してデータベースを更新するとき
        $billline->id = $_POST['id'];
        $billline->date = $_POST['date'];
        $billline->shop_id = $_POST['shop_id'];
        $billline->category_id = $_POST['category_id'];
        $billline->memo = $_POST['memo'];
        $billline->row_num = $_POST['row_num'];
        $billline->payment = $_POST['payment'];
        $billline->bill_id = $_POST['bill_id'];
        $billlineService->updateBillline($billline);

        $bill = $billService->selectById($billline->bill_id);
        $month = $bill[0]->month;

    }else if($_POST['id'] == NULL){
        //新規にbilllineを作成するとき（現金支出用）、未実装
    }


    $bills = $billService->selectByMonth($month);

    //var_dump($bills);

    for($i=0; $i < count($bills); $i++){
        $billlines = $billlineService->selectByBillId($bills[$i]->id);
        /*
        echo $bills[$i]->file_prefix . "<br>";
        var_dump($billlines);
        */

        echo "<table border=1><tbody>";
        echo $billlines[0]->getTableFormHeaderHtml();
        for($j=0; $j < count($billlines); $j++){
            echo $billlines[$j]->getTableFormLineHtml("./edit_bills.php");
        }
        echo "</tbody></table>";
    }

?>


<body>
</html>