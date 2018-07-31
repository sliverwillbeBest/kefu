<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 公共辅助函数
 * @author Sliver
 */

/**
 * HTTP请求函数
 * @param string $url
 * @param array|string $post
 * @param integer $connect_timeout
 * @param integer $read_timeout
 * @return mixed|boolean
 * @author Sliver
 */
function http_request($url, $post, $connect_timeout = 15, $read_timeout = 300) {
    if (function_exists('curl_init')) {
        $timeout = $connect_timeout + $read_timeout;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connect_timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    return false;
}

function http_request_get($url, $post, $connect_timeout = 15, $read_timeout = 300){
    if (function_exists('curl_init')) {
        $timeout = $connect_timeout + $read_timeout;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url.$post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
    }
    return false;
}

function timediff( $begin_time, $end_time )
{
    if ( $begin_time < $end_time ) {
        $starttime = $begin_time;
        $endtime = $end_time;
    } else {
        $starttime = $end_time;
        $endtime = $begin_time;
    }
    $timediff = $endtime - $starttime;
    $days = intval( $timediff / 86400 );
    $remain = $timediff % 86400;
    $hours = intval( $remain / 3600 );
    $remain = $remain % 3600;
    $mins = intval( $remain / 60 );
    $secs = $remain % 60;
    $res = array( "day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs );
    return $res;
}

function array_remove(&$arr, $offset){
    //array_splice($arr, $offset, 1);
    unset($arr[$offset]);
    //return $arr;
}

function return_json($data)
{
    echo json_encode($data);
}
function print_pre($data)
{
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
}

function upload_img($file,$position)
{
    $type=array('image/jpg', 'image/jpeg', 'image/png', 'image/pjpeg', 'image/gif', 'image/bmp', 'image/x-png');
    $max_size=20000000;
    $data['code']=500;
    if(!in_array($file["type"],$type))
    {
        $data['message']='文件类型不符！';
        return $data;
    }
    if($file["size"] > $max_size)
    {
        $data['message']='文件过大！';
        return $data;
    }
    //如果没有目录就创建目录
    if(!file_exists($position))
    {
        $old = umask(0);
        $dir=explode('/',$position);
        $path='';
        for($i=0;$i<count($dir);$i++)
        {
            $path.=$dir[$i].'/';
            if(!file_exists($path) && $dir[$i] != '')
            {
                mkdir($path,0777);
            }
        }
        umask($old);
    }
    //文件重命名
    $new_name=date('YmdHis').getRandChar(32).'.'.explode('/',$file["type"])[1];
    if (file_exists($position.$new_name))
    {
        $old = umask(0);
        chmod($position.$new_name, 0777);
        umask($old);
        unlink($position.$new_name);
    }
    if(!move_uploaded_file ($file['tmp_name'], $position.$new_name))
    {
        $data['message']= "移动文件出错";
        return $data;
    }
    $data['code']=200;
    $data['message']= "上传成功";
    $data['data']['url']=$position.$new_name;
    return $data;
}
function upload_apk($file,$position)
{
    $max_size=200000000;
    $data['code']=500;
    if($file["size"] > $max_size)
    {
        $data['message']='文件过大！';
        return $data;
    }
    //如果没有目录就创建目录
    if(!file_exists($position))
    {
        $old = umask(0);
        $dir=explode('/',$position);
        $path='';
        for($i=0;$i<count($dir);$i++)
        {
            $path.=$dir[$i].'/';
            if(!file_exists($path) && $dir[$i] != '')
            {
                mkdir($path,0777);
            }
        }
        umask($old);
    }
    //文件重命名
    $new_name='SecurityGuard.apk';
    if (file_exists($position.$new_name))
    {
        $old = umask(0);
        chmod($position.$new_name, 0777);
        umask($old);
        unlink($position.$new_name);
    }
    if(!move_uploaded_file ($file['tmp_name'], $position.$new_name))
    {
        $data['message']= "移动文件出错";
        return $data;
    }
    $data['code']=200;
    $data['message']= "上传成功";
    $data['data']['url']=$position.$new_name;
    return $data;
}
//获得视频文件的总长度时间和创建时间
function getTime($file){
    $vtime = exec("ffmpeg -i ".$file." 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//");//总长度
    $ctime = date("Y-m-d H:i:s",filectime($file));//创建时间
    //$duration = explode(":",$time);
    // $duration_in_seconds = $duration[0]*3600 + $duration[1]*60+ round($duration[2]);//转化为秒
    //return array('vtime'=>$vtime,'ctime'=>$ctime);
    return $vtime;
}
//生成随机字符串
function getRandChar($length)
{
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol)-1;
    for($i=0;$i<$length;$i++){
        $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
    }
    return $str;
}
//返回数组信息
function back_info($code, $message = '', $data = array())
{
    $back_info = array('code' => $code);
    $back_info['message']=$message;
    $back_info['data']=$data;
    return $back_info;
}

//输出接口信息
function response($code, $message = '', $data = array())
{
    $response_data = array('code' => $code);
    $response_data['message']=$message;
    $response_data['data']=$data;
    echo json_encode($response_data);
    exit;
}