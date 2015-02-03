<?PHP

// ********** Register the method to expose ********** //

	$server->register('SBSC_GetOrderStatus',					// method name
		array(
			'token'				=> 'xsd:string',
			'start_order_date'	=> 'xsd:string',
			'end_order_date'	=> 'xsd:string',
			'order_no_list'		=> 'xsd:string',
		),												// input parameters
		array('return' => 'xsd:string'),				// output parameters
		'urn:invokesensorwsdl',							// namespace
		'urn:invokesensorwsdl#SBSC_GetOrderStatus',			// soapaction
		'rpc',											// style
		'encoded',										// use
		'Get order status'								// documentation
	);

// ********************************************************* //

// ********** Define the method as a PHP function ********** //
	
	function SBSC_GetOrderStatus( $token, $start_order_date, $end_order_date, $order_no_list )
	{
		global $JSON, $link, $arrExcptMsg, $arrErrMsg;

		$funcName = 'SBSC_GetOrderStatus';

		$arrRet = array();

		//check validation
		if ( strlen( trim( $token ) ) <= 0 )
		{
			$arrRet = array( 'err_code' => QLM_NO_TOKEN, 'err_msg' => $arrErrMsg[ QLM_NO_TOKEN ], 'order_status_list' => '' );
			return $JSON->encode( $arrRet );
		}

		if ( strlen( trim( $start_order_date ) ) <= 0 || strlen( trim( $end_order_date ) ) <= 0 )
		{
			$arrRet = array( 'err_code' => QLM_NO_PARAM, 'err_msg' => $arrErrMsg[ QLM_NO_PARAM ], 'order_status_list' => '' );
			return $JSON->encode( $arrRet );
		}

		$arrStartDate = explode( '-', $start_order_date );
		$arrEndDate   = explode( '-', $end_order_date );

		if ( count( $arrStartDate ) < 3 || count( $arrEndDate ) < 3 )
		{
			$arrRet = array( 'err_code' => QLM_NOT_MACH_DATE_FORMAT, 'err_msg' => $arrErrMsg[ QLM_NOT_MACH_DATE_FORMAT ], 'order_status_list' => '' );
			return $JSON->encode( $arrRet );
		}

		$arrClientInfo = getClientInfoFromToken( $token );
		$bCheck = getFunctionPermission( $arrClientInfo['company_id'], $funcName );

		if ( $bCheck <= 0 )
		{
			$arrRet = array( 'err_code' => QLM_NO_PERMISSION_FUNCTION, 'err_msg' => $arrErrMsg[ QLM_NO_PERMISSION_FUNCTION ], 'order_status_list' => '' );
			return $JSON->encode( $arrRet );
		}

		$arrRet = array(
			'err_code'	=> QLM_SUCCESS,
			'err_msg'	=> $arrErrMsg[ QLM_SUCCESS ],
			'order_status_list' => array()
		);

		$startTimestamp = mktime( 0, 0, 0, $arrStartDate[1], $arrStartDate[2], $arrStartDate[0] );
		$endTimestamp   = mktime( 23, 59, 59, $arrEndDate[1], $arrEndDate[2], $arrEndDate[0] );


		if ( strlen( trim( $order_no_list ) ) <= 0 )
		{
			//get order_list
			$sql  = 'select * from tblordermaster';
			$sql .= ' where 1';
			$sql .= ' and order_date >= \'' . db_sql( $startTimestamp ) . '\'';
			$sql .= ' and order_date <= \'' . db_sql( $endTimestamp ) . '\'';
			$query = db_query( $sql, $link );

			while ( $row = mysql_fetch_assoc( $query ) )
			{
				$sbscInfo = getData( 'tblsbscorderlist', ' where order_no = \'' . db_sql( $row['order_no'] ) . '\'', '*' );
				$unit_order = array(
					'transmit_no'			=> $sbscInfo['transmit_no'],
					'order_no'				=> $sbscInfo['order_no'],
					'order_type_code'		=> $sbscInfo['order_type_code'],
					'pickup_date'			=> $sbscInfo['pickup_date'],
					'pickup_time'			=> $sbscInfo['pickup_time'],
					'goods_volumn'			=> $sbscInfo['goods_volumn'],
					'real_weight'			=> $sbscInfo['real_weight'],
					'volumn_weight'			=> $sbscInfo['volumn_weight'],
					'exception_reason'		=> $sbscInfo['exception_reason'],
					'exception_description' => $sbscInfo['exception_description'],
					'arrived_date'			=> $sbscInfo['arrived_date'],
					'arrived_time'			=> $sbscInfo['arrived_time']
				);
				
				$arrRet['order_status_list'][] = $unit_order;
			}
		}
		else
		{
			$arrOrderList = jsonDecode( $order_no_list );
			$where  = ' where 1';
			$where .= ' and (';
			foreach (  $arrOrderList as $order_no )
			{
				$where .= 'order_no = \'' . db_sql( $order_no ) . '\' or';
			}

			$where  = substr( $where, 0, strlen( $where ) - 3 );
			$where .= ')';

			$sql  = 'select * from tblordermaster';
			$sql .= $where;
			$sql .= ' and order_date >= \'' . db_sql( $startTimestamp ) . '\'';
			$sql .= ' and order_date <= \'' . db_sql( $endTimestamp ) . '\'';
			$query = db_query( $sql, $link );

			while ( $row = mysql_fetch_assoc( $query ) )
			{
				$sbscInfo = getData( 'tblsbscorderlist', ' where order_no = \'' . db_sql( $row['order_no'] ) . '\'', '*' );
				$unit_order = array(
					'transmit_no'			=> $sbscInfo['transmit_no'],
					'order_no'				=> $sbscInfo['order_no'],
					'order_type_code'		=> $sbscInfo['order_type_code'],
					'pickup_date'			=> $sbscInfo['pickup_date'],
					'pickup_time'			=> $sbscInfo['pickup_time'],
					'goods_volumn'			=> $sbscInfo['goods_volumn'],
					'real_weight'			=> $sbscInfo['real_weight'],
					'volumn_weight'			=> $sbscInfo['volumn_weight'],
					'exception_reason'		=> $sbscInfo['exception_reason'],
					'exception_description' => $sbscInfo['exception_description'],
					'arrived_date'			=> $sbscInfo['arrived_date'],
					'arrived_time'			=> $sbscInfo['arrived_time']
				);
				
				$arrRet['order_status_list'][] = $unit_order;
			}
		}

		//update current session time
		$sql  = 'update tblsession set';
		$sql .= '  last_time = \'' . time() . '\'';
		$sql .= ' where token = \'' . db_sql( $token ) . '\'';
		
		db_query( $sql, $link );

		return $JSON->encode( $arrRet );
	}
?>