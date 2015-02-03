<?php
	$modulePath = './modules';
	require_once( $modulePath . '/module_index.php' );
?>
<!DOCTYPE html>
<html>
	<head>
		<title>SSH List</title>
		<link href="./css/style.css" rel="stylesheet" type="text/css">
		<link href="./css/bootstrap.css" rel="stylesheet" type="text/css">
		<script type = 'text/javascript' src = './js/jquery-1.10.2.js'></script>
		<script type = 'text/javascript' src = './js/jquery.Ajax.js'></script>
		<script type = 'text/javascript' src = './js/bootstrap.js'></script>
		<script type = 'text/javascript' src = './js/init.js'></script>
		<link href="./js/jqueryDialog/jquery.dialog.css" rel="stylesheet" type="text/css">
		<script type = 'text/javascript' src = './js/jqueryDialog/jquery.dialog.js'></script>
	</head>
	<body>
		<div id = 'front-wrapper'>
			<?php
				$search_isp			= isset( $_POST['search_isp'] ) ? trim( $_POST['search_isp'] ) : '';
				$search_ip			= isset( $_POST['search_ip'] ) ? trim( $_POST['search_ip'] ) : '';
				$search_geocode		= isset( $_POST['search_geocode'] ) ? trim( $_POST['search_geocode'] ) : '';
				$search_geozone		= isset( $_POST['search_geozone'] ) ? trim( $_POST['search_geozone'] ) : '';
				$search_geocity		= isset( $_POST['search_geocity'] ) ? trim( $_POST['search_geocity'] ) : '';
				$search_zip			= isset( $_POST['search_zip'] ) ? trim( $_POST['search_zip'] ) : '';

				$where = ' WHERE 1';
				if ( $search_isp != '' )
					$where .= ' AND server_isp like \'%' . $search_isp . '%\'';

				if ( $search_ip != '' )
					$where .= ' AND server_ip like \'%' . $search_ip . '%\'';

				if ( $search_geocode != '' )
					$where .= ' AND server_geocode like \'%' . $search_geocode . '%\'';
				
				if ( $search_geozone != '' )
					$where .= ' AND server_geozone like \'%' . $search_geozone . '%\'';

				if ( $search_geocity != '' )
					$where .= ' AND server_geocity like \'%' . $search_geocity . '%\'';

				if ( $search_zip != '' )
					$where .= ' AND server_zip like \'%' . $search_zip . '%\'';

			?>
			<section id = 'home-title'>
				NoLogin - SSH Tunnel
			</section>
			<form id = 'searchForm' name = 'searchForm' method = post action = ''>
				<section id = 'search'>
					<input type="text" class="search-item" id="search_isp" name="search_isp" placeholder="isp eg(comcast, verizon)" value="<?php echo $search_isp ?>">
					<input type="text" class="search-item" id="search_ip" name="search_ip" placeholder="ip eg(123.43.23.22)" value="<?php echo $search_ip ?>">
					<input type="text" class="search-item" id="search_geocode" name="search_geocode" placeholder="geo code" value="<?php echo $search_geocode ?>">
					<input type="text" class="search-item" id="search_geozone" name="search_geozone" placeholder="geo zone" value="<?php echo $search_geozone ?>">
					<input type="text" class="search-item" id="search_geocity" name="search_geocity" placeholder="geo city" value="<?php echo $search_geocity ?>">
					<input type="text" class="search-item" id="search_zip" name="search_zip" placeholder="zip code" value="<?php echo $search_zip ?>">
				</section>
				<section id = 'search-button'><button type = 'submit' class = 'blue-btn'>Search</button></section>
			</form>
			<table class="table table-striped" id = 'server-list-table'>
				<thead>
					<tr>
						<th>IP</th>
						<th>Geo code</th>
						<th>Geo zone</th>
						<th>Geo city</th>
						<th>ISP</th>
						<th>Zip code</th>
						<th>View</th>
						<th>Used</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$sql  = 'SELECT * FROM tblserverlist';
						$sql .= $where;
						$query = db_query( $sql, $link );
						$counter = mysql_num_rows( $query );

						$totalRecord	= $counter;
						$recordPerPage	= ADMIN_RECORD_PER_PAGE;
						$pagePerBlock	= RECORD_PER_BLOCK;
						$currentPage	= ( isset ( $_GET['pageno'] ) ) ? $_GET['pageno'] : 1;
						$startno		= ( ($currentPage * $recordPerPage ) - $recordPerPage );
						$start			= ( ($currentPage * $recordPerPage ) - $recordPerPage ) + 1;

						$sql  = 'SELECT * FROM tblserverlist';
						$sql .= $where;
						$sql .= ' ORDER BY no ASC';
						$sql .= " LIMIT {$startno}, {$recordPerPage}";
						$query = db_query( $sql, $link );
						while ( $row = mysql_fetch_assoc( $query ) )
						{
							$img = '';
							if ( $row['server_used'] == 1 )
								$img = '<img src = "./images/used.png">';
							echo '
								<tr>
									<td align = center>' . $row['server_ip'] . '</td>
									<td align = center>' . $row['server_geocode'] . '</td>
									<td align = center>' . $row['server_geozone'] . '</td>
									<td align = center>' . $row['server_geocity'] . '</td>
									<td align = center>' . $row['server_isp'] . '</td>
									<td align = center>' . $row['server_zip'] . '</td>
									<td align = center>
										<button class = "blue-btn" onclick="javascript:showDialog(' . $row['no'] . ')">View</button>
									</td>
									<td align = center id = "used_' . $row['no'] . '">' . $img . '</td>
								</tr>
							';
						}
					?>
				</tbody>
			</table>
			<form id = 'searchForm' name = 'searchForm' method = post action = ''>
			</form>
			<br>
			<table cellpadding=10 cellspacing=0 border=0 width=100% style = 'background:white;'>
				<tr>
					<td align=center>
						<ul class = 'pagination'>
							<?php
								$isPrev = false;
								$currentBlock		= ceil( $currentPage / RECORD_PER_BLOCK );
								$totalNumOfPage		= ceil( $totalRecord / ADMIN_RECORD_PER_PAGE );
								$totalNumOfBlock	= ceil( $totalNumOfPage / RECORD_PER_BLOCK );
								$startPage = ( $currentBlock - 1 ) * $pagePerBlock + 1;			// 1page

								if($currentBlock > 1)
									$isPrev = true;
								
								//get prev block's first page.
								$prev_block_page = $startPage - RECORD_PER_BLOCK; // 11page
								$action = ( !$isPrev ) ? '#' : "index.php?pageno=" . $prev_block_page;
							?>
							<li><a href="javascript:post_form('<?php echo $action; ?>', 'searchForm')">&laquo;</a></li>
							<?php
								$totalNumOfPage = ceil( $totalRecord / ADMIN_RECORD_PER_PAGE );

								$endPage = $startPage + RECORD_PER_BLOCK -1; // 10page
								if($endPage > $totalNumOfPage) 
									$endPage = $totalNumOfPage;

								for ( $i = $startPage; $i <= $endPage; $i ++ )
								{
									$action = "index.php?pageno=".$i;

									if ( $currentPage == $i )
										echo '<li class="active"><a href = "javascript:post_form(\'' . $action. '\',\'searchForm\');">' . $i . '</a></li>';
									else
										echo '<li><a href = "javascript:post_form(\'' . $action. '\',\'searchForm\');">' . $i . '</a></li>';
								}
							?>
							<?php
								$isNext = false;
								if( $currentBlock < $totalNumOfBlock )
									$isNext = true;

								//get prev block's first page.
								$next_block_page = $startPage + RECORD_PER_BLOCK;
								$action = ( !$isNext ) ? '#' : 'index.php?pageno=' . $next_block_page;
							?>
							<li><a href="javascript:post_form('<?php echo $action ?>', 'searchForm')">&raquo;</a></li>
						</ul>
					</td>
				</tr>
			</table>
		</div>
		<script>
			function showDialog( no )
			{
				JqueryDialog.Open('View', 'popup/server_detail.php?no=' + no, 300, 200);
				$('#used_' + no).html('<img src = "./images/used.png">');
			}
		</script>
	</body>
</html>