<?php
	$modulePath = '../modules';
	require_once( $modulePath . '/module_index.php' );

	$no = $_GET['no'];
	
	$data = getData( 'tblserverlist', ' WHERE no = \'' . db_sql( $no ) . '\'', 'server_ip, server_user, server_passwd' );

	$sql  = 'UPDATE tblserverlist SET server_used = \'' . db_sql('1') . '\'';
	$sql .= ' WHERE no = \'' . db_sql( $no ) . '\'';
	db_query( $sql, $link );
?>

<!DOCTYPE html>
<html>
	<head>
		<link href="../css/style.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class = 'detail-line'>
			<div class = 'detail-left'>Server Ip:</div>
			<div class = 'detail-right'><?php echo $data['server_ip'] ?></div>
		</div>
		<div class = 'detail-line'>
			<div class = 'detail-left'>User name:</div>
			<div class = 'detail-right'><?php echo $data['server_user'] ?></div>
		</div>
		<div class = 'detail-line'>
			<div class = 'detail-left'>Password:</div>
			<div class = 'detail-right'><?php echo $data['server_user'] ?></div>
		</div>
	</body>
</html>