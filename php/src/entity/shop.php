<?php

    class Shop{
        var $id;
        var $name;
        var $default_category_id;
        
        
        public function getStandardHtml(){
            $ret = "";
            $ret .= "id=" . $this->id . "<br>";       
            $ret .= "name=" . $this->name . "<br>";       
            $ret .= "default_category_id=" . $this->default_category_id . "<br>";           
            return $ret;
        }
        
        public function getTableHeaderHtml(){
            
            $ret = "<tr>";
            $ret .= "<th>id</th>";    
            $ret .= "<th>name</th>";  
            $ret .= "<th>default_category_id</th>";
            $ret .= "</tr>";
            
            return $ret;
        }
        
        public function getTableLineHtml(){
            
            $categoryService = new CategoryService();
            $categories = $categoryService->getById($this->category_id);
            
            $ret = "<tr>";
            $ret .= "<td>" . $this->id . "</td>";       
            $ret .= "<td>" . $this->name . "</td>";              
            $ret .= "<td>" . $this->category_id . "=" . $categories[0]->name. "</td>";
            $ret .= "</td>";      
            
            return $ret;
        }
    }

?>