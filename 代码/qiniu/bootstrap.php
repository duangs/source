<?php
error_reporting(0);
require dirname(__FILE__) . '/vendor/autoload.php';

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

define('ACCESSKEY','QjhFzM91UMoA0BrxuoubEld64Wvb3rXr7yMaop4q');
define('SECRETKEY','RqrUk8S-h-Kne1gWrPO0iVdP1XCeyscqalIvmUsQ');

$bucket = 'shukugang-file';

$auth = new Auth(ACCESSKEY, SECRETKEY);
$bucket_manager = new BucketManager($auth);
$uploadMgr = new UploadManager();

function create_url($filename){
	//生成下载链接
	$host = 'http://7xli8b.com1.z0.glb.clouddn.com/';
	$time = time() + (3600 * 24);   //链接过期时间 +24小时
	$url = $host . $filename;
	$url .= '?e='.$time;
	$data = hash_hmac('sha1',$url,SECRETKEY,true);
	$data = base64_encode($data);
	$find = array('+', '/');
	$replace = array('-', '_');
	$key = str_replace($find, $replace,$data);
	$token = ACCESSKEY.':'.$key;
	$url .= '&token='.$token;
	return $url;
}

function getIP(){
	if (getenv("HTTP_CLIENT_IP"))
		$ip = getenv("HTTP_CLIENT_IP");
	else if(getenv("HTTP_X_FORWARDED_FOR"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	else if(getenv("REMOTE_ADDR"))
		$ip = getenv("REMOTE_ADDR");
	else $ip = "Unknow";
	return $ip;
}

$ip = getIP();