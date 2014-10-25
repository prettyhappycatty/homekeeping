homekeeping
===========


homekeeping/data
に、excelファイルをためています（ローカルのみ）

homekeeping/php/src/setting/database_setting.php（ローカルのみ）に、データベースの接続情報を書いています。

<?php

	class DatabaseSetting{
		var $setting_database_host;
		var $setting_database_user;
		var $setting_database_pass;
		var $setting_database_scheme;
		function DatabaseSetting(){
	
			$this->setting_database_host = "xxxxxxx.db.sakura.ne.jp";
			$this->setting_database_user = "xxxxx";
			$this->setting_database_pass = "xxxxx";
			$this->setting_database_scheme = "xxxxx";
		}
	}

?>
