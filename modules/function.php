<?php
	function checkLogin()
	{
		if ( !isset( $_SESSION['OMS_COM_SESSION'] ) )
		{
			echo '
			<script>
				document.location.href = "login.php";
			</script>
			';
		}
	}

	function displayTab ( $arrTabInfo, $getVar ) 
	{
		global $_GET;

		$tag	= "";
		$link	= "";
		$cls	= "tab sel";
		$link	= $_SERVER['PHP_SELF'].'?'.get_parameter ( $getVar );

		$i = 0;
		foreach ( $arrTabInfo as $k => $v )
		{
			$cls = ( ( !isset( $_GET[$getVar] ) and $i == 0 ) || ( isset( $_GET[$getVar] ) and strtolower( $_GET[$getVar] ) == strtolower( $k ) ) ) ? "tab sel" : "tab";
			$tag .= "<div class='".$cls."'>";
			$tag .= "	<a href='".$link.$getVar."=".$k."'>";
			$tag .=			$v[0];
			$tag .= "	</a>";
			$tag .= "</div>";

			$i++;
		}
		echo "<div class='wms-tab'>";
		echo $tag;
		echo "</div>";
	}

	function displayTabAction( $arrTabInfo, $getVar, $action )
	{
		global $_GET;

		$tag	= "";
		$link	= "";
		$cls	= "tab sel";
		$link	= $action;

		$i = 0;
		foreach ( $arrTabInfo as $k => $v )
		{
			$cls = ( ( !isset( $_GET[$getVar] ) and $i == 0 ) || ( isset( $_GET[$getVar] ) and strtolower( $_GET[$getVar] ) == strtolower( $k ) ) ) ? "tab sel" : "tab";
			$tag .= "<div class='".$cls."'>";
			$tag .= "	<a href='".$link."&tab=".$k."'>";
			$tag .=			$v[0];
			$tag .= "	</a>";
			$tag .= "</div>";

			$i++;
		}
		echo "<div class='wms-tab'>";
		echo $tag;
		echo "</div>";
	}

	function excelToArray( $filename, $offset )
	{
		$arrRet = array();
		
		try {
			$inputFileType = PHPExcel_IOFactory::identify( $filename );
		} catch(Exception $e) {
			return -1;
		}

		if( $inputFileType == 'HTML' )
		{
			$csv = new parseCSV();
			$csv->encoding('UTF-16', 'UTF-8');
			$csv->offset = 1;
			$csv->heading= false;
			$csv->delimiter = "\t";
			$csv->parse( $filename );

			$arrRet = $csv->data;
		}
		else
		{
			try {
				$objReader		= PHPExcel_IOFactory::createReaderForFile( $filename );
				$objPHPExcel	= $objReader->load($filename);
			} catch(Exception $e) {
				return -1;
			}
			
			$arrRet = $objPHPExcel->getActiveSheet()->toArray( null,false,true,false );			
		}

		return $arrRet;
	}

	function accessSSH( $ip, $port, $user, $passwd, $cmd )
	{
		global $cfg;

		$ssh = new Net_SSH2( $ip, $port );
		if ( !$ssh->login( $user, $passwd ) )
		{
			echo 'Login Failed';
			exit;
		}

		return $ssh->exec( $cmd );
	}

	function checkSSHLogin( $ip, $port, $user, $passwd )
	{
		$ssh = new Net_SSH2( $ip, $port );
		if ( !$ssh->login( $user, $passwd ) )
		{
			echo 'Login Failed';
			return -1;
		}

		return 1;
	}
?>