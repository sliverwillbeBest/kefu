<?php $this->load->view('admin/_meta');?>
</head>
<body>
<div class="je-p20">

        <!--<form id="itemcheac">-->
            <div class="je-form-item">
                <div class="je-w33 je-dib">
                    <label class="je-label je-f14">管理员账号</label>
                    <div class="je-inputbox">
                        <input type="text" name="title" autocomplete="off" placeholder="请输入账号" class="je-input" id="username">
                    </div>
                </div>
            </div>
            <div class="je-form-item">
                <div class="je-w33 je-dib">
                    <label class="je-label je-f14">管理员密码</label>
                    <div class="je-inputbox">
                        <input type="password" name="title" autocomplete="off" placeholder="请输入密码" class="je-input" id="password">
                    </div>
                </div>
            </div>
            <div class="je-form-item">
                <div class="je-w33 je-dib">
                    <label class="je-label je-f14">真实姓名</label>
                    <div class="je-inputbox">
                        <input type="text" name="title" autocomplete="off" placeholder="请输入姓名" class="je-input" id="real_name">
                    </div>
                </div>
            </div>

            <div class="je-form-item je-f14">
                <label class="je-label je-f14">选择角色</label>
                <select class="myselect" id="role">
                    <option value="1">超级管理员</option>
                    <option value="2">客服经理</option>
                    <option value="3">客服专员</option>
                    <option value="4">唤回组客服</option>
                </select>
            </div>

	        <div class="je-form-item je-f14 switch">
                <label id="label_status" class="je-label je-f14">管理员状态</label>
                <input type="checkbox" name="checkboxswitch" jename="switch" checked jetext="有效,无效" id="status">
                <input type="hidden" id="hidden" value="1">
            </div>
    
            <div class="je-form-item je-f14">
                <label class="je-label je-f14"></label>
                <button class="je-btn" id="btnIframe">注册</button>
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

$("#status").on('click', function(){
	if($("#label_status+ins").hasClass('on')){
		$("#hidden").val('0');
	}else{
		$("#hidden").val('1');
	}
});

$("#btnIframe").on('click', function(){

	username = $('#username').val();
	password = $('#password').val();
	real_name = $('#real_name').val();
	if(username == '' || password == '' || real_name == ''){
		jeBox.msg('缺少参数', {icon: 3});
		return;
	}

	data = {
		'username':username,
		'password':password,
		'real_name':real_name,
		'role':$('#role').val(),
		'status':$('#hidden').val()
	}
	$.post('<?=site_url('admin/system/add_user')?>', data, function(res){
			if(res.code == 200){
				jeBox.msg('注册成功', {icon:7});
				
				setTimeout("window.parent.location.reload();parent.jeBox.close(index);", 2000); 	
			}else{
				jeBox.msg('注册失败', {icon:6});
			}
	}, "JSON");
});

</script>

</body>
</html>
