<?php
/**
 * 工厂模式
 */

interface Comput{
	public function getResult();
}

class Operation{
	protected $n1=0;
	protected $n2=0;
	protected $res=0;

	public function __construct($num1, $num2) {
		$this->n1 = $num1;
		$this->n2 = $num2;
	}

	public function clearRes(){
		$this->res = 0;
	}
}

class Add extends Operation implements Comput{
	public function getResult(){
		return $this->res = $this->n1 + $this->n2;
	}
}

class Sub extends Operation implements Comput{
	public function getResult(){
		return $this->res = $this->n1 - $this->n2;
	}
}

class OperationFactory{
	private static $obj = NULL;
	public static function createFactory($num1, $num2, $type){
		try {
			switch ($type) {
				case '+':
					self::$obj = new Add($num1, $num2);
					break;
				case '-':
					self::$obj = new Sub($num1, $num2);
					break;				
				default:
					throw new Exception("Error Processing Request", 1);					
					break;
			}
			if(self::$obj instanceof Comput){
				self::$obj->getResult();
			}
			return self::$obj;
		} catch (Exception $e) {
			echo $e->getMessage();
			exit;
		}		
	}
}

echo OperationFactory::createFactory(21,20,'-')->getResult();