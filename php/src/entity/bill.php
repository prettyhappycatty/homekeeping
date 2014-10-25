<?php
class Bill{
    var $id;
    var $sum;
    var $month;
    var $file_prefix;

    public function Bills(){
        //echo "test";
    }

    public function getStandardHtml(){

        $ret = "";
        $ret .= "id=" . $this->id . "<br>";
        $ret .= "合計=" . $this->sum . "<br>";
        $ret .= "カード=" . $this->file_prefix . "<br>";

        return $ret;
    }

    public function getTableHeaderHtml(){
        $ret = "<tr>";
        $ret .= "<th>id</th>";
        $ret .= "<th>month</th>";
        $ret .= "<th>fileprefix</th>";
        $ret .= "<th>sum</th>";
        $ret .= "</tr>";

        return $ret;
    }

    public function getTableLineHtml(){

   		$ret = "<tr>";
    	$ret .= "<td>". $this->id ."</td>";
    	$ret .= "<td>" . $this->month . "</td>";
    	$ret .= "<td>" . $this->file_prefix . "</td>";
    	$ret .= '<td align="right">' . $this->sum . "</td>";
    	$ret .= "</tr>";

    	return $ret;
    }
}
?>