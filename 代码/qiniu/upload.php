<?php
require './bootstrap.php';

//上传文件到本地
$files = $_FILES["file"];
if($files){
	$dir = './upload';
	$allow_ext = array('docx');
	foreach($files['name'] as $k=>$name){
		$type = $files['type'][$k];
		$temp = $files['tmp_name'][$k];
		$error = $files['error'][$k];
		$size = $files['size'][$k];
		$ext = substr($name,strrpos($name,'.')+1);
		if(strrpos($type, 'office') && in_array($ext, $allow_ext)){
			if(!$error){
				if(move_uploaded_file($temp, $dir.'/'.$name)){

					// 生成上传 Token
					$token = $auth->uploadToken($bucket);

					// 要上传文件的本地路径
					$filePath = $dir . '/' .$name;
					$prefix = 'plan/';
					// 上传到七牛后保存的文件名
					$filename = $prefix . $name;

					//删除原有文件
					$bucket_manager->delete($bucket, $filename);
					// 初始化 UploadManager 对象并进行文件的上传。
					
					list($ret, $err) = $uploadMgr->putFile($token, $filename, $filePath);
					
					if ($err !== null) {
						file_put_contents('./log/'.date('Y-m-d').'.log', '[FAIL]['.$ip.'] ['.date('Y-m-d H:i:s').']'.$filePath.' upload failed'.PHP_EOL, FILE_APPEND);
					} else {
						file_put_contents('./log/'.date('Y-m-d').'.log', '[SUCCESS]['.$ip.'] ['.date('Y-m-d H:i:s').']'.$filePath.' upload success'.PHP_EOL, FILE_APPEND);
						unlink($filePath);
					}

					$url = create_url($filename);

					$urls[$name] = $url;
				}
			}
		}
	}
}
header('Location: ./index.php');
exit;