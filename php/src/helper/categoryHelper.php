<?php
    
    class CategoryHelper{
        
        function getSelectBox($select_name, $selected_id, $categories){
            
            $ret = "";
            $ret .= '<select name="' . $select_name . '">';
            for($j = 0; $j < count($categories); $j++){
                if($selected_id == $categories[$j]->id){
                    $ret .= '<option value="' . $categories[$j]->id .  '" selected=true>' .$categories[$j]->name . '</option>';
                    
                }else{
                    $ret .= '<option value="' . $categories[$j]->id .'">' .$categories[$j]->name . '</option>';
                }
            }
            $ret .= '</select>';
            
            return $ret;
        }
    }
    
?>