<?php
$ip = '192.168.0.154';
$port = 8881;

$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($sock < 0) {
	echo "socket_create() failed: reason: " . socket_strerror($sock) . "\n";
} else {
	echo "OK.\n";
}
$rel = socket_connect($sock, $ip, $port);
if ($rel < 0) {
	echo "socket_connect() failed.\nReason: ($rel) " . socket_strerror($rel) . "\n";
} else {
	echo "Connection OK\n";
}

$in = json_encode(['a','b','c','d']);
echo "sending...\n";
socket_write($sock, $in, strlen($in));
echo "send ok\n";

$out = socket_read($sock, 8192);

echo "{$out}\n";

socket_close($sock);