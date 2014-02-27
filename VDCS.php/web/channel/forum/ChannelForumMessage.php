<?
class ChannelForumMessage extends ChannelForumBase
{
	public $key, $treeMessage, $tableMessage;
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doLoad()
	{
		$this->cfg->setTitle('now',$this->cfg->v('title.message'));
		
		$this->key=query('key');
		if (len($this->key)<1) $this->key=VDCS_Client::getCookies('message.key');
	}
	
	##############################################
	##############################################
	public function doParse()
	{
		$dataPath=appFilePath('common.channel/'.$this->cfg->getChannel().'/data.message');
		if(!isFile($dataPath)){
			
			return false;
		}
		
		$this->tableMessage=getFile2Table($dataPath);
		$this->tableMessage->doBegin();
		while($this->tableMessage->isNext()){
			$this->tableMessage->setItemValue('_uid',$this->tableMessage->getItemValue('uuid'));
			
			if ($this->tableMessage->getItemValue('key')==$this->key){
				$this->treeMessage=$this->tableMessage->getItemTree();
				return;
			}
		}
		
	}
	
	public function doThemeCache()
	{
		parent::doThemeCache();
		$this->theme->output=CommonTheme::toCacheFilterTree($this->theme->output,'message','cpo.treeMessage','');
	}
	
	
}
?>