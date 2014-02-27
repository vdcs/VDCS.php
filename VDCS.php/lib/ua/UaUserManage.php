<?
class UaUserManage extends UaUser
{
	use UaRefManage;
	
	public $MinID				= 10000;
	
	protected $TableFields	= [
			'base:add'	=> '
				uid,uaid,{@table.px}no,{@table.px}name,{@table.px}email,{@table.px}mobile,{@table.px}idtype,{@table.px}idcard,{@table.px}groupid,{@table.px}teamid,{@table.px}grade,{@table.px}rank,{@table.px}gender,{@table.px}birthday,
				{@table.px}home,{@table.px}names,{@table.px}location,{@table.px}marks,{@table.px}sign,{@table.px}prop1,{@table.px}prop2,{@table.px}prop3,{@table.px}prop4,{@table.px}prop5,
				{@table.px}credit,{@table.px}money,{@table.px}emoney,{@table.px}points,{@table.px}exp,{@table.px}int1,{@table.px}int2,{@table.px}int3,{@table.px}num1,{@table.px}num2,{@table.px}num3,{@table.px}num4,{@table.px}num5,
				{@table.px}online,{@table.px}onlines,
				{@table.px}config,{@table.px}certs,{@table.px}auths,{@table.px}auth_email,{@table.px}auth_mobile,{@table.px}auth_idcard,{@table.px}auth_cert,{@table.px}auth_real,{@table.px}timed,{@table.px}isauth,{@table.px}islock,{@table.px}status,{@table.px}timezone,{@table.px}tim,{@table.px}tim_up
',
			'base:edit'	=> '
				{@table.px}gender,{@table.px}birthday,
				{@table.px}home,{@table.px}names,{@table.px}location,{@table.px}marks,{@table.px}sign,{@table.px}prop1,{@table.px}prop2,{@table.px}prop3,{@table.px}prop4,{@table.px}prop5,
				{@table.px}timezone,{@table.px}tim_up
',
			'info:add'	=> '
				uid,{@table.px}defined,{@table.px}realname,{@table.px}aliasname,{@table.px}firstname,{@table.px}lastname,{@table.px}avatar,{@table.px}face,{@table.px}photo,
				{@table.px}nickname,{@table.px}befrom,{@table.px}im,{@table.px}im1,{@table.px}im2,{@table.px}im3,{@table.px}im4,{@table.px}im5,{@table.px}homepage,{@table.px}sign,
				{@table.px}company,{@table.px}url,{@table.px}address,{@table.px}postcode,{@table.px}phone,{@table.px}fax,
				{@table.px}summary,{@table.px}area_country,{@table.px}area_province,{@table.px}area_city
',
			'info:edit'	=> '
				{@table.px}defined,{@table.px}realname,{@table.px}aliasname,{@table.px}firstname,{@table.px}lastname,{@table.px}avatar,{@table.px}face,{@table.px}photo,
				{@table.px}nickname,{@table.px}befrom,{@table.px}im,{@table.px}im1,{@table.px}im2,{@table.px}im3,{@table.px}im4,{@table.px}im5,{@table.px}homepage,{@table.px}sign,
				{@table.px}company,{@table.px}url,{@table.px}address,{@table.px}postcode,{@table.px}phone,{@table.px}fax,
				{@table.px}summary,{@table.px}area_country,{@table.px}area_province,{@table.px}area_city
'
];
	
	
	/*
	########################################
	########################################
	*/
	public function setDataDefault(&$tData)
	{
		//no,name,email,mobile,idtype,idcard
		$tData->addItem($this->TablePX.'groupid',0);
		$tData->addItem($this->TablePX.'teamid',0);
		$tData->addItem($this->TablePX.'grade',0);
		$tData->addItem($this->TablePX.'rank',0);
		
		$tData->addItem($this->TablePX.'gender',0);
		$tData->addItem($this->TablePX.'birthday','0000-00-00');
		
		//names,location,marks,prop1,prop2,prop3,prop4,prop5
		$tData->addItem($this->TablePX.'credit',0);
		$tData->addItem($this->TablePX.'money',0);
		$tData->addItem($this->TablePX.'emoney',0);
		$tData->addItem($this->TablePX.'points',0);
		$tData->addItem($this->TablePX.'exp',0);
		
		$tData->addItem($this->TablePX.'int1',0);
		$tData->addItem($this->TablePX.'int2',0);
		$tData->addItem($this->TablePX.'int3',0);
		$tData->addItem($this->TablePX.'num1',0);
		$tData->addItem($this->TablePX.'num2',0);
		$tData->addItem($this->TablePX.'num3',0);
		$tData->addItem($this->TablePX.'num4',0);
		$tData->addItem($this->TablePX.'num5',0);
		
		$tData->addItem($this->TablePX.'online',0);
		$tData->addItem($this->TablePX.'onlines',0);
		//config,certs
		$tData->addItem($this->TablePX.'auths','0000000000');
		$tData->addItem($this->TablePX.'auth_email',0);
		$tData->addItem($this->TablePX.'auth_mobile',0);
		$tData->addItem($this->TablePX.'auth_idcard',0);
		$tData->addItem($this->TablePX.'auth_cert',0);
		$tData->addItem($this->TablePX.'auth_real',0);
		
		$tData->addItem($this->TablePX.'timed',0);
		$tData->addItem($this->TablePX.'isauth',0);
		$tData->addItem($this->TablePX.'islock',0);
		$tData->addItem($this->TablePX.'status',1);
		$tData->addItem($this->TablePX.'timezone',DCS::timezone());
		$tData->addItem($this->TablePX.'tim',DCS::timer());
		$tData->addItem($this->TablePX.'tim_up',0);
	}
	
}
?>