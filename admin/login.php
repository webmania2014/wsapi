<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>SSH Administrator</title>
		<link href="../css/style.css" rel="stylesheet" type="text/css">
		<script type = 'text/javascript' src = '../js/jquery-latest.js'></script>
		<script type = 'text/javascript' src = '../js/jquery.Ajax.js'></script>
	</head>
	<body bgcolor = '#f7f7f7' style = 'margin:0; padding:0'>
		<script>
			jQuery(document).ready(function(){
				jQuery('#loginForm').ajaxForm({
					success:function( ret )
					{
						var real_ret = $.trim( ret );
						if ( real_ret == 'SUCCESS' )
							document.location.href = 'index.php';
						if ( real_ret == 'NO_ADMIN' )
							alert( 'No registered administrator' );
						else if ( real_ret == 'WRONG_PASSWD' )
							alert( 'Not match admin id and password' );
						else
							alert( real_ret );
					}
				});

				$('#admin_id').keydown(function(e){
					if (e.keyCode == 13)
					{
						DoLogin();
					}
				});

				$('#admin_passwd').keydown(function(e){
					if (e.keyCode == 13)
					{
						DoLogin();
					}
				});
			});

			function DoLogin()
			{
				if ( $.trim( $('#admin_id') .val() ) == '' )
				{
					alert( 'Please input Administrator ID' );
					return;
				}

				if ( $.trim( $('#admin_id') .val() ) == '' )
				{
					alert( 'Please input password' );
					return;
				}
				
				jQuery('#loginForm').submit();
			}
		</script>
		<table width = 100%>
			<tr>
				<td height = '200px'></td>
			</tr>
			<tr>
				<td height = '280px' bgcolor = '#FFFFFF' style = 'border-bottom:1px solid #d6d6d6; border-top:1px solid #d6d6d6;' align = center>
					<form id = 'loginForm' name = 'loginForm' method = post action = './src/ajax.php'>
					<input type = 'hidden' id = 'action' name = 'action' value = 'LOGIN'>
					<table>
						<tr>
							<td>
								<img src = '../images/login_txt_login.gif'>
							</td>
							<td width = '50px'></td>
							<td>
								<p style = 'margin:0; padding-top:8px;'>
									<span class = 'login-title'>ID:</span><span><input type = 'text' class = 'normal-text' id = 'admin_id' name = 'admin_id'></span>
								</p>
								<p style = 'margin-top:5px;'>
									<span class = 'login-title'>Password:</span><span><input type = 'password' class = 'normal-text' id = 'admin_passwd' name = 'admin_passwd'></span>
								</p>
							</td>
							<td>
								<a href = 'javascript:DoLogin();'><img src = '../images/login_bt_login.gif'></a>
							</td>
						</tr>
					</table>
					</form>
				</td>
			</tr>
		</table>
	</body>
</html>
