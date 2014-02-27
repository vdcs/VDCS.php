<?
/*
function initUsere()
{
	global $usere;
	//dcsLoadModule('UaUsere');
	$usere=new UaUserE();
	$usere->init();
}
*/
class UaUser extends UaU
{
	const RANK_NUM_BASE			= 4;
	const RANK_NUM_RADIX			= 5;
	const RANK_NUM_MAX			= 80;
	
	const DEFAULT_POPEDOM_CONFIG		= '00000000000000000000000000000000000000000000000000';
	const DEFAULT_UPOPEDOM_LENGTH		= 50;
	
	const OnlineUpdateTim			= 3600;	//second
	const OnlineUpdateSpace			= 30;	//second
	const OnlineUpdate			= 0;
	const OnlineTotal			= -1;
	const OnlineUserTotal			= -1;
	
	public function __construct()
	{
		parent::__construct();
		$this->rc					= 'user';
		$this->_cfg['UPDATE_SPACE']			= 100;
		
		$this->_cfg['cookie']				= true;
		$this->_cfg['verify']				= 'name';
		$this->_cfg['verify.pivotal']			= true;
		$this->_cfg['base:TableName']			= 'db_user';
		$this->_cfg['base:TablePX']			= '';
		$this->_cfg['base:FieldId']			= 'uid';
		$this->_cfg['base:FieldNo']			= 'no';
		$this->_cfg['base:FieldName']			= 'name';
		$this->_cfg['base:FieldEmail']			= 'email';
		$this->_cfg['base:FieldMobile']			= 'mobile';
		$this->_cfg['base:FieldGroupid']		= 'groupid';
		$this->_cfg['info:is']				= true;
		$this->_cfg['info:TableName']			= 'db_user_info';
	}
}
?>