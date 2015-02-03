<?PHP
	header('Content-Type: text/xml; charset=utf8');

	$modulePath = '../modules';
	require_once( $modulePath . '/module_index.php' );

	$_SERVER['HTTPS'] = '';
	// Create the server instance
	$server = new soap_server();
	$server->xml_encoding = "utf-8";
	$server->soap_defencoding = "utf-8";

	// Initialize WSDL support
	$server->configureWSDL('invokesensorwsdl', 'urn:invokesensorwsdl');

	define( 'QLM_SUCCESS',					0 );
	define( 'QLM_UNKNOWN_ERR',				1 );
	define( 'QLM_SYSTEM_ERR',				2 );
	define( 'QLM_NO_TOKEN',					3 );
	define( 'QLM_NO_PERMISSION_FUNCTION',	4 );
	define( 'QLM_NO_PARAM',					5 );
	define( 'QLM_INVALID_PARAM',			6 );
	define( 'QLM_NO_USER_VERIFY',			7 );
	define( 'QLM_NOT_MATCH_PASSWD',			8 );
	define( 'QLM_NO_BOX_NUMBER',			9 );
	define( 'QLM_NO_BOX_NO_LIST',			10 );
	define( 'QLM_NO_MATCH_BOX',				11 );
	define( 'QLM_NO_GOODS_PRICE',			12 );
	define( 'QLM_NO_MODELNO',				13 );
	define( 'QLM_NO_SERIALNO',				14 );
	define( 'QLM_NOT_MACH_DATE_FORMAT',		15 );
	define( 'QLM_NOT_MATCH_TIME_FORMAT',	16 );


	define( 'QLM_EXCEPT_EMPTY',			0 );
	define( 'QLM_EXCEPT_PICKUP_EMPTY',	1 );
	define( 'QLM_EXCEPT_PICKUP_NOSAME', 2 );
	define( 'QLM_EXCEPT_TRANS_BREAK',	3 );
	define( 'QLM_EXCEPT_ETC',			4 );

	$arrErrMsg = array(
		QLM_SUCCESS					=> 'Success',
		QLM_UNKNOWN_ERR				=> 'Unknow error',				
		QLM_SYSTEM_ERR				=> 'System error',				
		QLM_NO_TOKEN				=> 'Please input token',					
		QLM_NO_PERMISSION_FUNCTION	=> 'Not permission',	
		QLM_NO_PARAM				=> 'No parameter',					
		QLM_INVALID_PARAM			=> 'Invalid parameter',			
		QLM_NO_USER_VERIFY			=> 'Not user verified',			
		QLM_NOT_MATCH_PASSWD		=> 'Not match user name and password',			
		QLM_NO_BOX_NUMBER			=> 'Box number must bigger than 0',			
		QLM_NO_BOX_NO_LIST			=> 'There is not box number list',			
		QLM_NO_MATCH_BOX			=> 'Not match box number and box number list',				
		QLM_NO_GOODS_PRICE			=> 'Goods price must bigger than 0',			
		QLM_NO_MODELNO				=> 'There is not MODEL No.',				
		QLM_NO_SERIALNO				=> 'There is not SERIAL No.',				
		QLM_NOT_MACH_DATE_FORMAT	=> 'Date format must be YYYY-mm-dd',		
		QLM_NOT_MATCH_TIME_FORMAT	=> 'Time formst must be HH:MM'	
	);

	$arrExcptMsg = array(
		QLM_EXCEPT_EMPTY			=> 'There is not exception',
		QLM_EXCEPT_PICKUP_EMPTY		=> 'There are not pickup goods',
		QLM_EXCEPT_PICKUP_NOSAME	=> 'Pickup goods information do not right',
		QLM_EXCEPT_TRANS_BREAK		=> 'Break goods while transmitting',
		QLM_EXCEPT_ETC				=> 'Unknown Exception'
	);

	// ********** Define the method as a PHP function ********** //

	//out side...
	require_once( 'CreateSession.php' );
	require_once( 'SBSC_SendOrder.php' );
	require_once( 'SBSC_GetOrderStatus.php' );
	require_once( 'ReleaseSession.php' );

	//inside
	require_once( 'OMS_SendSBSCOrderStatus.php' );
	
	// ********************************************************* // 
/*
	$ret = CreateSession( 'sbsc_qlm', 'rootroot' );
	$arr = jsonDecode( $ret );

	echo SBSC_SendOrder( $arr['token'], '133454', '2013-03-20', 'HS', '환불', 'ASC1', '에이에스씨1', '에이에스씨1-주소', '에이에스씨1-우편번호', '판다1', '판다1-전화', 'BJ', '北京', '수신1', '수신자', '천진시 조양구 어찌구 저찌구',
		'수신-우편', '수신자-이름', '수신자-전화', '수신도시', '수신도시이름', 1, '["box1"]', 500.2, 2000.3, 'TV', 'TV-123', ''
	);

	echo OMS_SendSBSCOrderStatus( $arr['token'], '123455', 'QLM_123455', 1364023220, 50.5, 50.5, 50.5, 0, 'aaaaa', 0 );
*/
	// ********************************************************* // 

	// Use the request to (try to) invoke the service
	$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
	$server->service($HTTP_RAW_POST_DATA);
?>
