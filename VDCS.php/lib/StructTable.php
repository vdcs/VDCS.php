<?
class StructTable
{
	protected $TableName='',$TablePX='',$FieldID='';
	protected $_var=array();
	protected $Fielda=array();
	
	public function __construct($table='',$id='',$opt=[])
	{
		$this->TableName=$table;
		$this->TablePX=$opt['px'];
		$this->FieldID=$id;
		$this->_var['query']='';
		$this->_var['order']='';
		$this->_var['limit']='';
	}
	public function __destruct(){}
	
	
	public function __toString(){return $this->TableName;}
	
	
	public function table($value){$this->TableName=$value;return &$this;}
	public function where($query,$term=''){$this->_var['query']=DB::sqla($this->_var['query'],$query,$term);return &$this;}
	public function order($value){$this->_var['order']=$value;return &$this;}
	public function limit($start,$row=null){$this->_var['limit']=DB::sqlLimit($start,$row);return &$this;}
	
	
	public function count(){return DB::exec(DB::sqlSelect($this->TableName,'count','',$this->_var['query']);}
	public function sql(){return DB::sqlSelect($this->TableName,'','*',$this->_var['query'],$this->_var['order'],$this->_var['limit']);}
	public function exec(){return DB::exec($this->sql());}
	public function query(){return DB::query($this->sql());}
	public function queryi(){return DB::queryInt($this->sql());}public function queryInt(){return DB::queryInt($this->sql());}
	public function queryn(){return DB::queryNum($this->sql());}public function queryNum(){return DB::queryNum($this->sql());}
	public function queryTree(){return DB::queryTree(DB::sqlSelect($this->TableName,'','*',$this->_var['query'],$this->_var['order'],1));}
	public function queryTable(){return DB::queryTable($this->sql());}
	
	public function insert($values){return DB::exec(DB::sqlInsert($this->TableName,null,$values);}
	public function insertx($values){return DB::exec(DB::sqlInsertx($this->TableName,null,$values);}
	public function update($values){return DB::exec(DB::sqlUpdate($this->TableName,null,$values,$this->_var['query']));}
	public function updatex($values){return DB::exec(DB::sqlUpdatex($this->TableName,null,$values,$this->_var['query']));}
	public function delete($values){return DB::exec(DB::sqlDelete($this->TableName,$this->_var['query']));}
	
	
	public function exist(){return DB::isTableExist($this->TableName);}
	public function clear(){return DB::exec('TRUNCATE '.$this->TableName);}public function truncate(){return $this->clear();}
	public function optimize(){return DB::exec('OPTIMIZE TABLE '.$this->TableName, 'SILENT');}
}
