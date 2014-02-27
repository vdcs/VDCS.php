<?
class VDCSXCMLExtend extends VDCSXCML
{
	//const NODE_NAMES			= '__node';
	//const NODE_EXIST			= '__yes';
	//const NODE_CONNECT_SYMBOL		= '.';
	
	//const PrefixSymbol			= '__';
	//const ATT_CONNECT_SYMBOL		= '@';
	
	
	public static function doFileUpdate($path,$datas)
	{
		$_xml=isTable($datas)?self::toTableXML($datas):self::toTreeXCML($datas);
		//debugx($path);
		//debugvc($_xml);
		utilFile::doFileWrite($path,$_xml);
		unset($_xml);
	}
	
	public static function doItemUpdate($strTree,$strUpdateTree,$strField,$strItemNum=0)
	{
		$tmpNodes=$strTree->getItem('configure.field');
		$tmpNodeName=$strTree->getItem(XCML_NODE_PX.'node');
		
		if($strItemNum > 0){
			$aryNode=toSplit($tmpNodes,',');
			for($a=0;$a<count($aryNode);$a++){
				$tmpFieldName=$aryNode[$a];
				$tmpFieldValue=$strUpdateTree->getItem($tmpFieldName);
				$tmpNode=$tmpNodeName.'.'.$strItemNum.'.'.$tmpFieldName;
				
				//debugx($tmpNode.' - '.$tmpFieldName.' - '.$tmpFieldValue);
				$strTree->setItem($tmpNode,$tmpFieldValue);
			}
		}
		else{
			if( strpos(','.$tmpNodes.',',','.$strField.',')>=0 ){
				$tmpCount=$strTree->getItem(XCML_NODE_PX.$tmpNodeName.'.count');
				$tmpUpdateValue=$strUpdateTree->getItem($strField);
				
				for($i=1;$i <= $tmpCount;$i++){
					$tmpNode=$tmpNodeName.'.'.$i.'.'.$strField;
					$tmpValue=$strTree->getItem($tmpNode);
					
					if($tmpValue == $tmpUpdateValue){
						$aryNode=toSplit($tmpNodes,',');
						for($a=0;$a<count($aryNode);$a++){
							$tmpFieldName=$aryNode[$a];
							$tmpFieldValue=$strUpdateTree->getItem($tmpFieldName);
							$tmpNode=$tmpNodeName.'.'.$i.'.'.$tmpFieldName;
							
							//debugx($tmpNode.' - '.$tmpFieldName.' - '.$tmpFieldValue);
							$strTree->setItem($tmpNode,$tmpFieldValue);
						}
						
					}
				}
			}
		}
		return $strTree;
	}
	
	public static function toTree($strer)
	{
		$reTree=new utilTree();
		$tmpTree=new utilTree();
		$objXCML=new utilXCML();
		
		$objXCML->loadXML($strer);
		
		if($objXCML->isObj()){
			$reTree->addItem(XCML_NODE_PX.'basic.'.XCML_ROOT.'.version',$objXCML->getBasicXCML('@version'));
			$reTree->addItem(XCML_NODE_PX.'basic.'.XCML_ROOT.'.model',$objXCML->getBasicXCML('@model'));
			
			$reTree->doAppendTree($objXCML->getConfigureTree(),'configure.');
			$tmpNode=$objXCML->getConfigureItem();
			
			$reTree->addItem(XCML_NODE_NAMES,$tmpNode);
			
			if(len($objXCML->getConfigureField()) > 0){		//常规节点模式
				$reTree->addItem(XCML_NODE_PX.''.XCML_ROOT.'.mode','item');
				$objXCML->doParseItem();
				$objXCML->doItemBegin();
				$reTree->addItem(XCML_NODE_PX.$tmpNode.'.count',$objXCML->getItemCount());
				for($i=1;$i <= $objXCML->getItemCount();$i++){
					
					$tmpTree=$objXCML->getItemAttTree();
					$tmpTree->doBegin();
					for($t=1;$t <= $tmpTree->getCount();$t++){
						$reTree->addItem($tmpNode.XCML_NODE_CONNECT.$i.XCML_NODE_ATT.$tmpTree->getItemKey(),$tmpTree->getItemValue());
						$tmpTree->doMove();
					}
					
					$tmpTree=$objXCML->getItemTree();
					$reTree->addItem($tmpNode.XCML_NODE_CONNECT.$i,XCML_NODE_EXIST);
					$tmpTree->doBegin();
					for($t=1;$t <= $tmpTree->getCount();$t++){
						$reTree->addItem($tmpNode.XCML_NODE_CONNECT.$i.XCML_NODE_CONNECT.$tmpTree->getItemKey(),$tmpTree->getItemValue());
						$tmpTree->doMove();
					}
					
					$objXCML->doItemMove();
				}
			}else{							//Key节点模式
				$reTree->addItem(XCML_NODE_PX.''.XCML_ROOT.'.mode','key');
				$aryNode=toSplit($tmpNode,',');
				for($i=1;$i<=count($aryNode);$i++){
					$_node=$aryNode[$i-1];
					$reTree->addItem($_node,XCML_NODE_EXIST);
					$objXCML->doParseNode($_node);
					//##########
					$tmpTree=$objXCML->getItemAttTree();
					$tmpTree->doBegin();
					for($t=1;$t <= $tmpTree->getCount();$t++){
						$reTree->addItem($_node.XCML_NODE_ATT.$tmpTree->getItemKey(),$tmpTree->getItemValue());
						$tmpTree->doMove();
					}
					//##########
					$tmpTree=$objXCML->getItemTree();
					$tmpTree->doBegin();
					for($t=1;$t <= $tmpTree->getCount();$t++){
						$reTree->addItem($_node.XCML_NODE_CONNECT.$tmpTree->getItemKey(),$tmpTree->getItemValue());
						$tmpTree->doMove();
					}
					$objXCML->doItemMove();
				}
			}
			/*
			$tmpNodeHold=objXCML->getConfigure('node.hold')
			If Len(tmpNodeHold) > 0 Then
				$reTree->addItem XCML_NODE_NAMES.'.hold',tmpNodeHold
				aryNode=Split(tmpNodeHold,',')
				For i=0 To UBound(aryNode)
					objXCML->doParseNode(aryNode(i))
					Set tmpTree=objXCML->getItemTree()
					$reTree->addItem XCML_NODE_PX.'hold'.XCML_NODE_PX.aryNode(i),XCML_NODE_EXIST
					Call tmpTree.doBegin
					For t=1 To tmpTree.getCount()
						$reTree->addItem XCML_NODE_PX.'hold'.XCML_NODE_PX.aryNode(i).XCML_NODE_CONNECT.tmpTree.getItemKey(),tmpTree.getItemValue()
						Call tmpTree.doMove
					Next
					Call objXCML->doItemMove
				Next
			End If
			*/
		}
		$objXCML->doDestroy();
		return $reTree;
	}
	
	public static function toTreeXCML($strTree)
	{
		$re='';
		if(isTree($strTree)){
			$tmpVer=$strTree->getItem(XCML_NODE_PX.'basic.xml.version');
			if(len($tmpVer)<1) $tmpVer='1.0';
			$tmpCharset=$strTree->getItem(XCML_NODE_PX.'basic.xml.encoding');
			if(len($tmpCharset)<1) $tmpCharset=CHARSET_XML;
			$re='<?xml version="'.$tmpVer.'" encoding="'.$tmpCharset.'"?>';
			
			$tmpVer=$strTree->getItem(XCML_NODE_PX.'basic.'.XCML_ROOT.'.version');
			if(len($tmpVer)<1) $tmpVer='1.0';
			$tmpModel=$strTree->getItem(XCML_NODE_PX.'basic.'.XCML_ROOT.'.model');
			$re.=NEWLINE.'<'.XCML_ROOT.' version="'.$tmpVer.'" model="'.$tmpModel.'">';
			
			$re.=NEWLINE.XCML_PRESPACE.'<'.XCML_NODENAME_CONFIGURE.'>';
			$strTree->doBegin();
			for($t=1;$t<= $strTree->getCount();$t++){
				$tmpKey=$strTree->getItemKey();
				if(left($tmpKey,len(XCML_NODENAME_CONFIGURE) + 1) == ''.XCML_NODENAME_CONFIGURE.'.'){
					$tmpField=substr($tmpKey,len(XCML_NODENAME_CONFIGURE) + 1);
					$tmpValue=utilCode::toXMLValue($strTree->getItemValue());
					$re.=NEWLINE.XCML_PRESPACE2.'<'.$tmpField.'>'.$tmpValue.'</'.$tmpField.'>';
				}
				$strTree->doMove();
			}
			$re.=NEWLINE.XCML_PRESPACE.'</'.XCML_NODENAME_CONFIGURE.'>';
			
			switch($strTree->getItem(XCML_NODE_PX.''.XCML_ROOT.'.mode') ){
				case 'item':				//常规节点模式
					$tmpNodes=$strTree->getItem(XCML_NODE_NAMES);
					for($i=1;$i<=$strTree->getItemInt(XCML_NODE_PX.$tmpNodes.'.count');$i++){
						$tmpNode=$tmpNodes.'.'.$i;
						//##########
						$tmpAtts='';
						$strTree->doBegin();
						for($t=1;$t <= $strTree->getCount();$t++){
							$tmpKey=$strTree->getItemKey();
							if( left($tmpKey,len($tmpNode) + 1) == ''.$tmpNode.XCML_NODE_ATT){
								$tmpField=substr($tmpKey,len($tmpNode) + 1);
								$tmpValue=utilCode::toXMLValue($strTree->getItemValue());
								$tmpAtts=$tmpAtts.' '.$tmpField.'="'.$tmpValue.'"';
							}
							$strTree->doMove();
						}
						//##########
						$re.=NEWLINE.XCML_PRESPACE.'<'.$tmpNodes.$tmpAtts.'>';
						
						$strTree->doBegin();
						for($t=1;$t <= $strTree->getCount();$t++){
							$tmpKey=$strTree->getItemKey();
							if( left($tmpKey,len($tmpNode) + 1) == ''.$tmpNode.XCML_NODE_CONNECT){
								$tmpField=substr($tmpKey,len($tmpNode) + 1);
								$tmpValue=utilCode::toXMLValue($strTree->getItemValue());
								$re.=NEWLINE.XCML_PRESPACE2.'<'.$tmpField.'>'.$tmpValue.'</'.$tmpField.'>';
							}
							$strTree->doMove();
						}
						$re.=NEWLINE.XCML_PRESPACE.'</'.$tmpNodes.'>';
					}
					break;
				case 'key':				//Key节点模式
					$tmpNodes=$strTree->getItem(XCML_NODE_NAMES);
					$aryNode=toSplit($tmpNodes,',');
					for($a=1;$a<=count($aryNode);$a++){
						$tmpNode=$aryNode[$a-1];
						
						//##########
						$tmpAtts='';
						$strTree->doBegin();
						for($t=1;$t <= $strTree->getCount();$t++){
							$tmpKey=$strTree->getItemKey();
							if( left($tmpKey,len($tmpNode) + 1) == ''.$tmpNode.XCML_NODE_ATT){
								$tmpField=substr($tmpKey,len($tmpNode) + 1);
								$tmpValue=utilCode::toXMLValue($strTree->getItemValue());
								$tmpAtts=$tmpAtts.' '.$tmpField.'="'.$tmpValue.'"';
							}
							$strTree->doMove();
						}
						//##########
						$re.=NEWLINE.XCML_PRESPACE.'<'.$tmpNode.$tmpAtts.'>';
						
						$strTree->doBegin();
						for($t=1;$t <= $strTree->getCount();$t++){
							$tmpKey=$strTree->getItemKey();
							if( left($tmpKey,len($tmpNode) + 1) == ''.$tmpNode.XCML_NODE_CONNECT){
								$tmpField=substr($tmpKey,len($tmpNode) + 1);
								$tmpValue=utilCode::toXMLValue($strTree->getItemValue());
								$re.=NEWLINE.XCML_PRESPACE2.'<'.$tmpField.'>'.$tmpValue.'</'.$tmpField.'>';
							}
							$strTree->doMove();
						}
						$re.=NEWLINE.XCML_PRESPACE.'</'.$tmpNode.'>';
					}
					break;
			}
			
			/*
			tmpNodes=$strTree->getItem(XCML_NODE_NAMES.'.hold')		//保留节点
			If Len(tmpNodes) > 0 Then
			Dim tmpTree
			Set tmpTree=utils.getTreePrefix(strTree,XCML_NODE_PX.'hold'.XCML_NODE_PX)
			aryNode=Split(tmpNodes,',')
			For a=0 To UBound(aryNode)
			tmpNode=aryNode(a)
			re=re.(NEWLINE.XCML_PRESPACE.'<'.tmpNode.'>')
			Call tmpTree.doBegin
			For t=1 To tmpTree.getCount()
				tmpKey=tmpTree.getItemKey()
				If Left(tmpKey,Len(tmpNode) + 1)=''.tmpNode.XCML_NODE_CONNECT Then
					tmpField=Mid(tmpKey,Len(tmpNode) + 2)
					tmpValue=oCode.toXMLValue(tmpTree.getItemValue())
					re=re.(NEWLINE.XCML_PRESPACE2.'<'.tmpField.'>'.tmpValue.'</'.tmpField.'>')
				End If
				Call tmpTree.doMove
			Next
			re=re.(NEWLINE.XCML_PRESPACE.'</'.tmpNode.'>')
			Next
			End If
			*/
			
			$re.=NEWLINE.'</'.XCML_ROOT.'>';
			
		}
		return $re;
	}
	
	
	
	########################################
	########################################
	public static function getTreePlace($strTree,$key,$value)
	{
		$re=0;
		$strTree->doBegin();
		for($t=1;$t <= $strTree->getItemInt('__item.count');$t++){
			if($strTree->getItem('item.'.$t.'.'.$key)==$value){
				$re=$t;
				break;
			}
			$strTree->doMove();
		}
		return $re;
	}
	
	public static function toTreePlaceFilter($strTree,$strPlace)
	{
		$aryField=toSplit($strTree->getItem('configure.field'),',');
		for($t=$strPlace;$t <= $strTree->getItemInt(XCML_NODE_PX.'item.count');$t++){
			for($a=0;$a<count($aryField);$a++){
				$strTree->setItem('item.'.$t.'.'.$aryField[$a],$strTree->getItem('item.'.($t + 1).'.'.$aryField[$a]));
			}
		}
		$strTree->setItem('__item.count',$strTree->getItemInt(XCML_NODE_PX.'item.count') - 1);
		return $strTree;
	}
	
	
	public static function toTreePlaceSwap($strTree,$p1,$p2,$p3=0)
	{
		if($p2 == 0) $p2=$p1 + $p3;
		$fieldItem=$strTree->getItem('configure.node');
		if(($strTree->getItem($fieldItem.'.'.$p1) == XCML_NODE_EXIST) && ($strTree->getItem($fieldItem.'.'.$p2) == XCML_NODE_EXIST)){
			$aryField=toSplit($strTree->getItem('configure.field'),',');
			$treeAgent=new utilTree();
			
			for($a=0;$a<count($aryField);$a++){
				$treeAgent->addItem($aryField[$a],$strTree->getItem($fieldItem.'.'.$p1.'.'.$aryField[$a]));
			}
			
			for($a=0;$a<count($aryField);$a++){
				$strTree->setItem($fieldItem.'.'.$p1.'.'.$aryField[$a],$strTree->getItem($fieldItem.'.'.$p2.'.'.$aryField[$a]));
				$strTree->setItem($fieldItem.'.'.$p2.'.'.$aryField[$a],$treeAgent->getItem($aryField[$a]));
			}
		}
		return $strTree;
	}
	
	
	########################################
	########################################
	public static function getTable2FormXCML($tData,$isFrame=1)
	{
		$re='';
		//debugTable($tData);
		$DTMLItem=VDCSDTML::getConfigContent('common.config/control/form.input.item'.EXT_TEMPLATE);
		$tData->doBegin();
		while($tData->isNext()){
			$_item=utilRegex::toReplaceVar($DTMLItem,$tData->getItemTree());
			$re.=$_item;
		}
		if($isFrame){
			$DTMLFrame=VDCSDTML::getConfigContent('common.config/control/form.frame'.EXT_TEMPLATE);
			$re=rd($DTMLFrame,'input.items',$re);
		}
		return $re;
	}
	
}
?>