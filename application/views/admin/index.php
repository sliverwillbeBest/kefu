<?php $this->load->view('admin/_meta');?>

<title>JEUI系统后台管理</title>
</head>
<body>
<div jepane="top" class="je-admin-top">
    <div class="je-admin-logo je-tc je-fl je-white" title="JEUI"></div>
    <div class="shrink je-fl je-white je-icon je-f28 je-mr10">&#xe626;</div>
    <div class="je-admin-navs je-fl">
        <ul class="je-ovh je-dib" id="myTabNav">
        <!-- 根据session确定是否显示某一li -->
            <li class='top_menu_li system' onclick="change('system')"><em><img src="<?=base_url('public/jeui/images/setico.png')?>"></em><p>系统管理</p></li>
            <li class='top_menu_li callback' onclick="change('callback')"><em><img src="<?=base_url('public/jeui/images/actico.png')?>"></em><p>唤回管理</p></li>
            <li class='top_menu_li sanbiao' onclick="change('sanbiao')"><em><img src="<?=base_url('public/jeui/images/spico.png')?>"></em><p>散标管理</p></li>
        </ul>
    </div>
    <div class="je-admin-user je-fr">
        <span class="photo je-fl"><img src="<?=base_url('public/jeui/images/photo.png')?>"></span>
        <div class="usertext je-pl8 je-fl je-f14 je-pr30">administrator</div>
        <!--<p>XXX,欢迎您使用平台</p>
        <p class="je-tr"><a href="javascript:void(0);" class="exit">退出账户</a></p>-->
    </div>
</div>
<div jepane="left" class="je-admin-left">
    <ul class="je-admin-menu">
        <li class="level" id="system">
            <h3><em class="ico"></em>系统管理<i></i></h3>
            <ul class="levelnext">
                <li class="left_menu_li system_user"><a href="javascript:;" data-tab="admin_user" data-text="系统用户" data-url="<?=site_url('admin/system/get_user')?>" addtab>系统用户</a></li>
                <li class="left_menu_li system_role"><a href="javascript:;" data-tab="admin_role" data-text="角色管理" data-url="<?=site_url('admin/system/get_role')?>" addtab>角色管理</a></li>
                <li class="left_menu_li system_rule"><a href="javascript:;" data-tab="admin_auth" data-text="权限管理" data-url="<?=site_url('admin/system/get_auth')?>" addtab>权限管理</a></li>
            </ul>
        </li>
        <li class="level" id="callback">
            <h3><em class="ico"></em>唤回管理<i></i></h3>
            <ul class="levelnext">
                <li class="left_menu_li callback_noninvestment"><a href="javascript:;" data-tab="psy" data-text="未投资用户列表" data-url="<?=site_url('callback/noninvestment/get_list')?>" addtab>未投资用户列表</a></li>
                <li class="left_menu_li callback_noninvestment_recharge"><a href="javascript:;" data-tab="log" data-text="未投资用户充值列表" data-url="syslog.html" addtab>未投资用户充值列表</a></li>
            </ul>
        </li>
        <li class="level" id="sanbiao">
            <h3><em class="ico"></em>散标管理<i></i></h3>
            <ul class="levelnext">
                <li class="left_menu_li sanbiao_list"><a href="javascript:;" data-tab="psy" data-text="散标用户列表" data-url="<?=site_url('sanbiao/sanbiao/get_list')?>" addtab>散标用户列表</a></li>
            </ul>
        </li>    
    </ul>

</div>
<div jepane="center" class="je-admin-center" tabpane>

</div>
<div jepane="right" class="je-admin-right">right</div>
<div jepane="bottom" class="je-admin-bottom"><p>2017 © jayui.com MIT license</p></div>

<script type="text/javascript">

    $('.level').hide();
    $('.top_menu_li').hide();

    //顶部权限
    sess_top_menu = "<?=$_SESSION['auth_top_menu']?>";
    auth_top_menu = sess_top_menu.split(',');
    for(key in auth_top_menu){
        $('.top_menu_li.'+auth_top_menu[key]).show();
    }

    //左边栏权限
    sess_left_menu = "<?=$_SESSION['auth_left_menu']?>";
    auth_left_menu = sess_left_menu.split(',');
    function change(channel){
        $('.level').hide();
        $('#'+channel).show();
        $('.left_menu_li').hide();
        for(key in auth_left_menu){
            $('.left_menu_li.'+auth_left_menu[key]).show();
        }
    }

    jeui.use(["jquery","jeBox","jeLayout","jeTabPane","jeAccordion"],function () {
        //Layout面板布局
        $("body").jeLayout();
        $("#myTabNav").find("li").on("click",function () {
            $(this).addClass('curr').siblings().removeClass('curr');
        });
        //折叠菜单
        $(".je-admin-menu").jeAccordion({
            accIndex: 0,
            titCell:"h3",
            conCell:"ul",
            multiple:false,
            success:function (titelem, conelem) {
                //给菜单绑定事件
                conelem.children().on("click",function(){
                    conelem.children().removeClass("current");
                    $(this).addClass("current");
                });
            }
        });
        //addtabs
        $("[tabpane]").jeTabPane({
            firstItem:{                              //默认首页
                tab: "main",
                text: "欢迎页",
                url: "<?=site_url('welcome/main')?>",
                closable:false
            }
        });
    });
    

</script>

</body>
</html>
