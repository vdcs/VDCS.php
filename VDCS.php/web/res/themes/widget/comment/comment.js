
widget.comment={
	init:function(){var that=this;$(function(){that.initer()})},
	initer:function(){
		if(this._initer)return;this._initer=true;
		this.jwrap=$('.comments');
		if(!this.jwrap.length) return;
		if(!this.channel) this.channel=this.jwrap.find('input[name="channel"]').val();
		if(!this.rootid) this.rootid=this.jwrap.find('input[name="rootid"]').val();
		if(!this.channel || !this.rootid) return;
		var that=this;
		
		this.post.jwrap=this.jwrap;
		this.post.getURL=function(action){return that.getURL(action)};
		this.post.refresh=function(){return that.refresh()};
		this.post.initer();

		this.listParse();
	},

	getURL:function(action){
		if(action=='send') action='post';
		var _url=app.serve('c/comment','channel='+this.channel+'&rootid='+this.rootid+'&action='+action);
		//dbg.t('url',_url);
		return _url
	},
	uio:function(isreal){
		app.uio(this.jlist);
	},

	listInit:function(){
		if(this.islist)return;this.islist=true;
		var that=this;
		this.jlist=this.jwrap.finder('.comment_list');
		if(!this.jlist)return;
		this.jlist.finde('refresh').click(function(){that.refresh()});
		var opt={
			cont:this.jlist.finde('items'),tpl:this.jlist.finde('tpl'),
			paging:this.jlist.finde('paging'),loader:this.jlist.finde('loader'),loading:this.jlist.finde('loading'),
			serveURL:this.getURL('list'),
			binds:function(jcont,treeVar){
				//alert(jcont.outerHTML());
				var _total=treeVar.vi('paging.total');
				if(_total<1){
					that.jlist.hide();
				}
				else{
					that.jlist.show();
				}
				jcont.find('dl').each(function(){
					var jrow=$(this),id=jrow.attr('data-id');
					/*
					jrow.find('a[href^="#"]').each(function(){
						var jaction=$(this);
						//dbg.t(jaction.attr('href').substring(1));
						jaction.attr('href',that.actionURL(jaction.attr('href').substring(1),id,jaction));
					});
					jrow.find('a[href="#web"]').attr('target','_blank');
					*/
				});
				var jstats=that.jwrap.find('.stats');
				jstats.find('.total').text(_total);
				jstats.find('.ua').text(treeVar.v('ua.total'));
				$('[href="#comment"]').click(function(){
					that.jwrap.placeto({offset:-40});
					return false
				}).find('i').text(_total);
				that.uio(true);
			},
			fails:function(status){
				that.uio();
			},
		'':''};
		this.listo=extend(ui.list,{});
		this.listo.init(opt);
	},
	listParse:function(){
		this.listInit();
		this.listo.parse();
	},
	refresh:function(){
		this.listParse();
	},

	post:extend($form,{
		initer:function(){
			if(this._initer)return;this._initer=true;
			this.jforms=this.jwrap.find('.talki');
			this.jmsg=this.jforms.find('textarea');
			this.jact=this.jforms.find('h5');
			if(!this.jforms.length || !this.jmsg.length) return;
			var that=this;
			ui.effect.expandTalk&&ui.effect.expandTalk(this.jmsg,this.jact,{style:'high'});
			
			this.jforms.find('.send').click(function(){
				that.send();
			});
			
			this.jtoolbar=this.jforms.find('.toolbar');
			if(this.jtoolbar.length){
				ui.editor.loader(function(){
					ui.editor.toolbar.initer('talk',{j:that.jtoolbar,jcall:that});
				});
			}
		},
		contentSet:function(mode,value){
			this.jmsg.insertAtCaret(value);
		},
		
		isLogin:function(){return ua.isLogin(false,true)},

		reset:function(){
			this.jforms.find('textarea').val('');
		},
		sendSucceed:function(){

		},
	'':''}),

};
