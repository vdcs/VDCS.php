<?
class LinkitemWord
{
	const KEY			= 'linkitem';
	const TableName			= 'dbd_linkitem';
	const TablePX			= '';
	const FieldID			= 'id';
	
	const TableFieldsAdd		= 'channel,topic,linkurl,sort,type,summary,tim,tim_up';
	const TableFieldsEdit		= 'linkurl,sort,type,summary,tim_up';
	const TableFieldsTotalView	= 'total_view,tim_up';
	
	/*
	protected $treeData;
	public function __construct()
	{
		$this->_is=false;
		
		$this->treeData=newTree();
	}
	public function __destruct()
	{
		unsetr($this->treeData);
	}
	
	public function setChannel($channel_){$this->channel=$channel_;}
	public function is(){return $this->_is&&$this->_isRoot;}
	*/
	
	
	/*
	########################################
	########################################
	*/
	public static function saveSearch($word,$sort,$params=null)
	{
		self::save('search',$word,'','',$params);
	}
	
	public static function save($channel,$word,$sort='',$type='',$params=null)
	{
		global $cfg;
		$words=$word;
		$words=r($words,' ',',');
		$arWords=toSplit($words,',');
		foreach($arWords as $_word){
			self::saveWord($channel,$_word,$sort,$type,$params);
		}
		return true;
	}
	public static function saveWord($channel,$word,$sort='',$type='',$params=null)
	{
		global $cfg;
		if(!$channel || !$word) return false;
		$params=utilArray::toParamsTree($params);
		$treeWord=self::getTree($channel,$word);
		if($treeWord->getCount()<1){
			$treeWord=newTree();
			$treeWord->addItem('channel',$channel);
			$treeWord->addItem('topic',$word);
			$treeWord->addItem('linkurl',$params->getItem('linkurl'));
			$treeWord->addItem('sort',$params->getItem('sort'));
			$treeWord->addItem('type',$params->getItem('type'));
			$treeWord->addItem('summary',$params->getItem('summary'));
			$treeWord->addItem('tim',DCS::timer());
			$treeWord->addItem('tim_up','0');
			$sql=DB::sqlInsert(self::TableName,self::TableFieldsAdd,$treeWord);
			DB::exec($sql);
		}
		else{
			//$sqlQuery=self::sqlQuery($channel,$word);
			$sqlQuery=self::FieldID.'='.$treeWord->getItemInt(self::FieldID);
			/*
			$treeWord->addItem('total_view',$treeWord->getItemInt('total_view')+1);
			$treeWord->addItem('total_get',$treeWord->getItemInt('total_get')+1);
			$treeWord->addItem('tim_up',DCS::timer3());
			$sql=DB::sqlUpdate(self::TableName,self::TableFieldsTotalView,$treeWord,$sqlQuery);
			*/
			$sql=DB::sqlUpdate(self::TableName,'query','total_view=total_view+1,tim_up='.DCS::timer().'',$sqlQuery);
			DB::exec($sql);
		}
		return true;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function sqlQuery($channel,$word='')
	{
		$sql='channel='.DB::toSQL($channel,1).'';
		if(len($word)>0) $sql.=' and topic='.DB::toSQL($word,1).'';
		return $sql;
	}
	
	public static function getSearchTable($order='hot',$limit=10){return self::getTable('search',$order,$limit);}
	public static function getTable($channel,$order='hot',$limit=10)
	{
		global $cfg;
		$sqlOrder='';
		switch($type){
			case 'new':		$sqlOrder='orderid desc,tim desc';break;
			case 'get':		$sqlOrder='orderid desc,total_get desc,tim desc';break;
			case 'view':
			case 'hot':
			default:		$sqlOrder='orderid desc,total_view desc,tim desc';break;
		}
		$reTable=newTable();
		$sql=DB::toSQLSelect(self::TableName,'','*',self::sqlQuery($channel,$word),$sqlOrder,$limit);
		$reTable=DB::queryTable($sql);
		return $reTable;
	}
	
	public static function getTree($channel,$word)
	{
		$reTree=newTree();
		$sql=DB::toSQLSelect(self::TableName,'','*',self::sqlQuery($channel,$word));
		$reTree=DB::queryTree($sql);
		return $reTree;
	}
	
}
?>