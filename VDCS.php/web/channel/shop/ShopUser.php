<?
class ShopUser
{
	protected $treeUser=null,$tableLevel=null;
	protected $var_score,$var_discount;
	protected $var_isInit=false;
	protected $TABLE_NAME,$TABLE_FIELDS;
	
	protected $ua;
	public function __construct()
	{
		$this->ua=&$GLOBALS['ua'];
		
		$this->treeUser=newTree();
		$this->tableLevel=newTable();
		
		$this->uid=-1;
		$this->var_score=0;
		$this->var_discount=100;
		$this->var_isInit=false;
		
		$this->TableName='db_mall';
		$this->TABLE_NAME='db_usera';
		$this->TABLE_FIELDS='uid,realname,score,scores,level,multiple,discount,status,tim,tim_updated';
	}
	public function __destruct()
	{
		unsetr($this->treeUser,$this->tableLevel);
	}
	
	
	public function setuid($id=-1,$name='')
	{
		if($id<1 || len($name)<1){
			$this->uid=$this->ua->id;
			$this->username=$this->ua->name;
			return;
		}
		$this->uid=$id;
		$this->username=$name;
	}
	
	public function getTree()
	{
		//$reTree=newTree();
		//$reTree->setArray($this->treeUser->getArray());
		return $this->treeUser;
	}
	public function getLevelTable()
	{
		//$reTable=newTable();
		//$reTable->setArray($this->tableLevel->getArray());
		return $this->tableLevel;
	}
	
	public function getScore(){return $this->treeUser->getItemInt('score');}
	public function getDiscount(){return $this->var_discount;}
	public function getMultiple(){return $this->treeUser->getItemNum('multiple');}
	
	
	/*
	########################################
	########################################
	*/
	public function doInit()
	{
		global $cfg;
		if($this->var_isInit) return;
		if(len($this->channel)<1) $this->channel=$cfg->getChannel();
		$this->TableName=$cfg->chn->getSQLStruct('table.name');
		$this->TABLE_NAME=$this->TableName.'_user';
		if($this->uid<0) $this->setuid();
		if($this->uid>0){
			$sql='select * from '.$this->TABLE_NAME.' where uid='.$this->uid;
			$this->treeUser=DB::queryTree($sql);
			if($this->treeUser->getCount()<1){
				$timeValue=DCS::timer();
				$treeData=newTree();
				$treeData->addItem('uid',$this->uid);
				$treeData->addItem('score',0);
				$treeData->addItem('scores',0);
				$treeData->addItem('multiple',0);
				$treeData->addItem('level',0);
				$treeData->addItem('discount',0);
				$treeData->addItem('status',1);
				$treeData->addItem('tim',$timeValue);
				$treeData->addItem('tim_updated',$timeValue);
				DB::executeInsert($this->TABLE_NAME,$this->TABLE_FIELDS,$treeData);
				unsetr($treeData);
				$this->treeUser=DB::queryTree($sql);
			}
			$this->treeUser->doFilter('');
			$this->treeUser->addItem('username',$this->username);
		}
		else{
			$this->treeUser=newTree();
			$this->treeUser->addItem('uid',0);
			$this->treeUser->addItem('username',$this->username);
			$this->treeUser->addItem('score',0);
			$this->treeUser->addItem('scores',0);
			$this->treeUser->addItem('multiple',1);
			$this->treeUser->addItem('level',1);
			$this->treeUser->addItem('discount',0);
			$this->treeUser->addItem('status',0);
		}
		//##########
		if($this->tableLevel->getRow()<1) $this->tableLevel=VDCSDTML::getConfigTable('common.channel/'.$this->channel.'/user.level');
		$level=$this->treeUser->getItemInt('level');
		$isLevel=false;
		$this->tableLevel->doBegin();
		while($this->tableLevel->isNext()){
			if($this->tableLevel->getItemValueInt('id')==$level){
				$this->treeUser->addItem('level.name',$this->tableLevel->getItemValue('name'));
				if($this->treeUser->getItemInt('discount')==0) $this->treeUser->addItem('discount',$this->tableLevel->getItemValueInt('discount'));
				if($this->treeUser->getItemNum('multiple')==0) $this->treeUser->addItem('multiple',$this->tableLevel->getItemValueNum('multiple'));
				$isLevel=true;
				break;
			}
		}
		if(!$isLevel){
			$this->treeUser->addItem('level.name','General');
			$this->treeUser->addItem('level',1);
			$this->treeUser->addItem('multiple',1);
			$this->treeUser->addItem('discount',100);
			if($this->uid>0){
				$sql='update '.$this->TABLE_NAME.' set level=1 where uid='.$this->uid;
				DB::exec($sql);
			}
		}
		//##########
		$this->var_discount=$this->treeUser->getItemInt('discount');
		if($this->var_discount>100 or $this->var_discount<1) $this->var_discount=100;
		$this->treeUser->addItem('discount',$this->var_discount);
		if($this->treeUser->getItemNum('multiple')==0) $this->treeUser->addItem('multiple',1);
		//##########
		$this->var_isInit=true;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doScoreNote($ordernum_,$type_,$score_,$explain_)
	{
		$timeValue=DCS::timer();
		$sql='insert into '.$this->TableName.'_score_note(uid,ordernum,n_type,n_score,n_explain,n_tim) ' .
			'values('.$this->uid.','.utilCode::toSQL($ordernum_,1).','.utilCode::toSQL($type_,1).','.$score_.','.utilCode::toSQL($explain_,1).','.utilCode::toSQL($timeValue,1).')';
		DB::exec($sql);
	}
	public function doScoreConsume($score_,$ordernum_)
	{
		if(!$this->var_isInit || $this->uid<1 || $score_==0) return;
		$this->treeUser->addItem('score',$this->treeUser->getItemInt('score')-$score_);
		$sql='update '.$this->TABLE_NAME.' set score=score-'.$score_.' where uid='.$this->uid;
		DB::exec($sql);
		if(len($ordernum_)>0){
			$this->doScoreNote($ordernum_,'consume',-$score_,'');
		}
	}
	public function doScoreCancelOrder($score_,$ordernum_,$type_)
	{
		if(!$this->var_isInit || $this->uid<1 || $score_==0) return;
		$this->treeUser->addItem('score',$this->treeUser->getItemInt('score')-$score_);
		$sql='update '.$this->TABLE_NAME.' set score=score+'.$score_.' where uid='.$this->uid;
		DB::exec($sql);
		if(len($ordernum_)>0){
			$this->doScoreNote($ordernum_,'cancel'.$type_,$score_,'');
		}
	}
	public function doScoresUpdate($score_,$ordernum_)
	{
		if(!$this->var_isInit || $this->uid<1 || $score_==0) return;
		$this->treeUser->addItem('score',$this->treeUser->getItemInt('score')+$score_);
		$this->treeUser->addItem('scores',$this->treeUser->getItemInt('scores')+$score_);
		//debugx($this->treeUser->getItemInt('scores'));
		$treeLevel=getLevelTree($this->treeUser->getItemInt('scores'));
		//debugTree($treeLevel);
		$sql='update '.$this->TABLE_NAME.' set score=score+'.$score_.',scores=scores+'.$score_.
			',level='.$treeLevel->getItemInt('id').
			' where uid='.$this->uid;
		//,multiple='.$treeLevel->getItemInt('multiple').',discount='.$treeLevel->getItemInt('discount').'
		DB::exec($sql);
		if(len($ordernum_)>0){
			$this->doScoreNote($ordernum_,'add',-$score_,'');
		}
		unsetr($treeLevel);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getLevelTree($score_)
	{
		$reTree=newTree();
		$oldScore=-2;
		$this->tableLevel->doBegin();
		while($this->tableLevel->isNext()){
			$nowScore=$this->tableLevel->getItemValueInt('score');
			//debugx($score_.','.$nowScore);
			if($nowScore<=$score_){
				if($nowScore>$oldScore){
					$oldScore=$nowScore;
					$reTree->setArray($this->tableLevel->getItemTree()->getArray());
				}
			}
		}
		return $reTree;
	}
	public function getNextLevelTree($score_)
	{
		$reTree=newTree();
		$oldScore=999999999;
		$this->tableLevel->doBegin();
		while($this->tableLevel->isNext()){
			$nowScore=$this->tableLevel->getItemValueInt('score');
			//debugx($score_.','.$nowScore);
			if($nowScore>$score_){
				if($nowScore<$oldScore){
					$oldScore=$nowScore;
					$reTree->setArray($this->tableLevel->getItemTree()->getArray());
				}
			}
		}
		return $reTree;
	}
	
	
	public function toPriceDiscount($strPrice,$isDiscount=1)
	{
		$re=$strPrice;
		if($isDiscount==1){
			if($this->var_discount>0 && $this->var_discount<100) $re=$strPrice*$this->var_discount/100;
		}
		return utilCode::toPrice($re);
	}
	
}
?>