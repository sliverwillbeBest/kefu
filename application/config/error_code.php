<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

$config['error_code']['200'] = '成功';
$config['error_code']['400'] = '缺少参数';

//用户错误
$config['error_code']['500'] = '用户不存在';
$config['error_code']['501'] = '密码不正确';
$config['error_code']['502'] = '未登录状态';
$config['error_code']['503'] = '商品已存在';
$config['error_code']['504'] = '两次密码不一直';

//验证码错误
$config['error_code']['510'] = '验证码错误'; 
$config['error_code']['511'] = '验证码超时'; 
$config['error_code']['512'] = '还没有发送短信验证码'; 
$config['error_code']['513'] = '该手机号已注册'; 
$config['error_code']['514'] = '验证码发送间隔小于60秒';
$config['error_code']['515'] = '每日验证码不能超过4条';
$config['error_code']['516'] = '短信宝错误';

$config['error_code']['600'] = '激活码不存在';
$config['error_code']['601'] = '激活码已使用';
$config['error_code']['602'] = '激活失败';

$config['error_code']['701'] = '暂无视频';
$config['error_code']['702'] = '上传图片失败';
$config['error_code']['703'] = '上传视频失败';
$config['error_code']['704'] = '用户未激活';
$config['error_code']['705'] = '视频不存在';
$config['error_code']['706'] = '提交审核失败';
$config['error_code']['707'] = '删除失败';
$config['error_code']['708'] = '请上传文件';
$config['error_code']['709'] = '微信授权失败';

$config['error_code']['800'] = '暂无最新版本';

//验证码错误
$config['error_code'][1001]='验证码不正确';
$config['error_code'][1002]='验证码超时';
$config['error_code'][1003]='请先发送验证码';
$config['error_code'][1004]='请勿重复发送';
//用户错误码
$config['error_code'][2001]='请勿重复注册';
$config['error_code'][2002]='用户还未注册';
$config['error_code'][2003]='用户签名错误';
$config['error_code'][2004]='用户不存在';
$config['error_code'][2014]='用户旧密码错误';
$config['error_code'][2015]='新旧密码不能相同';
$config['error_code'][2016]='密码错误';
$config['error_code'][2017]='用户令牌错误';
//视频错误
$config['error_code'][3001]='视频不存在';

