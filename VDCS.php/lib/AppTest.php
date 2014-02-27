<?
/*
trait ComDemo{}
use ComDemo;
*/
class AppTest
{
	public static $values='AppTest value';
	
	public function set($s){self::$values=$s;}
	public function get(){return self::$values;}
}
?>