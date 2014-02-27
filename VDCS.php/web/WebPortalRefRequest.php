<?
trait WebPortalRefRequest
{
	
	protected $_requestMode=1;	//0=query 1=post
	protected function requestMode($mode){$this->_requestMode=$mode;}
	protected function requestIs(){return $this->_requestMode?isPost():true;}
	protected function request($k){return $this->_requestMode?post($k):query($k);}
	protected function requesti($k){return $this->_requestMode?posti($k):queryi($k);}
	protected function requestn($k){return $this->_requestMode?postn($k):queryn($k);}
	protected function requestx($k){return $this->_requestMode?postx($k):queryx($k);}
	
}
?>