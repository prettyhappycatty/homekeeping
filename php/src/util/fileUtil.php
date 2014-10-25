<?php
    
    class FileUtil{
        
        var $fileNames = array();
        
        /**
         * フォルダパスを渡すと中のファイルの名前が配列で帰ってくる関数。
         * @access public
         */
        public function getFileNames($folderPath){
            
            $folderPath="../../../data/";
            
            $fileNames = array();
            //ディレクトリの中身を取得する
            $handle = opendir($folderPath) or die('ディレクトリが開けません');
            while ($fname=readdir($handle)) {
                if (is_dir($folderPath . $fname)){
                    //何もしない
                }else if(is_file($folderPath . $fname)){
                    array_push($fileNames, $fname);
                }
            }
            closedir($handle);
            
            $this->fileNames = $fileNames;
                        
            return $this->fileNames;
             
             
        }
        
        /**
         * フォルダパス、ファイル名の一部を渡すと中のファイルの名前が配列で帰ってくる関数。
         * 年／月ごとにファイルを抽出したいときや、カード種類ごとに抽出したいときに使う。
         * @access public
         */
        public function searchFileByNames($folderPath, $name){
            
            //$folderPath="../../../data/";
            
            $fileNames = array();
            //ディレクトリの中身を取得する
            $handle = opendir($folderPath) or die('ディレクトリが開けません');
            while ($fname=readdir($handle)) {
                if (is_dir($folderPath . $fname)){
                    //何もしない
                }else if(is_file($folderPath . $fname)){
                    //抽出
                    if(ereg($name, $fname)){
                        array_push($fileNames, $fname);
                    }
                     
                }
            }
            closedir($handle);
            
            $this->fileNames = $fileNames;
            
            return $this->fileNames;
            
            
        }
         
        
    }
    
    
?>


<?php
    /*
    echo "test1<br>";
    
    $a = new FileUtil();
    $ret = $a->getFileNames("");
    //var_dump($ret);
     */

?>