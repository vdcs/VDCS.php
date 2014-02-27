<?
class ModelAttrManage extends ModelAttr
{
	const DataPX		= 'auction_';
	const TableFieldsAdd	= 'channel,rootid,type,key,name,value,valueint,valuenum,valuetxt,money,emoney,points';
	const TableFieldsEdit	= 'type,key,name,value,valueint,valuenum,valuetxt,money,emoney,points';
	
	
	/*
	########################################
	########################################
	*/
	public function getFormXCML()
	{
		$this->loadStruct();
		return self::toFormXCML($this->channel,$this->tableStruct);
	}
	public function getFormDataTree()
	{
		$this->loadStruct();
		$this->loadData();
		$reTree=newTree();
		$this->loadData();
		$this->tableData->doBegin();
		while($this->tableData->isNext()){
			$reTree->addItem(self::DataPX.$this->tableData->getItemValue('key'),$this->tableData->getItemValue('value'));
		}
		return $reTree;
	}
	public function doFormSave(&$treeData)
	{
		$this->loadStruct();
		//debugTable($this->tableStruct);
		//debugTree($treeData);
		$this->tableStruct->doBegin();
		while($this->tableStruct->isNext()){
			$treeItem=$this->tableStruct->getItemTree();
			$key=$treeItem->getItem('key');
			$value=$treeData->getItem(self::DataPX.$key);
			$treeItem->addItem('_value',$value);
			$this->doDataSave($treeItem);
		}
	}
	
	
	/*
	########################################
	########################################
	*/
	public function doDataSave($treeItem)
	{
		$this->loadData();
		if(!$this->treeData){
			$this->treeData=newTree();
			$this->treeData->addItem('channel',$this->channel);
			$this->treeData->addItem('rootid',$this->rootid);
			$this->treeData->addItem('type',$this->type);
			$this->treeData->addItem('key','');
			$this->treeData->addItem('name','');
			$this->treeData->addItem('value','');
			$this->treeData->addItem('valueint','0');
			$this->treeData->addItem('valuenum','0');
			$this->treeData->addItem('valuetxt','');
			$this->treeData->addItem('money','0');
			$this->treeData->addItem('emoney','0');
			$this->treeData->addItem('points','0');
		}
		$key=$treeItem->getItem('key');
		$this->treeData->addItem('key',$key);
		$this->treeData->addItem('name',$treeItem->getItem('name'));
		$this->treeData->addItem('value',$treeItem->getItem('_value'));
		$treeData=$this->getDataTree($key);
		$id=$treeData->getItemInt('id');
		if($id>0){
			$sqlQuery='channel=\''.$this->channel.'\' and rootid='.$this->rootid.' and id='.$id;
			$sql=DB::sqlUpdate(self::TableName,self::TableFieldsEdit,$this->treeData,$sqlQuery);
		}
		else{
			$sql=DB::sqlInsert(self::TableName,self::TableFieldsAdd,$this->treeData);
		}
		//debugx($sql);
		DB::exec($sql);
	}
	public function doDataRemove($ids)
	{
		$sqlQuery='channel=\''.$this->channel.'\' and rootid in ('.$ids.')';
		$sql=DB::sqlDelete(self::TableName,$sqlQuery);
		//debugx($sql);
		DB::exec($sql);
	}
	
	
	/*
	########################################
	########################################
	*/
	public static function toFormXCML($channel_,$tableStruct,$isFrame=0)
	{
		//Dim tmpTableForm As utilTable,tmpTreeForm As utilTree,tt As Integer,xx As Integer,tmpKey,tmpValue
		//Dim tmpType,tmpInputName,tmpCationName,tmpPropertys,tmpProperty,tmpTree As utilTree
		$tableForm=VDCSXCML::newFormTable();
		//debugTable($tableStruct);
		$tableStruct->doBegin();
		while($tableStruct->isNext()){
			$treeItem=$tableStruct->getItemTree();
			//#########
			$inputtype=$treeItem->getItem('inputtype');
			$inputname=self::DataPX.$treeItem->getItem('key');
			$captionName=$treeItem->getItem('name');
			//#########
			$property['item']='';
			$property['action']='';
			$property['class']='';
			$property['type']='string';
			$property['max']=250;
			$property['min']=$treeItem->getItemInt('inputmin');;
			//#########
			switch($inputtype){
				case 'radio':		$style='';break;
				case 'checkbox':	$style='';break;
				case 'select':		$style='class="itxt"';break;
				default:		$style='size=40 maxlength=250 class="itxt"';
			}
			//#########
			$atts='';
			$value=$treeItem->getItem('value');
			if(inp('radio,checkbox,select',$inputtype)>0){
				$aryAtt=toSplit($treeItem->getItem('values'),'$$$');
				foreach($aryAtt as $k){
					$v=$k;
					$atts.=';'.$k.'='.$v;
				}
				if(len($atts)>0) $atts=toSubstr($atts,2);
				if(len($value)<1) $value='__no1';
			}
			//#########
			$reeForm=newTree();
			$reeForm->addItem('type',$inputtype.'.'.$inputname);
			$reeForm->addItem('property','item=;action=;type='.$property['type'].';max='.$property['max'].';min='.$property['min']);
			$reeForm->addItem('style',$style);
			$reeForm->addItem('caption',$captionName);
			$reeForm->addItem('att',$atts);
			$reeForm->addItem('value',$value);
			$reeForm->addItem('explain',$treeItem->getItem('explain'));
			$tableForm->addItem($reeForm);
			/*
			If Left(tmpKey,2) <> "__" Then
				tmpInputName=tmpKey
				tmpCationName=treeStruct.getItemValue()
				tmpCationName=Replace(tmpCationName,Chr(9),"")
				tmpPropertys=""
				If InStr(tmpCationName,commons.STRUCT_PREPERTY_SYMBOL) > 0 Then
					tmpPropertys=Mid(tmpCationName,InStr(tmpCationName,commons.STRUCT_PREPERTY_SYMBOL) + Len(commons.STRUCT_PREPERTY_SYMBOL))
					tmpCationName=Mid(tmpCationName,1,InStr(tmpCationName,commons.STRUCT_PREPERTY_SYMBOL) - 1)
				End If
				Set tmpTree=utils.getString2Tree(tmpPropertys,commons.STRUCT_SEPARATOR_SYMBOL,commons.STRUCT_EVALUATE_SYMBOL)
				tmpValue="": tmpProperty=""
				tmpType=tmpTree.getItem("type")
				If Len(tmpType) < 1 Then
					tmpType="input." & tmpInputName
				Else
					If commons.inp(FORM_ITEM_TYPE_BARS,tmpType) < 1 Then tmpType=tmpType & "." & tmpInputName
				End If
				tmpValue=treeSource.getItem(tmpInputName)
				tmpValue=toValueEncode(tmpValue)
				//tmpValue=toFlagEncode(tmpValue)
				tmpTree.doBegin
				For xx=1 To tmpTree.getLength()
					If Left(tmpTree.getItemKey(),9)="property." Then
						tmpProperty=tmpProperty & (";" & Mid(tmpTree.getItemKey(),10) & "=" & tmpTree.getItemValue())
					End If
					tmpTree.doMove
				Next
				If InStr(";" & tmpProperty,";type=") < 1 Then tmpProperty=tmpProperty & (";type=string")
				If Len(tmpProperty) > 0 Then tmpProperty=Mid(tmpProperty,2)
				$reeForm=newTree();
				$reeForm->addItem("type",tmpType);
				$reeForm->addItem("property",tmpProperty);
				$reeForm->addItem("style",tmpTree.getItem("style"));
				$reeForm->addItem("caption",tmpCationName);
				$reeForm->addItem("att",tmpTree.getItem("att"));
				$reeForm->addItem("value",tmpValue);
				$reeForm->addItem("explain",tmpTree.getItem("explain"));
				$tableForm->addItem($reeForm);
			End If
			*/
		}
		return VDCSXCMLExtend::getTable2FormXCML($tableForm,$isFrame);
	}
	
}
?>