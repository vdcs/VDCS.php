<?
class StructUser
{
	public $treeData=null,$tableLevel=null;
	public $id=-1;
	protected $_var=array(),$Fielda=array();
	protected $KEY,$TableName,$TablePX,$FieldID;
	
	public function __construct()
	{
		$this->id=-1;
		
		$this->_var['name']='';
		$this->_var['FieldsAdd']='uid,uurc,uuid,name,grade,level,rank,money,emoney,points,exp,islock,status,tim,tim_up';
					//,prop1,prop2,prop3,prop4,prop5,int1,int2,int3,num1,num2,num3,num4,num5
		
		$this->rc='userc';
		$this->TableName='dbc_user';
		$this->TablePX='';
		$this->FieldID='uid';
	}
	public function __destruct()
	{
		unsetr($this->_var,$this->Fielda);
		unsetr($this->treeData,$this->tableLevel);
	}
	
	
	public function setID($id=-1,$name='')
	{
		if($id<0) $id=$ua->id;
		if(!$name) $name=$this->ua->name;
		$this->id=$id;
		$this->_var['name']=$name;
	}
	
	public function getTree()
	{
		//$reTree=newTree();
		//$reTree->setArray($this->treeData->getArray());
		return $this->treeData;
	}
	public function setData($k,$v){$this->treeData->addItem($k,$v);}
	public function getData($k){return $this->treeData->getItem($k);}
	public function getDataInt($k){return $this->treeData->getItemInt($k);}
	public function getDataNum($k){return $this->treeData->getItemNum($k);}
	
	public function getGrade(){return $this->getDataInt('grade');}
	public function getLevel(){return $this->getData('level');}
	public function getRank(){return $this->getDataInt('rank');}
	public function getStatus(){return $this->getDataInt('status');}
	
	public function isLock(){return $this->getDataInt('islock')==1;}
	
	
	/*
	########################################
	########################################
	*/
	public function doInit()
	{
		if($this->_isInit) return;
		global $cfg;
		if(len($this->channel)<1) $this->channel=$cfg->getChannel();
		$this->TableName=$cfg->vp('user:table.name');
		if(len($this->TableName)<1) $this->TableName=$cfg->vp('table.name').'_user';
		if($this->id<0) $this->setID();
		if($this->id>0){
			$sql='select * from '.$this->TableName.' where '.$this->FieldID.'='.$this->id;
			$this->treeData=DB::queryTree($sql);
			if($this->treeData->getCount()<1){
				$_tim=DCS::timer();
				$this->treeData->addItem('id',$this->id);
				$this->treeData->addItem('uid',$this->id);
				$this->treeData->addItem('uurc','user');
				$this->treeData->addItem('uuid',$this->id);
				$this->treeData->addItem('name','');
				$this->treeData->addItem('grade',0);
				$this->treeData->addItem('level','');
				$this->treeData->addItem('rank',0);
				$this->treeData->addItem('money',0);
				$this->treeData->addItem('emoney',0);
				$this->treeData->addItem('points',0);
				$this->treeData->addItem('exp',0);
				$this->treeData->addItem('islock',0);
				$this->treeData->addItem('status',1);
				$this->treeData->addItem('tim',$_tim);
				$this->treeData->addItem('tim_up',$_tim);
				//$this->_var['FieldsAddAppend']
				DB::execInsertx($this->TableName,$this->_var['FieldsAdd'],$this->treeData);
			}
			$this->treeData->doFilter($this->TablePX);
			$this->treeData->addItem('id',$this->id);
			if(len($this->treeData->getItem('name'))<1) $this->treeData->addItem('name',$this->ua->getData('name'));
			//debugTree($this->treeData);
		}
		else{
			$this->treeData=newTree();
			$this->treeData->addItem('id',0);
			$this->treeData->addItem('uid',0);
			$this->treeData->addItem('name',$this->ua->name);
			$this->treeData->addItem('grade',0);
			$this->treeData->addItem('level','');
			$this->treeData->addItem('rank',0);
			$this->treeData->addItem('money',0);
			$this->treeData->addItem('emoney',0);
			$this->treeData->addItem('points',0);
			$this->treeData->addItem('exp',0);
			$this->treeData->addItem('islock',0);
			$this->treeData->addItem('status',0);
			$this->treeData->addItem('tim',0);
		}
		//##########
		$this->cuse=($cfg->cfgi('userc.use')==1);
		$this->cuseScore=($cfg->cfgi('userc.use.score')==1);
		//##########
		$this->_isInit=true;
	}
	
	
	public function inite()
	{
		global $usere;
		initUsere();
		if($this->cuseScore){
			$usere->initStruct($this->TableName,$this->FieldID,$this->id);
			$usere->initDat($this->treeData);
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getNameTree($ids)
	{
		$reTree=newTree();
		if(len($ids)>0){
			if($this->cuse){
				$sql='select uid,name from '.$this->TableName.' where uid in ('.$ids.')';
				$sql='select uid,name from '.$this->ua->struct('TableName').' where uid in ('.$ids.')';
			}
			else{
				$sql='select uid,name from '.$this->ua->struct('TableName').' where uid in ('.$ids.')';
			}
			$reTree=DB::queryTrees($sql,1);
			//debugTree($reTree);
		}
		return $reTree;
	}
	
	public function getInfoTable($ids,$fieldc,$fields)
	{
		$reTable=newTable();
		if(len($ids)>0){
			if($this->cuse){
				$_fieldc='uid,uuid,name as uname';
				if($fieldc) $_fieldc.=','.$fieldc;
				if($this->cuseScore){
					$_fieldc.=',money,emoney,points,exp';
					$_fields=$fields;
				}
				else{
					$_fields='money,emoney,points,exp,'.$fields;
				}
				$_fieldc='uc.'.r($_fieldc,',',',uc.');
				$_fields='u.'.r($_fields,',',',u.');
				$sql='select '.$_fieldc.','.$_fields.' from '.$this->TableName.' uc,'.$this->ua->struct('TableName').' u where uc.uuid=u.uid and uc.uid in ('.$ids.')';
			}
			else{
				$_fields='uid,name,money,emoney,points,exp,'.$fields;
				$sql='select '.$_fields.' from '.$this->ua->struct('TableName').' where uid in ('.$ids.')';
			}
			//select uid,uid as uuid,name,money,emoney,points,exp,email,online from db_user where uid in (10001)
			//select uc.uid,uc.uuid,u.money,u.emoney,u.points,u.exp,u.email,u.online from db_forum_user uc,db_user u where uc.uuid=u.uid and uc.uid in (10001)
			//select uc.uid,uc.uuid,uc.money,uc.emoney,uc.points,uc.exp,u.email,u.online from db_forum_user uc,db_user u where uc.uuid=u.uid and uc.uid in (10001)
			//debugx($sql);
			$reTable=DB::queryTable($sql);
			//debugTable($reTable);
		}
		return $reTable;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doUpdate($sqlUpdate='')
	{
		if($this->id>0 && len($sqlUpdate)>0){
			$sql='update '.$this->TableName.' set '.$sqlUpdate.' where '.$this->FieldID.'='.$this->id;
			DB::exec($sql);
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function toDTMLCache($re,$vo='cpo')
	{
		if($vo && right($vo,1)!='.') $vo.='.';
		$vo.=$this->rc;
		if(right($vo,1)!='.') $vo.='.';
		//debugx($vo);
		//####################
		$re=CommonTheme::toCacheFilterTree($re,$this->rc,$vo,'getData');
		//####################
		return $re;
	}
}

		/*
		if($this->tableLevel->getRow()<1) $this->tableLevel=VDCSDTML::getConfigTable('common.channel/'.$this->channel.'/user.level');
		$level=$this->treeData->getItemInt('level');
		$isLevel=false;
		$this->tableLevel->doBegin();
		while($this->tableLevel->isNext()){
			if($this->tableLevel->getItemValueInt('id')==$level){
				$this->treeData->addItem('level.name',$this->tableLevel->getItemValue('name'));
				if($this->treeData->getItemInt('discount')==0) $this->treeData->addItem('discount',$this->tableLevel->getItemValueInt('discount'));
				if($this->treeData->getItemNum('multiple')==0) $this->treeData->addItem('multiple',$this->tableLevel->getItemValueNum('multiple'));
				$isLevel=true;
				break;
			}
		}
		if(!$isLevel){
			$this->treeData->addItem('level.name','General');
			$this->treeData->addItem('level',1);
			$this->treeData->addItem('multiple',1);
			$this->treeData->addItem('discount',100);
			if($this->id>0){
				$sql='update '.$this->TABLE_NAME.' set level=1 where uid='.$this->id;
				DB::exec($sql);
			}
		}
		//##########
		$this->var_discount=$this->treeData->getItemInt('discount');
		if($this->var_discount>100 or $this->var_discount<1) $this->var_discount=100;
		$this->treeData->addItem('discount',$this->var_discount);
		if($this->treeData->getItemNum('multiple')==0) $this->treeData->addItem('multiple',1);
		//##########
		*/
		
?>