<?
class UaUserE
{
	public $id=-1;
	public $num=array(),$plus=array(),$minus=array();
	
	protected $treeTemplate=null;
	
	protected $ua;
	public function __construct()
	{
		$this->ua=&$GLOBALS['ua'];
		
		$this->id=-1;
		$this->num['money']=0;
		$this->num['emoney']=0;
		$this->num['points']=0;
		$this->num['exp']=0;
		$this->num['_money']=0;
		$this->num['_emoney']=0;
		$this->num['_points']=0;
		$this->num['_exp']=0;
	}
	public function __destruct()
	{
		unset($this->num);
		unset($this->treeTemplate);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function init()
	{
		$this->num['money']=0;
		$this->num['emoney']=0;
		$this->num['points']=0;
		$this->num['exp']=0;
		$this->_test='';
		$this->isTester=false;
		$this->isStruct=false;
		$this->isDat=false;
	}
	
	public function inits()
	{
		if(!$this->isStruct) $this->initStruct();
		if(!$this->isDat) $this->initDat();
	}
	public function initStruct($tablename=null,$fieldid=null,$id=null)
	{
		if($this->isStruct) return;$this->isStruct=true;
		$this->TableName=isn($tablename)?$this->ua->struct('TableName'):$tablename;
		$this->FieldID=isn($fieldid)?$this->ua->struct('FieldId'):$fieldid;
		$this->id=isn($id)?$this->ua->id:$id;
	}
	public function initDat($tData=null)
	{
		if($this->isDat) return;$this->isDat=true;
		if(isn($tData)) $tData=$this->ua->getDataTree();
		$this->num['_money']=$tData->getItemNum('money');
		$this->num['_emoney']=$tData->getItemNum('emoney');
		$this->num['_points']=$tData->getItemInt('points');
		$this->num['_exp']=$tData->getItemInt('exp');
	}
	
	
	/*
	########################################
	########################################
	*/
	public function setID($s) { $this->id=$s; }
	
	
	public function setValue($k,$v){$v=toNum($v);if($v==0) return;$this->num[$k]=toNum($v);$this->isTester=false;}
	public function plusValue($k,$v){$v=toNum($v);if($v==0) return;$this->num[$k]+=toNum($v);$this->isTester=false;}
	public function minusValue($k,$v){$v=toNum($v);if($v==0) return;$this->num[$k]-=toNum($v);$this->isTester=false;}
	
	
	public function doTest()
	{
		$this->inits();
		if(!$this->isTester){
			$this->_test='';
			if($this->num['money']<0 && abs($this->num['money'])>$this->num['_money']) $this->_test='money';
			if($this->num['emoney']<0 && abs($this->num['emoney'])>$this->num['_emoney']) $this->_test='emoney';
			if($this->num['points']<0 && abs($this->num['points'])>$this->num['_points']) $this->_test='points';
			if($this->num['exp']<0 && abs($this->num['exp'])>$this->num['_exp']) $this->_test='exp';
			$this->isTester=true;
		}
	}
	public function isTest(){$this->doTest();return !len($this->_test);}
	public function getTest(){$this->doTest();return $this->_test;}
	
	
	/*
	########################################
	########################################
	*/
	public function doUpdate($sqlAppend='')
	{
		$sql=$this->getUpdateSQL($sqlAppend);
		if(len($sql)>0){
			DB::exec($sql);
		}
	}
	
	public function getUpdateSQL($sqlAppend='')
	{
		$this->inits();
		$re='';
		$sqlSet=''; 
		if($this->num['money']!=0){
			$sqlSet.=',u_money=u_money+'.$this->num['money'];
		}
		if($this->num['emoney']!=0){
			$sqlSet.=',u_emoney=u_emoney+'.$this->num['emoney'];
		}
		if($this->num['points']!=0){
			$sqlSet.=',u_points=u_points+'.$this->num['points'];
		}
		if($this->num['exp']!=0){
			$sqlSet.=',u_exp=u_exp+'.$this->num['exp'];
		}
		if(len($sqlAppend)>0) $sqlSet.=','.$sqlAppend;
		if(len($sqlSet)>0){
			$sqlSet=substr($sqlSet,1);
			$_id=$this->id;
			if($_id>=0) $re=('update '.$this->TableName.' set '.$sqlSet.' where '.$this->FieldID.'='.$_id);
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	########################################
	########################################
	*/
	
	
	/*
	########################################
	########################################
	*/
	public function loadTemplate() { if($this->treeTemplate==null) $this->treeTemplate=VDCSDTML::getConfigTree('common.config/template/user.extend'); }
	
	public function getTemplate($k) { $this->loadTemplate(); return $this->treeTemplate->getItem($k); }
	
	
	/*
	########################################
	########################################
	*/
	public function getPopedomMessage($s)
	{
		$re='';
		if(!$this->isPopedom($s)){
			$re=$this->getTemplate('message.nopopedom');
			if(!$re) $re='[user.extend: message.nopopedom]';
		}
		return $re;
	}
	
	public function isPopedom($s)
	{
		$re=true;
		if($s){
			if(inp($s,$this->ua->getData('groupid'),',')<1){
				if($this->ua->getDataInt('grade')!=10) $re=false;
			}
		}
		return $re;
	}
	
	/*
	########################################
	########################################
	*/
	/*
	public function getNotePayMessage($strTree,$strTreeUser)
	{
		Dim re 
		Dim tmpStatus As Integer
		tmpStatus=isNotePay(strTree,strTreeUser)
		Select Case CLng(tmpStatus)
		Case 1
			re=getTemplate('message.nopay')
			if(Len(re) < 1) re='[user.extend: message.nopay]'
		Case -1
			re=getTemplate('message.notpay')
			if(Len(re) < 1) re='[user.extend: message.notpay]'
		End Select
		if(Len(re) > 0)
			Dim tmpTreeUser
			Set tmpTreeUser=toUserTree(strTreeUser)
			tmpTreeUser.doAppendPrefix ('user.')
			re=utils.toDisplaceVar(re,strTree)
			re=utils.toDisplaceVar(re,tmpTreeUser)
		}
		getNotePayMessage=re
	}
	
	public function isNotePay($strTree,$strTreeUser)
		Dim re As Integer: re=1
		Dim tmpTreeUser
		Set tmpTreeUser=toUserTree(strTreeUser)
		if(tmpTreeUser.getItemInt('timed')=1 And tmpTreeUser.getItemInt('timed_e')=0)
			re=0
		else
			if(Len(strTree.getItem('channel')) < 1) Call strTree.addItem('channel','common')
			if(Len(strTree.getItem('handle')) < 1) Call strTree.addItem('handle','unknow')
			Dim $sql 
			$sql='select top 1 n_id from db_sys_note where uuid=' & tmpTreeUser.getItemInt('uuid') & ' and ns||t='' & strTree.getItem('channel') & '' and iid=' & strTree.getItemInt('id') & ' and n_handle='' & strTree.getItem('handle') & '''
			if(dcs.db.getQueryNum($sql) > 0) re=0
		}
		if(Int(re)!=0)
			if(strTree.getItemNum('sp_money') > tmpTreeUser.getItemNum('money') || strTree.getItemNum('sp_emoney') > tmpTreeUser.getItemNum('emoney') || strTree.getItemInt('sp_points') > tmpTreeUser.getItemInt('points')) re=-1
		}
		if(Int(re)=1)
			if(dcs.request.getF||m('_chk')='notepay')
				Call doUpdateUser(strTree,tmpTreeUser)
				Call doUpdateNote(strTree,tmpTreeUser)
				re=0
			}
		}
		isNotePay=re
	}
	
	
	public function doUpdateUser($strTree,$strTreeUser)
		Dim $sql 
		if(strTree.isItem('sp_money')) $sql.=',u_money=u_money-' & strTree.getItemNum('sp_money'))
		if(strTree.isItem('sp_emoney')) $sql.=',u_emoney=u_emoney-' & strTree.getItemNum('sp_emoney'))
		if(strTree.isItem('sp_points')) $sql.=',u_points=u_points-' & strTree.getItemInt('sp_points'))
		if(Len($sql) > 0)
			$sql=Mid($sql,2)
			$sql='update ims_user set ' & $sql & ' where uuid=' & strTreeUser.getItemInt('uuid')
			Call dcs.db.doExecute($sql)
		}
	}
	
	public function doUpdateNote($strTree,$strTreeUser)
	{
		if(Len(strTree.getItem('channel')) < 1) Call strTree.addItem('channel','common')
		if(Len(strTree.getItem('handle')) < 1) Call strTree.addItem('handle','unknow')
		Dim tmpTreeData As New utilTree
		tmpTreeData.addItem 'uuid',strTreeUser.getItemInt('uuid')
		tmpTreeData.addItem 'u_name',strTreeUser.getItem('u_name')
		tmpTreeData.addItem 'ns||t',strTree.getItem('channel')
		tmpTreeData.addItem 'iid',strTree.getItemInt('id')
		tmpTreeData.addItem 'sp_money',strTree.getItemNum('sp_money')
		tmpTreeData.addItem 'sp_emoney',strTree.getItemNum('sp_emoney')
		tmpTreeData.addItem 'sp_points',strTree.getItemInt('sp_points')
		tmpTreeData.addItem 'n_handle',strTree.getItem('handle')
		tmpTreeData.addItem 'n_remark',strTree.getItem('explain')
		tmpTreeData.addItem 'n_tim',dcs.time.getNow()
		define('USER_NOTE_FIELDS','uuid,u_name,ns||t,iid,sp_money,sp_emoney,sp_points,n_handle,n_remark,n_tim');
		Call dcs.db.doExecuteInsert('db_sys_note',USER_NOTE_FIELDS,tmpTreeData)
	}
	
	
	public function toUserTree($strTree)
	{
		Set toUserTree=New utilTree
		if(isTree(strTree))
			if(strTree.getCount() > 0) toUserTree.setArray (strTree.getArray())
		}
		if(toUserTree.getCount() < 1) toUserTree.setArray (user.getDataTree().getArray())
	}
	*/
	
	
	
	/*
	########################################
	########################################
	########################################
	########################################
	*/
	
	/*
	########################################
	########################################
	*/
	const DATAI_EXT		= '.dat';
	public function toDataiPath($key,$real=false)
	{
		$id=$this->ua->id;
		//debugx($id);
		$dirsn=utilFile::toDirVar('{$sn1}/{$sn2}{$sn3}/',$id);
		$dirs=$this->ua->rc.'/'.$dirsn.$id.'/';
		$dirpath=pathDir('data').$dirs;
		//debugx($dirpath);
		if($real) utilFile::doDirCreated($dirpath);
		return $dirpath.$key.self::DATAI_EXT;
		//pathDir('data.cache').$this->ua->rc.'/'.r($s,'/','__').EXT_CACHE;
	}
	public function getDatai($key)
	{
		$path=$this->toDataiPath($key);
		//debugx($path);
		return getFile($path);
	}
	public function setDatai($key,$content)
	{
		$path=$this->toDataiPath($key,true);
		//debugx($path);
		doFileWrite($path,$content);
	}
	
	
}
?>