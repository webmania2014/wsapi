<?php
	require_once( './page_inc/header.php' );
	$getMenu = ( isset( $_GET['menu'] ) ) ? $_GET['menu'] : '';
?>
<table id = 'main-table' width = '98%' style = 'border-left:1px solid #CAD0D7; border-right:1px solid #CAD0D7; border-bottom:1px solid #CAD0D7;'>
	<tr>
		<td width = '15%' bgcolor = '#e5ecf4' style = 'border-right:1px solid #cad0d7' valign = top>
			<?php require_once( './page_inc/menu.php' ) ?>
		</td>
		<td valign = top>
			<div id = 'contents-wrapper'>
				<div id = 'page-title'>
				<?php 
					$title = ( $getMenu != '' ) ? $arrMenu[ $getMenu ]['title'] : 'User management';
					echo $title;
				?>	
				</div>
				<div width = 100%>
				<?php
					$page = ( $getMenu == '' ) ? './src/admin.php' : './src/' . $getMenu . '.php';
					if ( file_exists( $page ) )
						require_once( $page );
					else
						echo '404 Error!';
				?>
				</div>
			</div>
		</td>
	</tr>
</table>
<?php
	require_once( './page_inc/footer.php' );
?>