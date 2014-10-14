<?php
	// ********** Register the method to expose ********** //
	$server->register('OMS_SendSBSCOrderStatus',			// method name
		array(
			'token'					=> 'xsd:string',
			'order_no'				=> 'xsd:string',
			'transmit_no'			=> 'xsd:string',
			'pickup_date'			=> 'xsd:int',
			'goods_volumn'			=> 'xsd:float',
			'real_weight'			=> 'xsd:float',
			'volumn_weight'			=> 'xsd:float',
			'exception_reason'		=> 'xsd:int',
			'exception_description' => 'xsd:int',
			'arrived_date'			=> 'xsd:int'
		),														// input parameters
		array('return' => 'xsd:string'),						// output parameters
		'urn:invokesensorwsdl',									// namespace
		'urn:invokesensorwsdl#OMS_SendSBSCOrderStatus',			// soapaction
		'rpc',													// style
		'encoded',												// use
		'Function that send SBSC order process status'			// documentation
	);

// ********************************************************* //

// ********** Define the method as a PHP function ********** //
	function OMS_SendSBSCOrderStatus( $token, $order_no, $transmit_no, $pickup_date, $goods_volumn, $real_weight, $volumn_weight, $exception_reason, $exception_description, $arrived_date )
	{
		global $JSON, $link, $arrExcptMsg, $arrErrMsg;

		$funcName = 'OMS_SendSBSCOrderStatus';

		if ( strlen( trim( $token ) ) <= 0 ) return QLM_NO_TOKEN;

		$arrClientInfo = getClientInfoFromToken( $token );
		$bCheck = getFunctionPermission( $arrClientInfo['company_id'], $funcName );

		if ( $bCheck <= 0 ) return QLM_NO_PERMISSION_FUNCTION;

		if ( strlen( trim( $order_no ) ) <= 0 ) return QLM_NO_PARAM;
		if ( strlen( trim( $transmit_no ) ) <= 0 || strlen( trim( $goods_volumn ) ) <= 0 || strlen( trim( $real_weight ) ) <= 0 || strlen( trim( $volumn_weight ) ) <= 0 || strlen( trim( $exception_reason ) ) <= 0 || strlen( trim( $exception_description ) ) <= 0  )
			return QLM_NO_PARAM;

		//get order_type_code from order_master
		$order_info = getData( 'tblordermaster', ' where order_no = \'' . db_sql( $order_no ) . '\'', 'order_type_code' );
		$order_type_code = $order_info['order_type_code'];

		$real_pickup_date = '';
		$real_pickup_time = '';
		if ( $pickup_date > 0 ) 
		{
			$real_pickup_date = date( 'Y-m-d', $pickup_date );			
			$real_pickup_time = date( 'H:i', $pickup_date );
		}

		$real_arrived_date = '';
		$real_arrived_time = '';
		if ( $arrived_date > 0 )
		{
			$real_arrived_date = date( 'Y-m-d', $arrived_date );
			$real_arrived_time = date( 'H:i', $arrived_date );
		}

		//check already exist order_no in status table
		$bCheck = getDataByMySQLFunc( 'tblsbscorderstate', ' where order_no = \'' . db_sql( $order_no ) . '\'', 'no', 'count' );
		$sql  = '';
		if ( $bCheck > 0 )
		{
			$sql  = 'update tblsbscorderstate set';
			$sql .= '  order_type = \'' . db_sql( $order_type_code ) . '\'';
			$sql .= ', transmit_no = \'' .  db_sql( $transmit_no ) . '\'';
			$sql .= ', pickup_date = \'' . db_sql( $real_pickup_date ) . '\'';
			$sql .= ', pickup_time = \'' . db_sql( $real_pickup_time ) . '\'';
			$sql .= ', goods_volumn = \'' . db_sql( $goods_volumn ) . '\'';
			$sql .= ', real_weight = \'' . db_sql( $real_weight ) . '\'';
			$sql .= ', volumn_weight = \'' . db_sql( $volumn_weight ) . '\'';
			$sql .= ', exception_reason = \'' . db_sql( $exception_reason ) . '\'';
			$sql .= ', exception_description = \'' . db_sql( $exception_description ) . '\'';
			$sql .= ', arrived_date = \'' . db_sql( $real_arrived_date ) . '\'';
			$sql .= ', arrived_time = \'' . db_sql( $real_arrived_time ) . '\'';
			$sql .= '  where order_no = \'' . db_sql( $order_no ) . '\'';
		}
		else
		{
			$sql  = 'insert into tblsbscorderstate set';
			$sql .= '  order_no = \'' . db_sql( $order_no ) . '\'';
			$sql .= ', order_type = \'' . db_sql( $order_type_code ) . '\'';
			$sql .= ', transmit_no = \'' .  db_sql( $transmit_no ) . '\'';
			$sql .= ', pickup_date = \'' . db_sql( $real_pickup_date ) . '\'';
			$sql .= ', pickup_time = \'' . db_sql( $real_pickup_time ) . '\'';
			$sql .= ', goods_volumn = \'' . db_sql( $goods_volumn ) . '\'';
			$sql .= ', real_weight = \'' . db_sql( $real_weight ) . '\'';
			$sql .= ', volumn_weight = \'' . db_sql( $volumn_weight ) . '\'';
			$sql .= ', exception_reason = \'' . db_sql( $exception_reason ) . '\'';
			$sql .= ', exception_description = \'' . db_sql( $exception_description ) . '\'';
			$sql .= ', arrived_date = \'' . db_sql( $real_arrived_date ) . '\'';
			$sql .= ', arrived_time = \'' . db_sql( $real_arrived_time ) . '\'';
		}

		$query = db_query( $sql, $link );
	
		if ( !$query ) return QLM_SYSTEM_ERR;

		return QLM_SUCCESS;
	}
?>