<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends Admin_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function dump(){
		//select($db, $table, $where=array(), $field='*', $limit=array(), $order_by='')
		$field = 'id, username, mobile, login_time, real_name, token';
		$res = $this->common_model->select($this->master_db, 'wm_user_users', array(), $field);
		$header = array('用户id'=>'string', '用户名'=>'string', '手机号'=>'string', '登录时间'=>'datetime', '真实姓名'=>'string', '令牌'=>'string');
		$this->dump_excel('用户数据导出.xlsx', $header, $res);	
	}


}