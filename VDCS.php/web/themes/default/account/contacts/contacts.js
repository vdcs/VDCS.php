
var contacts={

	optValues:function(module,selector,opt){
		selector=selector||'#contacts';
		this.jwrap=$(selector);
		var list_opt={cont:this.jwrap.find('.contacts ul'),tpl:$('#tpl_contacts'),paging:this.jwrap.find('.paging')};
		list_opt.serveVar={channel:'account',p:'contacts',m:module,action:'list',params:'listnum=10'};
		return list_opt
	},
	followIniter:function(selector,opt){
		var list_opt=this.optValues('follow',selector,opt);
		list_opt.bind=function(jitem){	
		};
		list_opt.binds=function(jcont){
		};
		this.follow.init(list_opt);
		this.follow.parse();
	},
	fansIniter:function(selector,opt){
		var list_opt=this.optValues('fans',selector,opt);
		list_opt.bind=function(jitem){	
		};
		list_opt.binds=function(jcont){
		};
		this.fans.init(list_opt);
		this.fans.parse();
	},

'':''};

contacts.list=extend(ui.list,{
	filterItem:function(treeItem){
		var uid=treeItem.v('uuid'),_ulink=rd(app.uu_link,'uid',uid),_uavatar=rd(app.uu_avatar,'uid',uid);
		treeItem.add('u.link',_ulink);treeItem.add('u.avatar',_uavatar);
		var uid2=treeItem.v('uuid2'),_ulink2=rd(app.uu_link,'uid',uid2),_uavatar2=rd(app.uu_avatar,'uid',uid2);
		treeItem.add('u2.link',_ulink2);treeItem.add('u2.avatar',_uavatar2);
		return treeItem
	},
'':''});

contacts.follow=extend(contacts.list,{
	bind:function(jitem,treeItem){
		
	},
	binds:function(jcont,treeVar){
		var that=this;
		jcont.find('.cancel').click(function(){
			that.cancelClick($(this));
			return false
		});
	},

	cancelClick:function(ja){
		if(this.iscancel)return;this.iscancel=true;
		var jitem=ja.parents('li');
		var uid=toi(jitem.attrd('uid2'));
		var that=this;
		var _url=app.serve('a/contacts/follow','action=cancel&uid='+uid);
		//dbg.t('url',_url);
		$ajax({url:_url,value:'map',ready:function(o){that.cancelAsync(o,uid,jitem)},error:true});
	},
	cancelAsync:function(maps,uid,jitem){
		var that=this;
		var treeVar=maps.getItemTree('var');
		if($form.statusSucceed(treeVar)){
			ui.effect.remove(jitem);
			return false
		}
		this.iscancel=false;
	},
'':''});

contacts.fans=extend(contacts.list,{
	binds:function(jcont,treeVar){
		var that=this;
		jcont.find('.follow').click(function(){
			that.followClick($(this));
			return false
		});
		jcont.find('.cancel').click(function(){
			that.cancelClick($(this));
			return false
		});
		jcont.find('.inbox').click(function(){
			//that.inboxClick($(this));
			return false
		});
	},

	followClick:function(ja){
		if(this.iscancel)return;this.iscancel=true;
		var jitem=ja.parents('li');
		var uid=toi(jitem.attrd('uid'));
		var that=this;
		var _url=app.serve('a/contacts/follow','action=create&uid='+uid);
		//dbg.t('url',_url);
		$ajax({url:_url,value:'map',ready:function(o){that.followAsync(o,uid,jitem)},error:true});
	},
	followAsync:function(maps,uid,jitem){
		var that=this;
		var treeVar=maps.getItemTree('var');
		if($form.statusSucceed(treeVar)){
			jitem.attrd('eacho',1);
			this.eachoActive(jitem);
		}
		this.iscancel=false;
	},

	cancelClick:function(ja){
		if(this.iscancel)return;this.iscancel=true;
		var jitem=ja.parents('li');
		var uid=toi(jitem.attrd('uid'));
		var that=this;
		var _url=app.serve('a/contacts/follow','action=cancel&uid='+uid);
		//dbg.t('url',_url);
		$ajax({url:_url,value:'map',ready:function(o){that.cancelAsync(o,uid,jitem)},error:true});
	},
	cancelAsync:function(maps,uid,jitem){
		var that=this;
		var treeVar=maps.getItemTree('var');
		if($form.statusSucceed(treeVar)){
			jitem.attrd('eacho',0);
			this.eachoActive(jitem);
		}
		this.iscancel=false;
	},

	eachoActive:function(jitem){
		var eacho=toi(jitem.attrd('eacho'));
		var jopt=jitem.find('opt');
		if(eacho){
			jopt.find('.unfollow').hide();
			jopt.find('.followed').ishow();
		}
		else{
			jopt.find('.followed').hide();
			jopt.find('.unfollow').ishow();
		}
	},
'':''});
