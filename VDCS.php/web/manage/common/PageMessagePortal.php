<?
class PageMessagePortal extends ManagePortalBase
{
	
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
		$this->theme->setModule('');
		$this->theme->setModulei('');
	}
	
	public function doParse()
	{
		global $_cfg,$ctl;
		$_type=$_cfg['entry']['type'];
		//debuga($_cfg['entry']);
		switch($_type){
			case 'noentry':
				$file=$_cfg['entry']['file'];
				if(!$file){
					$entry=$this->_p_;
					$file=MANAGE_CHANNEL_NOW.'/'.ManageCommon::entryFile(MANAGE_CHANNEL_NOW,PAGE_P,PAGE_M,PAGE_MI,PAGE_I,PAGE_X).'[Portal'.ucfirst(PAGE_CHN).ucfirst(PAGE_P).ucfirst(PAGE_M).ucfirst(PAGE_MI).ucfirst(PAGE_I).ucfirst(PAGE_X).']';
				}
				$ctl->addDTML('title',$this->cfg($_type.'.title','message'));
				$ctl->addDTML('message',$this->cfg($_type.'.message','message',array('file'=>$file)));
				//debugTree($ctl->treeDTML);
				break;
			case 'noportal':
				$ctl->addDTML('title',$this->cfg($_type.'.title','message'));
				$portalo=$this->_chn_.($this->_p_?'.'.$this->_p_:'');
				$ctl->addDTML('message',$this->cfg($_type.'.message','message',array('portal'=>$portalo,'portal.class'=>VDCS_MANAGE_ENTRY_PORTAL)));
				break;
		}
		$ctl->addDTML('tip.title',$ctl->treeDTML->getItem('title'));
		$ctl->addDTML('tip.message',$ctl->treeDTML->getItem('message'));
	}
}