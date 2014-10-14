<script>
	jQuery(document).ready(function(){
		jQuery("#checkForm").ajaxForm({
			success:function( ret )
			{
				var real_ret = $.trim( ret );
				var server_no = $('#checkForm').find('[id=server_no]').val();

				if ( real_ret == 'SUCCESS' )
				{
					$('#server_no_' + server_no).html('<span class="success">Login Success!</span>');
				}
				else
				{
					$('#server_no_' + server_no).html('<span class="alert">Login Failed!</span>');
				}

				show_loading(false);
			}
		});
	});


	function checkLogin( no )
	{
		show_loading(true);
		$("#checkForm").find("[id=server_no]").val( no );
		$("#checkForm").submit();
	}
</script>

<form id = 'checkForm' name = 'checkForm' method = post action = './src/ajax.php'>
<input type = 'hidden' id = 'action' name = 'action' value = 'CHECK_SSL_LOGIN'>	
<input type = 'hidden' id = 'server_no' name = 'server_no' value = ''>
</form>

<table class = 'admin-table' width = '100%' cellspacing = 0 cellpadding = 5>
	<thead>
		<th>No</th>
		<th>SSH Server IP</th>
		<th>Geo code</th>
		<th>Geo zone</th>
		<th>Geo city</th>
		<th>ISP</th>
		<th>User ID</th>
		<th>Password</th>
		<th>Check</th>
		<th>Result</th>
	</thead>
	<tbody>
	<?php
		$sql = "select * from tblserverlist";	
		$query = db_query( $sql, $link );
		$counter = mysql_num_rows($query);

		$totalRecord	= $counter;
		$recordPerPage	= ADMIN_RECORD_PER_PAGE;
		$pagePerBlock	= RECORD_PER_BLOCK;

		$currentPage	= ( isset ( $_GET['pageno'] ) ) ? $_GET['pageno'] : 1;
		$startno		= ( ( $currentPage * $recordPerPage ) - $recordPerPage );
		$start			= ( ($currentPage * $recordPerPage ) - $recordPerPage ) + 1;
		$start			= $totalRecord - ( ( $currentPage - 1 ) * $recordPerPage );

		$sql  = 'select * from tblserverlist order by no desc';
		$sql .= " limit {$startno}, {$recordPerPage}";
		$query = db_query( $sql, $link );
		
		while ( $row = mysql_fetch_assoc( $query ) )
		{
			echo '
			<tr>
				<td align=center>' . $start . '</td>
				<td align=center>' . $row['server_ip'] . '</td>
				<td align=center>' . $row['server_geocode'] . '</td>
				<td align=center>' . $row['server_geozone'] . '</td>
				<td align=center>' . $row['server_geocity'] . '</td>
				<td align=center>' . $row['server_isp'] . '</td>
				<td align=center>' . $row['server_user'] . '</td>
				<td align=center>' . $row['server_passwd'] . '</td>
				<td align=center><input type = "button" value = "Check" onclick = "javascript:checkLogin( ' . $row['no'] . ' )"></td>
				<td align=center id = "server_no_' . $row['no'] . '"></td>
			</tr>
			';

			$start --;
		}
	?>
	</tbody>
</table>

<form id = 'searchForm' name = 'searchForm' method = post action = ''>
</form>

<table cellpadding=10 cellspacing=0 border=0 width=100%>
	<tr>
		<td>
			<table cellpadding=10 cellspacing=0 border=0 align=center>
				<tr>
					<td align=center class='num_paging'>
						<?php
							echo Paging_post($totalRecord,$recordPerPage,$pagePerBlock,$currentPage, 'searchForm', '../images' );
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
