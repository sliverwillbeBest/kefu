<?php $this->load->view('admin/_meta');?>
</head>
<body>
<div class="je-p20">

        <!--<form id="itemcheac">-->
            <div class="je-form-item">
                <div class="je-w33 je-dib">
                    <label class="je-label je-f14">权限名称</label>
                    <div class="je-inputbox">
                        <input type="text" name="title" autocomplete="off" placeholder="请输入权限名" class="je-input" id="auth_name">
                    </div>
                </div>
            </div>
            <div class="je-form-item">
                <div class="je-w33 je-dib">
                    <label class="je-label je-f14">权限函数名</label>
                    <div class="je-inputbox">
                        <input type="text" name="title" autocomplete="off" placeholder="请输入函数名" class="je-input" id="auth_func">
                    </div>
                </div>
            </div>
	        <div class="je-form-item je-f14 switch">
                <label id="label_status" class="je-label je-f14">权限状态</label>
                <input type="checkbox" name="checkboxswitch" jename="switch" checked jetext="有效,无效" id="status">
                <input type="hidden" id="hidden" value="1">
            </div>
    
            <div class="je-form-item je-f14">
                <label class="je-label je-f14"></label>
                <button class="je-btn" id="btnIframe">添加</button>
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

	auth_name = $('#auth_name').val();
	auth_func = $('#auth_func').val();
	if(auth_name == '' || auth_func == ''){
		jeBox.msg('缺少参数', {icon: 3});
		return;
	}

	data = {
		'auth_name':auth_name,
		'auth_func':auth_func,
		'status':$('#hidden').val()
	}
	$.post('<?=site_url('admin/system/add_auth')?>', data, function(res){
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
