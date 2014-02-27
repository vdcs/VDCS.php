<?
class UaCorpManage extends UaCorp
{
	use UaRefManage;
	
	public $TableFields	= [
			'base:add'	=> '
				comid,uaid,classid,indid,{@table.px}no,{@table.px}name,{@table.px}email,{@table.px}mobile,{@table.px}groupid,{@table.px}teamid,{@table.px}grade,{@table.px}rank,
				{@table.px}names,{@table.px}location,{@table.px}marks,{@table.px}prop1,{@table.px}prop2,{@table.px}prop3,{@table.px}prop4,{@table.px}prop5,
				{@table.px}credit,{@table.px}money,{@table.px}emoney,{@table.px}points,{@table.px}exp,
				{@table.px}int1,{@table.px}int2,{@table.px}int3,{@table.px}num1,{@table.px}num2,{@table.px}num3,{@table.px}num4,{@table.px}num5,
				{@table.px}shortname,{@table.px}goodname,{@table.px}ename,
				{@table.px}linkman,{@table.px}linkinfo,{@table.px}im,{@table.px}im1,{@table.px}im2,{@table.px}im3,{@table.px}im4,{@table.px}im5,
				{@table.px}avatar,{@table.px}logo,{@table.px}photo,
				{@table.px}company,{@table.px}url,{@table.px}address,{@table.px}postcode,{@table.px}phone,{@table.px}fax,
				{@table.px}foundday,{@table.px}foundin,{@table.px}foundplace,{@table.px}incorporator,{@table.px}regtype,{@table.px}regcapital,{@table.px}regcapitals,{@table.px}productions,{@table.px}empamounts,{@table.px}turnovers,{@table.px}operate_value,
				{@table.px}summary,{@table.px}intro,{@table.px}cause,
				{@table.px}area_country,{@table.px}area_province,{@table.px}area_city,
				sp_code,sp_keyword,
				{@table.px}config,{@table.px}certs,{@table.px}auths,{@table.px}auth_email,{@table.px}auth_mobile,{@table.px}auth_cert,{@table.px}auth_real,
				{@table.px}timed,{@table.px}isauth,{@table.px}islock,{@table.px}status,{@table.px}tim,{@table.px}tim_up
',
			'base:edit'	=> '
				classid,indid,
				{@table.px}names,{@table.px}location,{@table.px}marks,{@table.px}prop1,{@table.px}prop2,{@table.px}prop3,{@table.px}prop4,{@table.px}prop5,
				{@table.px}shortname,{@table.px}goodname,{@table.px}ename,
				{@table.px}linkman,{@table.px}linkinfo,{@table.px}im,{@table.px}im1,{@table.px}im2,{@table.px}im3,{@table.px}im4,{@table.px}im5,
				{@table.px}avatar,{@table.px}logo,{@table.px}photo,
				{@table.px}company,{@table.px}url,{@table.px}address,{@table.px}postcode,{@table.px}phone,{@table.px}fax,
				{@table.px}foundday,{@table.px}foundin,{@table.px}foundplace,{@table.px}incorporator,{@table.px}regtype,{@table.px}regcapital,{@table.px}regcapitals,{@table.px}productions,{@table.px}empamounts,{@table.px}turnovers,{@table.px}operate_value,
				{@table.px}summary,{@table.px}intro,{@table.px}cause,
				{@table.px}area_country,{@table.px}area_province,{@table.px}area_city,
				sp_keyword,
				{@table.px}tim_up
',
			'info:add'	=> '
				comid,{@table.px}defined,
				{@table.px}value1,{@table.px}value2,{@table.px}value3,{@table.px}value4,{@table.px}value5,{@table.px}value6,{@table.px}value7,{@table.px}value8,{@table.px}value9,{@table.px}value10,
				{@table.px}remark
',
			'info:edit'	=> '
				{@table.px}defined,
				{@table.px}value1,{@table.px}value2,{@table.px}value3,{@table.px}value4,{@table.px}value5,{@table.px}value6,{@table.px}value7,{@table.px}value8,{@table.px}value9,{@table.px}value10,
				{@table.px}remark
'
];
	
	
	/*
	########################################
	########################################
	*/
	public function setDataDefault(&$tData)
	{
		$tData->addItem('classid',0);
		$tData->addItem('indid',0);
		//no,name,email,mobile
		$tData->addItem($this->TablePX.'groupid',0);
		$tData->addItem($this->TablePX.'teamid',0);
		$tData->addItem($this->TablePX.'grade',0);
		$tData->addItem($this->TablePX.'rank',0);
		
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
		
		//config,certs
		$tData->addItem($this->TablePX.'auths','0000000000');
		$tData->addItem($this->TablePX.'auth_email',0);
		$tData->addItem($this->TablePX.'auth_mobile',0);
		//$tData->addItem($this->TablePX.'auth_idcard',0);
		$tData->addItem($this->TablePX.'auth_cert',0);
		$tData->addItem($this->TablePX.'auth_real',0);
		
		$tData->addItem($this->TablePX.'timed',0);
		$tData->addItem($this->TablePX.'isauth',0);
		$tData->addItem($this->TablePX.'islock',0);
		$tData->addItem($this->TablePX.'status',1);
		//$tData->addItem($this->TablePX.'timezone',DCS::timezone());
		$tData->addItem($this->TablePX.'tim',DCS::timer());
		$tData->addItem($this->TablePX.'tim_up',0);
	}
	
}
?>