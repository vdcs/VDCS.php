<?
class UaStaff extends UaU
{
	const MinID				= 1000;
	
	const OnlineUpdateTim			= 3600;	//second
	const OnlineUpdateSpace			= 300;	//second
	const OnlineUpdate			= 0;
	const OnlineTotal			= -1;
	const OnlineUserTotal			= -1;
	
	public function __construct()
	{
		parent::__construct();
		$this->rc					= 'staff';
		$this->_cfg['UPDATE_SPACE']			= 100;
		
		$this->_cfg['cookie']				= true;
		$this->_cfg['verify']				= 'name';
		$this->_cfg['verify.pivotal']			= true;
		$this->_cfg['base:TableName']			= 'dba_staff';
		$this->_cfg['base:TablePX']			= '';
		$this->_cfg['base:FieldId']			= 'staffid';
		$this->_cfg['base:FieldNo']			= 'no';
		$this->_cfg['base:FieldName']			= 'name';
		$this->_cfg['base:FieldEmail']			= 'email';
		$this->_cfg['base:FieldGroupid']		= 'groupid';
		$this->_cfg['info:is']				= true;
		$this->_cfg['info:TableName']			= 'dba_staff_info';
	}
}
?>