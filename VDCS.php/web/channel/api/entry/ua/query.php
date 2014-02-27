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

		$this->addVarTree($this->ua->getDataTree(),'ua.');
		$this->setSucceed();
	}
	
}
