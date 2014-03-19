
extendo(pages,{
	
	initList:function(jlist,opt){this.list.initer(jlist,opt)},
	
	list:{
		initer:function(jlist,opt){
			jlist=jlist?jlist:$('.xlist').parent();
			this.jlist=jlist,this.jtable=jlist.find('table:first');
			this.datav=jlist.find('.xlist').data();
			//##########
			var jtable=$('<table>'+jlist.finde('tpl').html()+'</table>');
			//##########
			var parama=[];
			parama.push('mode='+pages.mode);
			parama.push('taxis='+pages.taxis);
			parama.push('dicts='+appi.dicts.getItems(jtable));
			parama.push(this.datav.params);
			//##########
			var action_url='?';
			if(this.datav.params_a) action_url=$url.link(action_url,this.datav.params_a);
			action_url=$url.link(action_url,'action={$action}&id={$id}');
			//##########
			opt=ox({channel:pages.channel,p:pages.p,m:pages.m,action:pages.action,params:parama.join('&'),node_table:'list',action_url:action_url},opt);
			this.opt=opt;
			//##########
			//ui.pages.channel=opt.channel;
			this.listo=extend(ui.list,{});
			this.parser();
		},
		parser:function(jlist,jtable,opt){
			var that=this;
			if(!jlist) jlist=this.jlist;
			if(!jtable) jtable=jlist.find('table:first');
			if(!opt) opt=this.opt;
			var list_opt={
				cont:jtable.finder('tbody:first'),
				tpl:jlist.finder('[el="tpl"]'),
				paging:jlist.finder('[el="paging"]'),
				loader:jlist.finder('[el="loader"]'),
				loading:jlist.finder('[el="loading"]'),
				serveVar:{channel:opt.channel,p:opt.p,m:opt.m,action:opt.action,params:opt.params},
				node_table:opt.node_table,
				binds:function(jcont,treeVar){
					//alert(treeVar.v('dicts.string'));
					appi.dicts.setString(treeVar.v('dicts.string'));
					//alert(jcont.outerHTML());
					jcont.find('tr').each(function(){
						var jrow=$(this),id=jrow.attr('data-id');
						appi.dicts.parser(jrow);
						if(opt.bindItem) opt.bindItem(jrow,id);
						if(that.bindItem) that.bindItem(jrow,id);
						
						jrow.find('.topic a,a[el="edit"]').attr('href','#edit');
						jrow.find('a[href^="#"]').each(function(){that.actionFilter($(this),id)});
					});
					that.binds(jcont,treeVar);
					that.uio(true);
				},
				fails:function(status){
					that.uio();
				},
			'':''};
			this.listo.init(list_opt);
			this.listo.parse();
			jlist.find('[el="refresh"]').click(function(){that.refresh()});
		},
		binds:function(jcont,treeVar){},
		refresh:function(){
			this.listo.refresh();
		},
		

		actionFilterTab:function(ja){
			if(!ja.attr('target') && ja.attr('href') && pages.linkClick){
				if(ja.attr('tab-url') || ja.attr('tab-title') || inp('edit,view',ja.attrd('action'))>0){
					if(!ja.attr('tab-url')){
						var url=ja.attr('href');
						if(url.substring(0,1)!='/' && ins(url,'://')<1) url=window.location.pathname+url;
						ja.attr('tab-url',url);
					}
					if(!ja.attr('tab-title')){
						var title=ja.parents('tr').attrd('topic')||ja.attrd('title')||ja.text();
						var actionname=this.tabPrefixName(ja.attrd('action'));
						ja.attr('tab-title',actionname==title?actionname:(actionname+' '+title));
					}
				}
				if(ja.attr('tab-url') || ja.attr('tab-title')){
					ja.attr('target','tab');
				}
			}
		},
		tabPrefixName:function(action){
			var re='['+action+']';
			switch(action){
				case 'edit':		re='编辑';break;
				case 'view':		re='浏览';break;
			}
			return re
		},


		actionFilter:function(ja,id){
			var that=this;
			var action=ja.attr('href').substring(1)
			ja.attrd('href',ja.attr('href'));
			ja.attrd('action',action);
			if(action=='web' || ja.attr('target')) ja.attr('target','_blank');
			ja.attr('href',that.actionURL(action,id,ja));
			this.actionFilterTab(ja);
			ja.click(function(){
				var ret=that.actionClick($(this));
				if(!ret) return false
			});
		},
		actionURL:function(action,id,jaction){
			var re='',router='';
			if(jaction){		//<a href="#action" data-action="" data-router="" data-action-url="">[item:name]</a>
				jaction.attrd('router');if(!router) router='';if(router) router=ui.serve.baseurl+router;
				var _action=jaction.attrd('action');
				if(_action) action=_action;
				re=jaction.attrd('action-url');
			}
			if(!re) re=this.opt.action_url;
			re=rd(rd(re,'action',action),'id',id);
			if(action=='web') re='/common/url?channel='+this.opt.channel+'&id='+id;
			return router+re
		},
		actionClick:function(jaction){
			var re=true;
			if(jaction.attrd('confirm') && !jaction.attrd('confirm-click')){
				//dbg.t('confirm')
				re=false;
				ui.confirm(jaction.attrd('confirm'),function(){
					jaction.attrd('confirm-click','yes');
					jaction.trigger('click');
					$p.go(jaction.attr('href'))
				},{mode:jaction.attrd('confirm-mode')})
			}
			//dbg.t('return:'+(re?'true':'false'));
			//re=false;
			return re
		},

		uio:function(isreal){
			app.uio(this.jtable);
			this.selectIniter(this.jtable);
		},
		bindItem:function(jrow,id){
			//jrow.finde('remind')
		},
		
		
		//select
		selectIniter:function(jwrap){
			var that=this;
			var jselectall=jwrap.finder('input[name="selectall"]');
			if(!jselectall) return;
			var isinit=false;jselectall.initer(function(){isinit=true});
			if(jselectall.is(':hidden')){
				var jlabel=jselectall.parent();
				if(jlabel.is('label')){
					jlabel.find('input[type="checkbox"]').checked(false);
					jlabel.find('em').removeClass('checked');
					if(isinit) jlabel.click(function(){that.selectClick($(this).parents('table'))});
				}
			}
			else{
				jselectall.checked(false);
				if(isinit) jselectall.click(function(){that.selectClick($(this).parents('table'))});
			}
			this.handleIniter();
		},
		selectClick:function(jwrap){
			var jselectall=jwrap.finder('input[name="selectall"]');
			var ischeck=jselectall.checked();
			jwrap.find('input[name="selectid"]').each(function(){
				var jthis=$(this);
				if(jthis.is(':hidden')){
					var jlabel=jthis.parent();
					if(jlabel.is('label')){
						jthis.checked(!ischeck);
						jlabel.click();
					}
				}
				else{
					jthis.checked(ischeck);
				}
			});
		},
		selectValues:function(jwrap){
			jwrap=jwrap?jwrap:this.jlist.find('table');
			return jwrap.find('input[name="selectid"]').vals();
		},
		
		
		//handle
		handleIniter:function(){
			var selectoptions=this.listo.treeVar.v('select.options');
			//dbg.t(selectoptions);
			if(!selectoptions || selectoptions=='=' ) return;
			var jhandle=this.jlist.find('.bos .handle');
			var jselect=jhandle.finder('select');
			if(!jselect){
				jhandle.addClass('inputs');
				jhandle.html('<select name="handle"></select><a class="btn" href="javascript:;"><span>操作</span></a>');
				jselect=jhandle.finder('select');
			}
			jselect.find('option').remove();
			var treeOption=$util.toTreeByString(selectoptions,';','=');
			treeOption.begin();
			for(var t=1;t<=treeOption.count();t++){
				$('<option value="'+treeOption.getItemKey()+'">'+treeOption.getItemValue()+'</option>').appendTo(jselect);
				treeOption.move();
			}
			var that=this;
			jhandle.find('a').click(function(){
				that.handleClick(jselect.val());
			});
		},
		handleClick:function(handle){
			if(this.ishandle)return;this.ishandle=true;
			var that=this;
			var values=this.selectValues();
			var opt=this.listo.opt.serveVar;
			if(handle && values){
				var _url=$url.link(ui.serve.getURL({channel:opt.channel,p:opt.p,m:opt.m,action:'handle',params:''}),'handle='+handle+'&selectids='+values+'');
				//dbg.t(_url);
				$ajax({url:_url,value:'xml',ready:function(o){that.handleParseAsync(o)},error:false});
			}
			else{
				app.popup('info','请先选择要处理的数据！',true);
			}
			this.ishandle=false;
		},
		handleParseAsync:function(xml){
			var that=this;
			var map=$util.toMapByXML(xml);
			this.treeVar=map.getItemTree('var');
			var _status=this.treeVar.v('status'),_message='';
			if(_status=='succeed'){
				_message=this.treeVar.v('handle.message');
				ui.popups('succeed',_message,true);
				$w.timeout(function(){
					that.refresh();
				},0.5);
			}
			else{
				_message=this.treeVar.v('message');
				if(!_message) _message='['+_status+']';
				app.popup('info',_message,true);
			}
			this.ishandle=false;
		},
		
	'':''},
	
'':''});
