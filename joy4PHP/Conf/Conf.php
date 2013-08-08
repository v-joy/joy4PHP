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
	'log_destination'=>WEB_ROOT."data".DIRECTORY_SEPARATOR."log".DIRECTORY_SEPARATOR,
	
	'default_module'=>'index',
	'default_action'=>'index',
	
	//cache setting
	'cache_type'=>"File",//currently joy4php only support file cache
	'cache_time'=>0, //0 means never expire
	'cache_path'=>WEB_ROOT."data".DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR
	
);