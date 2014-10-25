<?php
    
    class ShopHelper{
        
        function getSelectBox($select_name, $selected_id, $shops){
            
            $ret = "";
            $ret .= '<select name="' . $select_name . '">';
            for($j = 0; $j < count($shops); $j++){
                if($$selected_id == $shops[$j]->id){
                    $ret .= '<option value="' . $shops[$j]->id .  '" selected=true>' .$shops[$j]->name . '</option>';
                    
                }else{
                    $ret .= '<option value="' . $shops[$j]->id .'">' .$shops[$j]->name . '</option>';
                }
            }
            $ret .= '</select>';
            
            return $ret;
        }
    }
    
    ?>