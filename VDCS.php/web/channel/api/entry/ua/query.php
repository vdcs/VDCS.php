<?
class apiEntry extends apiBase
{
	
	public function parse()
	{
		$this->parseQuery();
	}
	
	public function parseQuery()
	{
		$by=queryx('by');
		$value=query('value');
		if(!$by){
			if(utilCheck::isEmail($value)) $by='email';
			elseif(utilCheck::isMobile($value)) $by='mobile';
			elseif(utilCheck::isName($value)) $by='name';
			else $by='uid';
		}
		debugxx('by='.$by.', value='.$value);
		$this->addVar('query.by',$by);
		$this->addVar('query.value',$value);
		if(len($value)<1){
			$this->setStatus('data');
			return;
		}

		switch($by){
			case 'email'		: $sqlQuery=$this->ua->sqlQuery('email',$value);break;
			case 'mobile'		: $sqlQuery=$this->ua->sqlQuery('mobile',$value);break;
			case 'name'		: $sqlQuery=$this->ua->sqlQuery('name',$value);break;
			case 'uid':
			default			: $sqlQuery=$this->ua->setID(toi($value));break;
		}
		$uid=$this->ua->queryField('id',$sqlQuery);
		if(!$uid){
			$this->setStatus('noexist');
			return;
		}
		if($this->isRaiseError()) return;
		
		$this->ua->setID($uid);
		$isload=$this->ua->dataLoader(1);

		global $cfg;
		$treeUa=$this->ua->getDataTree();
		$treeUa->addItem('_avatar',DCS::url($cfg->getLinkURL('account','avatar','res=s&id='.$treeUa->getItem('_id'))));
		$treeUa->addItem('_avatar_tpl',DCS::url($cfg->getLinkURL('account','avatar','res={type}&id={id}')));
		$this->addVarTree($treeUa,'ua.');

		$this->setSucceed();
	}
	
}
