<?
trait utilRefError
{
	protected $_errors=array();
	
	public function isError($value=null)
	{
		if(!is_null($value)) $this->_errors['is']=$value;
		return $this->_errors['is'];
	}
	public function setError($value=null){return $this->isError($value);}
	public function getErrorValue($key,$value=null)
	{
		if(!$key) $key='value';
		if(!is_null($value)) $this->_errors[$key]=$value;
		return $this->_errors[$key];
	}
	public function setErrorValue($key,$value=null){return $this->getErrorValue($key,$value);}
	public function getErrorCode($value=null)
	{
		if(!is_null($value)) $this->_errors['code']=$value;
		return $this->_errors['code'];
	}
	public function setErrorCode($value=null){return $this->getErrorCode($value);}
	public function getErrorType($value=null)
	{
		if(!is_null($value)) $this->_errors['type']=$value;
		return $this->_errors['type'];
	}
	public function setErrorType($value=null){return $this->getErrorType($value);}
	public function getErrorMessage($value=null)
	{
		if(!is_null($value)) $this->_errors['message']=$value;
		return $this->_errors['message'];
	}
	public function setErrorMessage($value=null){return $this->getErrorMessage($value);}
	public function getErrorDesc($value=null)
	{
		if(!is_null($value)) $this->_errors['desc']=$value;
		return $this->_errors['desc'];
	}
	public function setErrorDesc($value=null){return $this->getErrorDesc($value);}
	
}

/*
trait utilRefError
{
	private $_iserror=false,$_error='',$_error_code=-1,$_error_type='',$_error_message='';
	
	public function isError(){return $this->_iserror;}
	public function getError(){return $this->_error;}
	public function getErrorCode(){return $this->_error_code;}
	public function getErrorType(){return $this->_error_type;}
	public function getErrorMessage(){return $this->_error_message;}
	
}
*/
/*
class Talker {
	use A, B {
		B::smallTalk insteadof A;
		A::bigTalk insteadof B;
	}
}

class Aliased_Talker {
	use A, B {
		B::smallTalk insteadof A;
		A::bigTalk insteadof B;
		B::bigTalk as talk;
	}
}

trait HelloWorld {
	public function sayHello() {
		echo 'Hello World!';
	}
}

// 修改 sayHello 的访问控制
class MyClass1 {
	use HelloWorld { sayHello as protected; }
}

// 给方法一个改变了访问控制的别名
// 原版 sayHello 的访问控制则没有发生变化
class MyClass2 {
	use HelloWorld { sayHello as private myPrivateHello; }
}
*/

