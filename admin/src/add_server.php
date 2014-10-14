<script>
	$(document).ready(function(){
		$("#addForm").ajaxForm({
			success:function( ret )
			{
				var real_ret = $.trim( ret );
				var action = $('#addForm').find('[id=action]').val();

				if ( action != 'GET_SVR_INFO' )
				{
					if ( real_ret == 'SUCCESS' )
						confServer();
					else
						alert( real_ret );
				}
				else
				{
					if ( real_ret == 'IP Address not found' )
					{
						alert( real_ret );
						show_loading(false);
					}
					else
					{
						if ( real_ret == 'SUCCESS' )
						{
							show_loading(false);
							document.location.href = 'index.php?menu=mng_db';
						}
						else
						{
							alert( real_ret );
							show_loading(false);
						}
					}
				}
			}
		});
	});

	function addServer()
	{
		if ( $.trim( $("#server_ip").val() ) == '' )
		{
			alert( 'Please input SSH Server IP address!' );
			return;
		}

		if ( $.trim( $("#user_id").val() ) == '' )
		{
			alert( 'Please input user ID!' );
			return;
		}

		if ( $.trim( $("#passwd").val() ) == '' )
		{
			alert( 'Please input user ID!' );
			return;
		}

		if ( $.trim( $("#port").val() ) == '' )
		{
			alert( 'Please input SSH port!' );
			return;
		}

		show_loading(true);
		$("#addForm").submit();
	}

	function confServer()
	{
		show_loading(true);
		$('#addForm').find('[id=action]').val('GET_SVR_INFO');
		$('#addForm').submit();
	}
</script>

<form id = 'addForm' name = 'addForm' method = post action = './src/ajax.php'> 
<input type = 'hidden' id = 'action' name = 'action' value = 'ADD_SERVER'>
<table class = 'detail-table' width = '400px' cellpadding = 0 cellspacing = 0>
	<col width = '30%'></col>
	<col width = '70%'></col>
	<tr>
		<td>SSH Server IP</td>
		<td><input type = 'text' id = 'server_ip' name = 'server_ip' style = 'width:100%'></td>
	</tr>
	<tr>
		<td>User ID</td>
		<td><input type = 'text' id = 'user_id' name = 'user_id' style = 'width:100%'></td>
	</tr>
	<tr>
		<td>Password</td>
		<td><input type = 'text' id = 'passwd' name = 'passwd' style = 'width:100%'></td>
	</tr>
	<tr>
		<td>Port</td>
		<td><input type = 'number' id = 'port' name = 'port' value = '22' style = 'width:100%'></td>
	</tr>
	<tr>
		<tr><td align = center colspan = 2><input type = 'button' value = 'Add' onclick = 'javascript:addServer();'></tr>
	</tr>
</table>
</form>