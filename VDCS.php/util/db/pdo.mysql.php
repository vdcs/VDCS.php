<?
class pdo_mysql extends VDCSDBConstruct
{
	//use DBRefCommon;
	public $dbh;
	
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
		$this->_data['cfg']['opts']=array(	PDO::ERRMODE_EXCEPTION => true,
							PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
							PDO::ATTR_PERSISTENT => false,
							PDO::ATTR_EMULATE_PREPARES=>true);
		//$this->_data['cfg']['opts']=null;
	}
	
	public function __destruct()
	{
		$this->doDisconnect();
		parent::__destruct();
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
		if(isset($this->dbh)) return;
		//debuga($this->_data);
		$this->_data['cfg']['user']=$this->_data['cfg']['user']?$this->_data['cfg']['user']:$this->_data['cfg']['username'];
		//if($this->_data['cfg']['perdure']) $this->_data['cfg']['opts'][PDO::ATTR_PERSISTENT]=true;
		$dsn='mysql:host='.$this->_data['cfg']['server'].';dbname='.$this->_data['cfg']['database'].'';
		try{
			$this->dbh=new PDO($dsn,$this->_data['cfg']['user'],$this->_data['cfg']['password'],$this->_data['cfg']['opts']);
			//$this->dbh->exec('SET CHARACTER SET utf8');
			//$this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
			//if($this->_data['cfg']['perdure']) $this->dbh->setAttribute(PDO::ATTR_PERSISTENT, true);
			$this->cid=true;
		}catch(PDOException $e){
			if($imsg) $this->doErrorEvent('connect','Can not connect to MySQL server','Can not connect to MySQL Server: '.$this->_data['cfg']['server'].' ..','Connection failed: '.utilCoder::toUTF8($e->getMessage()),1);
		}
		if(!$this->cid) return false;
		if($this->cid && $this->_data['cfg']['charset']){
			//if(!$this->_data['cfg']['charset.result']) $this->_data['cfg']['charset.result']=$this->_data['cfg']['charset'];
			/*
			mysql_query('SET CHARACTER SET '.$this->_data['cfg']['charset']);
			mysql_query('SET CHARACTER_SET_CLIENT='.$this->_data['cfg']['charset']);
			mysql_query('SET CHARACTER_SET_CONNECTION='.$this->_data['cfg']['charset']);
			mysql_query('SET CHARACTER_SET_DATABASE='.$this->_data['cfg']['charset']);
			mysql_query('SET CHARACTER_SET_RESULTS='.$this->_data['cfg']['charset.result']);
			*/
			//debugx($this->_data['cfg']['charset']);
			//mysql_query('SET NAMES '.$this->_data['cfg']['charset']);
			//mysql_query('SET CHARACTER SET '.$this->_data['cfg']['charset']);
			$this->dbh->query('SET NAMES '.$this->_data['cfg']['charset']);
		}
		$this->_data['total.query']=0;
		return true;
	}
	
	public function doDisconnect()
	{
		if($this->cid){
			unset($this->dbh);
			$this->cid=false;
		}
	}
	public function doClose(){$this->doDisconnect();}
	
	
	/*
	########################################
	########################################
	*/
	public function isQuery($sql,&$erri=null)
	{
		if(substr($sql,0,1)!='@') $sql='@'.$sql;
		$query=$this->getQuery($sql,$erri);
		return !$erri;
	}
	public function getQuery($sql,&$erri=null)
	{
		if(!$this->cid) $this->doConnect();
		$_err=1;if(substr($sql,0,1)=='@'){$_err=0;$sql=substr($sql,1);}
		//if($this->queryo) $this->queryo->closeCursor();
		$query=$this->dbh->prepare($sql);
		$query->execute();
		if($query->errorCode()=='00000'){
			$this->addSQL($sql);
		}
		else{
			$erri=$query->errorInfo();
			if($_err==1) $this->doErrorEvent('query',$sql,$sql,$erri[2],1);
		}
		//$this->queryo=$query;
		return $query;
	}
	
	public function getQueryRS($sql,$strt=0) { return $this->getQueryAry($sql); }
	public function getQueryAry($sql) { return $this->toFetchAry($this->getQuery($sql)); }
	
	public function getQueryTree($sql)
	{
		$query=$this->getQuery($sql);
		$reTree=new utilTree();
		$reTree->setArray($query->fetch(PDO::FETCH_ASSOC));
		$query->closeCursor();
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
		$query->closeCursor();
		return $reTree;
	}
	
	public function getQueryTable($sql)
	{
		$query=$this->getQuery($sql);
		$reTable=new utilTable();
		$reTable->setFields($this->toFetchFields($query));
		while($rows=$query->fetch(PDO::FETCH_ASSOC)){
			$reTable->addItem($rows);
		}
		$query->closeCursor();
		return $reTable;
	}
	
	public function getQueryValue($sql,$row=0)
	{
		$query=$this->getQuery($sql);
		return $query->fetchColumn($row);
	}
	public function getQueryInt($sql,$row=0) { $re=$this->getQueryValue($sql,$row); return is_numeric($re) ? $re : 0; }
	public function getQueryNum($sql,$row=0) { $re=$this->getQueryValue($sql,$row); return is_numeric($re) ? $re : 0; }
	
	public function toQueryInt($sTable,$act,$sFields='',$sTerm=''){return $this->getQueryInt(DB::sqlSelect($sTable,$act,$sFields,$sTerm));}
	public function toQueryNum($sTable,$act,$sFields='',$sTerm=''){return $this->getQueryNum(DB::sqlSelect($sTable,$act,$sFields,$sTerm));}
	
	
	/*
	########################################
	########################################
	*/
	public function doExec($sql,&$row=-1)
	{
		$re=false;
		if(!$sql) return $re;
		if(!$this->cid) $this->doConnect();
		$_err=true;if(substr($sql,0,1)=='@'){$_err=false;$sql=substr($sql,1);}
		$row=$this->dbh->exec($sql);
		if($row!==FALSE){
			$re=true;
			$this->addSQL($sql);
		}
		elseif($_err){
			$errorInfo=$this->dbh->errorInfo();
			$this->doErrorEvent('exec',$sql,$sql,$errorInfo[2],1);
		}
		return $re;
	}
	public function isExec($sql,&$row=-1){return $this->doExec('@'.$sql,$row);}
	
	public function doExecBatch($sql,$sym=null)
	{
		if(!$this->cid) $this->doConnect();
		if(!$sym) $sym=BATCH_SYMBOL;
		$sqlAry=explode($sym,$sql);
		for($i=0;$i<count($sqlAry);$i++){
			if($sqlAry[$i]){
				$sql=trim($sqlAry[$i]);
				$count=$this->dbh->exec($sql);
				if($count!==FALSE){
					$this->addSQL($sql);
				}
				else{
					$errorInfo=$this->dbh->errorInfo();
					$this->doErrorEvent('exec.batch',$sql,$sql,$errorInfo[2],0);
				}
			}
		}
	}
	
	public function doExecuteInsert($table,$fields,$cDatas,$rDatas=null) { return $this->doExec(self::sqlInsert($table,$fields,$cDatas,$rDatas)); }
	public function doExecuteUpdate($table,$fields,$cDatas,$term='',$rDatas=null) { return $this->doExec(self::sqlUpdate($table,$fields,$cDatas,$term,$rDatas)); }
	
	
	/*
	########################################
	########################################
	*/
	public function isTableExist($table)
	{
		if(!$this->cid) $this->doConnect();
		$re=false;
		try{
			$sql='select count(*) from `'.$table.'`';
			$count=$this->dbh->exec($sql);
			if($count!==FALSE) $re=true;
			
		}catch(PDOException $e){}
		return $re;
	}
	
	public function doTableClear($table) { return $this->doExec('TRUNCATE `'.$table.'`'); }
	public function doTableDelete($table) { return $this->doExec('DROP TABLE IF EXISTS `'.$table.'`'); }
	
	
	/*
	########################################
	########################################
	*/
	public function getInsertID() { return $this->dbh->lastInsertId(); }
	
	public function getAffectedRows() { return $this->dbh->rowCount(); }
	
	public function toFetchAry($query,$result_type=2) { return $query->fetch(PDO::FETCH_ASSOC); }	// PDO::FETCH_ASSOC = 2
	
	public function toFetchField($query) { return $query->fetchColumn(); }
	public function toFetchFieldAry($query)
	{
		$re=array();$n=0;$coln=$query->columnCount();
		while($n<$coln){
			$fields=$query->getColumnMeta($n);
			$re[$n]=$fields['name'];
			$n++;
		}
		return $re;
	}
	public function toFetchFields($query)
	{
		$re='';$n=0;$coln=$query->columnCount();
		while($n<$coln){
			$fields=$query->getColumnMeta($n);
			$re.=','.$fields['name'];
			$n++;
		}
		return ltrim($re,',');
	}
	
	public function toFetchStruct($query,$iscomment=0)
	{
		$re=array();$comments=array();$n=0;$coln=$query->columnCount();
		while($n<$coln){
			$fields=$query->getColumnMeta($n);
			$table=$fields['table'];
			$tablepx=$table.'.';
			$name=$fields['name'];
			if($iscomment&&!$comments['_'.$table.'_']){
				$comments['_'.$table.'_']=$table;
				$sql='SELECT COLUMN_NAME, COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = \''.$table.'\' AND TABLE_SCHEMA = \''.$this->db.'\'';
				$queryc=$this->getQuery('@'.$sql);
				while($aryc=$this->toFetchAry($queryc)){
					$comments[$tablepx.$aryc['COLUMN_NAME']]=$aryc['COLUMN_COMMENT'];
				}
			}
			$flags=$fields['flags'];
			//$re[$n]['flags']=$flags;
			$re[$n]['name']=$name;					// 列名
			$re[$n]['type']=$this->toFieldType($fields['native_type']);			// 该列的类型
			$re[$n]['pdo_type']=$this->toPDOType($fields['pdo_type']);			// 
			$re[$n]['default']=$fields['def'];			// 默认值
			$re[$n]['len']=$fields['len'];				// 该列最大长度
			$re[$n]['precision']=$fields['precision'];		// 精度
			$re[$n]['null']=in_array('not_null',$fields['flags'])?1:0;			// 1，如果该列不能为 NULL
			//$re[$n]['unsigned']=$fields['unsigned'];		// 1，如果该列是无符号数
			$re[$n]['primary_key']=in_array('primary_key',$fields['flags'])?1:0;		// 1，如果该列是 primary key
			$re[$n]['unique_key']=in_array('unique_key',$fields['flags'])?1:0;		// 1，如果该列是 unique key
			$re[$n]['multiple_key']=in_array('multiple_key',$fields['flags'])?1:0;		// 1，如果该列是 non-unique key
			$re[$n]['table']=$table;				// 该列所在的表名
			$re[$n]['comment']=$comments[$tablepx.$name];		// 备注
			$n++;
		}
		return $re;
	}
	public function toFieldType($type)
	{
		$re='';
		switch($type) {
			case 'VAR_STRING':		$re = 'string'; break;
			case 'BLOB':			$re = 'text'; break;
			default: $re = $type; break;
		}
		$re=toUpper($re);
		return $re;
	}
	public function toPDOType($pdo_type)
	{
		$re=$pdo_type;
		switch($pdo_type) {
			case PDO::PARAM_BOOL:		$re = 'BOOL'; break;
			case PDO::PARAM_NULL:		$re = 'NULL'; break;
			case PDO::PARAM_INT :		$re = 'int'; break;
			case PDO::PARAM_STR :		$re = 'string'; break;
			case PDO::PARAM_LOB :		$re = 'LOB'; break;
			case PDO::PARAM_STMT:		$re = 'STMT'; break;
			case PDO::PARAM_INPUT_OUTPUT:	$re = 'INPUT_OUTPUT'; break;
			default: $re = 'NONE'; break;
		}
		$re=toUpper($re);
		return $re;
	}
	
	public function getStruct($table,$iscomment=0,$iscache=1)
	{
		if(!$this->_struct[$table]){
			$query=$this->getQuery('select * from '.$table.' limit 0,1');
			$this->_struct[$table]=$this->toFetchStruct($query,$iscomment);
			unset($query);
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
	public function getErrorMsg() { $errorInfo=$this->dbh->errorInfo();return $errorInfo[2]; }
	public function getErrorNo() { return $this->dbh->errorCode(); }
	
	
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