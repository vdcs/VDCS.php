<?
class ChannelCommonFeedbackX extends ChannelCommonBaseX
{
	
	public function doInit1()
	{
		$this->TableName	= 'dbc_feedback';
		$this->TableFields['base:add']='
				channel,rootid,dataid,uurc,uuid,
				m_names,m_location,m_marks,m_prop1,m_prop2,m_prop3,m_prop4,m_prop5,
				m_nickname,m_befrom,m_im,m_im1,m_im2,m_im3,m_im4,m_im5,
				m_realname,m_email,m_mobile,m_call,
				m_company,m_url,m_address,m_postcode,m_phone,m_fax,
				m_title,m_topic,m_icon,m_summary,m_message,m_remark,
				sp_ip,sp_agent,
				m_status,m_tim
				';
		$this->_var['names']='咨询';
	}
	
	public function doLoad()
	{
		$this->loadVcode('channel');
		$this->loadShield();
		
	}
	
	public function doParse()
	{
		$this->doParsePost();
	}
	
	public function doParsePost()
	{
		$this->setStatus('ready');
		
		if(!isPost()) return;
		$this->setStatus('parser');
		
		if($this->ua->isLogin()){
			$ctl->treeData->addItem('uurc',$this->ua->rc);
			$ctl->treeData->addItem('uuid',$this->ua->id);
		}
		
		if(!$ctl->subchannel) $ctl->subchannel=post('channel',50);
		if(utilCheck::isVariable($ctl->subchannel)){
			$ctl->treeData->addItem('channel',$ctl->subchannel);
		}
		$ctl->treeData->addItem('rootid',posti('rootid'));
		$ctl->treeData->addItem('dataid',posti('dataid'));
		$ctl->treeData->addItem('types',post('types',50));
		
		$ctl->treeData->addItem($this->TablePX.'names',post('names',50));
		$ctl->treeData->addItem($this->TablePX.'marks',post('marks',50));
		$ctl->treeData->addItem($this->TablePX.'nickname',post('nickname',50));
		$ctl->treeData->addItem($this->TablePX.'befrom',post('befrom',50));
		$ctl->treeData->addItem($this->TablePX.'im',post('im',50));
		$ctl->treeData->addItem($this->TablePX.'im1',post('qq',50));
		$ctl->treeData->addItem($this->TablePX.'im2',post('msn',50));
		
		$ctl->treeData->addItem($this->TablePX.'realname',post('realname',50));
		$ctl->treeData->addItem($this->TablePX.'email',post('email',100));
		$ctl->treeData->addItem($this->TablePX.'mobile',post('mobile',50));
		$ctl->treeData->addItem($this->TablePX.'call',post('call',20));
		
		$ctl->treeData->addItem($this->TablePX.'company',post('company',250));
		$ctl->treeData->addItem($this->TablePX.'url',post('url',250));
		$ctl->treeData->addItem($this->TablePX.'address',post('address',250));
		$ctl->treeData->addItem($this->TablePX.'postcode',post('postcode',20));
		$ctl->treeData->addItem($this->TablePX.'phone',post('phone',50));
		$ctl->treeData->addItem($this->TablePX.'fax',post('fax',50));
		
		$ctl->treeData->addItem($this->TablePX.'title',post('title',200));
		$ctl->treeData->addItem($this->TablePX.'topic',post('topic',200));
		$ctl->treeData->addItem($this->TablePX.'icon',post('icon',20));
		$ctl->treeData->addItem($this->TablePX.'summary',post('summary',250));
		$ctl->treeData->addItem($this->TablePX.'message',post('message',10000));
		$ctl->treeData->addItem($this->TablePX.'remark',post('remark',10000));
		
		if(len($ctl->treeData->getItem($this->TablePX.'summary'))<1 && len($ctl->treeData->getItem($this->TablePX.'message'))<1 && len($ctl->treeData->getItem($this->TablePX.'remark'))<1){
			$ctl->e->addItem($this->_var['names'].'的 内容 不能为空!');
		}
		if($ctl->treeData->getItem($this->TablePX.'realname') && !utilCheck::isName($ctl->treeData->getItem($this->TablePX.'realname'))){
			$ctl->e->addItem('您的 姓名 为空或不符合规则!');
		}
		if($ctl->treeData->getItem($this->TablePX.'email') &&!utilCheck::isEmail($ctl->treeData->getItem($this->TablePX.'email'))){
			$ctl->e->addItem('您的 电子邮件 为空或不符合规则!');
		}
		if($ctl->treeData->getItem($this->TablePX.'mobile')&&!utilCheck::isMobile($ctl->treeData->getItem($this->TablePX.'mobile'))){
			$ctl->e->addItem('您的 手机号码 为空或不符合规则!');
		}
		
		if($ctl->e->isCheck()){$this->doRaiseError();}
		else{
			$ctl->treeData->addItem('sp_ip',DCS::ip());
			$ctl->treeData->addItem('sp_agent',DCS::agent());
			$ctl->treeData->addItem($this->TablePX.'status',1);
			$ctl->treeData->addItem($this->TablePX.'tim',DCS::timer());
			
			//$this->testo($ctl->treeData,'treeData');
			
			$sql=DB::sqlInsert($this->TableName,$this->TableFields['base:add'],$ctl->treeData);
			//debugx($sql);
			DB::exec($sql);
			
			$this->setStatus('succeed');
		}
	}
	
}
?>