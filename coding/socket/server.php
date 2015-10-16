<?php
// error_reporting(E_ALL);
// set_time_limit(0);

// $ip = '192.168.0.154';
// $port = 8881;

// $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
// socket_bind($sock, $ip, $port);
// socket_listen($sock);
// $count=0;
// while (true) {
// 	$count++;
// 	$msgsock = socket_accept($sock);
// 	$buf = socket_read($msgsock, 8192);
// 	echo "$buf\n";
// 	$path = dirname(__FILE__).'/log.txt';
// 	$str = $count.' '.$buf."\r\n";
// 	error_log($str,3,$path);
// 	sleep(2);
// 	for($i=0;$i<1000;$i++){
// 		$out[$i] = $i>>$i;
// 	}
// 	$msg = json_encode($out);
// 	socket_write($msgsock, $msg, strlen($msg));
// 	socket_close($msgsock);

// 	echo "$count\n";
// }


require('Ws.php');
$ws = new Ws('127.0.0.1', '8888', 10);
$ws->function['add'] = 'user_add_callback';
$ws->function['send'] = 'send_callback';
$ws->function['close'] = 'close_callback';
$ws->start_server();
//回调函数们
function user_add_callback($ws) {
	$data = count($ws->accept);
	send_to_all($data, 'num', $ws);
}
function close_callback($ws) {
	$data = count($ws->accept);
	send_to_all($data, 'num', $ws);
}
function send_callback($data, $index, $ws) {
	var_dump($data,$index) ."\r\n";
	$data = json_encode(array(
		'text' => $data,
		'user' => $index,
		));
	send_to_all($data, 'text', $ws);
}
function send_to_all($data, $type, $ws){
	$res = array(
		'msg' => $data,
		'type' => $type,
		);
	$res = json_encode($res);
	$res = $ws->frame($res);
	foreach ($ws->accept as $key => $value) {
		socket_write($value, $res, strlen($res));
	}
}