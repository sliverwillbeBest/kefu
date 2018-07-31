<?php $this->load->view('admin/_meta'); ?>

</head>
<body>
<div class="je-p20">
   
            <div class="je-form-item je-f14 checkbox">
                <label class="je-label je-f14">授权列表</label>
                <?php foreach($rule_auth as $k => $v){ ?>
                    <input type="checkbox" name="checkbox" jename="checkbox" jetext="<?php echo $v['auth_name']; ?>" value="<?php echo $v['auth_id']; ?>" <?php if(in_array($v['auth_id'],$user_auth)){ echo "checked"; } ?>  />
                <?php } ?>
            </div>
    		<!-- <div align="float"><input type="button" value="保存" class="tdBtn" onclick="add_advert();"/></div> -->

    		<div class="je-form-item je-f14">
                <label class="je-label je-f14"></label>
                <button onclick="add_advert();" class="je-btn" id="btnIframe">保存</button>
            </div>
</div>

<script type="text/javascript">
    
jeui.use(["jquery","jeBox","jeCheck","jeSelect"],function () {
    $(".checkbox").jeCheck();
 })

function add_advert(){   
    var arr = new Array();
    $(".on").each(function(i){
        arr[i] = $('.on').eq(i).children('input').val();
    });
    // var num = $(".on").length;
    var auth_rule = arr.join(',');
    role_id = <?=$role_id?>;

    $.post("<?=site_url('admin/system/mod_role_auth')?>",
        {auth_rule:auth_rule,role_id:role_id},
        function(res){
            if (res.code == '200') {
                jeBox.msg('授权成功', {icon: 7});
                index = parent.jeBox.frameIndex(window.name);
                setTimeout("parent.jeBox.close(index);", 2000); 	
            }else{
                jeBox.msg('授权失败', {icon: 6});
            }
        },'json'
    )

}

</script>

</body>
</html>
