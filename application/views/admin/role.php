<?php $this->load->view('admin/_meta');?>
</head>
<body>
<div class="je-p20">
    <blockquote class="je-quote je-f16">
        角色管理
        <button style="float: right;" class="je-btn" id="addart"><i class="je-icon je-f20">&#xe66e;</i> 添加角色</button>
    </blockquote>
    <div width="100%" id="testtwo" class="je-f14"></div>
</div>  

<script type="text/javascript">
jeui.use(["jquery","jeTable","jeBox","jeCheck"],function () {

     $("#addart").on("click",function(){
            jeBox.open({
                title: "添加角色",
                type: 'iframe',
                boxSize: ['70%', '90%'],
                maxBtn: true,
                scrollbar: false,
                content: '<?=site_url('admin/system/add_role')?>'
            });
        });

    $("#testtwo").jeTable({
        //width:"100%",
        height:"auto",
        datas:{
            //url:"http://cm.jtccs.com/testitem.php",
            url:"<?=site_url('admin/system/get_role')?>",
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
            //{name:'角色id', field:'role_id' ,width:parseInt($('.je-p20').width()/6).toString(), align:'center' },
            {name:'角色名',field:'role_name',width:parseInt($('.je-p20').width()/5).toString(), align:'center'},
            {name:'上级角色',field:'role_pname',width:parseInt($('.je-p20').width()/5).toString(), align:'center'},
            {name:'状态', field:'status',width:parseInt($('.je-p20').width()/5).toString(), align:'center', renderer: function(obj, rowidx){
                return obj.status ? '可用': '停用';
            }},
            {name:'操作', field:'id' ,width:parseInt($('.je-p20').width()/5).toString(), align:'center',renderer: function(obj,rowidx){
                return "<a href='javascript:;' class='je-blue' onclick=auth('"+obj.role_id+"')>授权</a>"+"&nbsp;&nbsp;"+"<a href='javascript:;' class='je-blue' onclick=mod('"+obj.role_id+"')>修改</a>"+"&nbsp;&nbsp;"+"<a href='javascript:;' class='je-blue' onclick=del('"+obj.role_id+"')>删除</a>";
            }}
        ],
        itemfun:function (elem,data) {
            elem.on("dblclick",function () {
                //var trdata = $.parseJSON($(this).attr("trdata"));
            })
        }
    })
   
});

    function auth(obj){
        jeBox.open({
                title: "授权",
                type: 'iframe',
                boxSize: ['70%', '90%'],
                maxBtn: true,
                scrollbar: false,
                content: '<?=site_url('admin/system/mod_role_auth')?>'+'?id=' + obj
            });
    }
    
    function del(obj){
        jeBox.msg('确认删除吗？', {
        time: 0 ,
        button: [ 
            {
                name: '确认',
                callback:function(index){
                    jeBox.close(index);
                    $.post('<?=site_url('admin/system/del_role')?>', {'id':obj}, function(res){
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
                title: "修改角色",
                type: 'iframe',
                boxSize: ['70%', '90%'],
                maxBtn: true,
                scrollbar: false,
                content: '<?=site_url('admin/system/mod_role')?>'+'?id=' + obj
            });
    }

   
</script>
</body>
</html>
