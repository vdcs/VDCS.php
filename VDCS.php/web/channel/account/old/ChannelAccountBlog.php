<?
class ChannelAccountBlog extends ChannelAccountBlogBase
{
	protected $ngo=false;
	
	public function doLoad()
	{
		
		$this->loadVcode($this->channelKey.'.info');
		$this->loadShield();
		
	}
	
	public function doParse()
	{
		global $cfg,$ctl;
		if($this->_var['is']){
			if(ins('create,no',$ctl->action)>0) $ctl->action='';
		}
		else{
			if(ins('create,no',$ctl->action)<1) $ctl->action='no';
		}
		switch($ctl->action){
			case 'no':
				$this->theme->setModule('no');
				$this->doParseNo();
				break;
			case 'create':
				$cfg->setTitle('sub',$this->chn->v('title.create'));
				$this->theme->setModule('create');
				$this->doParseCreate();
				break;
			default:
				$this->theme->setModule('info');
				$this->doParseInfo();
				break;
		}
	}
	
	public function doParseNo()
	{
		
	}
	
	public function doParseCreate()
	{
		global $cfg,$ctl;
		if($this->chn->cfgi('create')!=1){
			$this->doMessage('this','info:提示信息','暂停'.$this->Names.'申请服务！感谢您的理解和支持。',$url);
			return;
		}
		
		$this->keyid=$this->ua->id;
		if(!isFormPost()) return;
		
		$classid=posti('classid');
		$ctl->treeData->addItem('classid',$classid);
		
		$ctl->treeData->addItem('b_name',post('b_name',200));
		$ctl->treeData->addItem('b_desc',post('b_desc',250));
		$ctl->treeData->addItem('b_summary',post('b_summary',250));
		
		if($classid<1) $ctl->e->addItem(''.$this->Names.' 分类 不能为空!');
		if(len($ctl->treeData->getItem('b_name'))<1) $ctl->e->addItem(''.$this->Names.' 名称 不能为空!');
		
		if(!$ctl->e->isCheck()){
			$sql=DB::sqlSelect($this->TableName,'count','*','blogid='.$this->keyid,'');
			//debugx($sql);
			if(DB::queryInt($sql)>0) $ctl->e->addItem(''.$this->Names.' ID 冲突！请与管理员联系。');
		}
		
		$this->vcodeFormCheck();
		$this->shieldFormCheck(true,'b_name,b_desc,b_summary');
		
		if($ctl->e->isCheck()){
			$ctl->doRaiseError();
		}
		else{
			$_status=$this->chn->cfgi('create.status');
			$ctl->treeData->addItem('blogid',$this->keyid);
			$ctl->treeData->addItem('uid',$this->ua->id);
			$ctl->treeData->addItem('b_status',$_status);
			$ctl->treeData->addItem('b_tim',DCS::timer());
			$ctl->treeData->addItem('b_tim_up',0);
			
			$this->TableFieldsAdd='blogid,classid,uuid,b_name,b_desc,b_summary,b_status,b_tim,b_tim_up';
			$sql=DB::sqlInsert($this->TableName,$this->TableFieldsAdd,$ctl->treeData);
			//debugx($sql);
			DB::exec($sql);
			
			if($_status==1){
				$this->doMessage('this','succeed:操作成功','您已成功开通了您的'.$this->Names.'！感谢您的使用。',$url);
			}
			else{
				$this->doMessage('this','succeed:操作成功','您已成功提交了'.$this->Names.'开通申请！请耐心等待系统审核！',$url);
			}
		}
	}
	
	public function doParseInfo()
	{
		global $cfg,$ctl,;
		$ctl->treeData->doAppendTree($this->treeBaseRS);
		if(!isFormPost()) return;
		
		$classid=posti('classid');
		$ctl->treeData->addItem('classid',$classid);
		
		$ctl->treeData->addItem('b_name',post('b_name',200));
		$ctl->treeData->addItem('b_desc',post('b_desc',250));
		$ctl->treeData->addItem('b_summary',post('b_summary',250));
		
		if($classid<1) $ctl->e->addItem(''.$this->Names.' 分类 不能为空!');
		if(len($ctl->treeData->getItem('b_name'))<1) $ctl->e->addItem(''.$this->Names.' 名称 不能为空!');
		
		$this->vcodeFormCheck();
		$this->shieldFormCheck(true,'b_name,b_desc,b_summary');
		
		if($ctl->e->isCheck()){
			$ctl->doRaiseError();
		}
		else{
			$ctl->treeData->addItem('blogid',$this->keyid);
			$ctl->treeData->addItem('b_tim_up',DCS::timer());
			
			$this->TableFieldsEdit='classid,b_name,b_desc,b_summary,b_tim_up';
			$sql=DB::sqlUpdate($this->TableName,$this->TableFieldsEdit,$ctl->treeData,$this->_var['sqlquery']);
			//debugx($sql);
			DB::exec($sql);
			
			$this->doMessage('this','succeed:操作成功','您已成功修改了您的'.$this->Names.'信息！感谢您的使用。',$url);
		}
	}
	
}
?>