<?php $this->load->view('admin/_meta');?>
</head>
<body>
<div class="je-p20">

        <!--<form id="itemcheac">-->
            <div class="je-form-item">
                <div class="je-w33 je-dib">
                    <label class="je-label je-f14">角色名称</label>
                    <div class="je-inputbox">
                        <input type="text" name="title" autocomplete="off" placeholder="请输入角色名" class="je-input" id="username" value="<?=$role['role_name']?>">
                    </div>
                </div>
            </div>

            <div class="je-form-item je-f14">
                <label class="je-label je-f14">选择上级权限</label>
                <select class="myselect" id="role_pid">
                    <?php foreach($role_array as $k => $v){ ?>
                        <option value="<?=$v['role_id']?>"><?=$v['role_name']?></option>
                    <?php }?>
                </select>
            </div>

	        <div class="je-form-item je-f14 switch">
                <label id="label_status" class="je-label je-f14">角色状态</label>
                <input type="checkbox" name="checkboxswitch" jename="switch" checked jetext="有效,无效" id="status">
                <input type="hidden" id="hidden" value="1">
            </div>
    
            <div class="je-form-item je-f14">
                <label class="je-label je-f14"></label>
                <button class="je-btn" id="btnIframe">修改</button>
            </div>
	

</div>

<script type="text/javascript">
    
jeui.use(["jquery","jeBox","jeCheck","jeSelect"],function () {
    
    $(".checkbox").jeCheck();
    $(".radio").jeCheck({jename:"radio"});
    $(".switch").jeCheck({jename:"switch"});
    $(".myselect").jeSelect({
        sosList: false
    });
    index = parent.jeBox.frameIndex(window.name);
    // $('#btnIframe').click(function(){
    //     parent.jeBox.close(index);
    // });
 });

$("option[value='<?=$role['role_pid']?>']").attr('selected', 'selected');

$("#status").on('click', function(){
	if($("#label_status+ins").hasClass('on')){
		$("#hidden").val('0');
	}else{
		$("#hidden").val('1');
	}
});

$("#btnIframe").on('click', function(){

	username = $('#username').val();
	if(username == ''){
		jeBox.msg('缺少参数', {icon: 3});
		return;
	}

	data = {
        'role_id' : <?=$role['role_id']?>,
        'role_name':username,
        'role_pid': $('#role_pid').val(),
        'status':$('#hidden').val()
	}

	$.post('<?=site_url('admin/system/mod_role')?>', data, function(res){
			if(res.code == 200){
				jeBox.msg('修改成功', {icon:7});
				setTimeout("window.parent.location.reload();parent.jeBox.close(index)", 2000);
			}else{
				jeBox.msg('修改失败', {icon:6});
			}
	}, "JSON");
});

</script>

</body>
</html>