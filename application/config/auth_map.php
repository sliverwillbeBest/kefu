<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 权限关系映射
 */


//----------------------------------左侧菜单映射-----------------------------------------
//系统管理
$config['left_auth_map']['system_user'] = array(1,2,3,4); //详细权限映射左侧  例： 管理员添加 对应左侧的 系统用户标签
$config['left_auth_map']['system_role'] = array(5,6,7,8,9);
$config['left_auth_map']['system_rule'] = array(10,11,12,13);

//唤回管理
$config['left_auth_map']['callback_noninvestment'] = array(14);
$config['left_auth_map']['callback_noninvestment_recharge'] = array(15);

//散标
$config['left_auth_map']['sanbiao_list'] = array(16);


//----------------------------------顶部菜单映射-----------------------------------------


$config['top_auth_map']['system'] = array_merge($config['left_auth_map']['system_user'], $config['left_auth_map']['system_role'], $config['left_auth_map']['system_rule']); //详细权限映射顶部   例：管理员添加 对应顶部的 系统管理标签
$config['top_auth_map']['callback'] = array_merge($config['left_auth_map']['callback_noninvestment'],$config['left_auth_map']['callback_noninvestment_recharge']);
$config['top_auth_map']['sanbiao'] = array_merge($config['left_auth_map']['sanbiao_list']);

?>