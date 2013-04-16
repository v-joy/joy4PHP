<?php
return array(
	//DB related
	'db_type'=>"mysql",
	'db_host'=>"localhost",
	'db_user'=>"root",
	'db_pwd'=>"",
	'db_name'=>"",
	'db_prefix'=>"",
	'db_charset'=>"utf8",
	
	//log setting 
	//supported level : info notice warning error none 
	'log_level'=>"info",
	//supported type : system mail file 
	'log_type'=>"file",
	'log_destination'=>WEB_ROOT."data".DIRECTORY_SEPARATOR."log".DIRECTORY_SEPARATOR
	
	
);