<?php

    require_once '../service/shopService.php';
    require_once '../service/categoryService.php';
    require_once '../helper/categoryHelper.php';
    require_once '../helper/shopHelper.php';

    class BillLine{
        var $id;
        var $date;
        var $shop_id;
        var $category_id;
        var $payment;
        var $row_num;
        var $memo;
        var $bill_id;

        public function getStandardHtml(){
            $ret = "";
            $ret .= "id=" . $this->id . "<br>";
            $ret .= "date=" . $this->date . "<br>";
            $ret .= "shop_id=" . $this->shop_id . "<br>";
            $ret .= "category_id=" . $this->category_id . "<br>";
            $ret .= "payment=" . $this->payment . "<br>";
            $ret .= "row_num=" . $this->row_num . "<br>";
            $ret .= "memo=" . $this->memo . "<br>";
            $ret .= "bill_id=" . $this->bill_id . "<br>";
            return $ret;
        }

        public function getTableHeaderHtml(){

            $ret = "<tr>";
            $ret .= "<th>id</th>";
            $ret .= "<th>date</th>";
            $ret .= "<th>shop_id</th>";
            $ret .= "<th>category_id</th>";
            $ret .= "<th>payment</th>";
            $ret .= "<th>row_num</th>";
            $ret .= "<th>memo</th>";
            $ret .= "<th>bill_id</th>";
            $ret .= "</tr>";

            return $ret;
        }

        public function getTableLineHtml(){

            $shopService = new ShopService();
            $shops = $shopService->getById($this->shop_id);
            $categoryService = new CategoryService();
            $categories = $categoryService->getById($this->category_id);

            $ret = "<tr>";
            $ret .= "<td>" . $this->id . "</td>";
            $ret .= "<td>" . $this->date . "</td>";
            //$ret .= "<td>" . $this->shop_id . "=" . $shops[0]->name ."</td>";
            $ret .= "<td>" . $this->shop_id . "=" . mb_convert_encoding($shops[0]->name, "UTF-8", "UTF-8,EUC-JP") ."</td>";
	if($this->category_id == ""){
            $ret .= '<td bgcolor="red">' . $this->category_id . "=" . mb_convert_encoding($categories[0]->name, "UTF-8", "UTF-8,EUC-JP") . "</td>";
	}else{

            $ret .= "<td>" . $this->category_id . "=" . mb_convert_encoding($categories[0]->name, "UTF-8", "UTF-8,EUC-JP") . "</td>";
}
            $ret .= "<td>" . $this->payment . "</td>";
            $ret .= "<td>" . $this->row_num . "</td>";
            $ret .= "<td>" . $this->memo . "</td>";
            $ret .= "<td>" . $this->bill_id . "</td>";
            $ret .= "</td>";

            return $ret;
        }

        public function getTableFormHeaderHtml(){

            $ret = "<tr>";
            //$ret .= "<th>id</th>";
            $ret .= "<th>date</th>";
            $ret .= "<th>shop_id</th>";
            $ret .= "<th>category_id</th>";
            $ret .= "<th>payment</th>";
            //$ret .= "<th>row_num</th>";
            $ret .= "<th>memo</th>";
            $ret .= "<th>bill_id</th>";
            $ret .= "</tr>";

            return $ret;
        }

        public function getTableFormLineHtml($post_url){
            //変更可能なのは、カテゴリーIdとmemo

            $shopService = new ShopService();
            $shops = $shopService->getById($this->shop_id);
            $categoryService = new CategoryService();
            $categories = $categoryService->selectAllCategories();
            $categoryHelper = new CategoryHelper();

            $ret = "<tr>";
            $ret .= '<form action = ' . $post_url . ' method="POST">';
            //$ret .= "<td>" . $this->id . "</td>";
            $ret .= "<td>" . $this->date . "</td>";
            $ret .= "<td>" . $this->shop_id . "=" . $shops[0]->name ."</td>";
            $ret .= "<td>" . $categoryHelper->getSelectBox("category_id", $this->category_id, $categories) . "</td>";
            $ret .= "<td>" . $this->payment . "</td>";
            //$ret .= "<td>" . $this->row_num . "</td>";
            $ret .= '<td><input type="text" name="memo" value="' . $this->memo . '"></td>';
            $ret .= '<td><input type="submit" value = "送信する"></td>';
            $ret .= '<input type="hidden" name="id" value="' . $this->id . '">';
            $ret .= '<input type="hidden" name="date" value="' . $this->date . '">';
            $ret .= '<input type="hidden" name="shop_id" value="' . $this->shop_id . '">';
            $ret .= '<input type="hidden" name="row_num" value="' . $this->row_num . '">';
            $ret .= '<input type="hidden" name="bill_id" value="' . $this->bill_id . '">';
            $ret .= '<input type="hidden" name="payment" value="' . $this->payment . '">';
            $ret .= "</form>";
            $ret .= "</td>";

            return $ret;
        }

        public function getTableCreateFormLineHtml($post_url){
            //新規作成（現金支出記録）用。すべて入力可

            $shopService = new ShopService();
            $shops = $shopService->selectAllShops();
            $categoryService = new CategoryService();
            $categories = $categoryService->selectAllCategories();
            $categoryHelper = new CategoryHelper();
            $shopHelper = new ShopHelper();

            $ret = "<tr>";
            $ret .= '<form action = ' . $post_url . ' method="POST">';
            //$ret .= "<td>" . $this->id . "</td>";
            //$ret .= "<td>" . $this->date . "</td>";
            $ret .= '<td><input type="text" name="date" value=""></td>';
            //$ret .= "<td>" . $this->shop_id . "=" . $shops[0]->name ."</td>";
            $ret .= "<td>" . $shopHelper->getSelectBox("shop_id", NULL, $shops) . "</td>";
            $ret .= '<td><input type="text" name="payment" value=""></td>';
            $ret .= "<td>" . $categoryHelper->getSelectBox("category_id", NULL, $categories) . "</td>";
            //$ret .= "<td>" . $this->payment . "</td>";
            $ret .= '<td><input type="text" name="payment" value=""></td>';
            //$ret .= "<td>" . $this->row_num . "</td>";
            $ret .= '<td><input type="text" name="memo" value=""></td>';
            $ret .= '<td><input type="submit" value = "送信する"></td>';
            $ret .= '<input type="hidden" name="id" value="' . $this->id . '">';
            $ret .= "</form>";
            $ret .= "</td>";

            return $ret;
        }


        public function getTableEditFormLineHtml($post_url){

            //編集（現金支出記録）用。すべて編集可

            $shopService = new ShopService();
            $shops = $shopService->getById($this->shop_id);
            $categoryService = new CategoryService();
            $categories = $categoryService->getById($this->category_id);

            $ret = "<tr>";
            $ret .= '<form action = ' . $post_url . ' method="POST">';
            //$ret .= "<td>" . $this->id . "</td>";
            $ret .= "<td>" . $this->date . "</td>";
            //$ret .= "<td>" . $this->shop_id . "=" . $shops[0]->name ."</td>";
            $ret .= "<td>" . $this->shop_id . "=" . mb_convert_encoding($shops[0]->name, "EUC-JP", "UTF-8,EUC-JP") ."</td>";
            //$ret .= "<td>" . $this->category_id . "=" . $categories[0]->name. "</td>";
            //$ret .= "<td>" . $this->category_id . "=" . mb_convert_encoding($categories[0]->name,"EUC-JP", "UTF-8,EUC-JP") . "</td>";
            $ret .= "<td>" . $this->payment . "</td>";
            //$ret .= "<td>" . $this->row_num . "</td>";
            $ret .= '<td><input type="text" name="memo" value="' . $this->memo . '"></td>';
            $ret .= '<td><input type="submit" value = "送信する"></td>';
            $ret .= '<input type="hidden" name="id" value="' . $this->id . '">';
            $ret .= "</form>";
            $ret .= "</td>";

            return $ret;
        }
    }

    class BilllineSum{

        var $category_id;
        var $count;
        var $sum;

            public function getTableHeaderHtml(){

            $ret = "<tr>";
            $ret .= "<td>category_id</td>";
            $ret .= "<td>count</td>";
            $ret .= "<td>sum</td>";
            $ret .= "</td>";

            return $ret;
        }

        public function getCompareTableHeaderHtml(){

        	$ret = "<tr>";
        	$ret .= "<td>category_id</td>";
        	$ret .= "<td>count</td>";
        	$ret .= "<td>sum</td>";
        	$ret .= "<td>compare</td>";
        	$ret .= "</td>";

        	return $ret;
        }

   	 	public function getTableLineHtml($cmp){

            $categoryService = new CategoryService();
            $categories = $categoryService->getById($this->category_id);

            $ret = "<tr>";
            $ret .= "<td>" . $this->category_id . "=" . $categories[0]->name . "</td>";
            $ret .= "<td>" . $this->count . "</td>";
            $ret .= '<td align="right">' . $this->sum . '</td>';
            $ret .= "</td>";

            return $ret;
        }

        public function getCompareTableLineHtml($cmp){

            $categoryService = new CategoryService();
            $categories = $categoryService->getById($this->category_id);

            $ret = "<tr>";
            $ret .= "<td>" . $this->category_id . "=" . $categories[0]->name . "</td>";
            $ret .= "<td>" . $this->count . "</td>";
            $ret .= '<td align="right">' . $this->sum . '</td>';
            $ret .= '<td align="right">' . $cmp . "</td>";
            $ret .= "</td>";

            return $ret;
        }


    }
?>