<?
class UaAccountManage extends UaAccount
{
	use UaRefManage;
	
	public $MinID				= 10000;
	
	protected $TableFields	= [
			'base:add'	=> '
				uid,uaid,types,no,name,email,mobile,idtype,idcard,groupid,grade,rank,gender,birthday,
				names,location,marks,
				credit,money,emoney,points,exp,
				config,auths,auth_real,auth_email,auth_mobile,auth_idcard,auth_cert,
				isauth,islock,status,timezone,tim,tim_up,tim_last
',
			'base:edit'	=> '
				gender,birthday,
				names,location,marks,
				timezone,tim_up
',
			'info:add'	=> '
				uid,realname,aliasname,firstname,lastname,call,nickname,
				befrom,im,im1,im2,im3,im4,im5,homepage,sign,
				avatar,face,photo,
				company,url,address,postcode,phone,fax,
				summary,intro,
				area_country,area_province,area_city
',
			'info:edit'	=> '
				realname,aliasname,firstname,lastname,call,nickname,
				befrom,im,im1,im2,im3,im4,im5,homepage,sign,
				avatar,face,photo,
				company,url,address,postcode,phone,fax,
				summary,intro,
				area_country,area_province,area_city
'
];
	
	
	/*
	########################################
	########################################
	*/
	public function setDataDefault(&$tData)
	{
		//no,name,email,mobile,idtype,idcard
		$this->treeData->addItem($this->TablePX.'groupid',0);
		$this->treeData->addItem($this->TablePX.'teamid',0);
		$this->treeData->addItem($this->TablePX.'grade',0);
		$this->treeData->addItem($this->TablePX.'rank',0);
		
		$this->treeData->addItem($this->TablePX.'gender',0);
		$this->treeData->addItem($this->TablePX.'birthday','0000-00-00');
		//names,location,marks,prop1,prop2,prop3,prop4,prop5
		$this->treeData->addItem($this->TablePX.'credit',0);
		$this->treeData->addItem($this->TablePX.'money',0);
		$this->treeData->addItem($this->TablePX.'emoney',0);
		$this->treeData->addItem($this->TablePX.'points',0);
		$this->treeData->addItem($this->TablePX.'exp',0);
		
		$this->treeData->addItem($this->TablePX.'int1',0);
		$this->treeData->addItem($this->TablePX.'int2',0);
		$this->treeData->addItem($this->TablePX.'int3',0);
		$this->treeData->addItem($this->TablePX.'num1',0);
		$this->treeData->addItem($this->TablePX.'num2',0);
		$this->treeData->addItem($this->TablePX.'num3',0);
		$this->treeData->addItem($this->TablePX.'num4',0);
		$this->treeData->addItem($this->TablePX.'num5',0);
		
		$this->treeData->addItem($this->TablePX.'online',0);
		$this->treeData->addItem($this->TablePX.'onlines',0);
		//config,certs
		$this->treeData->addItem($this->TablePX.'auths','0000000000');
		$this->treeData->addItem($this->TablePX.'auth_email',0);
		$this->treeData->addItem($this->TablePX.'auth_mobile',0);
		$this->treeData->addItem($this->TablePX.'auth_idcard',0);
		$this->treeData->addItem($this->TablePX.'auth_cert',0);
		$this->treeData->addItem($this->TablePX.'auth_real',0);
		
		$this->treeData->addItem($this->TablePX.'timed',0);
		$this->treeData->addItem($this->TablePX.'isauth',0);
		$this->treeData->addItem($this->TablePX.'islock',0);
		$this->treeData->addItem($this->TablePX.'status',1);
		$this->treeData->addItem($this->TablePX.'timezone',DCS::timezone());
		$this->treeData->addItem($this->TablePX.'tim',DCS::timer());
		$this->treeData->addItem($this->TablePX.'tim_up',0);
		$this->treeData->addItem($this->TablePX.'tim_last',0);
	}
	
}
?>