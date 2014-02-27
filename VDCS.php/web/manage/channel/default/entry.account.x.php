<?
class PagePortal extends PortalDefaultBaseX
{
	
	protected function parsePassword()
	{
		$this->pages->setFormFile('password');
		$this->loadPagesForm();
		if(!$this->ready(true)) return;
		$this->doPagesParse();
		
		$oldpassword=$this->treeData->getItem('oldpassword');
		$password=$this->treeData->getItem('password');
		$password2=$this->treeData->getItem('password2');
		
		if(!utilCheck::isPassword($oldpassword)) $this->addError('请先输入您当前的登录密码！');
		$checknext=!$this->isErrorCheck();
		if($checknext){
			if(!utilCheck::isPassword($password)) $this->addError('请先输入您需要修改的新密码！');
			elseif($password!=$password2) $this->addError('您输入的 新密码 与 确认密码 不一至！');
		}
		
		$checknext=!$this->isErrorCheck();
		if($checknext){
			$password_md5=utilCoder::toMD5($oldpassword);
			if($password_md5!=$this->ma->getData('password') || !$password_md5) $this->addError('您输入的当前登录密码有错误存在！');
		}
		
		if($this->isRaiseError()) return;
		
		$password_md5=utilCoder::toMD5($password);
		$this->ma->doModifyPassword($password_md5,1);
		
		$this->setMessages($this->chn->getTitle(),'您已成功修改了您的登录密码！',$this->getURL('action'));
		$this->setSucceed();
	}
	
}
?>