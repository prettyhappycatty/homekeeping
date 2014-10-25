<?php

    /** Include path **/

    /** PHPExcel_IOFactory */
    require_once '../service/cardService.php';
    require_once '../action/billsAction.php';
    require_once '../util/fileUtil.php';

    $folderPath = "../../../data/";

    $billsAction = new BillsAction();
    $month = "";

    if($_GET['filepath'] != null && $_GET['card'] != null){

        $filepath = $_GET['filepath'];
        $cardprefix = $_GET['card'];
        $month = split("_", $filepath);
        $month = split(".csv", $month[1]);
        $month = $month[0];

        $billsAction->setGetValue($filepath, $month, $cardprefix);

        echo $billsAction->showBillsFromCsv();


    }else if($_POST['request_type'] == 'import_bills'){

        echo "Bill Successfully Imported!<br>";

        $filepath = $_POST['filepath'];
        $cardprefix = $_POST['card'];
        $month = split("_", $filepath);
        $month = split(".csv", $month[1]);
        $month = $month[0];
        //echo $month . $cardprefix . $filepath;

        $billsAction->setGetValue($filepath, $month, $cardprefix);
        echo $billsAction->importBills();
        echo $billsAction->showBillsFromCsv();

    }

    echo '※店名にデフォルトカテゴリーが設定されていない場合インポートは失敗します。<br>カテゴリー欄が赤になっている行がある場合は、下記のリンクからカテゴリーの編集を先に実施して下さい。<br>';
    echo '<a href="./edit_default_category.php">[Edit Category & Shop]</a>';
    //echo '<a href="./edit_bills.php?month=' . $month .'">[Edit Bills]</a><br>';
    //Post先はどうしよう？
    echo '<form action="./show_csv.php" method="POST">';
    echo '<input type="hidden" name="request_type" value="import_bills">';
    echo '<input type="hidden" name="filepath" value="' . $filepath . '">';
    echo '<input type="hidden" name="card" value="' . $cardprefix . '">';
    echo '<input type="submit" value="インポート">';
    echo '</form>';

/*
    $cardService = new CardService();
    $cards = $cardService->getAllCards();
    //var_dump($cards);

    echo "<br>";

    $fileUtil = new FileUtil();
    for($i = 0; $i < count($cards) ; $i++){
        $filenames = $fileUtil->searchFileByNames($folderPath, $cards[$i]->file_prefix);
        echo $cards[$i]->name . "<br>";
        for($j = 0;$j < count($filenames); $j++){
            echo $filenames[$j] . "<br>";

        }
    }
    */

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
</head>

<body>

</body>
</html>