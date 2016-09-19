<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Delphix Security Server</title>
	<link rel="stylesheet" type="text/css" href="/login2/easyui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="/login2/easyui/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="/login2/easyui/themes/color.css">
	<link rel="stylesheet" type="text/css" href="/login2/easyui/demo/demo.css">
	<script type="text/javascript" src="/login2/jquery-1.6.min.js"></script>
	<script type="text/javascript" src="/login2/easyui/jquery.easyui.min.js"></script>
</head>
<body>
	<h2>Delphix Security Server User Management</h2>
	<table id="dg" title="My Users" class="easyui-datagrid" style="width:700px;height:250px"
			url="get_users.php"
			toolbar="#toolbar" pagination="true"
			rownumbers="true" fitColumns="true" singleSelect="true">
		<thead>
			<tr>
				<th field="username" width="50">User Name</th>
				<!-- <th field="password" type="password" width="50">Password</th> -->
				<th field="role" width="50">Role</th>
				<th field="datacenter" width="50">Datacenter</th>
				<th field="status" width="50">Status</th>
			</tr>
		</thead>
	</table>
	<div id="toolbar">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">New User</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">Edit User</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUser()">Remove User</a>
	</div>
	
	<div id="dlg" class="easyui-dialog" style="width:400px;height:280px;padding:10px 20px"
			closed="true" buttons="#dlg-buttons">
		<div class="ftitle">User Information</div>
		<form id="fm" method="post" novalidate>
			<div class="fitem">
				<label>User Name:</label>
				<input name="username" class="easyui-textbox" required="true">
			</div>
			<div class="fitem">
				<label>Password:</label>
				<input name="password" class="easyui-textbox" type="password" required="true">
			</div>
			<div class="fitem">
				<label>Role:</label>
				<input name="role" class="easyui-combobox"
					data-options="valueField:'role',textField:'role', url: 'rolesList_json.php', method: 'get', multiple:false ">
			</div>
			<div class="fitem">
				<label>datacenter:</label>
				<input name="datacenter[]" class="easyui-combobox" 
					data-options="valueField:'externalname',textField:'externalname', url:'engineList_json.php', method:'get', multiple:true ">
			</div>
			<div class="fitem">
				<label>Status:</label>
				<input name="status" class="easyui-combobox"
					data-options="valueField:'status',textField:'status', url: 'statusList_json.php', method: 'get', multiple:false ">

			</div>
		</form>
	</div>
	<div id="dlg-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUser()" style="width:90px">Save</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
	</div>
	<script type="text/javascript">

		$.fn.combobox.defaults.onLoadSuccess = function(items){
			if (items.length){
				var opts = $(this).combobox('options');
				$(this).combobox('select', items[0][opts.valueField]);
			}
		}


		var url;
		function newUser(){
			$('#dlg').dialog('open').dialog('setTitle','New User');
			$('#fm').form('clear');
			url = 'save_user.php';
		}
		function editUser(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#dlg').dialog('open').dialog('setTitle','Edit User');
				$('#fm').form('load',row);
				url = 'update_user.php?id='+row.id;
			}
		}
		function saveUser(){
			$('#fm').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					var result = eval('('+result+')');
					if (result.errorMsg){
						$.messager.show({
							title: 'Error',
							msg: result.errorMsg
						});
					} else {
						$('#dlg').dialog('close');		// close the dialog
						$('#dg').datagrid('reload');	// reload the user data
					}
				}
			});
		}
		function destroyUser(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to destroy this user?',function(r){
					if (r){
						$.post('destroy_user.php',{id:row.id},function(result){
							if (result.success){
								$('#dg').datagrid('reload');	// reload the user data
							} else {
								$.messager.show({	// show error message
									title: 'Error',
									msg: result.errorMsg
								});
							}
						},'json');
					}
				});
			}
		}
		
	</script>
	<style type="text/css">
		#fm{
			margin:0;
			padding:10px 30px;
		}
		.ftitle{
			font-size:14px;
			font-weight:bold;
			padding:5px 0;
			margin-bottom:10px;
			border-bottom:1px solid #ccc;
		}
		.fitem{
			margin-bottom:5px;
		}
		.fitem label{
			display:inline-block;
			width:80px;
		}
		.fitem input{
			width:160px;
		}
	</style>
</body>
</html>
