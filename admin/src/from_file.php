<form  id = 'uploadForm' name = 'uploadForm' method = post action = '' enctype="multipart/form-data">
	<input type = 'file' id = 'file' name = 'file'>
	<input type = 'submit' value = 'Upload'>
</form>

<?php
	if ( isset( $_FILES["file"] ) )
	{
		if ($_FILES["file"]["error"] > 0) 
		{
			echo "Error: " . $_FILES["file"]["error"] . "<br>";
		} 
		else 
		{
			move_uploaded_file( $_FILES["file"]["tmp_name"], './upload/ip.txt' );

			$file = './upload/ip.txt';
			$handle = fopen( $file, "r" );
			
			echo '<div id = "scroll-div" style = "max-height: 500px; overflow: auto;">';
			echo '
			<table class = "admin-table" width = 100% cellspacing = 0 cellpadding = 5>
				<col width = 10%></col>		
				<col width = 10%></col>		
				<col width = 10%></col>		
				<col width = 10%></col>		
				<col width = 10%></col>		
				<col width = 10%></col>		
				<col width = 10%></col>		
				<col width = 10%></col>		
				<col width = 10%></col>		
				<col width = 10%></col>		
			';
			echo '	<thead>
						<th>No</th>
						<th>SSH Server IP</th>
						<th>User ID</th>
						<th>Password</th>
						<th>Geo code</th>
						<th>Geo zone</th>
						<th>Geo city</th>
						<th>ISP</th>
						<th>Zip code</th>
						<th>**</th>
					</thead>';
			if ( $handle ) 
			{
				$i = 1;
				while (!feof($handle)) 
				{
					$buffer = fgets($handle, 4096);
					$arrData = explode( ' ', $buffer );

					$svr_ip = isset( $arrData[0] ) ? trim( $arrData[0] ) : '';
					$user	= isset( $arrData[1] ) ? trim( $arrData[1] ) : '';
					$passwd	= isset( $arrData[2] ) ? trim( $arrData[2] ) : '';

					echo '
						<tr id = "tr_' . $i . '">
							<td align = right>' . $i . '</td>
							<td align = center id = "ip_' . $i . '">' . $svr_ip . '</td>
							<td align = center id = "user_' . $i . '">' . $user . '</td>
							<td align = center id = "passwd_' . $i . '">' . $passwd . '</td>
							<td align = center id = "geo_code_' . $i . '"></td>
							<td align = center id = "geo_zone_' . $i . '"></td>
							<td align = center id = "geo_city_' . $i . '"></td>
							<td align = center id = "isp_' . $i . '"></td>
							<td align = center id = "zip_code_' . $i . '"></td>
							<td align = center id = "result_' . $i . '"></td>
						</tr>
					';
					$i ++;
				}
				fclose($handle);
			}
			echo '</table>';
			echo '</div>';
			echo '<input type = "hidden" id = "total_num" value = "' . ( $i - 1 ) . '">';
			echo '<input type = "button" id = "import_btn" value = "Import" onclick = "javascript:importData(1)">';
			echo '<img src = "../images/loader.gif" id = "import-loading">';
		}
	}
?>

<form id = 'importForm' name = 'importForm' method = post action = './src/ajax.php'>
	<input type = 'hidden' id = 'action'		name = 'action'		value = 'IMPORT_FROM_FILE'>
	<input type = 'hidden' id = 'index'			name = 'index'		value = ''>
	<input type = 'hidden' id = 'svr_ip'		name = 'svr_ip'		value = ''>
	<input type = 'hidden' id = 'svr_user'		name = 'svr_user'	value = ''>
	<input type = 'hidden' id = 'svr_passwd'	name = 'svr_passwd' value = ''>
</form>

<script>
	$(document).ready(function(){
		$('#importForm').ajaxForm({
			success:function( ret )
			{
				var cur_no = parseInt( $('#importForm').find('[id=index]').val() );
				var total = parseInt( $('#total_num').val() );

				if ( cur_no <= total )
				{
					$('#result_' + cur_no).html( ret );
					if ( ret == 'DUPLICATE' )
					{
						$('#result_' + cur_no).html('Duplicated Ip!');
						$('#tr_' + cur_no).attr('class', 'invalid');
					}
					else if ( ret == 'IP Address not found' )
					{
						$('#result_' + cur_no).html('IP not found!');
						$('#tr_' + cur_no).attr('class', 'invalid');
					}
					else
					{
						var arr = ret.split(':');
						var geo_code = arr[0];
						var geo_zone = arr[1];
						var geo_city = arr[2];
						var isp		 = arr[3];
						var zip		 = arr[4];

						$('#geo_code_' + cur_no).html( geo_code );
						$('#geo_zone_' + cur_no).html( geo_zone );
						$('#geo_city_' + cur_no).html( geo_city );
						$('#isp_' + cur_no).html( isp );
						$('#zip_code_' + cur_no).html( zip );

						$('#result_' + cur_no).html('Success!');
						$('#tr_' + cur_no).attr('class', 'complete');
					}
					
					$('#scroll-div').scrollTop( 40 + cur_no * 15 );

					if ( cur_no < total )
					{
						importData( cur_no + 1 );
					}
					else
					{
						alert('Import completed!');
						$('#import_btn').removeAttr('disabled');
						$('#import-loading').css('display', 'none');
						$('#scroll-div').scrollTop( 0 );
					}
				}
			}
		});
	});

	function importData( start )
	{
		$('#import_btn').attr('disabled', 'disabled');
		$('#import-loading').css('display', 'inline');

		$('#importForm').find('[id=index]').val( start );
		$('#importForm').find('[id=svr_ip]').val( $('#ip_' + start).html() );
		$('#importForm').find('[id=svr_user]').val( $('#user_' + start).html() );
		$('#importForm').find('[id=svr_passwd]').val( $('#passwd_' + start).html() );

		$('#importForm').submit();
	}
</script>