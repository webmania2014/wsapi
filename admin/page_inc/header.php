<?php
	$modulePath = '../modules';
	require_once ( $modulePath . '/module_index.php' );
	checkLogin();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>SSH Administrator</title>
		<link href="../css/style.css" rel="stylesheet" type="text/css">
		<script type = 'text/javascript' src = '../js/jquery-latest.js'></script>
		<script type = 'text/javascript' src = '../js/jquery.Ajax.js'></script>
		<script type = 'text/javascript' src = '../js/init.js'></script>
	</head>
	<body>
		<div id="sLoading">
			<img src="../images/loading1.gif">
		</div>

		<script>
			jQuery(document).ready(function(){
				jQuery("#logoutForm").ajaxForm({
					success:function( ret )
					{
						if ( $.trim( ret ) == 'SUCCESS' )
						{
							document.location.href = 'login.php';
						}
					}
				});
			});

			function logout()
			{
				jQuery("#logoutForm").submit();
			}

			function changeProfile()
			{
				jQuery("#changeForm").submit();
			}
		</script>
		<table width = 98% id = 'header-table'>
			<tr>
				<td>
					<form id = 'changeForm' method = 'post' action = 'index.php?menu=change_profile'>
					<input type = 'hidden' id = 'admin_no' name = 'admin_no' value = '<?= $_SESSION['OMS_COM_SESSION']['no'] ?>'>
					</form>

					<form id = 'logoutForm' method = 'post' action = './src/ajax.php'>
					<input type = 'hidden' id = 'action' name = 'action' value = 'LOGOUT'>
					<table width = 100%>
						<tr>
							<td><img src = '../images/logo.png'></td>
							<td align=right>
								<input class = 'normal-btn' type = 'button' value = 'Logout' onclick = 'javascript:logout();'>
							</td>
						</tr>
					</table>
					</form>
				</td>
			</tr>
			<tr>
				<td height = '37px' background = '../images/bar_bg.gif'></td>
			</tr>
		</table>