<?
class ChannelCommonDataX extends ChannelCommonBaseX
{
	
	public function doParse()
	{
		$t=query('t');
		switch($t){
			case 'channel':
				$this->doParseChannel();
				break;
				
		}
	}
	
	protected function doParseChannel()
	{
		$file=queryx('file');
		if($file=='channel'){
			$this->chnExtend=new CommonChannelExtend();
			$this->setTable($this->chnExtend->getStructTermTable('','','all'));
			$this->chnExtend=null;
		}
		else{
			//$this->addVar('file',appFilePath('common.channel/'.$file));
			$this->setTable(VDCSDTML::getConfigTable('common.channel/'.$file));
		}
	}
	
}

/*

sub doPageParse()
	select case ctl.module
	case "syns"
		doChannelSyns()
	case "digg"
		doChannelDigg()
	case "relate"
		doChannelRelate()
	end select
	xmls.putMaps()
end sub

sub doChannelSyns()
	dim res
	res=dcs.request.getQuery("res")
	if inp(res,"digg")>0 then doChannelDigg()
	if inp(res,"relate")>0 then doChannelRelate()
end sub

sub doChannelDigg()
	if len(ctl.subchannel)<1 then exit sub
	if clng(ctl.id)<1 then exit sub
	Const EV_DIGG_KEY = "digg"
	Const EV_DIGG_PEAK = 20
	Const EV_DIGG_SYMBOL = ","
	dim status_
	status_=1
	Dim vcodes,vcode
	vcodes = dcs.client.getSession(EV_DIGG_KEY)
	vcode = ctl.subchannel & ctl.id
	If Not dcs.code.isExtentValue(vcodes, vcode, EV_DIGG_SYMBOL) Then
		status_=0
		if inp("agree,oppose",ctl.action)>0 then
			cfg.setChannel(ctl.subchannel)
			cfg.doChannelInit()
			dim sql,diggField
			diggField="sp_poll_"&ctl.action&""
			sql="update "&cfg.getSQLStruct("table.name")&" set "&diggField&"="&diggField&"+1 where "&cfg.getSQLStruct("table.field.id")&"="&ctl.id&""
			dcs.db.doExecute(sql)
			call xmls.addVar("digg.action",ctl.action)
			vcodes=dcs.code.toExtentAppend(vcodes, vcode, EV_DIGG_SYMBOL, EV_DIGG_PEAK)
			Call dcs.client.setSession(EV_DIGG_KEY, vcodes)
			status_=1
		end if
	End If
	call xmls.addVar("digg.status",status_)
end sub

sub doChannelRelate()
	'debug ctl.subchannel
	if len(ctl.subchannel)<1 then exit sub
	if clng(ctl.id)<1 then exit sub
	cfg.setChannel(ctl.subchannel)
	cfg.doChannelInit()
	dim tableName,tablePrefix,fieldID
	tableName=cfg.getSQLStruct("table.name")
	tablePrefix=cfg.getSQLStruct("table.prefix")
	fieldID=cfg.getSQLStruct("table.field.id")
	dim sql
	'previous
	sql="select top 1 "&fieldID&","&tablePrefix&"topic from "&tableName&" where "&tablePrefix&"status=1 and "&fieldID&"<"&ctl.id&" order by "&fieldID&" desc"
	set ctl.treeDat=dcs.db.getQueryTree(sql)
	'debug test.toTreeString(ctl.treeDat)
	if ctl.treeDat.getCount()>0 then
		call xmls.addVar("previous.id",ctl.treeDat.getItem(fieldID))
		call xmls.addVar("previous.topic",ctl.treeDat.getItem(tablePrefix&"topic"))
		call xmls.addVar("previous.linkurl",cfg.toLinkurl("view","id="&ctl.treeDat.getItem(fieldID)))
	end if
	'next
	sql="select top 1 "&fieldID&","&tablePrefix&"topic from "&tableName&" where "&tablePrefix&"status=1 and "&fieldID&">"&ctl.id&" order by "&fieldID&" desc"
	set ctl.treeDat=dcs.db.getQueryTree(sql)
	'debug test.toTreeString(ctl.treeDat)
	if ctl.treeDat.getCount()>0 then
		call xmls.addVar("next.id",ctl.treeDat.getItem(fieldID))
		call xmls.addVar("next.topic",ctl.treeDat.getItem(tablePrefix&"topic"))
		call xmls.addVar("next.linkurl",cfg.toLinkurl("view","id="&ctl.treeDat.getItem(fieldID)))
	end if
	'debug test.toTreeString(ctl.treeData)
end sub
*/