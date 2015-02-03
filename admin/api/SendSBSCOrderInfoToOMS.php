<?php
	header('Content-Type: text/xml; charset=utf8');
	set_time_limit( 0 );

	$modulePath = '../modules';
	require_once( $modulePath . '/module_index.php' );

	$oms_url = $_GET['target_url'];

	$sql  = 'select * from tblsbscorderlist';
	$sql .= ' where is_processed = \'' . db_sql( '0' ) . '\'';
	$query = db_query( $sql, $link );

	while ( $row = mysql_fetch_assoc( $query ) )
	{
		$client = new SoapClient( $oms_url );

		$param = array(
			'order_no'			=> $row['order_no'],
			'order_date'		=> $row['order_date'],
			'order_type_code'	=> $row['order_type_code'],
			'order_type_name'	=> $row['order_type_name'],
			'asc_code'			=> $row['asc_code'],
			'asc_name'			=> $row['asc_name'],
			'asc_address'		=> $row['asc_address'],
			'asc_postcode'		=> $row['asc_postcode'],
			'sender_name'		=> $row['sender_name'],
			'sender_phone'		=> $row['sender_phone'],
			'sender_city_code'	=> $row['sender_city_code'],
			'sender_city_name'	=> $row['sender_city_name'],
			'receive_code'		=> $row['receive_code'],
			'receive_name'		=> $row['receive_name'],
			'receive_address'	=> $row['receive_address'],
			'receive_postcode'	=> $row['receive_postcode'],
			'receive_person'	=> $row['receive_person'],
			'receive_phone'		=> $row['receive_phone'],
			'receive_city_code' => $row['receive_city_code'],
			'receive_city_name' => $row['receive_city_name'],
			'box_number'		=> $row['box_number'],
			'box_no_list'		=> $row['box_no_list'],
			'goods_price'		=> $row['goods_price'],
			'insurance_price'	=> $row['insurance_price'],
			'model_no'			=> $row['model_no'],
			'serial_no'			=> $row['serial_no'],
			'description'		=> $row['description'],
		);

		$ret = $client->__call( 'OMS_SendSBSCOrderInfo', $param );

		if ( $ret == 0 )
		{
			$sql  = 'update tblsbscorderlist set';
			$sql .= ' is_processed = \'' . db_sql( '1' ) . '\'';
			$sql .= ' where no = \'' . db_sql( $row['no'] ) . '\'';
			db_query( $sql, $link );

			$sql  = 'update tblordermaster set';
			$sql .= ' is_processed = \'' . db_sql( '1' ) . '\'';
			$sql .= ' where order_no = \'' . db_sql( $row['order_no'] ) . '\'';
			db_query( $sql, $link );
		}
	}

	//delete sessions before 5 hours from now
	$sql  = 'select * from tblsession';
	$query = db_query( $sql, $link );
	while ( $row = mysql_fetch_assoc( $query ) )
	{
		$left = abs( time() - $row['last_time'] ) / 3600;

		if ( $left > 5 )
		{
			$sql  = 'delete from tblsession';
			$sql .= ' where no = \'' . db_sql( $row['no'] ) . '\'';
			db_query( $sql, $link );
		}
	}

	$sql  = 'select order_no from tblordermaster';
	$query = db_query( $sql, $link );
	$arrOrderList = array();

	while ( $row = mysql_fetch_assoc( $query ) )
	{
		//check exist order status table
		$check = getDataByMySQLFunc( 'tblsbscorderstate', ' where order_no = \'' . db_sql( $row['order_no'] ) . '\'', 'no', 'count' );

		if ( $check > 0 ) continue;

		$arrOrderList[] = $row['order_no'];
	}

	$client = new SoapClient( $oms_url );

	$param = array(
		'order_no' => $JSON->encode( $arrOrderList )
	);
	$ret = $client->__call( 'OMS_GetOrderState', $param );

	$arrRet = jsonDecode( $ret );
	
	foreach ( $arrRet as $arrUnit )
	{
		$trans_no 	= ( isset( $arrUnit['TRANS_NO'] ) ) ? $arrUnit['TRANS_NO'] : '';
		$order_no 	= ( isset( $arrUnit['ORDER_NO'] ) ) ? $arrUnit['ORDER_NO'] : '';
		$order_type = ( isset( $arrUnit['ORDER_TYPE_CODE'] ) ) ? $arrUnit['ORDER_TYPE_CODE'] : '';		
		$pickup 	= ( isset( $arrUnit['PICKUP'] ) ) ? $arrUnit['PICKUP'] : '';
		$pod 		= ( isset( $arrUnit['POD'] ) ) ? $arrUnit['POD'] : '';
		$vol		= ( isset( $arrUnit['VOLUMN'] ) ) ? $arrUnit['VOLUMN'] : '';
		$mass		= ( isset( $arrUnit['MASS'] ) ) ? $arrUnit['MASS'] : '';
		$sizemass	= ( isset( $arrUnit['SIZEMASS'] ) ) ? $arrUnit['SIZEMASS'] : '';
		$exception 	= ( isset( $arrUnit['EXCEPTION_REASON'] ) ) ? $arrUnit['EXCEPTION_REASON'] : '';
		$exception_note = ( isset( $arrUnit['EXCEPTION_NOTE'] ) ) ? $arrUnit['EXCEPTION_NOTE'] : '';

		$pickup_date = ( $pickup > 0 || $pickup == '' ) ? '' : date( 'Y-m-d', $pickup );
		$pickup_time = ( $pickup > 0 || $pickup == '' ) ? '' : date( 'H:i:s', $pickup );		
		
		$pod_date = ( $pod > 0 || $pod == '' ) ? '' : date( 'Y-m-d', $pod );
		$pod_time = ( $pod > 0 || $pod == '' ) ? '' : date( 'H:i:s', $pod );
		
		if ( trim( $order_no ) == '' ) continue;
		//check already exist this order's status
		$check = getDataByMySQLFunc( 'tblsbscorderstate', ' where order_no', 'no', 'count' );

		$sql = '';
		if ( $check > 0 )
		{
			$sql .= 'update tblsbscorderstate set ';
			$sql .= '  order_type = \'' . db_sql( $order_type ) . '\'';			
			$sql .= ', transmit_no = \'' . db_sql( $trans_no ) . '\'';						
			$sql .= ', pickup_date = \'' . db_sql( $pickup_date ) . '\'';
			$sql .= ', pickup_time = \'' . db_sql( $pickup_time ) . '\'';			
			$sql .= ', goods_volumn = \'' . db_sql( $vol ) . '\'';
			$sql .= ', real_weight = \'' . db_sql( $mass ) . '\'';			
			$sql .= ', volumn_weight = \'' . db_sql( $sizemass ) . '\'';						
			$sql .= ', exception_reason = \'' . db_sql( $exception ) . '\'';
			$sql .= ', exception_description = \'' . db_sql( $exception_note ) . '\'';
			$sql .= ', arrived_date = \'' . db_sql( $pod_date ) . '\'';
			$sql .= ', arrived_time = \'' . db_sql( $pod_time ) . '\'';
			$sql .= ' where order_no = \'' . db_sql( $order_no ) . '\'';
		}
		else
		{
			$sql .= 'insert into tblsbscorderstate set ';
			$sql .= '  order_no = \'' . db_sql( $order_no ) . '\'';
			$sql .= ', order_type = \'' . db_sql( $order_type ) . '\'';			
			$sql .= ', transmit_no = \'' . db_sql( $trans_no ) . '\'';						
			$sql .= ', pickup_date = \'' . db_sql( $pickup_date ) . '\'';
			$sql .= ', pickup_time = \'' . db_sql( $pickup_time ) . '\'';			
			$sql .= ', goods_volumn = \'' . db_sql( $vol ) . '\'';
			$sql .= ', real_weight = \'' . db_sql( $mass ) . '\'';			
			$sql .= ', volumn_weight = \'' . db_sql( $sizemass ) . '\'';						
			$sql .= ', exception_reason = \'' . db_sql( $exception ) . '\'';
			$sql .= ', exception_description = \'' . db_sql( $exception_note ) . '\'';
			$sql .= ', arrived_date = \'' . db_sql( $pod_date ) . '\'';
			$sql .= ', arrived_time = \'' . db_sql( $pod_time ) . '\'';
		}

		db_query( $sql, $link );
	}
?>