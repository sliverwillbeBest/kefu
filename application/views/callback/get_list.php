<?php $this->load->view('admin/_meta');?>
</head>
<body>
<div class="je-p20">
    <blockquote class="je-quote je-f16">
        未投资用户列表
        <!-- <button style="float: right;" class="je-btn" id="addart"><i class="je-icon je-f20">&#xe66e;</i> 添加管理员</button> -->
    </blockquote>
    <blockquote class="je-quote je-f16">年月日选择
        <input type="text" class="jeinput" id="test03" placeholder="YYYY-MM-DD">
    </blockquote>
    <div id="testtwo" class="je-f14"></div>
</div>  

<script type="text/javascript">
jeui.use(["jquery","jeTable","jeBox","jeCheck"],function () {

     $("#addart").on("click",function(){
            jeBox.open({
                title: "添加管理员",
                type: 'iframe',
                boxSize: ['70%', '90%'],
                maxBtn: true,
                scrollbar: false,
                content: '<?=site_url('admin/system/add_user')?>'
            });
        });

    $("#testtwo").jeTable({
        //width: $(window).width(),
        height:"auto",
        datas:{
            //url:"http://cm.jtccs.com/testitem.php",
            url:"<?=site_url('callback/noninvestment/get_list')?>",
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
            {name:'用户id', field:'id' ,width:parseInt($('.je-f14').width()/3).toString(), align:'center' },
            {name:'手机号',field:'mobile',width:parseInt($('.je-f14').width()/3).toString(), align:'center'},
        ],

    })
   
});
    
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
   
   jeDate("#test03",{
        //onClose:false,
        donefun:function(obj){
            console.log(obj);
        },
        format: "YYYY-MM-DD"
    });
</script>
</body>
</html>
