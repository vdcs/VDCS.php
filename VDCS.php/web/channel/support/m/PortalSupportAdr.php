<!-- #include file="../../common/config.asp" -->
<%
call doManagePage()

sub doPageLoad()
	ctl.module=ctl.portal		'"adr"
end sub

sub doPageParse()
	mp.doFrameParse()
	select case ctl.action
	case "add"
		doAdd()
	case "edit"
		doEdit()
	case else
		ctl.action="list"
		doList()
	end select
end sub

'####################
public sub doAdd()
	mp.loadPages()
	ctl.pages.setFormFile(ctl.module)
	if len(ctl.subchannel)<1 then
		ctl.pages.addFormVar "channel","_no1"
	else
		ctl.pages.addFormVar "channel",ctl.subchannel
	end if
	mp.loadPagesForm()
	if ctl.pages.isFormPost() then
		mp.doPagesParse()
		
		if ctl.e.isCheck() then
			mp.iPageError()
		else
			ctl.treeData.addItem "userid",mx.toUserID(ctl.treeData.getItem("username"))
			call dcs.dba.doExecuteInsert(dcs.db,mp.getConfig("table.name"),mp.getConfig("table.fields.add"),ctl.treeData)
			call mp.iPageMessage("",mp.getLang("handle.ok."&ctl.action),mp.getURL("action=list"))
			exit sub
		end if
	end if
	mp.doPagesFormParse()
end sub

'####################
public sub doEdit()
	'sql=ops.db.toSQLSelect(mp.getConfig("table.name"),"","*",mp.getConfig("table.field.id")&"="&ctl.id)
	sql="select top 1 * from "&mp.getConfig("table.name")&" where "&mp.getConfig("table.field.id")&"="&ctl.id
	set treeRS=dcs.db.getQueryTree(sql)
	if treeRS.getCount()<1 then
		call mp.iPageMessage("",mp.getLang("error.not.exist"),mp.getURL("action=list"))
		exit sub
	end if
	mp.loadPages()
	ctl.pages.setFormFile(ctl.module)
	ctl.pages.setFormTree(treeRS)
	mp.loadPagesForm()
	if ctl.pages.isFormPost() then
		mp.doPagesParse()
		
		if ctl.e.isCheck() then
			mp.iPageError()
		else
			ctl.treeData.addItem "userid",mx.toUserID(ctl.treeData.getItem("username"))
			ctl.treeData.addItem DB_SQL_TERM_KEY,mp.getConfig("table.field.id")&"="&ctl.id
			call dcs.dba.doExecuteUpdate(dcs.db,mp.getConfig("table.name"),mp.getConfig("table.fields.edit"),ctl.treeData)
			call mp.iPageMessage("",mp.getLang("handle.ok."&ctl.action),mp.getURL("action=list"))
			exit sub
		end if
	end if
	mp.doPagesFormParse()
end sub

'####################
'####################
public sub doHandle()
	mp.doHandle(ctl.module)
end sub

public sub doList()
	doHandle()
	if len(ctl.subchannel)>0 then
		mp.setAppendQuery("channel='"&ctl.subchannel&"'")
	end if
	mp.loadPaging()
	mp.doPaging()
	mp.loadBox()
  	mp.doBoxParse()
end sub
%>