<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
    后台基类
    @author sliver
 */

class Admin_Controller extends CI_Controller{

    public function __construct() {
        parent::__construct();  
        $this->load->config('error_code');
        $this->error_code = $this->config->item('error_code');
        $this->load->library('vars_validation');
        $this->load->config('tables_name');
        $this->load->model('common/common_model');
        //加载其他db对象，调用model方法时传递，方便选择多数据库
        $this->master_db = $this->load->database('master', TRUE);
	}

    public function show($arr){
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
        exit;
    }

    /*
        验证用户是否登录
     */

    public function check_token($table='admin_user'){
       
        if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_account'])) {
            echo "<script>alert('未登录状态，请进行登录！');parent.location.href='".site_url('admin/login/index')."'</script>";
            exit();
        }
        $admin_id = $_SESSION['admin_id'];
        $admin_account = $_SESSION['admin_account'];
        $where = array('username'=>$admin_account);
        $res = $this->common_model->select_one($this->db, $table, $where);
        if (!$admin_id || !$res || !$admin_account) {
            echo "<script>alert('未登录或登录过期，请重新登录！');parent.location.href='".site_url('admin/login/index')."'</script>";
        }
        return true;
    }

    /*
        验证func对应的权限是否足够
     */

    public function check_auth($func){
        //获取用户的权限列表,取得当前调用的方法对应的权限id，判断id是否在用户的权限中
        if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_account'])) {
            echo "<script>alert('未登录状态，请进行登录！');parent.location.href='".site_url('admin/login/index')."'</script>";
            exit();
        }
        $admin_id = $_SESSION['admin_id'];
        //获取用户的角色
        $role = $this->common_model->select_one($this->db, 'admin_user', array('id'=>$admin_id), 'role_id');
        //获取角色对应的权限列表
        $auth_rule = $this->common_model->select_one($this->db, 'admin_role', array('role_id'=>$role ['role_id']), 'auth_rule');
        //获取调用的func对应的权限id
        $auth_func = $this->common_model->select_one($this->db, 'admin_auth', array('auth_func'=>$func), 'auth_id');
        if(strstr($auth_rule['auth_rule'], $auth_func['auth_id'])){
            return true;    
        }else{
            echo "<script>alert('您没有权限访问这个功能');window.parent.parent.location.href='".site_url('admin/login/index')."'</script>";
            exit();
        }
    }
    
    /*
        封装response
     */

    public function response($code, $msg = '', $error = array(), $data = array()) {
        $response_data = array('code' => $code);
        if (!empty($error)) {
            $response_data['msg'] = $error[0] . ' ,' . $error[1];
            $response_data['data'] = new stdClass();
        } else {
            $response_data['msg'] = $this->error_code[$code];
            $response_data['data'] = (!empty($data)) ? $data : new stdClass();
        }
        //通用log处理
        $log['url'] = $this->router->uri->uri_string . '/';
        $log['param'] = $this->input->post();
        $log['return'] = $response_data;
        log_message('debug', json_encode($log));
        echo json_encode($response_data);
        exit;
    }

    /*
        应对分页格式的response
     */

    public function response_page($data = array(), $total_page = '1', $total_count = '15'){
        $response_data['data'] = (!empty($data)) ? $data : new stdClass();
        $response_data['total_page'] = $total_page;
        $response_data['total_count'] = $total_count;
        
        //通用log处理
        $log['url'] = $this->router->uri->uri_string . '/';
        $log['param'] = $this->input->post();
        $log['return'] = $response_data;
        log_message('debug', json_encode($log));
        echo json_encode($response_data);
        exit;       
    }


    /*
        导出excel
     */

    public function dump_excel($filename, $header, $data){
        $this->load->library('xlsxwriter');
        //设置 header，用于浏览器下载
        header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        $this->xlsxwriter->writeSheetHeader('Sheet1', $header);
        foreach ($data as $key => $value) {
            foreach ($value as $k => $v) {
                if(ctype_digit($v) && $v <= 2147483647 && strstr($k, 'time')){
                    $value[$k] = date('Y-m-d H:i:s', $v);
                }
            }
            $this->xlsxwriter->writeSheetRow('Sheet1', $value);
        }
        $this->xlsxwriter->writeToStdOut();
        exit(0);

    }

}