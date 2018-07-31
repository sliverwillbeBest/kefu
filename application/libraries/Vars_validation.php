<?php  
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 参数验证类库
 * @author Sliver
 */
class Vars_validation
{
	private $CI;
	private $api_users = array();
	private $vars = array();
	private $aliases = array();
	private $rules = array();
	private $error = array();
	
	/**
	 * 构造函数
	 * @author Sliver
	 */
	public function __construct() {
		$this->CI =& get_instance();
		
		$api_config = $this->CI->config->item('api');
		$this->api_users = $api_config['users'];
	}
	
	/**
	 * 载入参数
	 * @param array $vars
	 * @author Sliver
	 */
	public function load_vars($vars = array()) {
		if (empty($vars)) {
			$this->vars = $this->CI->input->post();
		} else {
			$this->vars = $vars;
		}
	}
	
	/**
	 * 设置错误码与错误信息
	 * @param string $var_name
	 * @param string $rule
	 * @param string $rule_val
	 * @author Sliver
	 */
	private function set_error($var_name, $rule, $rule_val = '') {
		$error_codes = array(
			'required' => 'EMPTY',
			'valid_api_user' => 'ILLEGAL',
			'valid_from' => 'ILLEGAL',
			'valid_token' => 'ILLEGAL',
			'matches' => 'UNMATCHED',
			'min_length' => 'SHORT',
			'max_length' => 'LONG',
			'exact_length' => 'INEXACT_LENGTH',
			'alpha' => 'ILLEGAL',
			'alpha_numeric' => 'ILLEGAL',
			'alpha_dash' => 'ILLEGAL',
			'numeric' => 'ILLEGAL',
			'integer' => 'ILLEGAL',
			'decimal' => 'ILLEGAL',
			'valid_email' => 'ILLEGAL',
			'valid_ip' => 'ILLEGAL',
			'valid_date' => 'ILLEGAL',
			'numeric_list' => 'ILLEGAL',
			'in_list' => 'ILLEGAL',
			'is_mobile' => 'ILLEGAL',
			'is_unique' => 'ILLEGAL',
			'alpha_cn' => 'ILLEGAL',
		);
		$error_msgs = array(
			'required' => '%s不能为空',
			'valid_api_user' => '%s不正确',
			'valid_from' => '%s不正确',
			'valid_token' => '%s不正确',
			'matches' => '%s与%s不匹配',
			'min_length' => '%s长度不能小于%s个字符',
			'max_length' => '%s长度不能大于%s个字符',
			'exact_length' => '%s长度应为%s个字符',
			'alpha' => '%s不正确',
			'alpha_numeric' => '%s不正确',
			'alpha_dash' => '%s不正确',
			'numeric' => '%s不正确',
			'integer' => '%s不正确',
			'decimal' => '%s不正确',
			'valid_email' => '%s不正确',
			'valid_ip' => '%s不正确',
			'valid_date' => '%s不正确',
			'numeric_list' => '%s不正确',
			'in_list' => '%s不正确',
			'is_mobile' => '%s不正确',
			'is_unique' => '%s已存在',
			'alpha_cn' => '%s不正确',
		);
		
		$this->error['code'] = $error_codes[$rule] . '_' . strtoupper($var_name);
		
		if ($rule_val != '') {
			$rule_val_aliase = (isset($this->aliases[$rule_val])) ? $this->aliases[$rule_val] : $rule_val;
			$this->error['msg'] = sprintf($error_msgs[$rule], $this->aliases[$var_name], $rule_val_aliase);
		} else {
			$this->error['msg'] = sprintf($error_msgs[$rule], $this->aliases[$var_name]);
		}
	}
	
	/**
	 * 获取错误码与错误信息
	 * @return array
	 * @author Sliver
	 */
	public function get_error() {
		return $this->error;
	}
	
	/**
	 * 设置别名与规则
	 * @param string $var_name
	 * @param string $alias
	 * @param string $rules
	 * @author Sliver
	 */
	//$this->vars_validation->set_alias_rules('api_user', 'API账号', 'required|valid_api_user');
	public function set_alias_rules($var_name, $alias = '', $rules = '') {
		if (empty($alias)) $alias = $var_name;
		$this->aliases[$var_name] = $alias;
		
		$this->rules[$var_name] = $rules;
	}
	
	/**
	 * 执行验证
	 * @param boolean $auto_response
	 * @return boolean
	 * @author Sliver
	 */
	public function run($auto_response = true) {
		if ($auto_response == true) {
			if ($this->check_rules() == false) {
				$error = $this->get_error();
				
				$this->CI->response('400', '', array($error['code'], $error['msg']));	// 输出响应信息并终止程序
			}
		} else {
			if ($this->check_rules() == false ) {
				return false;
			} else {
				return true;
			}
		}
	}
	
	/**
	 * 验证参数是否符合相应的规则
	 * @return boolean
	 * @author Sliver
	 */
	private function check_rules() {
		//rules['api_user']  = 'required|valid_api_user'
		foreach ($this->rules as $var_name => $rules) {
			if (empty($rules)) continue;
			
			$rules = explode('|', $rules);
			
			if (in_array('required', $rules) || isset($this->vars[$var_name])) {	// 仅在参数被要求非空或参数被设置时进行规则验证
				foreach ($rules as $rule) {
					$var_value = (isset($this->vars[$var_name])) ? $this->vars[$var_name] : '';
					
					if (strpos($rule, '[') !== false && preg_match_all('/\[(.*?)\]/', $rule, $matches)) {
						$x = explode('[', $rule);
						$rule = current($x);
						
						$rule_var = $matches[1][0];
					
						if ($this->$rule($var_value, $rule_var) == false) {
							$this->set_error($var_name, $rule, $rule_var);
		
							return false;
						}
					} else {
						if ($this->$rule($var_value) == false) {
							$this->set_error($var_name, $rule);
		
							return false;
						}
					}
				}
			}
		}
	
		return true;
	}
	
	/**
	 * 规则之参数非空
	 * @param string $str
	 * @return boolean
	 * @author Sliver
	 */
	public function required($str) {
		if (!is_array($str)) {
			return (trim($str) == '') ? false : true;
		} else {
			return (!empty($str));
		}
	}
	
	/**
	 * 规则之API账号有效
	 * @param string $str
	 * @return boolean
	 * @author Sliver
	 */
	public function valid_api_user($str) {
		if (isset($this->api_users[$str]) && $this->api_users[$str] != '') {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 规则之来源有效
	 * @param string $str
	 * @return boolean
	 * @author Sliver
	 */
	public function valid_from($str) {
		if (in_array($str, array('android', 'ios', 'wx', 'pc'))) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 规则之签名有效
	 * @param string $str
	 * @return boolean
	 * @author Sliver
	 */
	public function valid_token($str, $var) {
		$arr = explode(',', $var);
		if(md5($arr[1].$arr[0]) != $this->vars['sign']){
			return false;
		}
		// if(time() - $arr[1] > 60*60*2){
		// 	return false;
		// }
		return true;
	}
	
	/**
	 * 规则之匹配另一个参数
	 * @param string $str
	 * @param string $var
	 * @return boolean
	 * @author Sliver
	 */
	public function matches($str, $var) {
		if (!isset($this->vars[$var])) {
			return false;
		}
		
		return ($str !== $this->vars[$var]) ? false : true;
	}
	
	/**
	 * 规则之最小字符长度（占位长度）
	 * @param string $str
	 * @param integer $var
	 * @return boolean
	 */
	public function min_length($str, $var) {
		
		if (function_exists('mb_strlen')) {
			return ((strlen($str) + mb_strlen($str, 'utf8')) / 2) < $var ? false : true;
		}

		return (strlen($str) < $var) ? false : true;

	}
	
	/**
	 * 规则之最大字符长度（占位长度）
	 * @param string $str
	 * @param integer $var
	 * @return boolean
	 */
	public function max_length($str, $var) {

		if (function_exists('mb_strlen')) {
			return ((strlen($str) + mb_strlen($str, 'utf8')) / 2) > $var ? false : true;
		}

		return (strlen($str) > $var) ? false : true;
	}
	
	/**
	 * 规则之相符字符长度
	 * @param string $str
	 * @param integer $var
	 * @return boolean
	 */
	public function exact_length($str, $var) {

		if (function_exists('mb_strlen')) {
			return ((strlen($str) + mb_strlen($str, 'utf8')) / 2) != $var ? false : true;
		}

		return (strlen($str) != $var) ? false : true;
	}
	
	/**
	 * 规则之仅由字母组成
	 * @param string $str
	 * @return boolean
	 */
	public function alpha($str) {
		return (!preg_match('/^([a-z])+$/i', $str)) ? false : true;
	}
	
	/**
	 * 规则之仅由字母和数字组成
	 * @param string $str
	 * @return boolean
	 */
	public function alpha_numeric($str) {
		return (!preg_match('/^([a-z0-9])+$/i', $str)) ? false : true;
	}
	
	/**
	 * 规则之仅由字母、数字、下划线和破折号组成
	 * @param string $str
	 * @return boolean
	 */
	public function alpha_dash($str) {
		return (!preg_match('/^([-a-z0-9_-])+$/i', $str)) ? false : true;
	}
	
	/**
	 * 规则之仅由数字组成
	 * @param string $str
	 * @return boolean
	 */
	public function numeric($str) {
		return (bool)preg_match('/^[\-+]?[0-9]*\.?[0-9]+$/', $str);
	}
	
	/**
	 * 规则之仅由整数组成
	 * @param string $str
	 * @return boolean
	 */
	public function integer($str) {
		return (bool)preg_match('/^[\-+]?[0-9]+$/', $str);
	}
	
	/**
	 * 规则之仅由小数组成
	 * @param string $str
	 * @return boolean
	 */
	public function decimal($str) {
		return (bool)preg_match('/^[\-+]?[0-9]+\.[0-9]+$/', $str);
	}
	
	/**
	 * 规则之邮箱有效
	 * @param unknown_type $str
	 * @return boolean
	 */
	public function valid_email($str) {
		return (!preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix', $str)) ? false : true;
	}
	
	/**
	 * 规则之IP有效
	 * @param string $ip
	 * @param string $which
	 */
	public function valid_ip($ip, $which = '') {
		return $this->CI->input->valid_ip($ip, $which);
	}
	
	/**
	 * 规则之日期有效
	 * @param string $str
	 * @return boolean
	 * @author Sliver
	 */
	public function valid_date($str) {
		$arr = explode('-', $str);
		if (count($arr) == 3) {
			return checkdate($arr[1], $arr[2], $arr[0]);
		} else {
			return false;
		}
	}
	
	/**
	 * 规则之仅由逗号分割的数字组成
	 * @param string $str
	 * @return boolean
	 * @author Sliver
	 */
	function numeric_list($str) {
		return (bool)preg_match('/^[\d,]+$/', $str);
	}
	
	/**
	 * 规则之在列表中存在
	 * @param string $str
	 * @param string $var
	 * @return boolean
	 * @author Sliver
	 */
	function in_list($str, $var) {
		$var_arr = explode(',', $var);
		
		return in_array($str, $var_arr);
	}

	/**
	 * 规则之手机号合法
	 * @param string $str
	 * @param string $var
	 * @return boolean
	 * @author Sliver
	 */
	function is_mobile($str){    
    	return (bool)preg_match("/^(1(([35][0-9])|(47)|[7][01678]|[8][012356789]))\d{8}$/", $str);    
    }  

    /**
	 * 规则之字段唯一
	 *
	 * Check if the input value doesn't already exist
	 * in the specified database field.
	 *
	 * @param	string	$str
	 * @param	string	$field
	 * @return	bool
	 */
	public function is_unique($str, $field)
	{
		sscanf($field, '%[^.].%[^.]', $table, $field);
		return isset($this->CI->db)
			? ($this->CI->db->limit(1)->get_where($table, array($field => $str))->num_rows() === 0)
			: FALSE;
	}

	public function alpha_cn($str){
		return (!preg_match('/^[\x{4e00}-\x{9fa5}A-Za-z0-9]+$/u', $str)) ? false : true;
	}

}