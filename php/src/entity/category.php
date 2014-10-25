<?php

    class Category{
        var $name;
        var $id;
        var $additional_flg;
        
        public function getStandardHtml(){
            $ret = "";
            $ret .= "id=" . $this->id . "<br>";       
            $ret .= "name=" . $this->name . "<br>";       
            $ret .= "additional_flg=" . $this->additional_flg . "<br>";  
            return $ret;
        }
        
        public function getTableHeaderHtml(){
            
            $ret = "<tr>";
            $ret .= "<th>id</th>";    
            $ret .= "<th>name</th>";  
            $ret .= "<th>additional_flg(1=調整費)</th>";  
            $ret .= "</tr>";
            
            return $ret;
        }
        
        public function getTableLineHtml(){
            
            $ret = "<tr>";
            $ret .= "<td>" . $this->id . "</td>";       
            $ret .= "<td>" . $this->name . "</td>";       
            $ret .= "<td>" . $this->additional_flg . "</td>"; 
            $ret .= "</td>";      
            
            return $ret;
        }
        
    }
    
?>