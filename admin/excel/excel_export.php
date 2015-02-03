<?php
	//echo $_REQUEST['where_sql'];
	//table���궦 �ɵ۵� ���� �����궦 excel������ ȸ�� �θ����� 
	$modulePath = "../modules";
	require_once ( $modulePath."/module_index.php" );

	$where = $_POST['where_sql'];
	
	
	$excelFilename			= "order_" . date( 'Y-m-d' ) . '.xls';

	$engArr = array( "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
				 "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI", "AJ", "AK", "AL", "AM", "AN", "AO", "AP", "AQ", "AR", "AS", "AT", "AU", "AV", "AW", "AX", "AY", "AZ");
	$objPHPExcel = new PHPExcel();
	
	//label make
	$tblsbscorderlist_Key = array( 
		"no" 				=> "no",   
		"order_no" 			=> "客户订单", 
		"order_date"		=> "订单日期", 
		"order_type_code"	=> "业务代码", 
		"order_type_name"	=> "业务类型名称", 
		"asc_code"			=> "发件单位代码", 
		"asc_name"			=> "发件单位名称",
		"asc_address"		=> "发件单位地址",
		"asc_postcode"		=> "发件单位邮编",
		"sender_name"		=> "发件人姓名",
		"sender_phone"		=> "发件人电话",
		"sender_city_code"	=> "发件城市代码",
		"sender_city_name"	=> "发件城市名称",
		"receive_code"		=> "收件单位代码",
		"receive_name"		=> "收件单位名称",
		"receive_address"	=> "收件单位地址",
		"receive_postcode"	=> "收件单位邮编",
		"receive_person"	=> "收件单位邮编",
		"receive_person"	=> "收件人电话",
		"receive_city_code"	=> "收件城市代码",
		"receive_city_name" => "收件城市名称",
		"box_number"		=> "件数",
		"box_no_list"		=> "箱子号码",
		"goods_price"		=> "货物价值",
		"insurance_price"	=> "保险费",
		"model_no"			=> "型号",
		"serial_no"			=> "序列号",
		"description"		=> "备注",
		"is_processed" 		=> "处理" );
	$rowNum		= 1;
	$colNum 	= 0;
	foreach ( $tblsbscorderlist_Key as $key => $value )
	{
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue( $engArr[ $colNum ] . $rowNum , $key ); $colNum++;
	}
	$rowNum++;
	
	//data make
	$sql  = 'select * from tblordermaster';
	$sql .= $where;
	$query = db_query( $sql, $link );
	while ( $row = mysql_fetch_assoc( $query ) )
	{
		if ( $row['company_code'] == 'SBSC' )
		{
			$orderInfo = getData( 'tblsbscorderlist', ' where order_no = \'' . db_sql( $row['order_no'] ) . '\'', '*' );
			$colNum = 0;
			foreach ( $tblsbscorderlist_Key as $value )
			{
				if ( $value == "order_date" )
					$orderInfo[ $value ] = date('Y-m-d', $row['order_date'] );
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue( $engArr[ $colNum ] . $rowNum , $orderInfo[ $value ] ); $colNum++;
			}
			$rowNum++;
		}
	}

	$objPHPExcel->getActiveSheet()->setTitle('Sheet1');
	$objPHPExcel->setActiveSheetIndex(0);

	// Save Excel file
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save( $excelFilefolder . "templete.xls" );

	// ************************************************** //

	// ********** Download the excel file ********** //

	// Set Header
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header("Content-Disposition: attachment; filename=$excelFilename");
	header("Content-Type: application/octet-stream");
	header("Content-Transfer-Encoding: binary");

	$fp = fopen( $excelFilefolder . "templete.xls", 'r' );
	while( $buffer = fread($fp, 1024 ) )
	{
		echo $buffer;
	}
	fclose($fp);
?>