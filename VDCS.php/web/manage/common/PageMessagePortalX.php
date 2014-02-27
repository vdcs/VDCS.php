<?
class PageMessagePortalX extends ManagePortalBaseX
{
	
	/*
	########################################
	########################################
	*/
	public function doInit()
	{
		$this->ruler->setMode('ignore');
		$this->setInit(false);
		$this->setDebug(false);
	}
	
	public function doLoad()
	{
		$this->theme->setChannel('default');
		$this->theme->setDir('channel');
		$this->theme->setPage('message');
	}
	
	public function doParse()
	{
		global $_cfg,$ctl;
		$_type=$_cfg['entry']['type'];
		//debuga($_cfg);
		$this->setStatus($_type);
		switch($_type){
			case 'noentry':
				$entry_portal='Portal'.ucfirst(PAGE_CHN).ucfirst(PAGE_P).ucfirst(PAGE_M).ucfirst(PAGE_MI).ucfirst(PAGE_X);
				$file=$_cfg['entry']['file'];
				if(!$file){
					$entry=$this->_p_;
					$file=MANAGE_CHANNEL_NOW.'/'.ManageCommon::entryFile(MANAGE_CHANNEL_NOW,PAGE_P,PAGE_M,PAGE_MI,PAGE_X).'['.$entry_portal.']';
				}
				$this->addVar('title',$this->cfg($_type.'.title','message'));
				$this->addVar('message',$this->cfg($_type.'.message','message',array('file'=>$file)));
				$this->addVar('entry.portal',$entry_portal);
				break;
			case 'noportal':
				$this->addVar('title',$this->cfg($_type.'.title','message'));
				$portalo=$this->_chn_.($this->_p_?'.'.$this->_p_:'');
				$this->addVar('message',$this->cfg($_type.'.message','message',array('portal'=>$portalo,'portal.class'=>VDCS_MANAGE_ENTRY_PORTAL)));
				break;
		}
	}
}