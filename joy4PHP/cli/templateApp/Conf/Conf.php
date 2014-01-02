<?php
return array(
	//setting for joy4php framework
	'db_type'=>"mysql",
	'db_host'=>"localhost",
	'db_user'=>"{dbUser}",
	'db_pwd'=>"{dbPass}",
	'db_name'=>"{dbName}",
	'db_prefix'=>"",
	'db_charset'=>"utf8",
	
	//log setting 
	//supported level : info notice warning error none 
	'log_level'=>"error",
	//supported type : system mail file 
	'log_type'=>"file",
	'log_destination'=>WEB_ROOT."data".DIRECTORY_SEPARATOR."log".DIRECTORY_SEPARATOR,
	
	'page_size' =>20,
	
	//setting for this application
	'db_config_table'=>'dbms_config',
);