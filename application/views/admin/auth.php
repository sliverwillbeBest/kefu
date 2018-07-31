<?php $this->load->view('admin/_meta');?>
</head>
<body>
<div class="je-p20">
    <blockquote class="je-quote je-f16">
        权限管理
        <button style="float: right;" class="je-btn" id="addart"><i class="je-icon je-f20">&#xe66e;</i> 添加权限</button>
    </blockquote>
    <div id="testtwo" class="je-f14"></div>
</div>  

<script type="text/javascript">
jeui.use(["jquery","jeTable","jeBox","jeCheck"],function () {

     $("#addart").on("click",function(){
            jeBox.open({
                title: "添加权限",
                type: 'iframe',
                boxSize: ['70%', '90%'],
                maxBtn: true,
                scrollbar: false,
                content: '<?=site_url('admin/system/add_auth')?>'
            });
        });

    $("#testtwo").jeTable({
        //width: $(window).width(),
        height:"auto",
        datas:{
            //url:"http://cm.jtccs.com/testitem.php",
            url:"<?=site_url('admin/system/get_auth')?>",
            data:{},
            type:"POST",
            async:false,
            dataType:"json",
            field:"data"
        },
        pageField:{
            pageIndex:{field:"size",num:1},
            pageSize:{field:"pagesize",num:15},
            ellipsis:true,
            dataCount:"total_count",
            pageCount:"total_page" 
        },
        columnSort:[],
        columns:[
            //{name:'ID', field:'id', width:"150", align: 'center',isShow:true, renderer: ""},
            {name:'权限名', field:'auth_name' ,width:parseInt($('.je-f14').width()/5).toString(), align:'center' },
            {name:'权限函数名',field:'auth_func',width:parseInt($('.je-f14').width()/5).toString(), align:'center'},
            {name:'状态', field:'status',width:parseInt($('.je-f14').width()/5).toString(), align:'center', renderer: function(obj, rowidx){
                return obj.status ? '可用': '停用';
            }},
            {name:'操作', field:'id' ,width:parseInt($('.je-f14').width()/5).toString(), align:'center',renderer: function(obj,rowidx){
                return "<a href='javascript:;' class='je-blue' onclick=mod('"+obj.auth_id+"')>修改</a>"+"&nbsp;&nbsp;"+"<a href='javascript:;' class='je-blue' onclick=del('"+obj.auth_id+"')>删除</a>";
            }}
        ],

    })
   
});
    
    function del(obj){
        jeBox.msg('确认删除吗？', {
        time: 0 ,
        button: [ 
            {
                name: '确认',
                callback:function(index){
                    jeBox.close(index);
                    $.post('<?=site_url('admin/system/del_auth')?>', {'id':obj}, function(res){
                        if(res.code == 200){
                            jeBox.msg('删除成功', {icon: 7});
                            setTimeout("window.location.reload();parent.jeBox.close(index)", 2000);
                        }
                    }, 'json');
                }
            },{
                name: '取消'
            },
            ],
        });
    }


    function mod(obj){
        jeBox.open({
                title: "修改权限",
                type: 'iframe',
                boxSize: ['70%', '90%'],
                maxBtn: true,
                scrollbar: false,
                content: '<?=site_url('admin/system/mod_auth')?>'+'?id=' + obj
            });
    }

    function formatdate(str,attr) {
        if (isNaN(str)){
            return str;
        }else {
            var fmt = attr || "YYYY-MM-DD", dateNum = parseInt((str.toString()).substring(0,10)),
                times = new Date(dateNum * 1000);
            var o = {
                "M+": times.getMonth() + 1, // 月
                "D+": times.getDate(), // 日
                "h+": times.getHours(), // 时
                "m+": times.getMinutes(), // 分
                "s+": times.getSeconds(), // 秒
                "q+": Math.floor((times.getMonth() + 3) / 3), // 季度
                "ms": times.getMilliseconds() // 毫秒
            };
            if (/(Y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (times.getFullYear() + "").substr(4 - RegExp.$1.length));
            for (var k in o) {
                if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
            }
            return fmt;
        }
    }
   
</script>
</body>
</html>
