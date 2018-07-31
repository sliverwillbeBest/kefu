<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
	系统管理控制器
	@author sliver
 */

class System extends Admin_Controller{

	protected $user_table;
	protected $role_table;
	protected $auth_table;
	
	public function __construct(){
		parent::__construct();
		//通用token验证, 如用户未经过login动作直接访问，则返回登录
		$this->check_token();
		$this->user_table = $this->config->item('ADMIN_TABLE');
		$this->role_table = $this->config->item('ROLE_TABLE');
		$this->auth_table = $this->config->item('AUTH_TABLE');
	}

// -----------------------------------------管理员操作--------------------------------------------

	/*
		获取管理员列表
	 */

	public function get_user(){
		//通用权限验证，如用户不含有当前操作的权限，则返回登录（权限在登录时决定）
		$this->check_auth(__METHOD__);
		if(!empty($_POST)){
			$page = $this->input->post('size');
			$pagesize = $this->input->post('pagesize');
			$fields = 'id, username, real_name, role_name, ip_address, last_login_time, create_time, status';
			if(!empty($page) && !empty($pagesize)){
				$limit = array($pagesize, ($page-1)*$pagesize);
			}else{
				$limit = array();
			}
			$res = $this->common_model->select($this->db, $this->user_table, array(), $fields, $limit);
			$total_count = $this->common_model->get_num($this->db, $this->user_table);
			$this->response_page($res, ceil($total_count/15), $total_count);		
		}else{
			$this->load->view('admin/user');
		}
	}

	/*
		添加系统管理员
	 */

	public function add_user(){

		//get方法调用时，返回对应view（除修改操作，其余不返回数据）
		//post方法调用时，为页面中ajax调用，PHP只做数据返回。前后端分离

		$this->check_auth(__METHOD__);
		if(!empty($_POST)){
			$data['username'] = $this->input->post('username');
			$password = $this->input->post('password');
			$data['real_name'] = $this->input->post('real_name');
			$data['role_id'] = $this->input->post('role');
			$data['status'] = $this->input->post('status');
			$data['ip_address'] = $this->input->ip_address();
			$data['last_login_time'] = time();
			$data['create_time'] = time();
			//用户密码先md5之后hash
			$data['password'] = password_hash(md5($password), PASSWORD_DEFAULT);
			$role_name = $this->common_model->select_one($this->db, $this->role_table, array('role_id'=>$data['role_id']), "role_name");
			$data['role_name'] = $role_name['role_name'];
			$res = $this->common_model->insert($this->db, $this->user_table, $data);
			if($res){
				//通用response，JSON格式
				$this->response('200');
			}else{
				$this->response('501');
			}
		}else{
			$this->load->view('admin/add_user');
		}
	}

	/*
		修改管理员
	 */

	public function mod_user(){
		$this->check_auth(__METHOD__);
		if(!empty($_POST)){
			$data['id'] = $this->input->post('id');
			$data['username'] = $this->input->post('username');
			$data['real_name'] = $this->input->post('real_name');
			$data['role_id'] = $this->input->post('role');
			$data['status'] = $this->input->post('status');
			$password = $this->input->post('password');
			if(!empty($password)){
				$data['password'] = password_hash(md5($password), PASSWORD_DEFAULT);
			}
			$role_name = $this->common_model->select_one($this->db, $this->role_table, array('role_id'=>$data['role_id']), "role_name");
			$data['role_name'] = $role_name['role_name'];
			$res = $this->common_model->update($this->db, $this->user_table, $data, array('id'=>$data['id']));	
			if($res){
				$this->response('200');
			}else{
				$this->response('501');
			}
		}else{
			//get调用，返回对应需要显示的信息
			$id = $this->input->get('id');
			$res = $this->common_model->select_one($this->db, $this->user_table, array('id'=>$id), "id, username, real_name, role_id, role_name, status");
			$data['id'] = $res['id'];
			$data['username'] = $res['username'];
			$data['real_name'] = $res['real_name'];
			$data['role_id'] = $res['role_id'];
			$data['status'] = $res['status'];
			$this->load->view('admin/mod_user', $data);
		}
	}

	/*
		删除管理员
	 */

	public function del_user(){
		$this->check_auth(__METHOD__);
		$id = $this->input->post('id');
		$res = $this->common_model->delete($this->db, $this->user_table, array('id'=>$id));
		if($res){
			$this->response('200');
		}else{
			$this->response('501');
		}
	}

// -----------------------------------------角色操作--------------------------------------------

	/*
		获取角色列表
	 */

	public function get_role(){
		$this->check_auth(__METHOD__);
		if(!empty($_POST)){
			$page = $this->input->post('size');
			$pagesize = $this->input->post('pagesize');
			$fields = 'role_id, role_name, role_pname, status';
			if(!empty($page) && !empty($pagesize)){
				$limit = array($pagesize, ($page-1)*$pagesize);
			}else{
				$limit = array();
			}
			$res = $this->common_model->select($this->db, $this->role_table, array(), $fields, $limit);
			$total_count = $this->common_model->get_num($this->db, $this->role_table);		
			$this->response_page($res, ceil($total_count/15), $total_count);
		}else{
			$this->load->view('admin/role');
		}
	}

	/*
		修改权限对应的列表
	 */

	public function mod_role_auth(){
		$this->check_auth(__METHOD__);
		if(!empty($_POST)){
			$role_id = $this->input->post('role_id');
			$auth_rule = $this->input->post('auth_rule');
			$res = $this->common_model->update($this->db, $this->role_table, array('auth_rule'=>$auth_rule), array('role_id'=>$role_id)); 
			if($res){
				$this->response('200');
			}else{
				$this->response('501');
			}
		}else{
			$id = $this->input->get('id');
			//被选择的角色对应的权限列表
			$auth_rule = $this->common_model->select_one($this->db, $this->role_table, array('role_id'=>$id), "auth_rule");
			$auth_array = explode(',', $auth_rule['auth_rule']);
			//所有权限列表
			$rule_array = $this->common_model->select($this->db, $this->auth_table);	
			$data['user_auth'] = $auth_array;
			$data['rule_auth'] = $rule_array;
			$data['role_id'] = $id;
			$this->load->view('admin/mod_role_auth', $data);
		}
	}

	/*
		添加角色
	 */

	public function add_role(){
		$this->check_auth(__METHOD__);
		if(!empty($_POST)){
			$data['role_name'] = $this->input->post('role_name');
			$data['role_pid'] = $this->input->post('role_pid');
			$data['status'] = $this->input->post('status');
			$role = $this->common_model->select_one($this->db, $this->role_table, array('role_id'=>$data['role_pid']), "role_name");
			$data['role_pname'] = $role['role_name'];
			$res = $this->common_model->insert($this->db, $this->role_table, $data);
			if($res){
				$this->response('200');
			}else{
				$this->response('500');
			}
		}else{
			//角色列表，用于添加角色时选择对应上级角色
			$role = $this->common_model->select($this->db, $this->role_table);
			$data['role'] = $role;
			$this->load->view('admin/add_role', $data);
		}
	}

	/*
		修改角色
	 */

	public function mod_role(){
		$this->check_auth(__METHOD__);
		if(!empty($_POST)){
			$data['role_id'] = $this->input->post('role_id');
			$data['role_name'] = $this->input->post('role_name');
			$data['role_pid'] = $this->input->post('role_pid');
			$data['status'] = $this->input->post('status');
			$role_pname = $this->common_model->select_one($this->db, $this->role_table, array('role_id'=>$data['role_pid']), "role_name");
			$data['role_pname'] = $role_pname['role_name'];
			$res = $this->common_model->update($this->db, $this->role_table, $data, array('role_id'=>$data['role_id'])); 
			if($res){
				$this->response('200');
			}else{
				$this->response('500');
			}
		}else{
			$role_id = $this->input->get('id');
			$role = $this->common_model->select_one($this->db, $this->role_table, array('role_id'=>$role_id), "role_name, role_pid, role_id");
			$data['role'] = $role;
			$role_array = $this->common_model->select($this->db, $this->role_table);
			$data['role_array'] = $role_array;
			$this->load->view('admin/mod_role', $data);
		}
	}

	/*
		删除角色
	 */

	public function del_role(){
		$this->check_auth(__METHOD__);
		$id = $this->input->post('id');
		$res = $this->common_model->delete($this->db, $this->role_table, array('role_id'=>$id));
		if($res){
			$this->response('200');
		}else{
			$this->response('501');
		}		

	}

// -----------------------------------------权限操作--------------------------------------------
	//原则上权限操作只对超级管理员开放！

	/*
		获取权限列表
	 */

	public function get_auth(){
		$this->check_auth(__METHOD__);
		if(!empty($_POST)){
			$page = $this->input->post('size');
			$pagesize = $this->input->post('pagesize');
			$fields = 'auth_id, auth_name, auth_func, status';
			if(!empty($page) && !empty($pagesize)){
				$limit = array($pagesize, ($page-1)*$pagesize);
			}else{
				$limit = array();
			}
			$res = $this->common_model->select($this->db, $this->auth_table, array(), $fields, $limit);
			$total_count = $this->common_model->get_num($this->db, $this->auth_table);
			$this->response_page($res, ceil($total_count/15), $total_count);
		}else{
			$this->load->view('admin/auth');
		}
	}

	/*
		添加权限
	 */

	public function add_auth(){
		$this->check_auth(__METHOD__);
		if(!empty($_POST)){
			$data['auth_name'] = $this->input->post('auth_name');
			$data['auth_func'] = $this->input->post('auth_func');
			$data['status'] = $this->input->post('status');
			$res = $this->common_model->insert($this->db, $this->auth_table, $data);
			if($res){
				$this->response('200');
			}else{
				$this->response('501');
			}
		}else{
			$this->load->view('admin/add_auth');
		}
	}

	/*
		修改权限
	 */

	public function mod_auth(){
		$this->check_auth(__METHOD__);
		if(!empty($_POST)){
			$data['auth_id'] = $this->input->post('auth_id');
			$data['auth_name'] = $this->input->post('auth_name');
			$data['auth_func'] = $this->input->post('auth_func');
			$data['status'] = $this->input->post('status');
			$res = $this->common_model->update($this->db, $this->auth_table, $data, array('auth_id'=>$data['auth_id'])); 
			if($res){
				$this->response('200');
			}else{
				$this->response('500');
			}
		}else{
			$auth_id = $this->input->get('id');
			$auth = $this->common_model->select_one($this->db, $this->auth_table, array('auth_id'=>$auth_id), "auth_name, auth_func");
			$data['auth_name'] = $auth['auth_name'];
			$data['auth_func'] = $auth['auth_func'];
			$data['auth_id'] = $auth_id;
			$this->load->view('admin/mod_auth', $data);
		}
	}

	/*
		删除权限
	 */

	public function del_auth(){
		$this->check_auth(__METHOD__);
		$id = $this->input->post('id');
		$res = $this->common_model->delete($this->db, $this->auth_table, array('auth_id'=>$id));
		if($res){
			$this->response('200');
		}else{
			$this->response('501');
		}			
	}


}
