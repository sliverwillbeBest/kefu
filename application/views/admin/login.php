<?php $this->load->view('admin/_meta');?>
<title>登录</title>
</head>
<body class="loginbox">
    <div class="je-login"></div>
    <div class="je-logincon">
        <div class="logo"></div>
        <p class="logtext">欢迎登录后台管理系统</p>
        <p class="je-pb10"><input class="userinp" id="username" type="text" name="username" placeholder="请输入用户名"></p>
        <p class="je-pb10"><input class="userinp" id="password" type="password" name="password" placeholder="请输入密码"></p>
        <p class="je-pt10"><input class="userbtn" type="button" value="确 认 登 录" onclick="gosys()"></p>
    </div>
<script type="text/javascript">

    function gosys(){
        var data = {'username': $('#username').val(), 'password': $('#password').val()};
        $.post("<?=site_url('admin/login/is_login')?>", data, function(res){
            switch(res.code){
                case 200:
                    jeBox.msg('登录成功', {icon: 7});
		    setTimeout("window.location.href ='<?=site_url("admin/home")?>'", 2000); 	
                    break;
                case 500:
                    jeBox.msg('用户不存在', {icon: 3});
                    break;
                case 501:
                    jeBox.msg('用户名或密码错误', {icon: 6}); 
                    break;
            }
        }, "JSON");
    }

</script>
</body>
</html>
