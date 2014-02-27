
var contacts={
	module:'follow',
	initer:function(selector,ps){
		var that=this;
		selector=selector||'#contacts';
		this.jwrap=$(selector);
		this.jul=this.jwrap.find('ul');
		this.loader(ox({page:1},ps));
		this.search=$('[el=search_person]');
		this.search.submit(function(event){event.preventDefault();that.searchPerson(that.module,ps);});
	},

	dataLoad:function(){
		this.dataReset();
		var _html=$('tpl_loading').html()||'<span class="loading">loading..</span>';
		this.jul.html(_html);
	},
	dataReset:function(){
		this.jul.html('');
	},

	loader:function(ps){
		if(this.isloader) return;this.isloader=true;
		this.dataLoad();
		var that=this;
		var _url=app.serveURL('a/contacts/'+this.module,"action=list&page="+ps.page+"&groupid="+this.groupid);
		$ajax({url:_url,value:"xml",ready:function(o){that.parser(o,ps)},error:true});
	},
	parser:function(xml,ps){
		var that=this;
		var maps=$util.toMapByXML(xml);
		var treeVar=maps.getItemTree('var');
		
		if(this.module=='follow' && $(".followGroups p").length<3){
			var tableItem=maps.getItemTable('group');
			if(tableItem.row()<1){//如果没有数据则显示暂无数据，并结束
				this.dataNo();
				//return;
			}
			tableItem.begin();
			for(var i=1;i<=tableItem.row();i++){
				var treeItem=tableItem.getItemTree();
				var _html=this.toGroupString(treeItem);
				$('.followGroups').append(_html);
				tableItem.move();
			}
		}

		var tableItem=maps.getItemTable('item');
		if(tableItem.row()<1){
			this.dataNo();
			this.jwrap.find('.paging').empty();
			if(ps && ps.as_total){
				ps.as_total.html(0);
			}
			this.isloader=false;
			return;
		}
		this.dataReset();
		
		//开始遍历
		tableItem.begin();
		for(var i=1;i<=tableItem.row();i++){
			var treeItem=tableItem.getItemTree();
			treeItem.addItem('sn',i);
			treeItem.addItem('oe',2-(i)%2);
			var each1=treeItem.v('eacho')==0?1:0;
			treeItem.addItem('each1',each1);
			//dbg.t(treeItem.v('uusign2'));
			var uusingn2=treeItem.v('uusign2');
			if(uusingn2){
				uusingn2='签名：'+uusingn2;
				treeItem.add('nuusign2',uusingn2);
				//alert(123);
			}
			var uusingn=treeItem.v('uusign');
			if(uusingn){
				uusingn='签名：'+uusingn;
				treeItem.add('nuusign',uusingn);
			}
			//dbg.t(treeItem.getArray());
			var _html=this.toItemString(treeItem);
			//dbg.t(_html);
			this.jul.append(_html);
			tableItem.move();
		}
		
		this.parserBind();
		
		if(ps && ps.as_total){
			ps.as_total.html(treeVar.v('paging.total'));
		}
		appt.pagingParser(treeVar,this.jwrap.find('.paging'),function(page){
			that.loader({page:page});
		});
		this.isloader=false;
		
		this.team=this.jul.find('.team');
		this.team.click(function(){that.editFollows=$(this);that.showAllGroups()});
		
	},
	parserBind:function(){
	
	},
	dataNo:function(){
		this.dataReset();//清空
		$('<span class="nodata">暂无</span>').appendTo(this.jul);
	},
	searchPerson:function(type,ps){
		if(this.isloader) return;this.isloader=true;
		this.dataLoad();//加载loading...
		var that=this;
		var uname=this.search.find('.uname').val();
		var _url=$url.link(appt.ASrvURL,"p=contacts&m="+that.module+"&x=x&action=searchPerson&uname="+uname);
		//dbg.t(_url);
		$ajax({url:_url,value:"xml",ready:function(o){that.parser(o,ps)},error:true});//获取值
	},	
'':''};

/*关注*/
contacts.follow=$.extend({},contacts,{	
	module:'follow',
	initer:function(selector,ps){
		var that=this;
		selector=selector||'.Follows';
		this.jwrap=$(selector);
		this.jul=this.jwrap.find('ul');
		this.groupid='';
		this.createGroup=$('.createGroup');
		this.delGroup=$(".delGroup");
		this.delGroup.hide();
		this.delGroup.click(function(){that.doDelGroup()});
		this.loader(ox({page:1},ps));
		
		this.viewAll=$('.viewAll');//查看全部按钮
		this.viewAll.click(function(){that.showGroups(ps)});
		
		this.search=$('[el=search_person]');
		this.search.submit(function(event){event.preventDefault();that.searchPerson(that.module,ps);});
		this.createGroup.click(function(){that.showCreateGroup()});
	},
	loader:function(ps){
		//alert(123);
		if(this.isloader) return;this.isloader=true;
		this.dataLoad();//加载loading...
		var that=this;
		var _url=$url.link(appt.ASrvURL,"p=contacts&m="+this.module+"&x=x&action=list&page="+ps.page+"&groupid="+this.groupid);
		//dbg.t(_url);
		$ajax({url:_url,value:"xml",ready:function(o){that.parser(o,ps)},error:true});//获取值
		if(this.groupid>0){
			this.delGroup.show();
		}else{
			this.delGroup.hide();
		}
	},
	toItemString:function(treeItem){
		if(!treeItem.v('groupname')){
			treeItem.setItem('groupname','未分组');
		}
		var _ulink=rd(appt.uu_link,"uid","[item:uuid2]");//"/u/{$id}"  uid  [item:uuid]======/u/10002  怎么个解析法？
		var _uavatar=rd(appt.uu_avatar,"uid","[item:uuid2]");
		if(!this._item_tpl){
			this._item_tpl=this.jwrap.find('[el=_item_tpl]').html();
			if(!this._item_tpl){
				this._item_tpl='\
				<li class="" uid="[item:uuid2]" groupid="[item:groupid]" rootid="[item:id]">\
				<div class="avatar"><a href="'+_ulink+'" target="_blank"><img src="'+_uavatar+'" /></a></div>\
				<div class="info">\
				<h3><cite><a target="_blank" href="'+_ulink+'"><span>[item:uuname2]</span></a></cite></h3>\
				</div>\
				<div class="follow_action">\
					<div class="follow_action_teams"><a class="teams"><span class="team">[item:groupname]</span></a><div class="editGroups hide"><p>请选择分组</p><div class="allGroups"></div></div></div>\
					<div class="action">\
					<span el="_eacho" _eacho="[item:eacho]" class="hide[item:eacho]"><a class="sbtn"><span><i class="eacho"></i>相互关注</span></a></span>\
					<a class="follow" follow="1"><span>取消关注</span></a>\
					</div>\
				</div>\
				</li>';
				/*
				<a class="inbox" href=""><span>私信</span></a>\
				<i>|</i> \
				<a class="mark" href=""><span>标记</span></a>\
				<i>|</i> \
				*/
			}
		}
		var re=this._item_tpl;
		re=dcs.DTML.toReplaceEncodeFilter(re,treeItem,dcs.DTML.PATTERN_LEBEL_ITEMS);      
		return re;
	},
	
	//添加分组
	toGroupString:function(treeItem){
		var re='';
		re='<p el="[item:id]"><span class="group_name">[item:name]</span></p>';
		re=dcs.DTML.toReplaceEncodeFilter(re,treeItem,dcs.DTML.PATTERN_LEBEL_ITEMS);      
		return re;
	},
	
	//关注
	parserBind:function(){
		var that=this;
		var jli=this.jul.find("li");
		jli.find(".action .follow").click(function(){
			that.cancelEach($(this));//隐藏相互关注按钮
			
			var uid=$(this).parents('li').attr('uid');
			that.followClick(uid,$(this).attr('follow'));//uid , follow值
		});
	},
	
	followClick:function(uid,action){
		if(appt.followLock()) return;
		var that=this;
		appt.followAction(uid,action,function(uid,follow){that.followParser(uid,follow)});
	},
	followParser:function(uid,follow){
		var jli=this.jul.find("li[uid="+uid+"]");
		//jli.find(".action .follow").attr("follow",follow).find("span").text(follow==1?"取消关注":"重新关注");
		if(follow==1){
			jli.find(".action .follow").attr("follow",follow).find("span").text("取消关注");
			jli.find(".action .follow").removeClass("refollow");	
		}else{
			jli.find(".action .follow").attr("follow",follow).find("span").text("重新关注");
			jli.find(".action .follow").addClass("refollow");
		}
	},
	
	//隐藏相互关注按钮
	cancelEach:function($obj){
		var follow=$obj.attr("follow");
		var each=$obj.prevAll("[el=_eacho]").attr("_eacho");

		if(follow==1 && each==1){
			$obj.prevAll("[el=_eacho]").hide();
		}else if(follow==0 && each==1){
			$obj.prevAll("[el=_eacho]").show();
		}
		
		
	},
	//显示分组
	showGroups:function(ps){
		var that=this;
		if($('.followGroups').hasClass('hide')){
			$('.followGroups').removeClass('hide');
			$('.followGroups p').bind('click',function(){
				that.doShowGroups($(this),ps);
				$('.followGroups [el=0]').nextAll().remove();
				$('.followGroups p').unbind('click');
				$('.followGroups').addClass('hide');	
			});
		}else{
			$('.followGroups').addClass('hide');	
		}
			
	},
	doShowGroups:function(obj,ps){
		this.viewAll.find('span b').text(obj.text());
		if(obj.attr('el')=='all'){
			this.groupid=-1;
		}else{
			this.groupid=obj.attr('el');
		}
		this.loader(ox({page:1},ps));
	},
	showCreateGroup:function(){
		var that=this;
		var ps={};
		ps.title='创建分组';
		ps.body='<form>分组名:<input type="text" class="groupName" size="35" /><p class="hide" style="color:red;">组名不能为空</p>小组备注:<textarea cols="35" rows="4" class="groupSummary"></textarea></form>';
		ps.btn_submit='保存';
		ps.btn_close='取消';
		ps.submit=function(){
			var status=0;
			that.newGroupName=$('.groupName').val();
			var groupSummary=$('.groupSummary').val();
			status=that.checkGroupName();
			if(!status) return;
			$xbox.dialogClose();	
			var _url=$url.link(appt.ASrvURL,"p=contacts&m="+that.module+"&x=x&action=createGroup&groupname="+that.newGroupName+"&summary="+groupSummary);
			//dbg.t(_url);
			$ajax({url:_url,value:"xml",ready:function(xml){that.createGroupSuc(xml)},error:true});		
		}
		$xbox.load(ps);
		//ps.callback=this.myfun;
		//appt.sysTipsBox.init(ps);
	},
	checkGroupName:function(){
		var that=this;
		var status=1;
		if(this.newGroupName==''){
			$('.groupName').next('p').text('组名不能为空');
			$('.groupName').next('p').show();
			status=0;
		}else{
			$('.groupName').next('p').hide();
			status=1;
			$(".group_name").each(function(){
				if($(this).text()==that.newGroupName){
					//alert(this.newGroupName);
					$('.groupName').next('p').text('该组名已存在');
					$('.groupName').next('p').show();
					status=0;
				}
			});	
		}
		return status;
	},
	createGroupSuc:function(xml){
		var maps=$util.toMapByXML(xml);
		var treeVar=maps.getItemTree("var");
		var _status=treeVar.v("status");
		$xtip.popups_show=false;
		if(_status=="succeed"){
			appt.tips("succeed","小组创建成功",true,1);
			$('.followGroups').append('<p el="'+treeVar.v("insertid")+'"><span class="group_name">'+this.newGroupName+'</span></p>');
			$(".allGroups").empty();
		}
		else{
			appt.tips("error","小组创建失败",true,3);
		}
	},
	showAllGroups:function(){
		//alert(this.editFollows.html());
		var pos=this.editFollows.position();
		var left=parseInt(pos.left);
		this.editGroups=this.editFollows.parents(".follow_action_teams").find('.editGroups');
		this.editGroups.css('left',left);
		if(this.editGroups.hasClass("hide")){
			//$(".allGroups").empty();
			$(".editGroups").addClass("hide");
			var that=this;
			var _url=$url.link(appt.ASrvURL,"p=contacts&m="+this.module+"&x=x&action=list");
			//dbg.t(_url);
			if(this.sendMsg) return;
			if(!this.editGroups.find('.allGroups').html()){
				$ajax({url:_url,value:'xml',ready:function(xml){that.appendGroups(xml)},error:true});
			}
			this.editGroups.removeClass("hide");	
		}else{
			this.editGroups.addClass("hide");
		}
	},
	appendGroups:function(xml){
		var that=this;
		this.sendMsg=true;
		var maps=$util.toMapByXML(xml);
		var tableItem=maps.getItemTable('group');
		tableItem.begin();
		for(var i=1;i<=tableItem.row();i++){
			var treeItem=tableItem.getItemTree();
			var _html=this.appendGroupHtml(treeItem);
			this.editGroups.find(".allGroups").append(_html);
			tableItem.move();
		}
		this.editFollows.parents("li").append(this.groupsBox);
		this.groups=this.editFollows.parents("li").find(".allGroups p input");
		//this.editFollows.parents("li").find(".allGroups").one('mouseleave',function(){$('body').one('click',function(){alert(123)})});
		this.groups.click(function(){that.editGroupMember($(this))});
		this.sendMsg=false;
	},
	appendGroupHtml:function(treeItem){
		var groups=this.editFollows.parents("li").find(".team").text();
		groupsAry=groups.split(',');
		//alert(groupsAry[0]);
		var re="";
		if($.inArray(treeItem.v('name'),groupsAry)+1){
			this.groupAppendHtml_tpl='<p><input type="checkbox" id="[item:id]" checked="checked" value="[item:id]" /> [item:name]</p>';
		}else{
			this.groupAppendHtml_tpl='<p><input type="checkbox" id="[item:id]"  value="[item:id]" /> [item:name]</p>';
		}
		re=this.groupAppendHtml_tpl;
		re=dcs.DTML.toReplaceEncodeFilter(re,treeItem,dcs.DTML.PATTERN_LEBEL_ITEMS);      
		return re;
	},
	editGroupMember:function(obj){
		var that=this;
		var groupid=obj.val();
		var rootid=obj.parents("li").attr("rootid");
		var type=obj.attr("checked");
		if(type=="checked"){
			var _url=$url.link(appt.ASrvURL,"p=contacts&m="+this.module+"&x=x&action=addToGroup&rootid="+rootid+"&groupid="+groupid);
		}else{
			var _url=$url.link(appt.ASrvURL,"p=contacts&m="+this.module+"&x=x&action=delFromGroup&rootid="+rootid+"&groupid="+groupid);
		}
		
		if(this.sendMsg) return;
		//dbg.t(_url);
		$ajax({url:_url,value:'xml',ready:function(xml){that.editSuc(xml)},error:true});
	},
	editSuc:function(xml){
		this.sendMsg=true;
		var maps=$util.toMapByXML(xml);
		var treeVar=maps.getItemTree("var");
		var _status=treeVar.v("status");
		if(_status=='succeed'){
			var groups=treeVar.v('groupsname');
			this.editFollows.parents("li").find(".team").text(groups);
		}
		this.sendMsg=false;
	},
	doDelGroup:function(){
		var _url=$url.link(appt.ASrvURL,"p=contacts&m="+this.module+"&x=x&action=delGroup&groupid="+this.groupid);
		
		var ps={};
		ps.message='确定要删除"xx"分组吗？<br /> 此分组下的人不会被取消关注。';
		ps.btn_submit='确定';
		ps.btn_close='取消';
		ps.submit=function(){
			$ajax({url:_url,value:'xml',ready:function(){location.href="/homex/contacts/follow";}});
			$(".allGroups").empty();
			appt.confirmClose();
		}
		appt.confirms(ps);
	},
'':''});

/*粉丝*/
contacts.fans=$.extend({},contacts,{	
	module:'fans',
	toItemString:function(treeItem){
		var _ulink=rd(appt.uu_link,"uid","[item:uuid]");
		var _uavatar=rd(appt.uu_avatar,"uid","[item:uuid]");
		if(!this._item_tpl){
			this._item_tpl=this.jwrap.find('[el=_item_tpl]').html();
			if(!this._item_tpl){
				this._item_tpl='\
					<li class="" uid="[item:uuid]">\
					<div class="avatar"><a href="'+_ulink+'" target="_blank"><img src="'+_uavatar+'" /></a></div>\
					<div class="info">\
					<h3><cite><a target="_blank" href="'+_ulink+'"><span>[item:uuname]</span></a></cite></h3>\
					<p>[item:nuusign]</p>\
					</div>\
					<div class="action">\
					<span class="hide[item:each1]" el="_add"><a class="sbtnb follow" follow=0><span><i class="add"></i><b>关注</b></span></a></span>\
					<span class="hide[item:eacho]" el="_eacho"><a class="sbtn follow" follow=1><span><i class="eacho"></i>相互关注</span></a></span>\
					<span class="acts hide">\
					<a href=""><span>私信</span></a>\
					<i>|</i> \
					<a href=""><span>标记</span></a>\
					</span>\
					</div>\
				</li>';
			}
		}
		var re=this._item_tpl;
		re=dcs.DTML.toReplaceEncodeFilter(re,treeItem,dcs.DTML.PATTERN_LEBEL_ITEMS);      
		return re;
	},
        
	//加关注系列操作
	parserBind:function(){
		var that=this;
		var jli=this.jul.find("li");
		jli.find(".action .follow").click(function(){
			//that.cancelEach($(this));//隐藏相互关注按钮
			var uid=$(this).parents('li').attr('uid');
			that.followClick(uid,$(this).attr('follow'));//uid , follow值
			
		});
	},	
	followClick:function(uid,action){
		if(appt.followLock()) return;
		var that=this;
		appt.followAction(uid,action,function(uid,follow){that.followParser(uid,follow)});
	},
	followParser:function(uid,follow){
		if(follow==1){
			var jli=this.jul.find("li[uid="+uid+"]");
			jli.find(".action [el=_add]").hide();
			jli.find(".action [el=_eacho]").show();	
		}else if(follow==0){
			//alert(123);
			var jli=this.jul.find("li[uid="+uid+"]");
			jli.find(".action [el=_add]").show();
			jli.find(".action [el=_eacho]").hide();	
		}
	},
'':''});
