<?php
//加密解密
function encryptDecrypt($key, $string, $decrypt){
	if($decrypt){
		$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($key))), "12");
		return $decrypted;
	}else{
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
		return $encrypted;
	}
}

//生成随机字符串
function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $randomString;
}
//获取文件扩展名
function getExtension($filename){
	$myext = substr($filename, strrpos($filename, '.'));
	return str_replace('.','',$myext);
}

//获取文件大小并格式化
function formatSize($size) {
	$sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
	if ($size == 0) { 
		return('n/a'); 
	} else {
		return (round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizes[$i]); 
	}
}

//PHP替换标签字符
function stringParser($string,$replacer){
	$result = str_replace(array_keys($replacer), array_values($replacer),$string);
	return $result;
}

//列出目录下的文件
function listDirFiles($DirPath){
	if($dir = opendir($DirPath)){
		while(($file = readdir($dir))!== false){
			if(!is_dir($DirPath.$file))
			{
				echo "filename: $file<br />";
			}
		}
	}
}

//获取当前页面的url
function curPageURL() {
	$pageURL = 'http';
	if (!empty($_SERVER['HTTPS'])) {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

//强制下载
function download($filename){
	if ((isset($filename))&&(file_exists($filename))){
		header("Content-length: ".filesize($filename));
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		readfile("$filename");
	} else {
		echo "Looks like file does not exist!";
	}
}

/*
 Utf-8、gb2312都支持的汉字截取函数
 cut_str(字符串, 截取长度, 开始长度, 编码);
 编码默认为 utf-8
 开始长度默认为 0
*/
 function cutStr($string, $sublen, $start = 0, $code = 'UTF-8'){
 	if($code == 'UTF-8'){
 		$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
 		preg_match_all($pa, $string, $t_string);

 		if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."...";
 		return join('', array_slice($t_string[0], $start, $sublen));
 	}else{
 		$start = $start*2;
 		$sublen = $sublen*2;
 		$strlen = strlen($string);
 		$tmpstr = '';

 		for($i=0; $i<$strlen; $i++){
 			if($i>=$start && $i<($start+$sublen)){
 				if(ord(substr($string, $i, 1))>129){
 					$tmpstr.= substr($string, $i, 2);
 				}else{
 					$tmpstr.= substr($string, $i, 1);
 				}
 			}
 			if(ord(substr($string, $i, 1))>129) $i++;
 		}
 		if(strlen($tmpstr)<$strlen ) $tmpstr.= "...";
 		return $tmpstr;
 	}
 }


//获取用户真实IP
 function getIp() {
 	if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
 		$ip = getenv("HTTP_CLIENT_IP");
 	else
 		if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
 			$ip = getenv("HTTP_X_FORWARDED_FOR");
 		else
 			if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
 				$ip = getenv("REMOTE_ADDR");
 			else
 				if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
 					$ip = $_SERVER['REMOTE_ADDR'];
 				else
 					$ip = "unknown";
 				return ($ip);
 			}

//防止注入
 			function injCheck($sql_str) { 
 				$check = preg_match('/select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/', $sql_str);
 				if ($check) {
 					echo '非法字符！！'.$sql_str;
 					exit;
 				} else {
 					return $sql_str;
 				}
 			}

//页面提示跳转
 			function message($msgTitle,$message,$jumpUrl){
 				$str = '<!DOCTYPE HTML>';
 				$str .= '<html>';
 				$str .= '<head>';
 				$str .= '<meta charset="utf-8">';
 				$str .= '<title>页面提示</title>';
 				$str .= '<style type="text/css">';
 				$str .= '*{margin:0; padding:0}a{color:#369; text-decoration:none;}a:hover{text-decoration:underline}body{height:100%; font:12px/18px Tahoma, Arial,  sans-serif; color:#424242; background:#fff}.message{width:450px; height:120px; margin:16% auto; border:1px solid #99b1c4; background:#ecf7fb}.message h3{height:28px; line-height:28px; background:#2c91c6; text-align:center; color:#fff; font-size:14px}.msg_txt{padding:10px; margin-top:8px}.msg_txt h4{line-height:26px; font-size:14px}.msg_txt h4.red{color:#f30}.msg_txt p{line-height:22px}';
 				$str .= '</style>';
 				$str .= '</head>';
 				$str .= '<body>';
 				$str .= '<div class="message">';
 				$str .= '<h3>'.$msgTitle.'</h3>';
 				$str .= '<div class="msg_txt">';
 				$str .= '<h4 class="red">'.$message.'</h4>';
 				$str .= '<p>系统将在 <span style="color:blue;font-weight:bold">3</span> 秒后自动跳转,如果不想等待,直接点击 <a href="{$jumpUrl}">这里</a> 跳转</p>';
 				$str .= "<script>setTimeout('location.replace(\'".$jumpUrl."\')',2000)</script>";
 				$str .= '</div>';
 				$str .= '</div>';
 				$str .= '</body>';
 				$str .= '</html>';
 				echo $str;
 			}


//时间长度转换
 			function changeTimeType($seconds) {
 				if ($seconds > 3600) {
 					$hours = intval($seconds / 3600);
 					$minutes = $seconds % 3600;
 					$time = $hours . ":" . gmstrftime('%M:%S', $minutes);
 				} else {
 					$time = gmstrftime('%H:%M:%S', $seconds);
 				}
 				return $time;
 			}

/**
 *
 * 快速排序
 * 递归实现
 * 获取数组第一个数,循环使后面的数与其比较,
 * 比其小的放在一个数组中,比其大的放在一个数组中
 * 将2个数组递归调用,直到最终数组元素小于等于1时,没有可以比较的元素
 * 通过array_merge函数,将比较的数组按大小顺序合并然后一层一层的return出去,最终实现从小到大排序
 * @param array $array 要操作的数组
 * @return array $array 返回的数组
 */
function quickSort($array)
{
	if(count($array) <= 1 ) return $array;
	$key = $array[0];
	$left_arr = array();
	$right_arr = array();
	for ($i=1;$i<count($array);$i++){
		if($array[$i] <= $key){
			$left_arr[] = $array[$i];
		}else{
			$right_arr[] = $array[$i];
		}
	}

	$left_arr = quickSort($left_arr);
	$right_arr = quickSort($right_arr);
	return array_merge($left_arr,array($key),$right_arr);

}

/** 
 * 冒泡排序
 * 相邻2数比较,小的在前,大的在后
 * 数组有几个元素,就要比较几轮 $i
 * 每轮需要比较的次数为,数组元素个数-已比较的次数 $j
 * @param   array  $array 要操作的数组
 * @return  array  $array 返回的数组
 */
function bubbleSort($array)
{
	$cnt = count($array);
	for($i = 0; $i < $cnt ; $i++){
		for($j = 0 ; $j < ($cnt-$i-1) ; $j++){
			if($array[$j] > $array[$j+1]){
				$temp = $array[$j];
				$array[$j] = $array[$j+1];
				$array[$j+1] = $temp;
			}
		}
	}
	return $array;
}

/**
 * 选择排序
 * 2层循环
 * 第一层逐个获取数组的值 $array[$i]
 * 第二次遍历整个数组与$array[$i]比较($j=$i+1已经比较的,不再比较,减少比较次数)
 * 如果比$array[$i]小,就交换位置
 * 这样一轮下来就可以得到数组中最小值
 * 以此内推整个外层循环下来就数组从小到大排序了
 * @param array $array 要比较的数组
 * @return array $array 从小到大排序后的数组
 */

function selectSort($array){
	$cnt = count($array);
	for($i=0;$i<$cnt;$i++){
		for($j=($i+1);$j<$cnt;$j++){
			if($array[$i]>$array[$j]){
				$tmp = $array[$i];
				$array[$i] = $array[$j];
				$array[$j] = $tmp;
			}
		}
	}
	return $array;
}

/**
 * 二分法查找一个值在数组中的位置
 * @param array $array 操作的数组
 * @param void $val 要查找的值
 * @return int $mid 返回要查找的值在数组中的索引,如果不存在返回-1
 */

function binarySearch($array,$val)
{
	$cnt = count($array);
	$low = 0;
	$high = $cnt - 1;
	while ($low <= $high){
		$mid = intval(($low + $high)/2);
		if($array[$mid] == $val){
			return $mid;
		}

		if($array[$mid] < $val){
			$low = $mid + 1;
		}

		if($array[$mid] > $val){
			$high = $mid - 1;
		}
	}

	return -1;
}

/**
 * 顺序查找(最简单,效率低下)
 * 通过循环数组查找要的值
 * @param array $array 要操作的数组
 * @param void $val  要查找的值
 * @return int 如果存在,返回该值在数组中的索引,否则返回-1
 */
function seqSch($array,$val)
{
	for($i=0;$i<count($array);$i++){
		if($array[$i] == $val)
			break;
	}

	if($i < count($array)){
		return $i;
	}else{
		return -1;
	}
}

?>