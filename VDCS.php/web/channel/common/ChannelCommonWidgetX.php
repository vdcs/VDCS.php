<?
class ChannelCommonWidgetX extends ChannelCommonBaseX
{
	const EV_DIGG_KEY='digg';
	const EV_DIGG_PEAK=20;
	const EV_DIGG_SYMBOL=',';

	public function doLoad()
	{
		$this->_module=queryx('module');
		$this->rootid=queryi('rootid');

		$this->addVar('module',$this->_module);
		$this->addVar('rootid',$this->rootid);
	}

	protected function isRoot()
	{
		if(!$this->_module || $this->rootid<1){
			$this->setStatus('root');
			return false;
		}
		return true;
	}

	protected function parseSyns()
	{
		$res=query('res');
		if(inp($res,'digg')>0) $this->parseDigg();
		if(inp($res,'relate')>0) $this->parseRelate();
	}

	protected function parseDigg()
	{
		if(!$this->isRoot()) return;
		$type=queryx('type');
		$this->addVar('digg.type',$type);
		$status_=1;
		$vcodes=DCS::sessionGet(self::EV_DIGG_KEY);
		$vcode=$this->_module.$this->rootid;
		if(!utilStrings::isExtentValue($vcodes,$vcode,self::EV_DIGG_SYMBOL)){
			$status_=0;
			if(inp('agree,oppose',$type)>0){
				$this->cfg->setChannel($this->_module);
				$this->cfg->doChannelInit();
				$diggField='sp_poll_'.$type;
				$sql='update '.$this->cfg->chn->getSQLStruct('table.name').' set '.$diggField.'='.$diggField.'+1 where '.$this->cfg->chn->getSQLStruct('table.field.id').'='.$this->rootid;
				DB::exec($sql);
				$vcodes=utilStrings::toExtentAppend($vcodes,$vcode,self::EV_DIGG_SYMBOL,self::EV_DIGG_PEAK);
				DCS::sessionSet(self::EV_DIGG_KEY,$vcodes);
				$status_=1;
			}
		}
		$this->addVar('digg.status',$status_);
		$this->setSucceed();
	}

	protected function parseRelate()
	{
		if(!$this->isRoot()) return;
		$this->cfg->setChannel($this->_module);
		$this->cfg->doChannelInit();
		$tableName=$this->cfg->chn->getSQLStruct('table.name');
		$tablePX=$this->cfg->chn->getSQLStruct('table.px');
		$fieldID=$this->cfg->chn->getSQLStruct('table.field.id');
		//previous
		$sql='select '.$fieldID.','.$tablePX.'topic from '.$tableName.' where '.$tablePX.'status=1 and '.$fieldID.'<'.$this->rootid.' order by '.$fieldID.' desc limit 0,1';
		$this->treeDat=DB::queryTree($sql);
		if($this->treeDat->getCount()>0){
			$this->addVar('previous.id',$this->treeDat->getItem($fieldID));
			$this->addVar('previous.topic',$this->treeDat->getItem($tablePX.'topic'));
			$this->addVar('previous.linkurl',$this->cfg->toLinkurl('view','id='.$this->treeDat->getItem($fieldID)));
		}
		//next
		$sql='select '.$fieldID.','.$tablePX.'topic from '.$tableName.' where '.$tablePX.'status=1 and '.$fieldID.'>'.$this->rootid.' order by '.$fieldID.' desc limit 0,1';
		$this->treeDat=DB::queryTree($sql);
		if($this->treeDat->getCount()>0){
			$this->addVar('next.id',$this->treeDat->getItem($fieldID));
			$this->addVar('next.topic',$this->treeDat->getItem($tablePX.'topic'));
			$this->addVar('next.linkurl',$this->cfg->toLinkurl('view','id='.$this->treeDat->getItem($fieldID)));
		}
		$this->setSucceed();
	}

}
