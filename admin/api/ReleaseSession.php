<?PHP

// ********** Register the method to expose ********** //

	$server->register('ReleaseSession',					// method name
		array(
			'token' => 'xsd:string',
		),												// input parameters
		array('return' => 'xsd:string'),				// output parameters
		'urn:invokesensorwsdl',							// namespace
		'urn:invokesensorwsdl#ReleaseSession',			// soapaction
		'rpc',											// style
		'encoded',										// use
		'Release current session'						// documentation
	);

// ********************************************************* //

// ********** Define the method as a PHP function ********** //
	
	function ReleaseSession( $token )
	{
		global $JSON, $arrErrMsg, $link;
		$arrRet = array();

		//check validation
		if ( strlen( trim( $token ) ) <= 0 )
		{
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ], 'token' => '' );
		}
		else
		{
			//check already exist token
			$bExist = getDataByMySQLFunc( 'tblsession', ' where token = \'' . db_sql( $token ) . '\'', 'no', 'count' );

			if ( $bExist > 0 )		
			{
				//delete session info
				$sql  = 'delete from  tblsession';
				$sql .= ' where 1';
				$sql .= ' and token = \'' . db_sql( $token ) . '\'';
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

		return $JSON->encode( $arrRet );
	}
?>