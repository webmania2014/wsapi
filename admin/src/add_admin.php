<script>
	jQuery(document).ready(function(){
		jQuery("#addForm").ajaxForm({
			success:function( ret )
			{
				var real_ret = $.trim( ret );

				if ( real_ret == 'SUCCESS' )
				{
					document.location.href = 'index.php?menu=admin';
				}
				else if ( real_ret == 'EXIST' )
				{
					alert( 'Already exist Administrator ID! Please input again' );
					return;
				}
				else
				{
					
				}
			}
		});
	});

	function addAdmin()
	{
		if ( $.trim( $("#admin_id").val() ) == '' )
		{
			alert( 'Please input adminstrator ID.' );
			return;
		}

		if ( $.trim( $("#admin_name").val() ) == '' )
		{
			alert( 'Please input adminstrator name.' );
			return;
		}

		if ( $.trim( $("#admin_passwd").val() ) == '' || $.trim( $("#confirm_passwd").val() ) == '' )
		{
			alert( 'Please input password.' );
			return;
		}

		if ( $("#admin_passwd").val() != $("#confirm_passwd").val() )
		{
			alert( 'Please input confirm password.' );
			return;
		}

		$("#addForm").submit();
	}
</script>

<form id = 'addForm' name = 'addForm' method = post action = './src/ajax.php'> 
<input type = 'hidden' id = 'action' name = 'action' value = 'SIGNUP'>
<table class = 'detail-table' width = '400px' cellpadding = 0 cellspacing = 0>
	<col width = '30%'></col>
	<col width = '70%'></col>
	<tr>
		<td>Administrator ID</td>
		<td><input type = 'text' id = 'admin_id' name = 'admin_id' style = 'width:100%'></td>
	</tr>
	<tr>
		<td>Administrator Name</td>
		<td><input type = 'text' id = 'admin_name' name = 'admin_name' style = 'width:100%'></td>
	</tr>
	<tr>
		<td>Password</td>
		<td><input type = 'text' id = 'admin_passwd' name = 'admin_passwd' style = 'width:100%'></td>
	</tr>
	<tr>
		<td>Confirm Password</td>
		<td><input type = 'text' id = 'confirm_passwd' name = 'confirm_passwd' style = 'width:100%'></td>
	</tr>
	<tr>
		<tr><td align = center colspan = 2><input type = 'button' value = 'Add' onclick = 'javascript:addAdmin();'></tr>
	</tr>
</table>
</form>