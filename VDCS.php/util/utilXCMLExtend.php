<?
class utilXCMLExtend
{
	
	public static function toXMLTree($re,$k='',$v='')
	{
		$xcml=new utilXCML();
		//$xcml->loadFile($re);
		$xcml->loadXML($re);
		//debugx($xcml->getErrorType());
			$xcml->doParse();
		return XCMLParser::toTree($xcml,$k,$v);
	}
	public static function toTree($xcml,$k='',$v='')
	{
		$reTree=new utilTree();
		if($xcml && isXCML($xcml)){
			if($k && $v){
				$xcml->doItemBegin();
				for($i=1;$i<=$xcml->getItemCount();$i++){
					$reTree->addItem($xcml->getItem($k),$xcml->getItem($v));
					$xcml->doItemMove();
				}
			}
			else{
				$tmpNode=$xcml->getConfigureItem();
				$reTree->addItem(XCML_NODE_NAMES,$tmpNode);
				if(len($xcml->getConfigureField())>0){				//常规节点模式
					$xcml->doParseItem();
					$xcml->doItemBegin();
					for($i=1;$i<=$xcml->getItemCount();$i++){
						$tmpTree=$xcml->getItemTree();
						$reTree->addItem($tmpNode.XCML_NODE_CONNECT.$i,XCML_NODE_EXIST);
						$tmpTree->doBegin();
						for($n=1;$n<=$tmpTree->getCount();$n++){
							$reTree->addItem($tmpNode.XCML_NODE_CONNECT.$i.XCML_NODE_CONNECT.$tmpTree->getItemKey(),$tmpTree->getItemValue());
							$tmpTree->doMove();
						}
						$xcml->doItemMove();
					}
				}
				else{								//Key节点模式
					$aryNode=toSplit($tmpNode,',');
					for($i=1;$i<=count($aryNode);$i++){
						$_node=$aryNode[$i-1];
						$xcml->doParseNode($_node);
						$tmpTree=$xcml->getItemTree();
						$reTree->addItem($_node,XCML_NODE_EXIST);
						$tmpTree->doBegin();
						for($n=1;$n<=$tmpTree->getCount();$n++){
							$reTree->addItem($_node.XCML_NODE_CONNECT.$tmpTree->getItemKey(),$tmpTree->getItemValue());
							$tmpTree->doMove();
						}
					}
				}
			}
		}
		return $reTree;
	}

	public static function toTable($xcml)
	{
		$reTable=new utilTable();
		if($xcml && isXCML($xcml)){
			if(len($xcml->getConfigureField())>0){
				$reTable->setFields($xcml->getConfigureField());
				$xcml->doParseItem();
				$xcml->doItemBegin();
				for($i=1;$i<=$xcml->getItemCount();$i++){
					$reTable->addItem($xcml->getItemTree());
					$xcml->doItemMove();
				}
			}
			else{
				//debugx($xcml->getConfigureItem());
				$tmpNode=$xcml->getConfigureItem();
				if(len($tmpNode)>0){
					$reTable->setFields(XCML_NODE_NAMES);
					$aryNode=toSplit($tmpNode,',');
					for($i=1;$i<=count($aryNode);$i++){
						$_node=$aryNode[$i-1];
						$xcml->doParseNode($_node);
						$tmpTree=$xcml->getItemTree();
						$tmpTree->addItem(XCML_NODE_NAMES,$_node);
						//$reTable->doAppendFields($tmpTree->getFields());
						$reTable->addItem($tmpTree);
						$xcml->doItemMove();
					}
				}
			}
		}
		return $reTable;
	}
	
	public static function toMap($xcml)
	{
		$reMap=new utilMap();
		if($xcml && isXCML($xcml)){
			$tmpNode=$xcml->getConfigureItem();
			if(!$tmpNode) $tmpNode='var';
			$aryNode=toSplit($tmpNode,',');
			for($i=1;$i<=count($aryNode);$i++){
				$_node=$aryNode[$i-1];
				$xcml->doParseNode($_node);
				$tmpTree=$xcml->getItemTree();
				$tmpTree->doBegin();
				$reTree=new utilTree();
				for($n=1;$n<=$tmpTree->getCount();$n++){
					$reTree->addItem($tmpTree->getItemKey(),$tmpTree->getItemValue());
					$tmpTree->doMove();
				}
				//debugTree($reTree);
				$reMap->addItem($_node,$reTree,'tree');
			}
			$tmpNode=$xcml->getConfigureValue('nodes');
			$aryNode=toSplit($tmpNode,',');
			for($i=1;$i<=count($aryNode);$i++){
				$_node=$aryNode[$i-1];
				$xcml->doParseNode($_node);
				$reTable=new utilTable();
				$reTable->setFields($xcml->getConfigureValue('field.'.$_node));
				$xcml->doParseItem($_node);
				$xcml->doItemBegin();
				for($n=1;$n<=$xcml->getItemCount();$n++){
					$reTable->addItem($xcml->getItemTree());
					$xcml->doItemMove();
				}
				//debugTable($reTable);
				$reMap->addItem($_node,$reTable,'table');
			}
			
			
		}
		return $reMap;
	}
	
	
	function data_to_xml($data) {
		if (is_object($data)) {
			$data = get_object_vars($data);
		}
		$xml = '';
		foreach ($data as $key => $val) {
			is_numeric($key) && $key = "item id=\"$key\"";
			$xml.="<$key>";
			$xml.= ( is_array($val) || is_object($val)) ? data_to_xml($val) : $val;
			//utilString::lists($key,$key,$val,' ');
			list($key, ) = explode(' ', $key);
			$xml.="</$key>";
		}
		return $xml;
	}
}
?>