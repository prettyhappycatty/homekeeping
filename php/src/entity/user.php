<?php
class User{
    var $id;
    var $name;
    var $default_payment;
    var $additional_ratio;
    
    public function User(){
        //echo "test";
    }
    
    public function getStandardHtml(){
        
        $ret = "";
        $ret .= "id=" . $this->id . "<br>";
        $ret .= "name=" . $this->name . "<br>";
        $ret .= "default_payment=" . $this->default_payment . "<br>";
        $ret .= "additional_ratio=" . $this->additional_ratio . "<br>";
        
        return $ret;
    }
}
?>