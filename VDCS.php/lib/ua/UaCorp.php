<?
function initCorpe()
{
	global $corpe;
	//dcsLoadModule('UaCorpE');
	$corpe=new UaCorpE();
	$corpe->init();
}

class UaCorp extends UaU
{
	const MinID				= 10000;
	
	public function __construct()
	{
		parent::__construct();
		$this->rc					= 'corp';
		$this->_cfg['UPDATE_SPACE']			= 100;
		
		$this->_cfg['cookie']				= true;
		$this->_cfg['verify']				= 'name';
		$this->_cfg['verify.pivotal']			= true;
		$this->_cfg['base:TableName']			= 'dba_corp';
		$this->_cfg['base:TablePX']			= '';
		$this->_cfg['base:FieldId']			= 'corpid';
		$this->_cfg['base:FieldNo']			= 'no';
		$this->_cfg['base:FieldName']			= 'name';
		$this->_cfg['base:FieldEmail']			= 'email';
		$this->_cfg['base:FieldGroupid']		= 'groupid';
		$this->_cfg['info:is']				= true;
		$this->_cfg['info:TableName']			= 'dba_corp_info';
	}
}
?>