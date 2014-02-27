<?
class ChannelCommonSubscribe extends ChannelCommonBaseX
{
	
	public function doLoad()
	{
		$this->TableName='dbc_report';
		$this->TableFields='r_id,channel,rootid,dataid,uuid,r_realname,r_email,r_title,r_url,r_topic,r_remark,sp_ip,sp_agent,r_tim,r_status,r_trans,r_trans_tim,r_trans_remark';
		
		$this->loadVcode('channel');
		$this->loadShield();
		
	}
	
	public function doParse()
	{
		$this->doReportPost();
	}
	
	public function doReportPost()
	{
		$this->theme->setModule('post');
		
		$rootid=queryi('rootid');
		$dataid=queryi('dataid');
		$url=query('url',250);
		$ctl->treeData->addItem('rootid',$rootid);
		$ctl->treeData->addItem('dataid',$dataid);
		$ctl->treeData->addItem('r_url',$url);
		$ctl->treeData->addItem('r_title',query('title',250));
		$ctl->treeData->addItem('r_url',$url);
		if($this->ua->isLogin()) {
			$ctl->treeData->addItem('r_realname',$this->ua->getData('realname'));
			$ctl->treeData->addItem('r_email',$this->ua->('email'));	
		}
		if(!isFormPost()) return;
		
		if(utilCheck::isVariable($ctl->subchannel)){
			$ctl->treeData->addItem('channel',$ctl->subchannel);
		}
		
		$ctl->treeData->addItem('r_realname',post('r_realname',50));
		$ctl->treeData->addItem('r_email',post('r_email',100));
		$ctl->treeData->addItem('r_title',post('r_title',200));
		$ctl->treeData->addItem('r_url',post('r_url',200));
		$ctl->treeData->addItem('r_topic',post('r_topic',200));
		$ctl->treeData->addItem('r_remark',post('r_remark',5000));
		
		if(len($ctl->treeData->getItem("r_title"))<1 || len($ctl->treeData->getItem("r_url"))<1){
			$ctl->e->addItem('报错的页面的 标题 和 地址 不能为空!');
		}
		
		if(len($ctl->treeData->getItem("r_realname"))>0){
			if(!utilCheck::isName($ctl->treeData->getItem('r_realname'))){
				$ctl->e->addItem('您的姓名 的格式不符合规则!');
			}
		}
		
		if(len($ctl->treeData->getItem('r_email'))>0){
			if(!utilCheck::isEmail($ctl->treeData->getItem('r_email'))){
				$ctl->e->addItem('您的电子邮件 的格式不符合规则!');
			}
		}
		
		$this->vcodeFormCheck();
		
		if($ctl->e->isCheck()){$ctl->doRaiseError();}
		else{
			$ctl->treeData->addItem('uuid',$this->ua->id);
			$ctl->treeData->addItem('sp_ip',DCS::ip());
			$ctl->treeData->addItem('sp_agent',DCS::agent());
			$ctl->treeData->addItem('r_status',1);
			$ctl->treeData->addItem('r_tim',DCS::timer());
			$ctl->treeData->addItem('r_trans',0);
			$ctl->treeData->addItem('r_trans_remark','');
			$ctl->treeData->addItem('r_trans_tim',0);
			
			$sql=DB::sqlInsert($this->TableName,$this->TableFields,$ctl->treeData);
			//debugx($sql);
			DB::exec($sql);
			
			//$this->doMessage('','succeed','#ok.password',$this->getURL('action=list'));
			$this->doMessage('common','succeed:报错提交成功','您已成功提交了报错信息，我们会尽快处理，感谢您的配合！',$url);
			//call hmsg.btn("back","返回原页面",ctl.treeData.getItem("r_url"))
		}
	}
	
}
?>