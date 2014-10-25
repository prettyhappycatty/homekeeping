<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<body>
<!-- Rakuten Widget FROM HERE --><script type="text/javascript">rakuten_design="slide";rakuten_affiliateId="0d2db834.adb4071f.0d2db835.c76f546c";rakuten_items="ctsmatch";rakuten_genreId=0;rakuten_size="728x90";rakuten_target="_blank";rakuten_theme="gray";rakuten_border="on";rakuten_auto_mode="on";rakuten_genre_title="off";rakuten_recommend="on";</script><script type="text/javascript" src="http://xml.affiliate.rakuten.co.jp/widget/js/rakuten_widget.js"></script><!-- Rakuten Widget TO HERE -->
<?php

    require_once '../service/billlineService.php';
    require_once '../service/billService.php';
    require_once '../service/categoryService.php';
    require_once '../service/cardService.php';
    require_once '../service/userService.php';
    require_once '../entity/billline.php';
    require_once '../util/fileUtil.php';
    //とりあえず直近２年分を出すことにする。

    //$year[]="2011";
    $folderPath = "../../../data/";
    $year[]="2012";

    $fileUtil = new fileUtil();

    $categoryService = new CategoryService();
    $userService = new UserService();
    $cardService = new CardService();
    $cards = $cardService->getAllCards();

    //post処理
    if($_POST['card_name']){
    	echo "posted!";
		if (is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
			//echo "test0";
			$postfilename = $_POST['card_name'] . "_" . $_POST['year'] . $_POST['month'] . ".csv";
			//echo $postfilename;

			/* utf8,Shift-Jisのファイルに対応するため変換*/
			$file = $_FILES["upfile"]["tmp_name"];
			$data = file_get_contents($file);
			//echo "before" . $data . "<br>END<br>";

			$data = mb_convert_encoding($data, 'utf8', 'sjis-win,utf8');
			//echo "after" . $data . "<br>END<br>";
			file_put_contents($file, $data);

			/* アップロード処理 */
			if (move_uploaded_file($_FILES["upfile"]["tmp_name"], $folderPath . $postfilename)) {
			//if (move_uploaded_file($temp, $folderPath . $postfilename)) {
		    	chmod($folderPath . $_FILES["upfile"]["name"], 0644);
		    	echo $_FILES["upfile"]["name"] . "をアップロードしました。";
		  	} else {
		    	echo "ファイルをアップロードできません。";
		  	}
		} else {
		  echo "ファイルが選択されていません。";
		}
    }

    //アップロードフォーム
    echo '<hr>';
    echo '<form action="./show_filelist.php" method="post" enctype="multipart/form-data">';
    echo 'ファイル：<input type="file" name="upfile" size="30" /><br>';
    echo 'カード：<select name="card_name">';
    for($i = 0; $i < count($cards) ; $i++){
    	echo '<option value="' . $cards[$i]->file_prefix . '">' .$cards[$i]->name . '</option>';
    }
    echo '</select>';
    //日付
    $year = date("Y");
    $month = date("m");
    echo '年月：<select name="year">';
    for($i = $year-1; $i <= $year; $i++){
    	if($i == $year){
	    	echo '<option value="' . $i . '" selected=true>' . $i .'<option>';
    	}else{
    		echo '<option value="' . $i . '" >' . $i . '<option>';
    	}
    }
    echo '</select>年';
    echo '<select name="month">';
    for($i = 1; $i <= 12; $i++){
    	$month_str ="01"; //String型にするため先に代入
    	if($i < 10){//2桁にする
    		$month_str = "0" . $i;
    	}else{
    		$month_str = $i;
    	}
    	if($i == $month){
	    	echo '<option value="' . $month_str . '" selected=true>' . $month_str . '<option>';
    	}else{
    		echo '<option value="' . $month_str . '" >' . $month_str . '<option>';
    	}
    }
    echo '</select>月';

    echo '<input type="submit" value="アップロード" />';
    echo '</form>';

    echo '<hr>';
    echo '<a href="./">[Top]</a>|<a href="./edit_default_category.php">[Edit Default Category & Add Category]</a><br>';

    for($i = 0; $i < count($cards) ; $i++){
        $filenames = $fileUtil->searchFileByNames($folderPath, $cards[$i]->file_prefix);
        echo "<h2>" . $cards[$i]->name . "</h2>";

        for($j = 0;$j < count($filenames); $j++){
            echo $filenames[$j] . "&nbsp;";
            echo '<a href="./show_csv.php?filepath=' . $folderPath. $filenames[$j] .'&card=' . $cards[$i]->file_prefix . '">[import Shops & Preview Bill]</a><br>';

        }
    }


?>
</body>
</html>