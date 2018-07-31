<?php $this->load->view('admin/_meta');?>
</head>
<body>
<div>欢迎您, 本次登录ip为 <?=$this->input->ip_address();?></div>
    <script type="text/javascript">

        function aa(){
            jeBox.msg('登录成功', {icon: 7});
        }

    </script>
</body>
</html>
