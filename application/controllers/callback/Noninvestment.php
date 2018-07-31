<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
	未投资用户控制器
	未投资用户的相关操作都在这里
	@author sliver
 */

class Noninvestment extends Admin_Controller{

	protected $master_user_table;

	public function __construct(){
		parent::__construct();
		$this->check_token();
		$this->master_user_table = $this->config->item('MASTER_USER_TABLE');
	}

	/*
		获取未投资用户列表
	 */

	public function get_list(){
		$this->check_auth(__METHOD__);
		if(!empty($_POST)){
			$page = $this->input->post('size');
			$pagesize = $this->input->post('pagesize');
			$fields = 'id, mobile';
			if(!empty($page) && !empty($pagesize)){
				$limit = array($pagesize, ($page-1)*$pagesize);
			}else{
				$limit = array();
			}
			$order_by = 'id ASC';
			$res = $this->common_model->select($this->master_db, $this->master_user_table, array(), $fields, $limit, $order_by);
			//TODO 根据id列表查询数据库中数据拼接
			$total_count = $this->common_model->get_num($this->master_db, $this->master_user_table);	
			$this->response_page($res, ceil($total_count/15), $total_count);		
		}else{
			$this->load->view('callback/get_list');
		}
	}

}
