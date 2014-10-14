<?php
	function jsonDecode ( $jsonObj )
	{
		if ( trim( $jsonObj ) == "" ) return array();

		$comment = false;
		$out = '$x=';
   
		for ($i=0; $i<strlen($jsonObj); $i++)
		{
			if (!$comment)
			{
				if ($jsonObj[$i] == '{')        $out .= ' array(';
				else if ($jsonObj[$i] == '}')   $out .= ')';
				else if ($jsonObj[$i] == '[')   $out .= ' array(';
				else if ($jsonObj[$i] == ']')   $out .= ')';
				else if ($jsonObj[$i] == ':')   $out .= '=>';
				else							$out .= $jsonObj[$i];           
			}
			else $out .= $jsonObj[$i];
			if ($jsonObj[$i] == '"')    $comment = !$comment;
		}
	    eval($out.';');
		return $x; 
	}
	
	function fill_options_no_space ( $var, $opt= "", $flag_single = false )
	{
		while (list ($key, $val) = each ($var)) {
			if( $flag_single ) $key = $val;
			if ( $opt != '' && trim($key) == trim($opt) ) {
				echo "<option value='".$key."' selected>".$val."</option>\r\n";
			} else {
				echo "<option value='".$key."' >".$val."</option>\r\n";
			}
		}
	}

	function fill_options ( $var, $opt= "", $default_str="", $flag_single = false )
	{
		echo "<option value='' style='color:silver'>" . $default_str . "</option>\r\n";
		fill_options_no_space ( $var, $opt, $flag_single );
	}

	function fill_radios ( $var, $name, $default_sel="" )
	{
		while (list ($key, $val) = each ($var)) {
			echo "<span style='margin-left:5px;' onclick='javascript:radio_span_box_select( $(this) );'>";
				if ( $default_sel != '' && trim( $default_sel ) == trim( $key ) )
				{
					echo "<input type='radio' id='".$name."' name='".$name."' class='oms-radio validate[required]' value='".$key."' checked='checked'> ".$val." ";
				}
				else
				{
					echo "<input type='radio' id='".$name."' name='".$name."' class='oms-radio validate[required]' value='".$key."'> ".$val." ";
				}
			echo "</span>";
		}
	}

	function fill_enum_YN ( $opt= "" ) 
	{
		$arr = array ( "Y"=>"Y", "N"=>"N" );
		echo "	<option value=0></option>";
		while (list ($key, $val) = each ($arr)) {
			if ( $opt != '' && trim($key) == trim($opt) ) {
				echo "<option value=".$key." selected>".$val."</option>";
			} else {
				echo "<option value=".$key." >".$val."</option>";
			}
		}
	}

	function out_log( $msg )
	{
		$fp = fopen( "error.log", "w" );
		chmod("error.log",0777);
		fwrite( $fp, $msg );
		fclose( $fp );
	}

	function go_page ( $url ) 
	{
		echo '<script>{';
		echo "window.open('".$url."','_self')";
		echo '}</script>';
	}
	
	function parent_go_page ( $url ) 
	{
		echo '<script>{';
		echo "parent.window.open('".$url."','_self')";
		echo '}</script>';
	}

	function alert_msg ( $msg ) 
	{
		echo '<script>{';
		echo "	alert('".$msg."')";
		echo '}</script>';
	}

	function get_arr_val ( $arr_var, $key ) 
	{
		return ( isset($arr_var[$key])) ? $arr_var[$key] : '';
	}

	function echo_array ( $arr )
	{
		echo "<pre>";
		print_r ( $arr );
		echo "</pre>";
	}

	function get_parameter ( $para = "", $action = "" ) 
	{
		global $_GET;

		$parameters = '';
		reset ( $_GET );

		while ( list ( $key, $val ) = each ( $_GET ) ) 
		{
			if ( $key == '' ) continue;
			if ( $para != "" ) 
			{
				$oneword = ( explode  ( ',',  $para ) );

				if ( $action != "" && $action == "ONLY" ) 
				{
					if ( in_array ($key, $oneword ) )
					{
						$parameters .= $key."=".$val.'&';
					}
				} 
				else if ( $action == "" || $action == "OTHER") 
				{
					if ( !in_array ($key, $oneword ) )
					{
						$parameters .= $key."=".$val.'&';
					}
				}
			} 
			else 
			{
				$parameters .= $key."=".$val.'&';
			}
		}
		return $parameters;
	}

	function string_cut ( $str, $length ) 
	{
		$str = strip_tags ( $str );

		if ( mb_strlen ( $str, "utf-8" ) > $length ) 
		{
			$str = iconv( "utf-8", "utf-8", mb_substr( $str, 0, $length, "utf-8") ).'..';
		}
		return $str;
	}

	function Paging($totalRecord,$recordPerPage,$pagePerBlock,$currentPage)
	{
		global $cfg;
		$imgPath = $cfg['img_path'];

		if ( $totalRecord > 0 )
		{
			$totalNumOfPage = ceil($totalRecord/$recordPerPage);	//16page
			$totalNumOfBlock = ceil($totalNumOfPage/$pagePerBlock); //2block
			$currentBlock = ceil($currentPage/$pagePerBlock);		// 1page
				
			$startPage = ($currentBlock-1)*$pagePerBlock+1;			// 1page
			$endPage = $startPage+$pagePerBlock -1; // 10page
			if($endPage > $totalNumOfPage) $endPage = $totalNumOfPage;
			
			//NEXT,PREV 존재 여부
			$isNext = false;
			$isPrev = false;
			$isFirst = false;
			$isLast = false;

			if($currentBlock < $totalNumOfBlock)    $isNext = true; $isLast = true;
			if($currentBlock > 1)                   $isPrev = true; $isFirst = true;
			if($totalNumOfPage == $currentPage)		$isLast = false;
			if($currentPage == 1)					$isFirst = false;

			if($totalNumOfBlock == 1)
			{
				$isNext		= false;
				$isPrev		= false;
				$isFirst	= false;
				$isLast		= false;
			} 

			$para = get_parameter('pageno');
			$basicUrl = $_SERVER['PHP_SELF'].'?'.$para;

			if($isFirst)
			{
				$goFirstPage = 1; // 1page
				echo "<span class = 'btn'>";
				echo "	<a href=".$basicUrl."&pageno=".$goFirstPage.">";
				echo "		<img src='".$imgPath."/first.png'></a></span>";
			}
			else
			{
				echo "<span class = 'btn'>";
				echo "	<img src='".$imgPath."/first.png'>";
				echo "</span>";        
			}

			if($isPrev)
			{
				$goPrevPage = $startPage-$pagePerBlock; // 11page
				echo "<span class = 'btn'>";
				echo "	<a href=".$basicUrl."&pageno=".$goPrevPage.">";
				echo "		<img src='".$imgPath."/prev.png'></a></span>";
			}
			else
			{
				echo "<span class = 'btn'>";
				echo "	<img src='".$imgPath."/prev.png'>";
				echo "</span>";        
			}
			
			for($i=$startPage;$i<=$endPage;$i++)
			{
				$clsName = ( $i == $currentPage ) ? 'nav_selected' : 'nav';
				$clsNm = ( $i == $startPage ) ? "num_1" : "num";

				if ( $currentPage == $i )
					echo "<span class = nav_selected>".$i." </span>";
				else
					echo "<a href = ".$_SERVER['PHP_SELF']."?".$para."pageno=".$i." ><span class = nav style='cursor:pointer'>".$i."</span></a>";
			}

			if($isNext)
			{
				$goNextPage = $startPage + $pagePerBlock; // 11page
				echo "<span class = 'btn'>";
				echo "	<a href=".$basicUrl."&pageno=".$goNextPage.">";
				echo "		<img src='".$imgPath."/next.png'></a></span>";
			}
			else
			{
				echo "<span class = 'btn'>";
				echo "	<img src='".$imgPath."/next.png'>";
				echo "</span>";        
			}

			if($isLast)
			{
				$goLastPage = $totalNumOfPage; // 11page
				echo "<span class = 'btn'>";
				echo "	<a href=".$basicUrl."&pageno=".$goLastPage.">";
				echo "		<img src='".$imgPath."/last.png'></a></span>";
			}
			else
			{
				echo "<span class = 'btn'>";
				echo "	<img src='".$imgPath."/last.png'>";
				echo "</span>";        
			}
		}
	} 

	function Paging_post ( $totalRecord,$recordPerPage,$pagePerBlock,$currentPage,$formname, $btnPath )
	{
		$imgPath = $btnPath;

		if ( $totalRecord > 0 )
		{

			$totalNumOfPage = ceil($totalRecord/$recordPerPage);	//16page
			$totalNumOfBlock = ceil($totalNumOfPage/$pagePerBlock); //2block
			$currentBlock = ceil($currentPage/$pagePerBlock);		// 1page
				
			$startPage = ($currentBlock-1)*$pagePerBlock+1;			// 1page
			$endPage = $startPage+$pagePerBlock -1; // 10page
			if($endPage > $totalNumOfPage) $endPage = $totalNumOfPage;
			
			//NEXT,PREV 존재 여부
			$isNext = false;
			$isPrev = false;
			$isFirst = false;
			$isLast = false;

			if($currentBlock < $totalNumOfBlock)    $isNext = true; $isLast = true;
			if($currentBlock > 1)                   $isPrev = true; $isFirst = true;
			if($totalNumOfPage == $currentPage)		$isLast = false;
			if($currentPage == 1)					$isFirst = false;

			if($totalNumOfBlock == 1)
			{
				$isNext = false;
				$isPrev = false;
				$isFirst = false;
				$isLast = false;
			} 

			$para = get_parameter('pageno');
			$basicUrl = $_SERVER['PHP_SELF'].'?'.$para;

			if($isFirst)
			{
				$goPrevPage = 1; // 1page
				$action = $basicUrl."&pageno=".$goPrevPage;
				echo "<span class = 'btn'>";
				echo "	<a href=\"javascript:post_form('".$action."','".$formname."');\">";
				echo "		<img src='".$imgPath."/first.png'>";
				echo "	</a>";
				echo "</span>";        
			}
			else
			{
				echo "<span class = 'btn'>";
				echo "	<img src='".$imgPath."/first1.png'>";
				echo "</span>";        
			}

			if($isPrev)
			{
				$goPrevPage = $startPage-$pagePerBlock; // 11page
				$action = $basicUrl."&pageno=".$goPrevPage;
				echo "<span class = 'btn'>";
				echo "	<a href=\"javascript:post_form('".$action."','".$formname."');\">";
				echo "		<img src='".$imgPath."/prev.png'>";
				echo "	</a>";
				echo "</span>";        
			}
			else
			{
				echo "<span class = 'btn'>";
				echo "	<img src='".$imgPath."/prev1.png'>";
				echo "</span>";
			}
			
			for($i=$startPage;$i<=$endPage;$i++)
			{
				$action = $basicUrl."&pageno=".$i;
				$clsName = ( $i == $currentPage ) ? 'sel_num_nav' : 'num_nav';
				$clsNm = ( $i == $startPage ) ? "num_1" : "num";

				echo "<span class = '".$clsNm."'>";
				echo "	<a href=\"javascript:post_form('".$action."','".$formname."');\" class = '".$clsName."'>".$i."</a>";
				echo "</span>";
			}
			if($isNext)
			{
				$goNextPage = $startPage+$pagePerBlock; // 11page
			
				echo "<span class = 'btn'>";
				$action = $basicUrl."&pageno=".$goNextPage;
				echo "	<a href=\"javascript:post_form('".$action."','".$formname."');\">";
				echo "		<img src='".$imgPath."/next.png'>";
				echo "	</a>";
				echo "</span>";
			}
			else
			{
				echo "<span class = 'btn'>";
				echo "	<img src='".$imgPath."/next1.png'>";
				echo "</span>";      
			}
			if($isLast)
			{
				$goNextPage = $totalNumOfPage; // 11page
				$action = $basicUrl."&pageno=".$goNextPage;
				echo "<span class = 'btn'>";
				echo "	<a href=\"javascript:post_form('".$action."','".$formname."');\">";
				echo "		<img src='".$imgPath."/last.png'>";
				echo "	</a>";
				echo "</span>";
			}
			else
			{
				echo "<span class = 'btn'>";
				echo "	<img src='".$imgPath."/last1.png'>";
				echo "</span>";
			}
		}
	}

	function getData ( $tableName, $where, $fields ) 
	{
		global $link;

		$arr = array();

		if ( $fields == "*"  )
		{
			$sql		= "select * from ".$tableName." ".$where;
			$query		= db_query( $sql, $link );
			$columns	= mysql_num_fields( $query );
			$row		= mysql_fetch_assoc( $query );

			for($i = 0; $i < $columns; $i++) 
			{
				$field				= mysql_field_name( $query, $i );
				$arr[trim($field)]	= $row[trim($field)];
			}
		}
		else
		{
			$sql	= "select ".$fields." from ".$tableName." ".$where;
			$query	= db_query ( $sql, $link );
			$row	= mysql_fetch_assoc( $query );

			$split	= explode( ",", $fields );
			foreach ( $split as $k => $v )
			{
				$arr[trim($v)] = $row[trim($v)];
			}
		}

		return $arr;
	}

	function getDataByMySQLFunc ( $tableName, $where, $fieldName, $mysqlFunc )
	{
		global $link;

		$sql	= "SELECT ".$mysqlFunc."( ".$fieldName." ) as sp FROM ". $tableName ." ".$where;
		$query	= db_query( $sql, $link );
		$sp		= array_shift ( mysql_fetch_assoc ( $query ) );

		return $sp;
	}
?>