<?php
/**
 * Copyright (c) 2014-2015 http://www.ardorss.com All rights reserved.
 *
 * Author: zpf <perfei@live.com>
 *
 * Date: 2017年4月25日 下午12:15:11
**/

date_default_timezone_set('PRC');
//print_r($_POST);

$path=$_SERVER['DOCUMENT_ROOT'].'\\'.date("Y-m-d");
creatdir($path);
error_log(
		"\r\n".date("Y-m-d H:i:s").
		"\r\n公司名称：".$_POST['companyname'].
		"\r\n职位：".$_POST['position'].
		"\r\n联系人：".$_POST['contacts'].
		"\r\n手机：".$_POST['mobile'].
		"\r\n邮箱：".$_POST['email'].
		"\r\nQQ：".$_POST['qq'].
		"\r\n",
		 3,
		 $path.'\\reg.log');
echo "提交成功！";

function creatdir($path){
	if(!is_dir($path)){
		if(creatdir(dirname($path))){
			mkdir($path,0777);
			return true;
		}
	}else{
		return true;
	}
}
function Get_Ip_Addr(){
	if(!empty($_SERVER["HTTP_CLIENT_IP"])){
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	}
	if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ //获取代理ip
		$ips = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
	}
	if($ip){
		$ips = array_unshift($ips,$ip);
	}
	$count = count($ips);
	for($i=0;$i<$count;$i++){
		if(!preg_match("/^(10|172\.16|192\.168)\./i",$ips[$i])){//排除局域网ip
			$ip = $ips[$i];
			break;
		}
	}
	$tip = empty($_SERVER['REMOTE_ADDR']) ? $ip : $_SERVER['REMOTE_ADDR'];
	if($tip=="127.0.0.1"){ //获得本地真实IP
		return self::get_onlineip();
	}
	else{
		return $tip;
	}
}
function Get_Ip_From($ip=''){
	if(empty($ip)){
		$ip = Get_Ip_Addr();
	}
	$ip_json=@file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=".$ip);//根据taobao ip
	//$ip_json=iconv('gbk','utf-8', $ip_json);
	$ip_arr=json_decode(stripslashes($ip_json),1);
	if($ip_arr['code']==0)
	{
		$str=$ip_arr['data']['area'].$ip_arr['data']['city'];
		$str=str_replace("u", '\u',$str);
		return unicode_decode(iconv('gbk','utf-8', $str));
	}
	else
	{
		return false;
	}

}
function unicode_decode($name){
	// 转换编码，将Unicode编码转换成可以浏览的utf-8编码
	$pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
	preg_match_all($pattern, $name, $matches);
	if (!empty($matches))
	{
		$name = '';
		for ($j = 0; $j < count($matches[0]); $j++)
		{
		$str = $matches[0][$j];
		if (strpos($str, '\\u') === 0)
			{
			$code = base_convert(substr($str, 2, 2), 16, 10);
			$code2 = base_convert(substr($str, 4), 16, 10);
			$c = chr($code).chr($code2);
			$c = iconv('UCS-2', 'UTF-8', $c);
			$name .= $c;
			}
			else
			{
			$name .= $str;
			}
			}
			}
			return $name;
			}

			header("Content-type:text/html;charset=utf-8");		
					
?>