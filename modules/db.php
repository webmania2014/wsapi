<?php
	//************************************************
	//********* global function
	// DB Connect	
	$link = db_conn();
	$gQueryResult = true;
	function db_conn()
	{
		global $cfg;
		$link = mysql_connect ( $cfg['DBHost'], $cfg['DBUser'], $cfg['DBPasswd'] ) or die(error_html('Fail DB Connect.'));
		mysql_select_db( $cfg['DBName'], $link ) or die( db_error ( '', $link ) );
		return $link;
	}

	// DB Query
	function db_query($query,$link)
	{
		if ( !is_resource($link) ) $GLOBALS['link'] = $link = db_conn();
		mysql_query("set names utf8");
		$result = mysql_query($query,$link);
		if ( !$result ) 
		{
			$gQueryResult = false;
			die(db_error($query,$link));
		}
		else
		{
			$gQueryResult = true;
		}
		return $result;
	}

	function db_get_value( $query, $link = "" )
	{
		return @mysql_result( db_query( $query, $link ), 0, 0 );
	}

	function db_sql( $value )
	{
		$sql = $value;
		$ret = '';

		if ( function_exists('mysql_real_escape_string') )
			$ret = mysql_real_escape_string( $sql );
		else
			$ret = htmlspecialchars( addslashes( $sql ) );

		return $ret;
	}

	// DB Error
	function db_error($query,$link)
	{
		$errNO = mysql_errno($link);
		$errMSG = mysql_error($link);

		$message = 'DB Error!<br />' . $errMSG . '(' . $errNO . ')';
		if ( $query ) $message .= '<br />' . $query;

		error_html( $message );
	}

	// Message HTML
	function error_html($msg,$back=0)
	{
		echo $msg;
		error_back($back);
		exit;
	}

	// Page Back
	function error_back($opt)
	{
		if (!$opt ) return false;
		echo "<script>history.back();</script>";
	}

	function ErrorMsg ($error_msg) { 
		echo "<script> alert(\" $error_msg \"); history.go(-1); </script> "; 
		exit; 
	}

	function db_close()
	{
		if ( is_resource($GLOBALS['link']) ) mysql_close($GLOBALS['link']);
	}

	/*******************************/
	function db_transaction_start()
	{
		mysql_query("BEGIN");
	}

	function db_transaction_rollback()
	{
		mysql_query("ROLLBACK");
	}

	function db_transaction_commit()
	{
		mysql_query("COMMIT");
	}
?>