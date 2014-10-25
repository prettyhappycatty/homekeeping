
<?php
	echo '<html>';

	echo '<head>';
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
	echo '<link rel="stylesheet" type="text/css" href="../../../lib/extjs-4.1.1/resources/css/ext-all.css" />';
	echo '<script type="text/javascript" src="../../../lib/jquery-ui-1.7.3.custom/js/jquery-1.3.2.min.js"></script>';
	echo '<script type="text/javascript" src="../../../lib/extjs-4.1.1/ext-all.js"></script>';

	echo '<script src="../../../lib/jquery-ui-1.7.3.custom/js/jquery-ui-1.7.3.custom.min.js"></script>';
	echo '<script type="text/javascript" src="../../../lib/extjs-4.1.1/locale/ext-lang-ja.js"></script>';


    require_once '../service/billlineService.php';
    require_once '../service/billService.php';
    require_once '../service/categoryService.php';
    require_once '../service/cardService.php';
    require_once '../service/userService.php';
    require_once '../entity/billline.php';
    require_once '../util/fileUtil.php';

    //とりあえず直近２年分を出すことにする。
    $folderPath = "../../../data/";
    $year[]="2012";

    $fileUtil = new fileUtil();

    $categoryService = new CategoryService();
    $userService = new UserService();
    $cardService = new CardService();
    $cards = $cardService->getAllCards();


    //該当する月のデータの検索
    $billService = new BillService();
    $bill_months = $billService->selectDistinctMonth();

    for($i = 0; $i < count($bill_months); $i++){
        $month = $bill_months[$i]->month;
        //複数の月のデータがある場合、前の月からの差分を取得するため、前月と前月のbillを取得
        if($i < count($bill_months) - 1){
        	$previous_month = $bill_months[$i+1]->month;
        	$previous_bills = $billService->selectByMonth($previous_month);
        }
        //その月のbillのデータを取得
        $bills = $billService->selectByMonth($month);
        //その月のファイルの名称を取得
        $filenames = $fileUtil->searchFileByNames($folderPath, $month);

        //見つかったbill_idを配列にする。
        $bill_ids = array();
        for($k=0; $k < count($bills); $k++){
            $bill_ids[] = $bills[$k]->id;
        }
        //探す。
        $billlineService = new BilllineService();
        $billlineSums = $billlineService->getCategorySumsByBillIds($bill_ids);

        //前の月のデータを取得する
        if($i < count($bill_months) - 1){
        	//見つかったbill_idを配列にする。
	        $previous_bill_ids = array();
	        for($k=0; $k < count($previous_bills); $k++){
	            $previous_bill_ids[] = $previous_bills[$k]->id;
	        }
	        //探す。
	        //$billlineService = new BilllineService();
	        $previous_billlineSums = $billlineService->getCategorySumsByBillIds($previous_bill_ids);
        }

        //echo "<h2>" .$month . "</h2>";
        $tabtitle[] = $month;

        //echo "<h3>カード別</h3>";
        $_tabcontent = "";
        $_tabcontent .= "<h3>カード別</h3>";
        //各カードの合計を表示する＆立替金の取得
        $cash = array();
        //echo '<table border="1">';
        $_tabcontent .= '<table border="1">';
        if(count($bills) > 0){
        	//echo $bills[0]->getTableHeaderHtml();
        	$_tabcontent .= $bills[0]->getTableHeaderHtml();
        }

        for($k = 0; $k<count($bills); $k++){
            //echo $bills[$k]->getTableLineHtml();
        	$_tabcontent .= $bills[$k]->getTableLineHtml();
            if($bills[$k]->file_prefix == "MIYUKI"){
                $cash["miyuki"] = $bills[$k]->sum;
            }else if($bills[$k]->file_prefix == "MASAHIRO"){
                $cash["masahiro"] = $bills[$k]->sum;
            }
        }
        //echo "</table>";
        $_tabcontent .= "</table><hr>";

        //echo "<h3>カテゴリ別</h3>";
        //echo '<a href="./edit_bills.php?month=' . $month .'">[edit]</a><br>';
        $_tabcontent .= "<h3>カテゴリ別</h3>";
        $_tabcontent .= '<a href="./edit_bills.php?month=' . $month .'">[edit]</a><br>';

        //各カテゴリの合計金額を表示する
        //echo "<table border=1><tbody>";
        $_tabcontent .= "<table border=1><tbody>";
        $all_sum = 0;
        if(count($billlineSums) > 0){
            //echo $billlineSums[0]->getTableHeaderHtml();
            $_tabcontent .= $billlineSums[0]->getCompareTableHeaderHtml();
        }
        for($k = 0 ; $k < count($billlineSums); $k++){
            //echo $billlineSums[$k]->getTableLineHtml();
	        //前の月のデータを取得
	        //echo "billmonth:".var_dump($bill_months);
	        if($i < count($bill_months) - 1){
	            $compare_sum = getCategorySum($billlineSums[$k]->category_id, $previous_billlineSums);
	            $compare = $billlineSums[$k]->sum - $compare_sum;
	            $_tabcontent .=  $billlineSums[$k]->getCompareTableLineHtml($compare);
	        }else{
	        	$compare = $billlineSums[$k]->sum;
	        	$_tabcontent .=  $billlineSums[$k]->getCompareTableLineHtml($compare);
	        }
            $all_sum += $billlineSums[$k]->sum;
        }
        //echo "</tbody></table>";
        $_tabcontent .=  "</tbody></table>";

        //echo "合計:" . $all_sum . "<br>";
        $_tabcontent .= "合計:" . $all_sum . "<br>";
        //調整費を計算する
        $additional_categories=$categoryService->getByAdditionalFlg(1);
        //var_dump($additional_categories);
        $additional_payment = 0;

        for($k = 0 ; $k < count($billlineSums); $k++){

            if(isIncludedAdditionalCategories($billlineSums[$k]->category_id, $additional_categories)){
                $additional_payment += $billlineSums[$k]->sum;
            }
        }

        //echo "調整費:" . $additional_payment;
        $_tabcontent .= "調整費:" . $additional_payment . "<hr>";

        //振込額を計算する（ユーザデータから、割合を取得する。）

        $users = $userService->selectAllUsers();
        $bunbo=0;
        if(count($users) > 0){
	        for($k = 0 ; $k < count($users); $k++){
	            $bunbo += $users[$k]->additional_ratio;
	        }

	        //echo "<h3>振込額</h3>";
	        $_tabcontent .= "<h3>振込額(標準振込額＋調整費ー立て替え金)</h3>";
	        $sum_of_user_payment = 0;
	        for($k = 0 ; $k < count($users); $k++){
	            //echo $cash[$users[$k]->name];
	            $user_payment = $users[$k]->default_payment + $additional_payment * $users[$k]->additional_ratio/$bunbo - $cash[$users[$k]->name];
	            //echo $users[$k]->name . ":¥" . round($user_payment, -2) . "<br>";
	            $_tabcontent .=   $users[$k]->name . ":¥" . round($user_payment, -2) . "<br>";
	            $sum_of_user_payment =$sum_of_user_payment +  round($user_payment, -2);
	        }
	        //印刷用に調整
	        $_tabcontent .="<hr>";
	        //月々収支を求める。
	        $saving = 43000;
	        $_tabcontent .="積み立て：" . $saving . "<br>";
	        $_tabcontent .="収支：" . ($sum_of_user_payment - $all_sum - $saving) . "<br>";
	        $_tabcontent .="<hr>";

        }
	        $tabcontent[] = $_tabcontent;

        //echo "<hr>";
    }

    //billlineSumの配列から、カテゴリIdを指定して探すメソッド
    function getCategorySum($category_id, $billlineSumArray){
    	foreach($billlineSumArray as $billlineSum){
    		if($billlineSum->category_id == $category_id) return $billlineSum->sum;
    	}
    	return "0";
    }

    //
    function isIncludedAdditionalCategories($category_id, $additional_categories){
        for($i=0; $i<count($additional_categories); $i++){
            if($category_id == $additional_categories[$i]->id){
                return 1;
            }
        }
        return 0;
    }
?>
<script type="text/javascript">
<!--
Ext.require(['Ext.tabPanel.*']);

Ext.onReady(function()
{

	var _items = [];

	<?php for ($i = 0; $i < count($tabtitle) ; $i++) { ?>
		var _title = '<?php echo $tabtitle[$i];?>';
		var _html = '<?php echo $tabcontent[$i];?>';
	    _items.push({
			title: _title,
			html: _html
	        });
	<?php } ?>

    var myTabPanel = new Ext.TabPanel(
    {
        id: "myTabPanel",
        width: 800,
        //height: 320,
        renderTo: "floatmenu",
        items: _items
    });
});
//-->
</script>
<?php
	echo '</head>';
	echo '<body>';
?>
<!-- Rakuten Widget FROM HERE --><script type="text/javascript">rakuten_design="slide";rakuten_affiliateId="0d2db834.adb4071f.0d2db835.c76f546c";rakuten_items="ctsmatch";rakuten_genreId=0;rakuten_size="728x90";rakuten_target="_blank";rakuten_theme="gray";rakuten_border="on";rakuten_auto_mode="on";rakuten_genre_title="off";rakuten_recommend="on";</script><script type="text/javascript" src="http://xml.affiliate.rakuten.co.jp/widget/js/rakuten_widget.js"></script><!-- Rakuten Widget TO HERE -->
<?php
	echo '<hr><a href="./show_filelist.php">[Import Shops & Bills]</a><br>';
	echo '<div id="floatmenu"></div>';
	echo '</body>';
	echo '</html>';
?>
