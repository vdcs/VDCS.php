<?
trait ManageRefBox
{
	
	public $box=null,$_isBox=false,$_isBoxData=false;
	public function isBox(){return $this->_isBox;}
	public function isBoxData(){return $this->_isBoxData;}
	public function initBox(){if($this->box==null){$this->box=new PageBox();}}
	public function loadBox()
	{
		$this->initBox();
		$this->box->setMode($this->_var['PageMode']);
		//$this->box->setTemplateTree($this->chn->getTemplateTree(''));
		if($this->_var['PageMode']=='list' || $this->_var['PageMode']=='lists'){
			$tablepx=$this->getConfiga('table.px',true);
			//if(len($tablepx)<1) $tablepx=$this->getConfigma('table.px',':');
			//if(len($tablepx)<1) $tablepx=$this->getConfigm('table.px');
			//if(len($tablepx)<1) $tablepx=$this->getConfig('table.px');
			$this->box->setTablePX($tablepx);
			$fieldsvalue=$this->getConfiga('table.fields.value',true);
			$this->box->setTableFieldsValue($fieldsvalue);
			$fieldid=$this->getConfiga('table.field.id',true);
			//debugx($fieldid);
			$this->box->setTableFieldID($fieldid);
			$title=$this->getLanga('title.{action}.'.$this->mode);
			$this->addBoxVar('title',$title);
			$selectoption=$this->getConfiga('handle.select.option');
			$selectoption=$this->ruler->toFilterSelectOption($selectoption);
			$this->addVar('select.options',$this->ui->toOptions($selectoption));
			/*
			$this->addBoxVar('select.option',$selectoption);
			$this->addBoxVar('select.options',$this->ui->toOptions($selectoption));
			$this->addBoxVar('url',$this->getURL('action=view&id=[item:id]'));
			$this->addBoxVar('url.web',$this->toURLCommon('channel='.$this->getChannel().'&id=[item:id]'));
			$this->addBoxVar('url.web.ua',$this->toURLCommon('channel=ua&id=[item:uuid]'));
			$this->addBoxVar('url.view',$this->getURL('action=view&id=[item:id]'));
			$this->addBoxVar('url.edit',$this->getURL('action=edit&id=[item:id]'));
			$this->addBoxVar('url.del',$this->getURL('action=del&id=[item:id]'));
			$this->addBoxVar('url.reply',$this->getURL('action=reply&id=[item:id]'));
			$this->addBoxVar('url.trans',$this->getURL('action=trans&id=[item:id]'));
			$this->addBoxVar('url.update',$this->getURL('action=update&id=[item:id]'));
			$this->addBoxVar('url.clear',$this->getURL('action=clear&id=[item:id]'));
			*/
			if($this->_var['PageMode']=='lists'){
				if($this->p){
					$this->box->setDataTable(DB::queryTable($this->p->getSQL('querys')));
				}
			}
			else{
				if($this->p && $this->p->getTotal()>0){
					$this->box->setDataTable(DB::queryTable($this->p->getSQL('query')));
				}
			}
			$this->_isBoxData=true;
		}
		else if($this->_var['PageMode']=='view'){
			$sql=$this->getConfiga('sql');
			if(!$sql) $sql='select '.$this->getConfig($this->_var['PageMode'].'.field').' from '.$this->getConfig('table.name').' where '.$this->getConfig($this->_var['PageMode'].'.query').' limit 0,1';
			$id=$this->_var['PageID'];
			if($id<1) $id=$this->id;
			$sql=rd($sql,'id',$id);
			$this->addBoxVar('id',$id);
			$this->box->setDatTree(DB::queryTree($sql));
			if($this->box->treeDat->getCount()>0){
				$tmpstr=$this->getConfiga('table.px',true);
				$this->box->addVar('table.px',$tmpstr);
				$tmpstr=$this->getLanga('title.{action}');
				$this->addBoxVar('title',$tmpstr);
				$this->addBoxVar('url',$this->getURL('action=&id=[item:id]'));
				$this->addBoxVar('url.web',$this->toURLCommon('channel='.$this->getChannel().'&id=[item:id]'));
				$this->addBoxVar('url.web.ua',$this->toURLCommon('channel=ua&id=[item:uuid]'));
				$this->addBoxVar('url.view',$this->getURL('action=view&id=[item:id]'));
				$this->addBoxVar('url.edit',$this->getURL('action=edit&id=[item:id]'));
				$this->addBoxVar('url.del',$this->getURL('action=del&id=[item:id]'));
				$this->addBoxVar('url.reply',$this->getURL('action=reply&id=[item:id]'));
				$this->addBoxVar('url.trans',$this->getURL('action=trans&id=[item:id]'));
				$this->addBoxVar('url.update',$this->getURL('action=update&id=[item:id]'));
				$this->addBoxVar('url.clear',$this->getURL('action=clear&id=[item:id]'));
				$this->treeDat->doAppendTree($this->box->treeDat);
				$this->_isBoxData=true;
			}
			else{
				$this->setMessages('!handle',$this->getLangm('error.not.exist'),$this->getURL('action=list'));
			}
		}
		$this->_isBox=true;
		$this->tableData=&$this->box->tableData;
		$this->tableList=&$this->box->tableList;
	}
	
	public function doBoxParse()
	{
		if(!$this->_isBoxData) return;
		if(!$this->_isBox) $this->loadBox();
		$this->box->doParse();
	}
	
	public function getBoxVar($k){return $this->ctl->getVar($k);}
	public function addBoxVar($k,$v)
	{
		$this->box->addVar($k,$v);
		//$this->treeV->addItem($k,$v);
		$this->ctl->addVar($k,$v);
	}
	public function getBoxDat($k){return $this->ctl->getDat($k);}
	public function addBoxDat($k,$v)
	{
		$this->box->addDat($k,$v);
		$this->ctl->addDat($k,$v);
	}
	
}
?>