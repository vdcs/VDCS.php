<?
require('mysql.common.php');

/*
##################################################
##################################################
*/
class drv_mysql extends VDCSDBConstruct
{
	use DBRefCommon;
	
	public function __construct($cfg)
	{
		parent::__construct();
		$this->_data['name']='MySQL';
		$this->_data['fmtdate']="'Y-m-d'";
		$this->_data['fmttimestamp']="'Y-m-d,h:i:s'";
		$this->_data['sysDate']='CURDATE()';
		$this->_data['sysTimeStamp']='NOW()';
		
		$this->_data['cfg']=$cfg;
		$this->_data['cfg']['server'] || $this->_data['cfg']['server']='localhost';
		$this->_data['cfg']['perdure']!=0 && $this->_data['cfg']['perdure']=1;		//数据库连接方式 0=connect,1=pconnect
	}
	
	public function __destruct()
	{
		parent::__destruct();
		$this->doDisconnect();
	}
	
	public function cfg($k,$v=null)
	{
		if(!isn($v)) $this->_data['cfg'][$k]=$v;
		return $this->_data['cfg'][$k];
	}
	public function cfgTree()
	{
		$reTree=newTree();
		$reTree->setArray($this->_data['cfg']);
		return $reTree;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doConnect($imsg=true)
	{
		if($this->cid) return;
		$this->_data['cfg']['user']=$this->_data['cfg']['user']?$this->_data['cfg']['user']:$this->_data['cfg']['username'];
		if($this->_data['cfg']['perdure']==1){
			$this->cid=@mysql_pconnect($this->_data['cfg']['server'],$this->_data['cfg']['user'],$this->_data['cfg']['password']);
			if($imsg && !$this->cid) $this->doErrorEvent('pconnect','Can not pconnect to MySQL server','Can not pconnect to MySQL server','Can not pconnect to MySQL server: '.$this->_data['cfg']['server'].' ..',1);
			//$this->cid===false
		}
		else{
			$this->cid=@mysql_connect($this->_data['cfg']['server'],$this->_data['cfg']['user'],$this->_data['cfg']['password']);
			if($imsg && !$this->cid) $this->doErrorEvent('connect','Can not connect to MySQL server','Can not connect to MySQL server','Can not connect to MySQL server: '.$this->_data['cfg']['server'].' ..',1);
		}
		if(!$this->cid) return false;
		if($this->cid && $this->_data['cfg']['charset']){
			if(!$this->_data['cfg']['charset.result']) $this->_data['cfg']['charset.result']=$this->_data['cfg']['charset'];
			/*
			mysql_query('SET CHARACTER SET '.$this->_data['cfg']['charset']);
			mysql_query('SET CHARACTER_SET_CLIENT='.$this->_data['cfg']['charset']);
			mysql_query('SET CHARACTER_SET_CONNECTION='.$this->_data['cfg']['charset']);
			mysql_query('SET CHARACTER_SET_DATABASE='.$this->_data['cfg']['charset']);
			mysql_query('SET CHARACTER_SET_RESULTS='.$this->_data['cfg']['charset.result']);
			*/
			//debugx($this->_data['cfg']['charset']);
			mysql_query('SET NAMES '.$this->_data['cfg']['charset']);
			//mysql_query('SET CHARACTER SET '.$this->_data['cfg']['charset']);
		}
		$this->doSelectDatabase();
		$this->_data['total.query']=0;
		return true;
	}
	
	public function doDisconnect()
	{
		if($this->cid && $this->_data['cfg']['pconnect']!=1){
			return @mysql_close($this->cid);
			$this->cid=false;
		}
	}
	public function doClose(){$this->doDisconnect();}
	
	public function doSelectDatabase($db='')
	{
		$db || $db=$this->_data['cfg']['database'];
		$this->db=$db;
		$this->dbid=@mysql_select_db($db,$this->cid);
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getQuery($sql)
	{
		$this->doConnect();
		$_err=1;if(substr($sql,0,1)=='@'){$_err=0;$sql=substr($sql,1);}
		$query=@mysql_query($sql,$this->cid);
		if($query) { $this->addSQL($sql); }else{ if($_err==1) $this->doErrorEvent('query',$sql,$sql,mysql_error($this->cid),1); }
		return $query;
	}
	
	public function getQueryRS($sql,$strt=0) { return $this->getQueryAry($sql); }
	public function getQueryAry($sql) { return $this->toFetchAry($this->getQuery($sql)); }
	
	public function getQueryTree($sql)
	{
		$query=$this->getQuery($sql);
		$reTree=new utilTree();
		$reTree->setArray($this->toFetchAry($query));
		return $reTree;
	}
	public function getQueryTrees($sql,$type=0)
	{
		if(substr($sql,0,1)=='!'){$type=1;$sql=substr($sql,1);}
		$query=$this->getQuery($sql);
		$reTree=new utilTree();
		if($type==1){
			$fields=$this->toFetchFieldAry($query);
			while($arys=$this->toFetchAry($query)){$reTree->addItem($arys[$fields[0]],$arys[$fields[1]]);}
		}
		else{
			$reTree->setArray($this->toFetchAry($query));
		}
		return $reTree;
	}
	
	public function getQueryTable($sql)
	{
		$query=$this->getQuery($sql);
		$reTable=new utilTable();
		$reTable->setFields($this->toFetchFields($query));
		while($rows=$this->toFetchAry($query)){
			$reTable->addItem($rows);
		}
		return $reTable;
	}
	
	public function getQueryValue($sql,$row=0)
	{
		$query=$this->getQuery($sql);
		return @mysql_result($query,$row);
	}
	public function getQueryInt($sql,$row=0) { $re=$this->getQueryValue($sql,$row); return is_numeric($re) ? $re : 0; }
	public function getQueryNum($sql,$row=0) { $re=$this->getQueryValue($sql,$row); return is_numeric($re) ? $re : 0; }
	
	public function toQueryInt($sTable,$act,$sFields='',$sTerm=''){return $this->getQueryInt(self::toSQLSelect($sTable,$act,$sFields,$sTerm));}
	public function toQueryNum($sTable,$act,$sFields='',$sTerm=''){return $this->getQueryNum(self::toSQLSelect($sTable,$act,$sFields,$sTerm));}
	
	
	/*
	########################################
	########################################
	*/
	public function doExec($sql)
	{
		$this->doConnect();
		$_err=true;if(substr($sql,0,1)=='@'){$_err=false;$sql=substr($sql,1);}
		$query=@mysql_query($sql,$this->cid);
		$re=true;
		if($query){
			$this->addSQL($sql);
		}
		else{
			$re=false;
			if($_err) $this->doErrorEvent('exec',$sql,$sql,mysql_error($this->cid),1);
		}
		unset($query);
		return $re;
	}
	public function isExec($sql){return $this->doExec('@'.$sql);}
	
	public function doExecBatch($sql,$sym=null)
	{
		if(!$sym) $sym=BATCH_SYMBOL;
		$this->doConnect();
		$sqlAry=explode($sym,$sql);
		for($i=0;$i<count($sqlAry);$i++){
			if($sqlAry[$i]){
				$sql=trim($sqlAry[$i]);
				$query=@mysql_query($sql,$this->cid);
				if($query) { $this->addSQL($sql); } else { $this->doErrorEvent('exec.batch',$sql,$sql,mysql_error($this->cid),0); }
			}
		}
		unset($query);
	}
	
	public function doExecuteInsert($table,$fields,$cDatas,$rDatas=null) { return $this->doExec(self::sqlInsert($table,$fields,$cDatas,$rDatas)); }
	public function doExecuteUpdate($table,$fields,$cDatas,$term='',$rDatas=null) { return $this->doExec(self::sqlUpdate($table,$fields,$cDatas,$term,$rDatas)); }
	
	
	/*
	########################################
	########################################
	*/
	public function isTableExist($table)
	{
		$this->doConnect();
		return @mysql_query('select count(*) from `'.$table.'`',$this->cid)? true : false;
	}
	
	public function doTableClear($table) { return $this->doExec('TRUNCATE `'.$table.'`'); }
	public function doTableDelete($table) { return $this->doExec('DROP TABLE IF EXISTS `'.$table.'`'); }
	
	
	/*
	########################################
	########################################
	*/
	public function getInsertID() { return @mysql_insert_id($this->cid); }
	
	public function getAffectedRows() { return @mysql_affected_rows($this->cid); }
	
	public function toFetchAry($query,$result_type=1) { return @mysql_fetch_array($query,$result_type); }
	
	public function toFetchField($query) { return @mysql_fetch_field($query); }
	public function toFetchFieldAry($query)
	{
		$re=array();$n=0;
		while($n<@mysql_num_fields($query)){
			$fields=@mysql_fetch_field($query);
			$re[$n]=$fields->name;
			$n++;
		}
		return $re;
	}
	public function toFetchFields($query)
	{
		$re='';$n=0;
		while($n<@mysql_num_fields($query)){
			$field=@mysql_fetch_field($query);
			$re.=','.$fields->name;
			$n++;
		}
		return ltrim($re,',');
	}
	
	public function toFetchStruct($query,$iscomment=0)
	{
		$re=array();$comments=array();$n=0;
		while($n<@mysql_num_fields($query)){
			$fields=@mysql_fetch_field($query);
			$table=$fields->table;
			$tablepx=$table.'.';
			$name=$fields->name;
			if($iscomment&&!$comments['_'.$table.'_']){
				$comments['_'.$table.'_']=$table;
				$sql='SELECT COLUMN_NAME, COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = \''.$table.'\' AND TABLE_SCHEMA = \''.$this->db.'\'';
				$queryc=$this->getQuery('@'.$sql);
				while($aryc=$this->toFetchAry($queryc)){
					$comments[$tablepx.$aryc['COLUMN_NAME']]=$aryc['COLUMN_COMMENT'];
				}
			}
			$re[$n]['name']=$name;					// 列名
			$re[$n]['type']=$this->toFieldType($fields->type);	// 该列的类型
			$re[$n]['default']=$fields->def;			// 默认值
			$re[$n]['len']=$fields->max_length;			// 该列最大长度
			$re[$n]['null']=$fields->not_null;			// 1，如果该列不能为 NULL
			$re[$n]['unsigned']=$fields->unsigned;			// 1，如果该列是无符号数
			$re[$n]['numeric']=$fields->numeric;			// 1，如果该列是 numeric
			$re[$n]['blob']=$fields->blob;				// 1，如果该列是 BLOB
			$re[$n]['zerofill']=$fields->zerofill;			// 1，如果该列是 zero-filled
			$re[$n]['primary_key']=$fields->primary_key;		// 1，如果该列是 primary key
			$re[$n]['unique_key']=$fields->unique_key;		// 1，如果该列是 unique key
			$re[$n]['multiple_key']=$fields->multiple_key;		// 1，如果该列是 non-unique key
			$re[$n]['table']=$table;				// 该列所在的表名
			$re[$n]['comment']=$comments[$tablepx.$name];		// 备注
			$n++;
		}
		return $re;
	}
	public function toFieldType($type)
	{
		$re=$type;
		switch($type) {
			case 'VAR_STRING':		$re = 'string'; break;
			case 'BLOB':			$re = 'text'; break;
			default: $re = $type; break;
		}
		$re=toUpper($re);
		return $re;
	}
	
	public function getStruct($table,$iscomment=0,$iscache=1)
	{
		if(!$this->_struct[$table] && $iscache){
			$this->_struct[$table]=$this->toFetchStruct($this->getQuery('select * from '.$table),$iscomment);
		}
		return $this->_struct[$table];
	}
	public function getStructTable($table,$iscomment=0,$iscache=1)
	{
		$_struct=$this->getStruct($table,$iscomment,$iscache);
		$reTable=new utilTable();
		if($_struct){
			$reTable->setFields(implode(',',array_keys($_struct[0])));
			$treeItem=new utilTree();
			foreach ($_struct as $items){
				foreach ($items as $k=>$v){
					$treeItem->addItem($k,$v);
				}
				$reTable->addItem($treeItem);
			}
		}
		return $reTable;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function getErrorMsg() { return @mysql_error($this->cid); }
	public function getErrorNo() { return @mysql_errno($this->cid); }
	
	
	/*
	function fetch_row($query)
	{
		return mysql_fetch_row($query);
	}
	
	function result($query,$row=0)
	{
		$query=@mysql_result($query,$row,$this->cid);
		if(!is_numeric($tmpnum)) $tmpnum=-1;
		return $query;
	}
	
	function num_rows($query)
	{
		$tmpnum=mysql_num_rows($query);
		return $tmpnum;
	}
	
	function num_fields($query)
	{
		return mysql_num_fields($query);
	}
	
	
	function free_result($query)
	{
		return mysql_free_result($query);
	}
	
	function unbuffered_query($sql,$silence=0)
	{
		$func_unbuffered_query=@function_exists('mysql_unbuffered_query') ? 'mysql_unbuffered_query' : 'mysql_query';
		$query=$func_unbuffered_query($sql);
		if(!$query && !$silence)
		{
			$this->doErrorEvent('query','',$sql,mysql_error(),1);
		}
		$this->_data['sql'][$this->_data['total.query']]=$sql;
		$this->_data['total.query']++;
		return $query;
	}
	*/
}
?>