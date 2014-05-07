<?
class StructData
{
	/*
	const TableName			= 'tablename';
	const TablePX			= '';
	const FieldID			= 'id';
	*/


	public static function filterTree(&$tree)
	{
		return $tree;
	}
	public static function filterList(&$tableData)
	{
		/*
		$tableData->doAppendFields('status.name');
		$tableData->doBegin();
		while($tableData->isNext()){
			$tableData->setItemValue('status.name',$treeStatus->getItem($tableData->getItemValue('status')));
		}
		*/
		return $tableData;
	}

	public static function filterQuery($query)
	{
		if(ins($query,'=')<1) $query=static::FieldID.'='.$query;
		return $query;
	}
	public static function parami(&$id,&$tree=null)
	{
		$re=true;
		if(!iso($id)){
			//$id=toInt($id);
			if(!$tree) $tree=static::getTree($id);
			if($tree->getCount()<1) $re=false;
		}else{
			$tree=$id;
			$id=$tree->getItem('id');
			if(!$id) $re=false;
		}
		return $re;
	}
	public static function isCheck($id,$ua=null,&$tree=null)
	{
		$_status=1;
		static::parami($id,$tree);
		if(!$tree || $tree->getCount()<1){
			$_status=0;
		}
		else if($ua && $tree->getItemInt('uuid')!=$ua->id){
			$_status=-5;
		}
		return $_status;
	}
	public static function getTree($query,$filter=true)
	{
		$tree=newTree();
		if(!$query) return $tree;
		$sqlQuery=static::filterQuery($query);
		//debugx($sqlQuery);
		$sql=DB::sqlSelect(static::TableName,'','*',$sqlQuery,null,1);
		//debugx($sql);
		$tree=DB::queryTree($sql);
		if($filter && $tree->getCount()>0) static::filterTree($tree);
		return $tree;
	}

	
	
	public static function add($values,$fields=null){return static::insert($values,$fields);}			//hold
	public static function up($query,$values,$fields=null){return static::update($query,$values,$fields);}		//hold
	public static function del($values){return static::delete($values);}						//hold
	
	public static function insert($values,$fields=null)
	{
		$sql=DB::sqlInsertx(static::TableName,$fields,$values);
		//debugx($sql);
		$isexec=DB::exec($sql);
		$newid=DB::insertid();
		return $newid;
	}
	public static function update($query,$values,$fields=null)
	{
		$sqlQuery=static::filterQuery($query);
		$sql=DB::sqlUpdate(static::TableName,$fields,$values,$sqlQuery);
		return DB::exec($sql);
	}
	public static function delete($query)
	{
		$sqlQuery=static::filterQuery($query);
		return DB::exec(DB::sqlDelete(static::TableName,$sqlQuery));
	}

	public static function set($query,$values)
	{
		$sqlQuery=static::filterQuery($query);
		$sql='update '.static::TableName.' set '.$values;
		if($sqlQuery) $sql.=' where '.$sqlQuery;
		return DB::exec($sql);
	}


	public static function query($query='',$order=null,$limit=0)
	{
		if(!is_array($query)){
			$params=['query'=>$query,'order'=>$order,'limit'=>$limit];
		}
		else $params=$query;
		return static::getTable($params);
	}
	public static function getTable($params)
	{
		if(!is_array($params)){
			$query=$params;
			$params=['fields'=>'','query'=>$query,'order'=>'','limit'=>0];
		}
		if(!$params['table']) $params['table']=static::TableName;
		$sql=DB::sqlQuery($params['table'],$params['fields'],$params['query'],$params['order'],$params['limit']);
		$tableData=DB::queryTable($sql);
		$tableData=static::filterList($tableData);
		return $tableData;
	}

	public static function querier($ua,&$p=null,$params=array())
	{
		$params['table']=static::TableName;
		VDCSFCA::querier($p,$params);
		$p->setTotal(DB::queryInt($p->getSQL('count')));
		$p->doParse();
		$tableData=DB::queryTable($p->getSQL('query'));
		$tableData=static::filterList($tableData);
		return $tableData;
	}

}
