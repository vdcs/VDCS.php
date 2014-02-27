<?
class utilURL
{
	
	function toRealA($relative,$referer)
	{
		/**
		* 去除#后面的部分
		*/
		$pos = strpos($relative,'#');
		if($pos >0)$relative = substr($relative,0,$pos);
		/**
		* 检测路径如果是绝对地址直接返回
		*/
		if(preg_match('~^(http|ftp)://~i',$relative)) return $relative;
		/**
		* 解析引用地址，获得协议,主机等信息
		*/
		preg_match('~((http|ftp)://([^/]*)(.*/))([^/#]*)~i', $referer, $preg_rs);
		$parentdir = $preg_rs[1];
		$petrol = $preg_rs[2].'://';
		$host = $preg_rs[3];
		/**
		* 如果以/开头的情况
		*/
		if(preg_match('~^/~i',$relative)) return $petrol.$host.$relative;
		return $parentdir.$relative;
	}
	
}
?>