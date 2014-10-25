<?php

    require_once '../service/categoryService.php';
    require_once '../service/shopService.php';

/*
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Asia/Tokyo');
*/
    echo '<a href="./">[Top]</a>';

    echo $_POST['category_name'] . $_POST['category_id'];


    if($_POST['category_name'] != NULL){
        //カテゴリー追加の場合
        addCategory($_POST['category_name']);

    }else if($_POST['shop_id'] != NULL){
        //shopのデフォルトカテゴリの変更の場合
        echo "updateDefalutCategory:" . $_POST['shop_id'] . $_POST['category_id'];
        changeCategory($_POST['shop_id'], $_POST['category_id']);


    }else if($_POST['category_id'] != NULL){
        //shop_idはなくてcategory_idがある→カテゴリの変更の場合
        echo "updateCategory:" . $_POST['category_id'];
        $category = new Category();
        $category->id = $_POST['category_id'];
        $category->name = $_POST['name'];
        $category->additional_flg = $_POST['additional_flg'];
        updateCategory($category);


    }

    echo "categories<br>";
    $categoryService = new CategoryService();
    $categories = $categoryService->selectAllCategories();

    echo '<table border=1><tbody>';
    echo '<tr>';
    echo '<th>id</th>';
    echo '<th>name</th>';
    echo '<th>additional_flg</th>';
    echo '<th>&nbsp;</th>';
    echo '</tr>';
    for($i = 0; $i<count($categories); $i++){
        echo '<form method="POST" action="./edit_default_category.php">';
        echo "<tr>";
        echo '<td><input type="hidden" name="category_id" value="' . $categories[$i]->id .'"></td>';
        echo '<td><input type="text" name="name" value="' . $categories[$i]->name.'"></td>';
        echo '<td><select name="additional_flg">';
        if($categories[$i]->additional_flg == 1){
            echo '<option value="0">非対象</option>';
            echo '<option value="1" selected=true>対象</option>';
        }else{
            echo '<option value="0" selected=true>非対象</option>';
            echo '<option value="1">対象</option>';
        }
        echo '</select></td>';
        echo '<td><input type="submit" value="送信する"></td>';
        echo "</tr>";
        echo '</form>';
    }
    echo "</tbody></table>";


    function addCategory($categoryName){
        echo "addCategory!<br>";
        $categoryService = new CategoryService();
        $categoryService->addCategory($categoryName);
    }

    function changeCategory($shopId, $categoryId){

        $shopService = new ShopService();
        $shopService->setDefaultCategory($shopId,$categoryId);
        //var_dump($shops);

    }

    function updateCategory($category){

        $categoryService = new CategoryService();
        $categoryService->updateCategory($category);
    }


?>
<html>
<head>
<title>edit_default_category</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
</head>
<body>
<form method="POST" action="./edit_default_category.php">
<div>カテゴリー名：<input type="text" name="category_name"></div>
<input type="submit" value="送信">
<input type="reset" value="取消">
</form>
</body>
</html>
<hr>

<?php
    echo "shops:<br>";

    $shopService = new ShopService();
    $shops = $shopService->selectAllShops();


    //var_dump($shops);

    echo '<table border=1><tbody>';
    echo '<tr>';
    echo '<th>name</th>';
    echo '<th>before</th>';
    echo '<th>after</th>';
    echo '<th>&nbsp;</th>';
    echo '</tr>';
    for($i = count($shops)-1 ; $i > -1 ; $i--){
        echo '<form method="POST" action="./edit_default_category.php">';
        echo "<tr>";
        echo '<input type="hidden" name="shop_id" value="' . $shops[$i]->id .'">';
        echo '<td>' . $shops[$i]->name . '</td>';
        $category = $categoryService->getById($shops[$i]->default_category_id);
        echo '<td>' . $category[0]->name . '</td>';
        //selectbox;
        echo '<td><select name="category_id">';
        for($j = 0; $j < count($categories); $j++){
            if($shops[$i]->default_category_id == $categories[$j]->id){
                echo '<option value="' . $categories[$j]->id .  '" selected=true>' .$categories[$j]->name . '</option>';

            }else{
            echo '<option value="' . $categories[$j]->id .'">' .$categories[$j]->name . '</option>';
            }
        }

        echo '</select></td>';
        echo '<td><input type="submit" value="送信する"></td>';
        echo "<tr>";
        echo '</form>';
    }
    echo '</tbody></table>';

?>