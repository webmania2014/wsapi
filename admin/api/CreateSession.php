<?PHP
// ********** Register the method to expose ********** //
	$server->register('CreateSession',			// method name
		array(
			'client_id' => 'xsd:string',
			'client_password' => 'xsd:string',
		),										// input parameters
		array('return' => 'xsd:string'),		// output parameters
		'urn:invokesensorwsdl',					// namespace
		'urn:invokesensorwsdl#CreateSession',	// soapaction
		'rpc',									// style
		'encoded',								// use
		'Get client verify token'				// documentation
	);

// ********************************************************* //

// ********** Define the method as a PHP function ********** //
	
	function CreateSession( $client_id, $client_password )
	{
		global $JSON, $arrErrMsg, $link;
		$arrRet = array();

		//check validate client_id and client_password
		if ( strlen( trim( $client_id ) ) <= 0 || strlen( trim( $client_password ) ) <= 0 )
		{
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ], 'token' => '' );
		}
		else
		{
			//check already exist company id...
			$bExist = getDataByMySQLFunc( 'tblcustomercompany', ' where company_id = \'' . db_sql( $client_id ) . '\'', 'no', 'count' );
			if ( $bExist <= 0 )
			{
				$arrRet = array( 'err_code' => QLM_NO_USER_VERIFY, 'err_msg' => $arrErrMsg[ QLM_NO_USER_VERIFY ], 'token' => '' );
			}
			else
			{
				$companyInfo = getData( 'tblcustomercompany', ' where company_id = \'' . db_sql( $client_id ) . '\'', 'company_passwd' );
				$db_password = base64_decode( $companyInfo['company_passwd'] );
				if ( $client_password != $db_password )
				{
					$arrRet = array( 'err_code' => QLM_NOT_MATCH_PASSWD, 'err_msg' => $arrErrMsg[ QLM_NOT_MATCH_PASSWD ], 'token' => '' );
				}
				else
				{
					//create token...
					//token is client_id, client_password and current timestamp
					$base_token = $client_id . '#' . base64_encode( $client_password ) . '#' . time();
					$token = base64_encode( $base_token );

					//insert session table.
					$sql  = 'insert into tblsession set ';
					$sql .= '  company_id = \'' . db_sql( $client_id ) . '\'';
					$sql .= ', token = \'' . db_sql( $token ) . '\'';
					$sql .= ', last_time = \'' . time() . '\'';

					$query = db_query( $sql, $link );

					if ( !$query )
					{
						$arrRet = array( 'err_code' => QLM_SYSTEM_ERR, 'err_msg' => $arrErrMsg[ QLM_SYSTEM_ERR ], 'token' => '' );
					}
					else
					{
						$arrRet = array( 'err_code' => QLM_SUCCESS, 'err_msg' => $arrErrMsg[ QLM_SUCCESS ], 'token' => $token );
					}
				}
			}
		} 

		return $JSON->encode( $arrRet );
	}
?>