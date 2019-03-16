<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-修改角色
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-17
 * @version 2019-03-16
 */
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>修改角色 / <?=$this->Setting_model->get('systemName');?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'修改角色','path'=>[['角色列表',base_url('admin/role/list')],['修改角色','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">

		<input type="hidden" id="roleID" value="<?=$roleID;?>">

		<div class="panel panel-default">
			<div class="panel-heading">修改角色</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="name">角色名称</label>
					<input class="form-control" id="name" onkeyup='if(event.keyCode==13)$("#remark").focus();' value="<?=$info['name']; ?>">
					<p class="help-block">请输入<font color="green">1</font>-<font color="green">20</font>字的角色名称</p>
				</div>
				<br>
				<div class="form-group">
					<label for="remark">备注</label>
					<textarea class="form-control" id="remark"><?=$info['remark']; ?></textarea>
					<p class="help-block">选填</p>
				</div>

				<hr>

				<div class="form-group">
					<label>创建时间</label>
					<input class="form-control" value="<?=$info['create_time']; ?>" disabled>
				</div>
				<br>
				<div class="form-group">
					<label>最后修改时间</label>
					<input class="form-control" value="<?=$info['update_time']; ?>" disabled>
				</div>

				<hr>

				<button class="btn btn-success btn-block" onclick='edit()'>确 认 编 辑 角 色 &gt;</button>
			</div>
		</div>
	</section>
	<!-- ./页面主要内容 -->
</div>
<!-- ./页面内容 -->

<?php $this->load->view('include/footer'); ?>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>

<script>
function edit(){
	lockScreen();
	name=$("#name").val();
	remark=$("#remark").val();
	roleID=$("#roleID").val();

	if(name==""){
		unlockScreen();
		$("#tips").html("请输入角色名称！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(name.length<1 || name.length>20){
		unlockScreen();
		$("#tips").html("请输入 1-20字 的角色名称！");
		$("#tipsModal").modal('show');
		return false;
	}

	$.ajax({
		url:"<?=site_url('admin/role/toEdit'); ?>",
		type:"post",
		data:{<?=$this->ajax->showAjaxToken(); ?>,"name":name,"remark":remark,'roleID':roleID},
		dataType:'json',
		error:function(e){
			console.log(JSON.stringify(e));
			unlockScreen();
			$("#tips").html("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
			$("#tipsModal").modal('show');
			return false;
		},
		success:function(ret){
			unlockScreen();
			
			if(ret.code=="200"){
				alert("修改成功！");
				history.go(-1);
				return true;
			}else if(ret.message=="updateFailed"){
				$("#tips").html("修改失败！！！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.code=="403"){
				$("#tips").html("Token无效！<hr>Tips:请勿在提交前打开另一页面哦~");
				$("#tipsModal").modal('show');
				return false;
			}else{
				$("#tips").html("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
				$("#tipsModal").modal('show');
				return false;
			}
		}
	});
}
</script>

</body>
</html>
