<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
	首页控制器
	@author sliver
 */

class Home extends Admin_Controller{

	/*
		首页需要权限验证，所以单独抽离不跟登录放在一起
	 */
	public function __construct(){
		parent::__construct();
		$this->check_token();
	}

	public function index(){
		$this->load->view('admin/index');
	}
}