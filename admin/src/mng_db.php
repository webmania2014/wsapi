<script>
	jQuery(document).ready(function(){
		jQuery("#delForm").ajaxForm({
			success:function( ret )
			{
				var real_ret = $.trim( ret );

				if ( real_ret == 'SUCCESS' )
				{
					alert('Server delete Successed!');
					document.location.href = document.location.href;
				}
				else
				{
					alert( real_ret );
				}
			}
		});
	});

	function addSSHServer()
	{
		document.location.href = 'index.php?menu=add_server';
	}

	function deleteServer( no )
	{
		if ( confirm( 'Are you sure？' ) )
		{
			$("#delForm").find("[id=server_no]").val( no );
			$("#delForm").submit();
		}
	}
</script>

<form id = 'delForm' name = 'delForm' method = post action = './src/ajax.php'>
<input type = 'hidden' id = 'action' name = 'action' value = 'DEL_SERVER'>	
<input type = 'hidden' id = 'server_no' name = 'server_no' value = ''>
</form>

<div style = 'width:100%; text-align:right;'>
	<input type = 'button' id = 'add_admin' name = 'add_admin' value = 'Add SSH Server' onclick = 'javascript:addSSHServer();'>
</div>
<table class = 'admin-table' width = '100%' cellspacing = 0 cellpadding = 5>
	<thead>
		<th>No</th>
		<th>SSH Server IP</th>
		<th>Geo code</th>
		<th>Geo zone</th>
		<th>Geo city</th>
		<th>ISP</th>
		<th>Zip code</th>
		<th>User ID</th>
		<th>Password</th>
		<th>**</th>
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
				<td align=center>' . $row['server_zip'] . '</td>
				<td align=center>' . $row['server_user'] . '</td>
				<td align=center>' . $row['server_passwd'] . '</td>
				<td align=center><input type = "button" value = "Delete" onclick = "javascript:deleteServer( ' . $row['no'] . ' )"></td>
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
