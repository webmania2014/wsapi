<?php
	session_start();
	$cfg = array();

	define( 'ADMIN_RECORD_PER_PAGE', 15 );
	define( 'RECORD_PER_BLOCK', 10 );

	if( $_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '192.168.1.33' )
	{
		$cfg['DBHost']					= 'localhost';
		$cfg['DBUser']					= 'root';
		$cfg['DBPasswd']				= '';
		$cfg['DBName']					= 'ssh_admin';
		$cfg['img_path']				= 'http://localhost/ssh/images';
	}
	else
	{
		$cfg['DBHost']					= '';
		$cfg['DBUser']					= '';
		$cfg['DBPasswd']				= '';
		$cfg['DBName']					= '';
	}

	$cfg['client_ip']				= '104.131.240.64';
	$cfg['client_user']				= 'root';
	$cfg['client_passwd']			= 'zysuqxtajmjx';
?>
