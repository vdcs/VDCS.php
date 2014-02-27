<?
class StructTable
{
	protected $TableName='',$TablePX='',$FieldID='';
	protected $Fielda=array();
	
	public function __construct($table='',$px='',$id='')
	{
		$this->TableName=$table;
		$this->TablePX=$px;
		$this->FieldID=$id;
		$this->__constructExtend();
	}
	public function __destruct(){}
	
	
	protected function __constructExtend(){}
	public function __toString(){return $this->TableName;}


	public function getTable(){return $this->TableName;}
	public function setTable($name){return $this->TableName=$name;}

	
	public function count(){
		$count=(int) DB::result_first("SELECT count(*) FROM ".DB::table($this->TableName));
		return $count;
	}

	public function update($val, $data, $unbuffered=false, $low_priority=false){
		if(isset($val) && !empty($data) && is_array($data)){
			$this->checkpk();
			$ret=DB::update($this->TableName, $data, DB::field($this->_pk, $val), $unbuffered, $low_priority);
			foreach((array)$val as $id){
				$this->update_cache($id, $data);
			}
			return $ret;
		}
		return !$unbuffered ? 0 : false;
	}

	public function delete($val, $unbuffered=false){
		$ret=false;
		if(isset($val)){
			$this->checkpk();
			$ret=DB::delete($this->TableName, DB::field($this->_pk, $val), null, $unbuffered);
			$this->clear_cache($val);
		}
		return $ret;
	}

	public function truncate(){
		DB::query("TRUNCATE ".DB::table($this->TableName));
	}

	public function insert($data, $return_insert_id=false, $replace=false, $silent=false){
		return DB::insert($this->TableName, $data, $return_insert_id, $replace, $silent);
	}

	public function checkpk(){
		if(!$this->_pk){
			throw new DbException('Table '.$this->TableName.' has not PRIMARY KEY defined');
		}
	}

	public function fetch($id, $force_from_db=false){
		$data=array();
		if(!empty($id)){
			if($force_from_db || ($data=$this->fetch_cache($id)) === false){
				$data=DB::fetch_first('SELECT * FROM '.DB::table($this->TableName).' WHERE '.DB::field($this->_pk, $id));
				if(!empty($data)) $this->store_cache($id, $data);
			}
		}
		return $data;
	}

	public function fetch_all($ids, $force_from_db=false){
		$data=array();
		if(!empty($ids)){
			if($force_from_db || ($data=$this->fetch_cache($ids)) === false || count($ids) != count($data)){
				if(is_array($data) && !empty($data)){
					$ids=array_diff($ids, array_keys($data));
				}
				if($data === false) $data =array();
				if(!empty($ids)){
					$query=DB::query('SELECT * FROM '.DB::table($this->TableName).' WHERE '.DB::field($this->_pk, $ids));
					while($value=DB::fetch($query)){
						$data[$value[$this->_pk]]=$value;
						$this->store_cache($value[$this->_pk], $value);
					}
				}
			}
		}
		return $data;
	}

	public function fetch_all_field(){
		$data=false;
		$query=DB::query('SHOW FIELDS FROM '.DB::table($this->TableName), '', 'SILENT');
		if($query){
			$data=array();
			while($value=DB::fetch($query)){
				$data[$value['Field']]=$value;
			}
		}
		return $data;
	}

	public function range($start=0, $limit=0, $sort=''){
		if($sort){
			$this->checkpk();
		}
		return DB::fetch_all('SELECT * FROM '.DB::table($this->TableName).($sort ? ' ORDER BY '.DB::order($this->_pk, $sort) : '').DB::limit($start, $limit), null, $this->_pk ? $this->_pk : '');
	}

	public function optimize(){
		DB::query('OPTIMIZE TABLE '.DB::table($this->TableName), 'SILENT');
	}

	public function attach_before_method($name, $fn){
		$this->methods[$name][0][]=$fn;
	}

	public function attach_after_method($name, $fn){
		$this->methods[$name][1][]=$fn;
	}

}
