<?
class libSearch
{
	public $keyword,$field,$term,$time,$time1,$time2;
	protected $_FieldID,$_Fields,$_FieldsProperty,$_FieldType,$_Relations,$_TermType,$mode;
	protected $_Times,$_TimeField,$_TimeType;
	protected $_isInit,$_isParse,$_Query;
	
	
	public function __construct()
	{
		$this->_Fieldtype=-1;
		$this->_TermType=-1;
		$this->_isInit=false;
		$this->_isParse=false;
	}
	public function __destruct(){}
	
	
	/*
	########################################
	########################################
	*/
	public function isCheck()
	{
		$re=false;
		if(!$this->_isInit) $this->doInit();
		if($this->keyword) $re=true;
	}
	
	public function setMode($s){if($s) $this->mode=$s;}
	
	public function setKeyword($s){$this->keyword=self::toKeyword($s);}
	public function setField($s){$this->field=$s;}
	public function setTerm($s){$this->term=$s;}
	public function setTime($time1,$time2=null){$this->time1=$time1;$this->time2=$time2;}
	
	
	/*
	########################################
	########################################
	*/
	public function setFieldID($s){$this->_FieldID=$s;}
	public function setFields($s){$this->_Fields=$s;}
	public function setFieldsProperty($s){$this->_FieldsProperty=$s;}
	public function setRelations($s){$this->_Relations=$s;}
	public function setTimes($s)
	{
		$this->_Times=$s;
		if($this->_Times){
			if(ins($this->_Times,'=')<1) $this->_Times.='=int';
			utilString::lists($this->_Times,$this->_TimeField,$this->_TimeType,'=');
		}
	}
	public function setTermType($s){$this->_TermType=$s;}
	
	
	/*
	########################################
	########################################
	*/
	public function doInit()
	{
		if(!$this->_isInit){
			$this->setKeyword(query('keyword'));
			$this->setField(query('sea_field'));
			$this->setTerm(query('sea_term'));
			$this->setTime(query('sea_time1'),query('sea_time2'));
			$this->_isInit=true;
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function isQuery(){$this->doParse();return $this->_isQuery;}
	public function getQuery(){$this->doParse();return $this->_Query;}
	
	public function toAppendQuery($query,$type='')
	{
		$this->doParse();
		if($this->_isQuery) $query=self::toSQLAppend($query,$this->_Query,$type);
		return $query;
	}
	
	public function getQueryURL($url=null)
	{
		$this->doParse();
		$re='';
		if($this->_isQuery){
			if(len($this->keyword)>0) $re.='&keyword='.DCS::urlEncode($this->keyword);
			if(len($this->field)>0) $re.='&sea_field='.$this->field;
			if(len($this->term)>0) $re.='&sea_term='.$this->term;
			if(len($this->time1)>0) $re.='&sea_time1='.$this->time1;
			if(len($this->time2)>0) $re.='&sea_time2='.$this->time2;
			$re=ltrim($re,'&');
			$re=$url?urlLink($url,$re):$re;
		}
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doParse()
	{
		if(!$this->mode) $this->mode='multi';
		$func='doParse'.ucfirst($this->mode);
		$this->$func();
	}
	
	public function doParseSimple()
	{
		if($this->_isParse) return;$this->_isParse=true;
		if($this->keyword){
			$tmpnum=ins(';'.$this->_Fields.';',';'.$this->field.'=');
			if($tmpnum>0){
				$this->fieldValue=toSubstr($this->_Fields,$tmpnum+len($this->field)+1);
				if(ins($this->fieldValue,';')>0) $this->fieldValue=toSubstr($this->fieldValue,1,ins($this->fieldValue,';')-1);
				utilString::lists($this->fieldValue,$this->_FieldType,$this->fieldReal,',');
				//if(len($this->fieldReal)<1) $this->fieldReal=$this->field;
			}
			else{
				$this->field='';
				$this->fieldReal='';
			}
			if($this->field){
				$this->_Query=$this->toQuery($this->field,$this->fieldReal,$this->_FieldType,$this->keyword,$this->term,$this->_FieldID,$this->_Relations);
				//debugx($this->_Query);
				//$this->_Query='';
			}
		}
		if($this->_TimeField){
			$this->_Query=self::toSQLAppend($this->_Query,$this->toQueryTime($this->_TimeField,$this->_TimeType,$this->time1,$this->time2));
		}
		$this->_isQuery=len($this->_Query)>0 ? true : false;
	}
	
	public function doParseMulti()
	{
		if($this->_isParse) return;$this->_isParse=true;
		if($this->keyword){
			$fieldsAry=utilString::toAry($this->_Fields,';');
			foreach($fieldsAry as $v){
				$this->field=strstr($v,'=',true);
				$tmpnum=ins(';'.$this->_Fields.';',';'.$this->field.'=');
				if($tmpnum>0){
					$this->fieldValue=toSubstr($this->_Fields,$tmpnum+len($this->field)+1);
					if(ins($this->fieldValue,';')>0){
						$this->fieldValue=toSubstr($this->fieldValue,1,ins($this->fieldValue,';')-1);
					}
					utilString::lists($this->fieldValue,$this->_FieldType,$this->fieldReal,',');
					//if(len($this->fieldReal)<1) $this->fieldReal=$this->field;
				}
				else{
					$this->field='';
					$this->fieldReal='';
				}
				if($this->field){
					$sql=$this->toQuery($this->field,$this->fieldReal,$this->_FieldType,r($this->keyword,' ',''),$this->term,$this->_FieldID,$this->_Relations);
					//$this->_Query.='or '.$sql;
					if($sql) $this->_Query.=' or '.$sql;
				}
			}
			$this->_Query=ltrim($this->_Query,' or');
			//$this->_Query='';
		}
		if($this->_TimeField){
			$this->_Query=self::toSQLAppend($this->_Query,$this->toQueryTime($this->_TimeField,$this->_TimeType,$this->time1,$this->time2));
		}
		$this->_isQuery=len($this->_Query)>0 ? true : false;
	}
	
	public function toQuery($field,$fieldReal,$fieldType,$strKeyword,$strTerm,$fieldid=null,$relations=null)
	{
		$re='';
		if(!is_numeric($fieldType)){
			$strKeyword='';
			$fieldType=-1;
		}
		else{
			if($fieldType<0) $strKeyword='';
		}
		if($fieldType>5){
			$tmpCT='\'';
			if($fieldType>8 && !$strKeyword) $strKeyword='';			//?????
		}
		else{
			if(!is_numeric($strKeyword)) $strKeyword='';
		}
		if($strKeyword){
			if($fieldType!=5 && $fieldType!=6 && $fieldType!=8 && ins(',>,>=,<,<=,',','.$strTerm.',')<1) $strTerm='=';
			if($strTerm=='exact') $strTerm='=';
			switch($strTerm){
				//case 'exact'
				//	re=field&'='&tmpCT&strKeyword&tmpCT
				case '=':
				case '>':
				case '>=':
				case '<':
				case '<=':
					$exp=$tmpCT.$strKeyword.$tmpCT;
					$exps='='.$exp;
					$re='{field}{exps}';
					break;
				default:
					if($strTerm!='and') $strTerm='or';
					$tmpAry=toSplit($strKeyword,' ');
					for($a=0;$a<count($tmpAry);$a++){
						$exp=$tmpCT.'%'.$tmpAry[$a].'%'.$tmpCT;
						$exps=' like '.$exp;
						if($tmpAry[$a]) $re.=' '.$strTerm.' {field} like '.$tmpCT.'%'.$tmpAry[$a].'%'.$tmpCT;
					}
					if($re) $re=toSubstr($re,len($strTerm)+3);
					break;
			}
			$isua=false;
			if(inp('uname,uuname,username',$field)>0){
				$re=UaExtendManage::toSearchQuery($re);
				$isua=true;
			}
			//debugx($field.','.$fieldReal);
			if(!$isua && $relations){
				$treeRelations=utilString::toTree(utilString::toFilterBlank($relations),'$$$','=');
				//db_account=email,name,names@uuid,uid;db_account_info=realname,phone@uuid,uid
				//uuid=uid@db_account_info,uid$$$
				//linkman=uid@sql:select uuid from dbu_linkman where {query}
				$relation=$treeRelations->getItem($field);
				//debugx($field.': '.$relation);
				if($relation){
					if(ins($relation,'@')<1) $relation=$field.'@'.$relation;
					utilString::lists($relation,$_fieldid,$_value,'@');
					//debugx($field.','.$fieldReal.' = '.$_fieldid.'@'.$_value);
					if(left($_value,4)=='sql:'){
						$sql=toSubstr($_value,5);
						$sql=rv($sql,'query',rv($re,'field',$_fieldid));
						//$sql=rv($sql,'exp',$exp);
					}
					else{
						if(ins($_value,',')<1) $_value.=','.$fieldid;
						utilString::lists($_value,$_tablename,$_tableid,',');
						$sql='select '.$_tableid.' from '.$_tablename.' where '.rv($re,'field',$_fieldid).'';
						//debugx($sql);
					}
					$re=($fieldReal?$fieldReal:$fieldid).' in ('.$sql.')';
					//debugx('query: '.$re);
				}
			}
			if(!$fieldReal) $fieldReal=$field;
			$re=r($re,'{field}','`'.$fieldReal.'`');
			$re=r($re,'{exp}',$exp);
			$re=r($re,'{exps}',$exps);
			if($re) $re='('.$re.')';
		}
		return $re;
	}
	
	public function toQueryTime($field,$fieldType,$date1,$date2=''){return DB::sqlSerachTime($field,$fieldType,$date1,$date2);}
	
	
	/*
	########################################
	########################################
	*/
	public static function toSQLAppend($re,$query,$type='')
	{
		if($query){
			if(!$type) $type='and';
			if($re) $re.=' '.$type.' ';
			$re.=$query;
		}
		return $re;
	}
	
	public static function toKeyword($strer)
	{
		$re=$strer;
		//$re=r($re,'\','\\');
		$re=r($re,'\'','');
		$re=r($re,'\"','');
		$re=r($re,';','');
		$re=r($re,chr(9),'');
		$re=r($re,chr(10),'');
		$re=r($re,chr(13),'');
		$re=r($re,',',' ');
		return $re;
	}
}
