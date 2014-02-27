<?
class VDCSXCML extends XCML
{
	//const NodeItem				= 'item';
	const NodeFieldForm			= 'type,property,style,caption,att,value,explain';
	
	
	public static function XMLHeader() { return '<?xml version="1.0" encoding="'.CHARSET_XML.'"?>'; }
	
	public static function newFormTable()
	{
		$reTable=newTable();
		$reTable->setFields(self::NodeFieldForm);
		return $reTable;
	}
	
	public static function toXMLValue($s) { return utilCode::toXMLValue($s); }
	
	
	/*
	########################################
	########################################
	*/
	public static function getXMLDefault($bodys='',$strItem='',$strFields='')
	{
		if(len($strItem)<1) $strItem=XCML_NODENAME_ITEM;
		//if(!$strFields) $strFields=self::NodeFieldForm;
		$re=self::XMLHeader();
		$re.=NEWLINE.'<'.XCML_ROOT.' version="1.0" model="data">';
		$re.=NEWLINE.XCML_PRESPACE.'<'.XCML_NODENAME_CONFIGURE.'>';
		$re.=NEWLINE.XCML_PRESPACE2.'<node>'.$strItem.'</node>';
		if($strFields) $re.=NEWLINE.XCML_PRESPACE2.'<field>'.$strFields.'</field>';
		$re.=NEWLINE.XCML_PRESPACE.'</'.XCML_NODENAME_CONFIGURE.'>';
		if($bodys) $re.=$bodys;
		$re.=NEWLINE.'</'.XCML_ROOT.'>';
		return $re;
	}
	
	public static function toXMLNodeTree($strTree,&$node='var')
	{
		$re='';
		$re.=NEWLINE.XCML_PRESPACE.'<'.$node.'>';
		$strTree->doBegin();
		for($i=0;$i<$strTree->getCount();$i++){
			$re.=NEWLINE.XCML_PRESPACE2.'<'.$strTree->getItemKey().'>'.self::toXMLValue($strTree->getItemValue()).'</'.$strTree->getItemKey().'>';
			$strTree->doMove();
		}
		$re.=NEWLINE.XCML_PRESPACE.'</'.$node.'>';
		return $re;
	}
	public static function toXMLNodeTable($strTable,&$strItem='item',&$strFields='')
	{
		if(len($strItem)<1) $strItem=XCML_NODENAME_ITEM;
		if(len($strFields)<1) $strFields=$strTable->getFields();
		$aryItem=toSplit($strFields,',');
		$re='';
		$strTable->doItemBegin();
		for($t=0;$t<$strTable->getRow();$t++){
			$re.=NEWLINE.XCML_PRESPACE.'<'.$strItem.'>';
			for($s=0;$s<count($aryItem);$s++){
				$re.=NEWLINE.XCML_PRESPACE2.'<'.$aryItem[$s].'>'.self::toXMLValue($strTable->getItemValue($aryItem[$s])).'</'.$aryItem[$s].'>';
			}
			$re.=NEWLINE.XCML_PRESPACE.'</'.$strItem.'>';
			$strTable->doItemMove();
		}
		return $re;
	}
	
	public static function toTableXML($strTable,$strItem='item',$strFields='')
	{
		if(len($strItem)<1) $strItem=XCML_NODENAME_ITEM;
		if(len($strFields)<1) $strFields=$strTable->getFields();
		$re='';
		$re.=self::toXMLNodeTable($strTable,$strItem,$strFields);
		return self::getXMLDefault($re,$strItem,$strFields);
	}
	public static function toMapXML($strTree,$strTable='',$strItem='item',$strFields='')
	{
		if(isMap($strTree)){
			return self::toMapsXML($strTree);
		}
		else{
			if(!isTable($strTable)) $strTable=newTable();
			if(len($strItem)<1) $strItem=XCML_NODENAME_ITEM;
			if(len($strFields)<1) $strFields=$strTable->getFields();
			$node='var';
			$re='';
			$re.=self::toXMLNodeTree($strTree,$node);
			$re.=self::toXMLNodeTable($strTable,$strItem,$strFields);
			return self::getXMLDefault($re,$strItem,$strFields);
		}
	}
	public static function toMapsXML($maps)
	{
		$vxml=array();
		$vxml['node']='';$vxml['trees']='';
		$vxml['nodes']='';$vxml['tables']='';$vxml['fields']='';
		$maps->doBegin();
		while($maps->isNext()){
			switch($maps->getItemValue('type')){
				case 'tree':
					$key=$maps->getItemValue('key');
					$vxml['node'].=','.$key;
					$vxml['trees'].=self::toXMLNodeTree($maps->getItemTree(),$key);
					break;
				case 'table':
					$key=$maps->getItemValue('key');
					$vxml['tData']=$maps->getItemTable();
					$vxml['nodes'].=','.$key;
					$vxml['tables'].=self::toXMLNodeTable($vxml['tData'],$key);
					$fields=$vxml['tData']->getFields();
					if($fields) $vxml['fields'].=NEWLINE.XCML_PRESPACE2.'<field.'.$key.'>'.$vxml['tData']->getFields().'</field.'.$key.'>';
					break;
			}
		}
		if($vxml['node']) $vxml['node']=substr($vxml['node'],1);
		if($vxml['nodes']) $vxml['nodes']=substr($vxml['nodes'],1);
		$re=self::XMLHeader();
		$re.=NEWLINE.'<'.XCML_ROOT.' version="1.0" model="map">';
		$re.=NEWLINE.XCML_PRESPACE.'<'.XCML_NODENAME_CONFIGURE.'>';
		$re.=NEWLINE.XCML_PRESPACE2.'<node>'.$vxml['node'].'</node>';
		$re.=NEWLINE.XCML_PRESPACE2.'<nodes>'.$vxml['nodes'].'</nodes>';
		$re.=$vxml['fields'];
		$re.=NEWLINE.XCML_PRESPACE.'</'.XCML_NODENAME_CONFIGURE.'>';
		$re.=$vxml['trees'];
		$re.=$vxml['tables'];
		$re.=NEWLINE.'</'.XCML_ROOT.'>';
		unsetr($vxml);
		return $re;
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toStringbyTree($treeData,$strModel=null,$strCharset=null)
	{
		$re='';
		$node=$treeData->getItem('__node');
		$arNode=toSplit($node,',');
		for($a=0;$a<count($arNode);$a++){
			$item=$arNode[$a];
			$items=$item.'.';
			$itemLen=strlen($item)+1;
			$re.=NEWLINE.'	<'.$item.'>';
			$treeData->doBegin();
			for($t=0;$t<$treeData->getCount();$t++){
				$k=$treeData->getItemKey();
				if(substr($k,0,$itemLen)==$items){
					$k=substr($k,$itemLen);
					$re.=NEWLINE.'		<'.$k.'>'.self::toXMLValue($treeData->getItemValue()).'</'.$k.'>';
				}
				$treeData->doMove();
			}
			$re.=NEWLINE.'	</'.$item.'>';
		}
		return self::toStringFrame($re,$node,null,$strModel,$strCharset);
	}
	
	public static function toStringbyTable($tableData,$strNode=null,$strField=null,$treeConfigAppend=null,$strModel=null,$strCharset=null)
	{
		$re='';
		if($strNode==null) $strNode='item';
		if($strField==null) $strField=$tableData->getFields();
		$arField=toSplit($strField,',');
		$arFields=array();
		$fields='';
		for($a=0;$a<count($arField);$a++){
			$k=$v=$arField[$a];
			$s=ins($k,'=');
			if($s>0) { $v=substr($k,$s); $k=substr($k,0,$s-1); }
			$arFields[$k]=$v;
			$fields.=','.$k;
		}
		if($fields) $fields=substr($fields,1);
		$tableData->doItemBegin();
		for($t=0;$t<$tableData->getRow();$t++){
			$re.=NEWLINE.'	<'.$strNode.'>';
			foreach($arFields as $k=>$v){
				if($k && $v) $re.=NEWLINE.'		<'.$k.'>'.self::toXMLValue($tableData->getItemValue($v)).'</'.$k.'>';
			}
			$re.=NEWLINE.'	</'.$strNode.'>';
			$tableData->doItemMove();
		}
		return self::toStringFrame($re,$strNode,$fields,$strModel,$strCharset);
	}
	
	
	public static function toStringFrame($strer,$strNode=null,$strField=null,$strModel=null,$strCharset=null)
	{
		$re='';
		if($strNode==null) $strNode='item';
		if($strCharset==null) $strCharset=CHARSET_CONFIG;
		if($strModel==null) $strModel='data';
		$re="<?xml version=\"1.0\" encoding=\"".$strCharset."\"?>";
		$re.=NEWLINE."<xcml version=\"1.0\" model=\"".$strModel."\">";
		$re.=NEWLINE."	<configure>";
		$re.=NEWLINE."		<node>".$strNode."</node>";
		if($strField!=null) $re.=NEWLINE."		<field>".$strField."</field>";
		$re.=NEWLINE."	</configure>";
		$re.=$strer;
		$re.=NEWLINE.'</xcml>';
		return $re;
	}
	
	public static function toOjbectVars($o)
	{
		$re='';
		foreach($o as $k=>$v){
			$re.=NEWLINE.'<'.$k.'>'.self::toXMLValue($v).'</'.$k.'>';
		}
		return $re;
	}
	public static function toTreeVars($strTree)
	{
		$strTree->doBegin();
		for($t=0;$t<$strTree->getCount();$t++){
			$re.=NEWLINE.'<'.$strTree->getItemKey().'>'.self::toXMLValue($strTree->getItemValue()).'</'.$strTree->getItemKey().'>';
			$strTree->doMove();
		}
		return $re;
	}
}
?>