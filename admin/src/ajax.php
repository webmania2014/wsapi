<?php
	ini_set('memory_limit', '-1');
	set_time_limit(0);
	ini_set('max_execution_time', 0);

	$modulePath = '../../modules';
	require_once( $modulePath . '/module_index.php' );
	$action = $_POST['action'];

	switch ( $action )
	{
		case 'LOGIN':
			$admin_id		= $_POST['admin_id'];
			$admin_passwd	= $_POST['admin_passwd'];

			//check already exist administrator
			$bCheck = getDataByMySQLFunc( 'tbladmin', ' where admin_id = \'' . db_sql( $admin_id ) . '\'', 'no', 'count' );

			if ( $bCheck <= 0 )
			{
				echo 'NO_ADMIN';
			}
			else
			{
				//get administrator's password
				$adminInfo = getData( 'tbladmin', ' where admin_id = \'' . db_sql( $admin_id ) . '\'', '*' );

				$enc_passwd = base64_encode( $admin_passwd );

				if ( $enc_passwd != $adminInfo['admin_passwd'] )
				{
					echo 'WRONG_PASSWD';
				}
				else
				{
					$_SESSION['OMS_COM_SESSION'] = array( 
						'no'			=> $adminInfo['no'], 
						'admin_id'		=> $admin_id, 
						'admin_passwd'	=> $admin_passwd 
					);

					echo 'SUCCESS';
				}
			}
			break;

		case 'LOGOUT':
			unset( $_SESSION['OMS_COM_SESSION'] );
			echo 'SUCCESS';
			break;

		case 'CHANGE_PASSWD':
			$no				= $_POST['admin_no'];
			$origin_passwd	= $_POST['origin_passwd'];
			$new_passwd		= $_POST['new_passwd'];
			$confirm_passwd = $_POST['confirm_passwd'];

			//check if it is right origin password;
			$admin_info = getData( 'tbladmin', ' where no = \'' . db_sql( $no ) . '\'', '*' );
			$encode_passwd = base64_encode( $origin_passwd );

			if ( $encode_passwd != $admin_info['admin_passwd'] )
			{
				echo 'NOT_MATCH';
			}
			else
			{
				$sql  = 'update tbladmin set';				
				$sql .= ' admin_passwd = \'' . db_sql( base64_encode( $new_passwd ) ) . '\'';
				$sql .= ' where no = \'' . db_sql( $no ) . '\'';
				$query = db_query( $sql, $link );
				unset( $_SESSION['OMS_COM_SESSION'] );
				echo 'SUCCESS';
			}
			break;

		case 'SIGNUP':
			$admin_id		= $_POST['admin_id'];
			$admin_name		= $_POST['admin_name'];
			$admin_passwd	= $_POST['admin_passwd'];

			//check already exist admin id
			$bCheck = getDataByMySQLFunc( 'tbladmin', ' where admin_id = \'' . db_sql( $admin_id ) . '\'', 'no', 'count' );
			if ( $bCheck > 0 )
			{
				echo 'EXIST';
			}
			else
			{
				$sql  = 'insert into tbladmin set';
				$sql .= '  admin_id = \'' . db_sql( $admin_id ) . '\'';
				$sql .= ', admin_name = \'' . db_sql( $admin_name ) . '\'';
				$sql .= ', admin_passwd = \'' . db_sql( base64_encode( $admin_passwd ) ) . '\'';
				db_query( $sql, $link );
				echo 'SUCCESS';
			}
			break;

		case 'DEL_ADMIN':
			$admin_no = $_POST['admin_no'];

			$sql  = 'delete from tbladmin';
			$sql .= ' where no = \'' . db_sql( $admin_no ) . '\'';
			db_query( $sql, $link );

			echo 'SUCCESS';
			break;
		

		case 'ADD_SERVER':
			$server_ip	= $_POST['server_ip'];
			$user_id	= $_POST['user_id'];
			$passwd		= $_POST['passwd'];
			$port		= $_POST['port'];

			//check already exist admin id
			$bCheck = getDataByMySQLFunc( 'tblserverlist', ' where server_ip = \'' . db_sql( $server_ip ) . '\'', 'no', 'count' );
			if ( $bCheck > 0 )
			{
				echo 'Already exist server ip';
				exit;
			}

			$sql  = 'INSERT INTO tblserverlist SET ';
			$sql .= '  server_ip = \'' . db_sql( $server_ip ) . '\'';
			$sql .= ', server_geocode = \'\'';
			$sql .= ', server_geozone = \'\'';
			$sql .= ', server_geocity = \'\'';
			$sql .= ', server_isp = \'\'';
			$sql .= ', server_zip = \'\'';
			$sql .= ', server_user = \'' . db_sql( $user_id ) . '\'';
			$sql .= ', server_passwd = \'' . db_sql( $passwd ) . '\'';
			$sql .= ', server_port = \'' . db_sql( $port ) . '\'';
			db_query( $sql, $link );

			echo 'SUCCESS';
			break;

		case 'GET_SVR_INFO':
			$server_ip	= $_POST['server_ip'];
			$cmd  = 'geoiplookup -f /usr/share/GeoIP/GeoLiteCity.dat ' . $server_ip;

			$stream = accessSSH( $cfg['client_ip'], 22, $cfg['client_user'], $cfg['client_passwd'], $cmd );

//			GeoIP City Edition, Rev 1: US, NY, New York, Rochester, 14624, 43.126701, -77.731598, 538, 585
//			GeoIP City Edition, Rev 1: IP Address not found

			$tempArr = explode( ':', $stream );
			$retArr = explode( ',', trim( $tempArr[1] ) );

			if ( count( $retArr ) == 0 )
			{
				echo 'IP Address not found';
				exit;
			}
			else
			{
				$geo_code = isset( $retArr[0] ) ? trim( $retArr[0] ) : '';
				$geo_zone = isset( $retArr[1] ) ? trim( $retArr[1] ) : '';
				$geo_city = isset( $retArr[2] ) ? trim( $retArr[2] ) : '';
				$isp	  = isset( $retArr[3] ) ? trim( $retArr[3] ) : '';
				$zip	  = isset( $retArr[4] ) ? trim( $retArr[4] ) : '';

				$sql  = 'UPDATE tblserverlist SET ';
				$sql .= '  server_geocode = \'' . db_sql( $geo_code ) . '\'';
				$sql .= ', server_geozone = \'' . db_sql( $geo_zone ) . '\'';
				$sql .= ', server_geocity = \'' . db_sql( $geo_city ) . '\'';
				$sql .= ', server_isp = \'' . db_sql( $isp ) . '\'';
				$sql .= ', server_zip = \'' . db_sql( $zip ) . '\'';
				$sql .= ' WHERE server_ip = \'' . db_sql( $server_ip ) . '\'';
				db_query( $sql, $link );

				echo 'SUCCESS';
			}
			break;

		case 'DEL_SERVER':
			$server_no = $_POST['server_no'];

			$sql  = 'DELETE FROM tblserverlist ';
			$sql .= ' WHERE no = \'' . db_sql( $server_no ) . '\'';
			db_query( $sql, $link );

			echo 'SUCCESS';
			break;

		case 'CHECK_SSL_LOGIN':
			$server_no = $_POST['server_no'];

			$svrInfo = getData( 'tblserverlist', ' WHERE no = \'' . db_sql( $server_no ) . '\'', 'server_ip, server_user, server_passwd, server_port' );
			
			$bRet = checkSSHLogin( $svrInfo['server_ip'], $svrInfo['server_port'], $svrInfo['server_user'], $svrInfo['server_passwd'] );

			if ( !$bRet )
			{
				echo 'FAILED';
				exit;
			}

			echo 'SUCCESS';
			break;

		case 'IMPORT_FROM_FILE':
			$server_ip		= $_POST['svr_ip'];
			$server_user	= $_POST['svr_user'];
			$server_passwd	= $_POST['svr_passwd'];
/*
			//check already exist admin id
			$bCheck = getDataByMySQLFunc( 'tblserverlist', ' where server_ip = \'' . db_sql( $server_ip ) . '\'', 'no', 'count' );
			if ( $bCheck > 0 )
			{
				echo 'DUPLICATE';
				exit;
			}
*/
			$sql  = 'INSERT INTO tblserverlist SET ';
			$sql .= '  server_ip = \'' . db_sql( $server_ip ) . '\'';
			$sql .= ', server_geocode = \'\'';
			$sql .= ', server_geozone = \'\'';
			$sql .= ', server_geocity = \'\'';
			$sql .= ', server_isp = \'\'';
			$sql .= ', server_zip = \'\'';
			$sql .= ', server_user = \'' . db_sql( $server_user ) . '\'';
			$sql .= ', server_passwd = \'' . db_sql( $server_passwd ) . '\'';
			$sql .= ', server_port = \'' . db_sql( '22' ) . '\'';
			db_query( $sql, $link );
			echo 'DUPLICATE';
/*						
			//get geo data.
			$cmd  = 'geoiplookup -f /usr/share/GeoIP/GeoLiteCity.dat ' . $server_ip;
			$stream = accessSSH( $cfg['client_ip'], 22, $cfg['client_user'], $cfg['client_passwd'], $cmd );

			$tempArr = explode( ':', $stream );
			$retArr = explode( ',', trim( $tempArr[1] ) );

			if ( count( $retArr ) == 0 )
			{
				echo 'IP Address not found';
				exit;
			}
			else
			{
				$geo_code = isset( $retArr[0] ) ? trim( $retArr[0] ) : '';
				$geo_zone = isset( $retArr[1] ) ? trim( $retArr[1] ) : '';
				$geo_city = isset( $retArr[2] ) ? trim( $retArr[2] ) : '';
				$isp	  = isset( $retArr[3] ) ? trim( $retArr[3] ) : '';
				$zip	  = isset( $retArr[4] ) ? trim( $retArr[4] ) : '';

				$sql  = 'UPDATE tblserverlist SET ';
				$sql .= '  server_geocode = \'' . db_sql( $geo_code ) . '\'';
				$sql .= ', server_geozone = \'' . db_sql( $geo_zone ) . '\'';
				$sql .= ', server_geocity = \'' . db_sql( $geo_city ) . '\'';
				$sql .= ', server_isp = \'' . db_sql( $isp ) . '\'';
				$sql .= ', server_zip = \'' . db_sql( $zip ) . '\'';
				$sql .= ' WHERE server_ip = \'' . db_sql( $server_ip ) . '\'';
				db_query( $sql, $link );

				echo $geo_code . ':' . $geo_zone . ':' . $geo_city . ':' . $isp . ':', $zip;
			}			*/
			break;
		default :
			break;
	}
?>