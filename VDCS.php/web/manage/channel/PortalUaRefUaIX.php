<?
trait PortalUaRefUaIX
{
	use PortalUaRefBase;
	
	//####################
	protected function parseViewi()
	{
		$uid=queryi('uid');
		$ua=Ua::instance(APP_UA);
		$uTree=newTree();
		$uTree=$ua->queryTree($uid);
		if($uTree->getCount()<1){
			$this->setStatus('failed');
			$this->setMessage($this->getLang('error.no.exist'));
			return;
		}
		$this->addVarTree($uTree,'info.');
		$this->setSucceed();
	}
	
	
	protected function doListFilter(&$tableData)
	{
		$this->doListFilterBase($tableData);
	}
	protected function doListFilterBase(&$tableData)
	{
		$relateid=$this->getConfig('table.field.relateid');
		if(!$relateid) $relateid=$this->FieldID;
		$relateids=$tableData->getValues($relateid);
		if($relateids){
			$_tablename=$this->getConfig('info:table.name');
			$_fieldid=$this->getConfig('info:table.field.id');
			$_fields=$this->getConfig('info:list.table.fields');
			if(!$_fieldid) $_fieldid=$relateid;
			if($_tablename && $_fields) $tableData=CommonExtend::toExtendTable($tableData,$relateid.'='.$_fieldid,'',$_tablename,$_fieldid.','.$_fields,$_fieldid.' in ('.$relateids.')');
		}
		$tableData->doAppendFields('_names');
		$tableData->doBegin();
		while($tableData->isNext()){
			$_names=$tableData->getItemValue('names');
			if(!$_names) $_names=$tableData->getItemValue('name');
			if(!$_names) $_names=$tableData->getItemValue('email');
			if(!$_names) $_names=$tableData->getItemValue('mobile');
			$tableData->setItemValue('_names',$_names);
		}
	}
	
	
	//####################
	//####################
	protected function parseSearch()
	{
		$keyword=queryx('keyword');
		if(!$keyword){
			$this->setMessage('缺少搜索关键字');
			$this->setStatus('keyword');
		}
		$this->addVar('keyword',$keyword);
		//$sqlTerm='email='.DB::q($keyword,1).' or names='.DB::q($keyword,1);
		// %t
		$sqlTerm='uid like '.DB::q($keyword,2).' or name like '.DB::q($keyword,2).' or email like '.DB::q($keyword,2).' or mobile like '.DB::q($keyword,2);
		$sqlTerm.=' or names like '.DB::q($keyword,2).' or realname like '.DB::q($keyword,2).' or nickname like '.DB::q($keyword,2).' or company like '.DB::q($keyword,2).' or url like '.DB::q($keyword,2).'';
		$sql=DB::sqlSelect($this->TableName,'','*',$sqlTerm);
		$tableList=DB::queryTable($sql);
		if($tableList->getRow()<1){
			$this->setMessage('没有搜索到符合要求的记录');
			$this->setStatus('nodata');
		}
		
		$this->setTable($tableList);
		$this->setSucceed();
	}
	
	protected function parseSearchi()
	{
		$this->refSearchiLoad();
	}
	
	protected function refSearchiLoad()
	{
		$keywords = queryx('keyword');
		##########
		$sqlTerm='';
		$sqlTerm=DB::sqla($sqlTerm,'realname like '.DB::q($keywords,2),'or');
		$sqlTerm=DB::sqla($sqlTerm,'nickname like '.DB::q($keywords,2),'or');
		$sqlTerm=DB::sqla($sqlTerm,'company like '.DB::q($keywords,2),'or');
		$sqlTerm=DB::sqla($sqlTerm,'url like '.DB::q($keywords,2),'or');
		$sqlInfo=DB::sqlSelect($this->TableName.'_info','','uid',$sqlTerm);
		//debugx($sqlInfo);
		##########
		$sqlTerm='uid like '.DB::q($keywords,2);
		$sqlTerm=DB::sqla($sqlTerm,'name like '.DB::q($keywords,2),'or');
		$sqlTerm=DB::sqla($sqlTerm,'email like '.DB::q($keywords,2),'or');
		$sqlTerm=DB::sqla($sqlTerm,'mobile like '.DB::q($keywords,2),'or');
		$sqlTerm=DB::sqla($sqlTerm,'names like '.DB::q($keywords,2),'or');
		$sqlTerm=DB::sqla($sqlTerm,'uid in ('.$sqlInfo.')','or');
		if($this->_var['AppendQuery']) $sqlTerm=DB::sqla($this->_var['AppendQuery'],'('.$sqlTerm.')');
		$sql=DB::sqlSelect($this->TableName,'','*',$sqlTerm,'',10);
		$tableUser=DB::queryTable($sql);
		$opt=[];
		$opt['relateid']='uid';
		UaExtendManage::appendInfo($tableUser,$opt);
		$this->setTable($tableUser);
		$this->setSucceed();
	}
	
}
