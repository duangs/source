<?php
/**
 * 单例模式
 */
class Test{
	private static $_instance = NULL;

	private function __construct(){}

	public function __clone(){}

	public static function getInstance(){
		if(!(self::$_instance instanceof self)){
			echo 'new self()<br/>';
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function test(){
		echo '单例调用<br/>';
	}

}

$test = Test::getInstance();
$test->test();
$test1 = Test::getInstance();
$test1->test();
$test2 = Test::getInstance();
$test2->test();