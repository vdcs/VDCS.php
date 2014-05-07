<?
class StructExtend
{
	
	public static function appendTable(&$tableData,$opt=[],$params=[])
	{
		if(!$params || !$params['table.name'] || !$params['field.id']){
			debugx(__CLASS__.'::'.__METHOD__.': require params');
			debuga($params);
			return;
		}
		$FieldID=$params['field.id'];
		if(!$opt) $opt=[];
		//if(isn($opt['info'])) $opt['info']=true;
		if(!$opt['px']) $opt['px']=$params['opt.px'];				//'u';
		if(!$opt['relateid']) $opt['relateid']=$params['opt.relateid'];		//'uuid';
		if(!$opt['fields']) $opt['fields']=$params['opt.fields'];		//'name,email,mobile,names';
		//if(!$opt['fields.x']) $opt['fields.x']='name,email,mobile';
		//if(!$opt['fieldx']) $opt['fieldx']='name,email,mobile';
		$opt['relateida']=$tableData->getValuea($opt['relateid'],'\'');
		//debuga($opt['relateida']);
		//debuga($opt);
		if(count($opt['relateida'])>0){
			if(!isInt($opt['relateida'][0])){
				$ary=[];
				foreach($opt['relateida'] as $value){
					array_push($ary,'\''.$value.'\'');
				}
				$opt['relateida']=$ary;
				//debuga($opt['relateida']);
			}
			//$uu[$rc]->struct('FieldName')
			$fields=$FieldID;
			if($opt['fields']) $fields.=','.$opt['fields'];
			if($opt['fieldx']) $fields.=','.$opt['fieldx'];
			$tableU=DB::queryTable('select '.$fields.' from '.$params['table.name'].' where '.$FieldID.' in ('.implode(',',$opt['relateida']).')');
			
			$afields='names';
			if($opt['info']) $afields.=','.($opt['fields.x']?$opt['fields.x']:$opt['fields']);
			if($opt['fieldx']) $afields.=','.$opt['fieldx'];
			$afielda=toSplit($afields,',');
			//debugx($afields);
			$afieldar=[];
			foreach($afielda as $afield){
				array_push($afieldar,$opt['px'].$afield.$opt['sx']);
			}
			$tableData->doAppendFields(implode(',',$afieldar));
			$tableData->doBegin();
			while($tableData->isNext()){
				$_id=$tableData->getItemValue($opt['relateid']);
				$treeU=newTree();
				$tableU->doBegin();
				while($tableU->isNext()){
					if($tableU->getItemValue($FieldID)==$_id){
						$treeU=$tableU->getItemTree();
						break;
					}
				}
				if($params['on.names']){
					$_names='['.$_id.']';
					if($treeU->getCount()>0){
						$_names=Ua::toNames($treeU,$FieldID);
					}
					$treeU->addItem('names',$_names);
				}
				foreach($afielda as $afield){
					$tableData->setItemValue($opt['px'].$afield.$opt['sx'],$treeU->getItem($afield));
				}
			}
		}
	}
	
}
