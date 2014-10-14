<?PHP
// ********** Register the method to expose ********** //
	$server->register('SBSC_SendOrder',			// method name
		array(
			'token'				=> 'xsd:string',
			'order_no'			=> 'xsd:string',
			'order_date'		=> 'xsd:string',
			'order_type_code'	=> 'xsd:string',
			'order_type_name'	=> 'xsd:string',
			'asc_code'			=> 'xsd:string',
			'asc_name'			=> 'xsd:string',
			'asc_address'		=> 'xsd:string',
			'asc_postcode'		=> 'xsd:string',
			'sender_name'		=> 'xsd:string',
			'sender_phone'		=> 'xsd:string',
			'sender_city_code'	=> 'xsd:string',
			'sender_city_name'	=> 'xsd:string',
			'receive_code'		=> 'xsd:string',
			'receive_name'		=> 'xsd:string',
			'receive_address'	=> 'xsd:string',
			'receive_postcode'	=> 'xsd:string',
			'receive_person'	=> 'xsd:string',
			'receive_phone'		=> 'xsd:string',
			'receive_city_code'	=> 'xsd:string',
			'receive_city_name'	=> 'xsd:string',
			'box_number'		=> 'xsd:int',
			'box_no_list'		=> 'xsd:string',
			'goods_price'		=> 'xsd:float',
			'insurance_price'	=> 'xsd:float',
			'model_no'			=> 'xsd:string',
			'serial_no'			=> 'xsd:string',
			'description'		=> 'xsd:string',
		),												// input parameters
		array('return' => 'xsd:string'),				// output parameters
		'urn:invokesensorwsdl',							// namespace
		'urn:invokesensorwsdl#SBSC_SendOrder',			// soapaction
		'rpc',											// style
		'encoded',										// use
		'Send order information to cholima'				// documentation
	);

// ********************************************************* //

// ********** Define the method as a PHP function ********** //
	
	function SBSC_SendOrder($token, 
							$order_no,
							$order_date,
							$order_type_code,
							$order_type_name, 
							$asc_code, 
							$asc_name, 
							$asc_address, 
							$asc_postcode,
							$sender_name, 
							$sender_phone, 
							$sender_city_code,
							$sender_city_name, 
							$receive_code, 
							$receive_name,
							$receive_address,
							$receive_postcode,
							$receive_person,
							$receive_phone,
							$receive_city_code,
							$receive_city_name,
							$box_number,
							$box_no_list,
							$goods_price,
							$insurance_price,
							$model_no,
							$serial_no,
							$description )
	{
		global $JSON, $arrErrMsg, $link;
		$funcName = 'SBSC_SendOrder';

		if ( strlen( trim( $token ) ) <= 0 ) 
		{
			registerFailedOrderList( $order_no, 'SBSC', $arrErrMsg[ QLM_NO_TOKEN ] );
			$arrRet = array( 'err_code' => QLM_NO_TOKEN, 'err_msg' => $arrErrMsg[ QLM_NO_TOKEN ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $order_no ) ) <= 0  )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty order no' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $order_date ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty order date' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $order_date ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty order date' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $order_type_code ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty order type code' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $order_type_name ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty order type name' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $asc_code ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty ASC code' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $asc_name ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty ASC name' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $asc_address ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty ASC address' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $asc_postcode ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty ASC code' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $sender_name ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty sender name' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $sender_phone ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty sender phone' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $sender_city_code ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty sender city code' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $sender_city_name ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty sender city name' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $receive_code ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty receive code' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $receive_name ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty receive name' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $receive_address ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty receive address' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $receive_postcode ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty receive post code' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $receive_person ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty receive person' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}
			 
		if ( strlen( trim( $receive_phone ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty receive phone' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $receive_city_code ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty receive city code' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $receive_city_name ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty receive city name' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $box_number ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty box number' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $box_no_list ) ) <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Empty box number list' );
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
			return $JSON->encode( $arrRet );
		}


		$arrClientInfo = getClientInfoFromToken( $token );
		$client_id = $arrClientInfo['company_id'];

		$bCheck = getFunctionPermission( $arrClientInfo['company_id'], $funcName );

		if ( $bCheck <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', $arrErrMsg[ QLM_NO_PERMISSION_FUNCTION ] );
			$arrRet = array( 'err_code' => QLM_NO_PERMISSION_FUNCTION, 'err_msg' => $arrErrMsg[ QLM_NO_PERMISSION_FUNCTION ] );
			return $JSON->encode( $arrRet );
		}

		//check if already exist same order no
		$bCheck = getDataByMySQLFunc( 'tblordermaster', ' where order_no = \'' . db_sql( $order_no ) . '\'', 'no', 'count' );
		if ( $bCheck > 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Already exist same order no' );
			$arrRet = array( 'err_code' => QLM_INVALID_PARAM, 'err_msg' => $arrErrMsg[ QLM_INVALID_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		//check order type - order type code must be 'HS', 'YF', 'TH'...
		if ( $order_type_code != 'HS' && $order_type_code != 'YF' && $order_type_code != 'TH' )
		{
			registerFailedOrderList( $order_no, 'SBSC', 'Wrong order type code' );
			$arrRet = array( 'err_code' => QLM_INVALID_PARAM, 'err_msg' => $arrErrMsg[ QLM_INVALID_PARAM ] );
			return $JSON->encode( $arrRet );
		}

		//check box number is int
		if ( (int)$box_number <= 0 )
		{
			registerFailedOrderList( $order_no, 'SBSC', $arrErrMsg[ QLM_NO_BOX_NUMBER ] );
			$arrRet = array( 'err_code' => QLM_NO_BOX_NUMBER, 'err_msg' => $arrErrMsg[ QLM_NO_BOX_NUMBER ] );
			return $JSON->encode( $arrRet );
		}

		//check box count and box number list
		if ( (int)$box_number != (int)count( jsonDecode( $box_no_list ) ) )
		{
			registerFailedOrderList( $order_no, 'SBSC', $arrErrMsg[ QLM_NO_MATCH_BOX ] );
			$arrRet = array( 'err_code' => QLM_NO_MATCH_BOX, 'err_msg' => $arrErrMsg[ QLM_NO_MATCH_BOX ] );
			return $JSON->encode( $arrRet );
		}

		if ( $order_type_code == 'HS' )
		{
			if ( strlen( trim( $goods_price ) ) <= 0 )
			{
				registerFailedOrderList( $order_no, 'SBSC', $arrErrMsg[ QLM_NO_GOODS_PRICE ] );
				$arrRet = array( 'err_code' => QLM_NO_GOODS_PRICE, 'err_msg' => $arrErrMsg[ QLM_NO_GOODS_PRICE ] );
				return $JSON->encode( $arrRet );
			}

			if ( strlen( trim( $insurance_price ) ) <= 0 )
			{
				registerFailedOrderList( $order_no, 'SBSC', 'Empty insurance price' );
				$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ] );
				return $JSON->encode( $arrRet );
			}
		}
		else if ( $order_type_code == 'YF' )
		{
			if ( strlen( trim( $goods_price ) ) <= 0 )
			{
				registerFailedOrderList( $order_no, 'SBSC', $arrErrMsg[ QLM_NO_GOODS_PRICE ] );
				$arrRet = array( 'err_code' => QLM_NO_GOODS_PRICE, 'err_msg' => $arrErrMsg[ QLM_NO_GOODS_PRICE ] );
				return $JSON->encode( $arrRet );
			}
		}
		else if ( $order_type_code == 'TH' )
		{
			if ( strlen( trim( $model_no ) ) <= 0 )
			{
				registerFailedOrderList( $order_no, 'SBSC', $arrErrMsg[ QLM_NO_MODELNO ] );
				$arrRet = array( 'err_code' => QLM_NO_MODELNO, 'err_msg' => $arrErrMsg[ QLM_NO_MODELNO ] );
				return $JSON->encode( $arrRet );
			}
			
			if ( strlen( trim( $serial_no ) ) <= 0 )
			{
				registerFailedOrderList( $order_no, 'SBSC', $arrErrMsg[ QLM_NO_SERIALNO ] );
				$arrRet = array( 'err_code' => QLM_NO_SERIALNO, 'err_msg' => $arrErrMsg[ QLM_NO_SERIALNO ] );
				return $JSON->encode( $arrRet );
			}
		}

		//check order_date validation
		$arrDate = explode( '-', $order_date );
		if ( count( $arrDate ) < 3 )
		{
			registerFailedOrderList( $order_no, 'SBSC', $arrErrMsg[ QLM_NOT_MACH_DATE_FORMAT ] );
			$arrRet = array( 'err_code' => QLM_NOT_MACH_DATE_FORMAT, 'err_msg' => $arrErrMsg[ QLM_NOT_MACH_DATE_FORMAT ] );
			return $JSON->encode( $arrRet );
		}

		$order_timestamp = mktime( 0, 0, 0, $arrDate[1], $arrDate[2], $arrDate[0] );
		//now insert order info in table
		$sql  = 'insert into tblsbscorderlist set ';
		$sql .= '  order_no				= \'' . db_sql( $order_no ) . '\'';
		$sql .= ', order_date			= \'' . db_sql( $order_timestamp ) . '\'';
		$sql .= ', order_type_code		= \'' . db_sql( $order_type_code ) . '\'';
		$sql .= ', order_type_name		= \'' . db_sql( $order_type_name ) . '\'';
		$sql .= ', asc_code				= \'' . db_sql( $asc_code ) . '\'';
		$sql .= ', asc_name				= \'' . db_sql( $asc_name ) . '\'';
		$sql .= ', asc_address			= \'' . db_sql( $asc_address ) . '\'';
		$sql .= ', asc_postcode			= \'' . db_sql( $asc_postcode ) . '\'';
		$sql .= ', sender_name			= \'' . db_sql( $sender_name ) . '\'';
		$sql .= ', sender_phone			= \'' . db_sql( $sender_phone ) . '\'';
		$sql .= ', sender_city_code		= \'' . db_sql( $sender_city_code ) . '\'';
		$sql .= ', sender_city_name		= \'' . db_sql( $sender_city_name ) . '\'';
		$sql .= ', receive_code			= \'' . db_sql( $receive_code ) . '\'';
		$sql .= ', receive_name			= \'' . db_sql( $receive_name ) . '\'';
		$sql .= ', receive_address		= \'' . db_sql( $receive_address ) . '\'';
		$sql .= ', receive_postcode		= \'' . db_sql( $receive_postcode ) . '\'';
		$sql .= ', receive_person		= \'' . db_sql( $receive_person ) . '\'';
		$sql .= ', receive_phone		= \'' . db_sql( $receive_phone ) . '\'';
		$sql .= ', receive_city_code	= \'' . db_sql( $receive_city_code ) . '\'';
		$sql .= ', receive_city_name	= \'' . db_sql( $receive_city_name ) . '\'';
		$sql .= ', box_number			= \'' . db_sql( $box_number ) . '\'';
		$sql .= ', box_no_list			= \'' . db_sql( $box_no_list ) . '\'';
		$sql .= ', goods_price			= \'' . db_sql( $goods_price ) . '\'';
		$sql .= ', insurance_price		= \'' . db_sql( $insurance_price ) . '\'';
		$sql .= ', model_no				= \'' . db_sql( $model_no ) . '\'';
		$sql .= ', serial_no			= \'' . db_sql( $serial_no ) . '\'';
		$sql .= ', description			= \'' . db_sql( $description ) . '\'';
		$sql .= ', is_processed			= \'' . db_sql( '0' ) . '\'';

		$query = db_query( $sql, $link );
		
		if ( !$query )
		{
			registerFailedOrderList( $order_no, 'SBSC', $arrErrMsg[ QLM_SYSTEM_ERR ] );
			$arrRet = array( 'err_code' => QLM_SYSTEM_ERR, 'err_msg' => $arrErrMsg[ QLM_SYSTEM_ERR ], 'token' => '' );	
			return $JSON->encode( $arrRet );
		}
	
		//get customer company's code
		$customerInfo = getData( 'tblcustomercompany', ' where company_id = \'' . db_sql( $client_id ) . '\'', 'company_code' );

		//and insert master table
		$sql  = 'insert into tblordermaster set';
		$sql .= '  order_no			= \'' . db_sql( $order_no ) . '\'';
		$sql .= ', order_date		= \'' . db_sql( $order_timestamp ) . '\'';
		$sql .= ', order_type_code	= \'' . db_sql( $order_type_code ) . '\'';
		$sql .= ', order_type_name	= \'' . db_sql( $order_type_name ) . '\'';
		$sql .= ', company_code		= \'' . db_sql( $customerInfo['company_code'] ) . '\'';
		$sql .= ', is_processed		= \'' . db_sql( '0' ) . '\'';

		$query = db_query( $sql, $link );
		
		if ( !$query )
		{
			registerFailedOrderList( $order_no, 'SBSC', $arrErrMsg[ QLM_SYSTEM_ERR ] );
			$arrRet = array( 'err_code' => QLM_SYSTEM_ERR, 'err_msg' => $arrErrMsg[ QLM_SYSTEM_ERR ], 'token' => '' );	
			return $JSON->encode( $arrRet );
		}

		//update current session time
		$sql  = 'update tblsession set';
		$sql .= '  last_time = \'' . time() . '\'';
		$sql .= ' where token = \'' . db_sql( $token ) . '\'';
		
		db_query( $sql, $link );

		$arrRet = array( 'err_code' => QLM_SUCCESS, 'err_msg' => $arrErrMsg[ QLM_SUCCESS ] );

		return $JSON->encode( $arrRet );
	}
?>