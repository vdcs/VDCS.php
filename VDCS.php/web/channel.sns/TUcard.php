<?
class TUcard
{
	const tableName   = 'dbu_gcard';
	const fields      ='uid,gc_hpost,gc_lpost,gc_sendcomment,gc_commentby,gc_help,gc_helpg,gc_fans,gc_tag,gc_fml,gc_idsp,gc_all_exp,gc_update_tim';
	
	public static function addExpItem($uid,$item,$less=''){
		if(!$uid || !$item) return;
		$_status=self::checkFields($uid,$item);
		if(!$_status) return;
		if(!$less){//如果不是删除
			$value=self::getExpItemVal($uid,$item)+1;
		}else{
			$value=self::getExpItemVal($uid,$item)-1;
			if($value<0) $value=0;
		}
		$sql='update '.self::tableName.' set '.$item.'='.$value.' where uid='.$uid.'';
		DB::query($sql);
	}
	
	public static function getExpItemVal($uid,$item){
		if(!$uid || !$item) return;
		$_status=self::checkFields($uid,$item);
		if(!$_status) return;
		
		$val=DB::queryInt('select '.$item.' from '.self::tableName.' where uid='.$uid.'');
		return $val;
	}
	
	public static function checkFields($uid,$item){
		$_status=1;
		$fieldAry=explode(',',self::fields);
		if(!in_array($item,$fieldAry)) $_status=0;
		//如果不存在该uid的记录uid
		self::checkExists($uid);
		return $_status;
	}
	
	public static function checkExists($uid){
		$userTree=DB::queryTree('select * from dbu_gcard where uid='.$uid.'');
		if($userTree->getCount()<1){
			$userTree->addItem('uid',$uid);
			//$userTree->addItem('gc_grade',0);
			$userTree->addItem('gc_hpost',0);
			$userTree->addItem('gc_lpost',0);
			$userTree->addItem('gc_sendcomment',0);
			$userTree->addItem('gc_commentby',0);
			$userTree->addItem('gc_help',0);
			$userTree->addItem('gc_helpg',0);
			$userTree->addItem('gc_fans',0);
			$userTree->addItem('gc_tag',0);
			$userTree->addItem('gc_fml',0);
			$userTree->addItem('gc_onlinetime',0);
			$userTree->addItem('gc_idsp',0);
			$userTree->addItem('gc_all_exp',0);
			$userTree->addItem('gc_update_tim',0);
			$FieldsAdd='uid,gc_hpost,gc_lpost,gc_sendcomment,gc_commentby,gc_help,gc_helpg,gc_fans,gc_tag,gc_fml,gc_onlinetime,gc_idsp,gc_all_exp,gc_update_tim';
			$sql=DB::sqlInsert('dbu_gcard',$FieldsAdd,$userTree);
			DB::exec($sql);
		}
	}
	
	
	public static function getExpnow($uid)
	{
		$userTree=newTree();
		$userTree=DB::queryTree('select * from dbu_gcard where uid='.$uid.'');
		if($userTree->getCount()<1){
			self::checkExists($uid);
			$userTree->addItem('mainexp_now',0);
			$userTree->addItem('vit',0);
			$userTree->addItem('pro',0);
			$userTree->addItem('fame',0);
			return $userTree;//返回当前等级的exp
		}
		$grade=DB::queryInt('select u_grade from db_user where uid='.$uid.'');//用户目前等级
		
		$gameyear=DB::queryValue('select u_tim1 from db_user where uid='.$uid.'');//用户第一次游戏时间=
		$gameage=date('Y')-$gameyear;//游戏年龄
		if($gameyear==0) $gameage=0;
		$userTree->addItem('gameage',$gameage);
		$userTree->addItem('grade',$grade);
		$hpost=$userTree->getItem('gc_hpost');//高级发布
		$lpost=$userTree->getItem('gc_lpost');//微文
		$sendcomment=$userTree->getItem('gc_sendcomment');//总的评论
		$commentby=$userTree->getItem('gc_commentby');//总的被评论
		$help=$userTree->getItem('gc_help');//回复求助
		$helpg=$userTree->getItem('gc_helpg');//求助被采纳
		$fans=$userTree->getItem('gc_fans');//总的粉丝
		$tag=$userTree->getItem('gc_tag');//总的贴标签次数
		$fml=$userTree->getItem('gc_fml');//我呸
		$onlinetime=DB::queryInt('select u_online from db_user where uid='.$uid.'');//在线时长
		$userTree->addItem('onlinetime',$onlinetime/3600);
		$IDSP=$userTree->getItem('gc_idsp');//特殊身份
		$allexp=$userTree->getItem('gc_all_exp');//已经累加的exp值
		
		//升级之后，以上所有数据也清零,allexp累加
		
		$vit=3*$hpost+2*$lpost+$sendcomment+$commentby+$help+5*$helpg+$fans+$tag+$fml+$onlinetime/3600+$IDSP;//当前等级活跃度
		$pro=$tag+5*$helpg+$IDSP+3*$hpost+5*$commentby+$fml;//当前等级专业度
		$fame=$fans+$commentby+$IDSP;//当前等级声望
		
		
		
		$coe=0;//每个等级的系数
		switch($grade){
			case '1':
				$coe=1;
				break;
			case '2':
				$coe=0.8;
				break;
			case '3':
				$coe=0.5;
				break;
			case '4':
				$coe=0.1;
				break;
			case '5':
				$coe=0.001;
				break;
			case '6':
				$coe=0.00001;
				break;			
		}
		$vit=$coe*$vit;
		$pro=$coe*$pro;
		$fame=$coe*$fame;
		if($vit>100) $vit=100;
		if($pro>100) $pro=100;
		if($fame>100) $fame=100;
		$mainexp_now=$vit+$pro+$fame;
		$userTree->addItem('mainexp_now',$mainexp_now);
		$userTree->addItem('vit',$vit);
		$userTree->addItem('pro',$pro);
		$userTree->addItem('fame',$fame);
		return $userTree;//返回当前等级的exp
	}
	
	public static function checkUserGrade($uid){
		$sql='select u_sign from db_user_info where uid='.$uid.'';
		$userTree=DB::queryTree($sql);
		$is_sign=$userTree->getItem('u_sign');
		//$is_ava=$userTree->getItem('u_avatar');
		//$is_follow_tag=DB::queryInt('select count(*) from db_tags_follow where uuid='.$uid.' and status=1');
		$is_gameage=DB::queryInt('select u_tim1 from db_user where uid='.$uid.'');
		//debugx($is_follow_tag);
		if($is_sign && $is_gameage>0){
			DB::query('update db_user set u_grade=1 where uid='.$uid.'');
		}
	}
}

?>