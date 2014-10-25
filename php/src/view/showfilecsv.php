<?php
$file = 'test.csv';
$data = file_get_contents($file);
$data = mb_convert_encoding($data, 'UTF-8', 'SJIS');
$temp = tmpfile();
$csv  = array();
 
fwrite($temp, $data);
rewind($temp);
 
while (($data = fgetcsv($temp, 0, ",")) !== FALSE) {
  $csv[] = $data;
}
fclose($temp);
 
var_dump($csv);

?>