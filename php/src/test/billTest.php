<?php
    
    require_once 'PHPUnit/Autoload.php';
    require_once '../entity/bill.php';
    
    class BillTest extends PHPUnit_Framework_TestCase
    {
        public function setUp()
        {
            $this->bill = new Bill;
        }
        
        public function testGetStandardHtml(){
            
            $result = $this->bill->getStandardHtml();
            
            $ret = "";
            $ret .= "id=" . $this->bill->id . "<br>";
            $ret .= "合計=" . $this->bill->sum . "<br>";
            $ret .= "カード=" . $this->bill->file_prefix . "<br>";
            
            $this->assertEquals($ret, $result);
        }
    }

?>