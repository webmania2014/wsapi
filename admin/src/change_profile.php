<?php
	$user_no = $_POST['admin_no'];
	$user_info = getData( 'tbladmin', ' where no = \'' . db_sql( $user_no ) . '\'', '*' );
?>
<script>
	jQuery(document).ready(function(){
		jQuery("#profileForm").ajaxForm({
			success:function( ret )
			{
				var real_ret = $.trim( ret );

				if ( real_ret == 'SUCCESS' )
				{
					document.location.href = document.location.href;
				}
				else if ( real_ret == 'NOT_MATCH' )
				{
					alert( '密码是不对' );
					return;
				}
			}
		});
	});

	function changePassword()
	{
		if ( $.trim( $("#origin_passwd").val() ) == '' || $.trim( $("#new_passwd").val() ) == '' || $.trim( $("#confirm_passwd").val() ) == '' )
		{
			alert( '请录入密码' );
			return;
		}

		if ( $("#new_passwd").val() != $("#confirm_passwd").val() )
		{
			alert( '密码确认不一样新的密码' );
			return;
		}

		jQuery("#profileForm").submit();
	}
</script>

<form id = 'profileForm' method = post action = './src/ajax.php'>
<input type = 'hidden' id = 'action' name = 'action' value = 'CHANGE_PASSWD'>
<input type = 'hidden' id = 'admin_no' name = 'admin_no' value = '<?= $_SESSION['OMS_COM_SESSION']['no'] ?>'>
<table class = 'detail-table' width = '400px' cellpadding = 0 cellspacing = 0>
	<col width = '30%'></col>
	<col width = '70%'></col>
	<tr>
		<td>用可ID</td>
		<td><?= $user_info['admin_id'] ?></td>
	</tr>
	<tr>
		<td>管理人名字</td>
		<td><?= $user_info['admin_name'] ?></td>
	</tr>
	<tr>
		<td>密码</td>
		<td><input type = 'password' id = 'origin_passwd' name = 'origin_passwd' style = 'width:100%'></td>
	</tr>
	<tr>
		<td>新的密码</td>
		<td><input type = 'password' id = 'new_passwd' name = 'new_passwd' style = 'width:100%'></td>
	</tr>
	<tr>
		<td>密码确认</td>
		<td><input type = 'password' id = 'confirm_passwd' name = 'confirm_passwd' style = 'width:100%'></td>
	</tr>
	<tr>
		<tr><td align = center colspan = 2><input type = 'button' value = '确认' onclick = 'javascript:changePassword();'></tr>
	</tr>
</table>
</form>