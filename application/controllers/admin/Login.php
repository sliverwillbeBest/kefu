<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
	登录控制器
	@author sliver
 */

class Login extends Admin_Controller{

	protected $user_table;
	protected $role_table;
	protected $top_auth_map;
	protected $left_auth_map;

	public function __construct(){
		parent::__construct();
		$this->user_table = $this->config->item('ADMIN_TABLE');
		$this->role_table = $this->config->item('ROLE_TABLE');
		$this->load->config('auth_map');
        $this->top_auth_map = $this->config->item('top_auth_map');
        $this->left_auth_map = $this->config->item('left_auth_map');
	}

	public function index(){
		$this->load->view('admin/login');
	}

	/*
		登录方法
		判断登录，如果登录成功，则更新用户登录信息，并处理对应权限逻辑方便后续使用，是后续接口中判断数据的来源
	 */

	public function is_login(){
		$admin_account = $this->input->post('username');
		$admin_pass = $this->input->post('password');
		$where = array('username'=>$admin_account);
		$res = $this->common_model->select_one($this->db, $this->user_table, $where);
		if(!$res){
			$this->response(500);
		} 
		if(!password_verify(md5($admin_pass),$res['password'])) {
			$this->response(501);
		}

		//登录成功后处理
		//修改登录信息：登录时间，ip
		$data = array('ip_address'=>$this->input->ip_address(), 'last_login_time'=>time());
		$where = array('id'=>$res['id']);
		$this->common_model->update($this->db, $this->user_table, $data, $where);
		//判断登录权限
		//获取角色对应的权限列表
		$where = array('role_id'=>$res['role_id']);
		$role = $this->common_model->select_one($this->db, $this->role_table, $where);
		$auth_rule = explode(',', $role['auth_rule']);
		//判断该用户的权限对应可以显示的顶部菜单都有哪些
		$top_arr = array();
		$left_arr = array();
		foreach ($this->top_auth_map as $key => $value) {
			if(array_intersect($auth_rule, $value)){
				array_push($top_arr, $key);
			}
		}
		foreach ($this->left_auth_map as $key => $value) {
			if(array_intersect($auth_rule, $value)){
				array_push($left_arr, $key);
			}
		}
		$_SESSION['auth_top_menu'] = implode(',', $top_arr);
		$_SESSION['auth_left_menu'] = implode(',', $left_arr);
		$_SESSION['admin_id'] = $res['id'];
		$_SESSION['admin_account'] = $res['username'];

		$this->response(200);
	}

}