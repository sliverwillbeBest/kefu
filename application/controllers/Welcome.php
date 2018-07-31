<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function main(){
		$this->load->view('main');
	}

	public function test(){
	}

	public function dump_mysql(){
		$db2 = $this->load->database('master', True);
		$this->load->model('common/common_model');
		//$db, $table, $where=array(), $field='*', $limit=array(), $order_by=''
		$master_res = $this->common_model->run_sql_more($db2, "select id as user_id, mobile from wm_user_users where id != '' and mobile like '1%'");
		$this->common_model->insert_batch($this->db, 'callback_non_investment', $master_res);

	}

}
