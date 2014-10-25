<?php
    
    
    //echo get_include_path();
    /** Include path **/
    set_include_path(get_include_path() . PATH_SEPARATOR . '/home/prettyhappy/www/kondo/PHPExcel-1.7.7/Classes/');
    /** PHPExcel_IOFactory */

    include_once 'PHPExcel/IOFactory.php';
    /* //エラー確認用
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Asia/Tokyo');
*/
    class ExcelService{
        
        var $sheetData = null;
        //$bills = new Bills;
        
        /**
         * これは文書化される
         * Excelを読み、ShetDataを返す関数。ここにある必要なし。ExcelActionやExcelServiceに変更する方が良さそう。
         * @access public
         */
        public function readExcel($filepath){
            
            //echo "filepath-excelservice:" . $filepath . "<br>";
            
            //$filepath="../../../data/COSTCO_201208.csv";
            
            
            $inputFileType = 'CSV';
            $inputFileNames = array($filepath);
            
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);

            $inputFileName = array_shift($inputFileNames);


            //echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' into WorkSheet #1 using IOFactory with a defined reader type of ',$inputFileType,'<br />';
            $objPHPExcel = $objReader->load($inputFileName);
            $objPHPExcel->getActiveSheet()->setTitle(pathinfo($inputFileName,PATHINFO_BASENAME));
	

            foreach($inputFileNames as $sheet => $inputFileName) {
                //echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' into WorkSheet #',($sheet+2),' using IOFactory with a defined reader type of ',$inputFileType,'<br />';
                $objReader->setSheetIndex($sheet+1);
                $objReader->loadIntoExisting($inputFileName,$objPHPExcel);
                $objPHPExcel->getActiveSheet()->setTitle(pathinfo($inputFileName,PATHINFO_BASENAME));
            }



            
            $loadedSheetNames = $objPHPExcel->getSheetNames();
            foreach($loadedSheetNames as $sheetIndex => $loadedSheetName) {
                //echo '<b>Worksheet #',$sheetIndex,' -> ',$loadedSheetName,'</b><br />';
                $objPHPExcel->setActiveSheetIndexByName($loadedSheetName);
                $this->sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                //var_dump($this->sheetData);
                //echo '<hr>';
            }
            
            /* //確認用
            for($i = 1 ; $i < count($this->sheetData); $i++){
                
                var_dump($this->sheetData[$i]);
                echo "<br>";
                
            }
             */
            
            return $this->sheetData;
        }
        
    }
    
    
?>


<?php
    
    //echo "test1<br>";
    
    //$a = new ExcelService();
    //$a->readExcel("");

    ?>