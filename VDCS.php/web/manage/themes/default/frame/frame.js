
var mframe={
	_data:{
		'frameset.main':'FrameSetMain',
		'frameset.body':'FrameSetBody',
		'nav.frame':'FrameNav',
		'nav.size':80,
		'menu.frame':'FrameMenu',
		'menu.size':160,
		'menu.space':6,
		'main.frame':'FrameMain'
	},
	set:function(k,v){this._data[k]=v;},
	get:function(k){return this._data[k];},
	
	
	initer:function(){
		this.initNow();
		this.inited();
	},
	initNow:function(){
		var tmpDate=new Date();
		var tmpYear=tmpDate.getYear();
		tmpYear=(tmpYear<1000) ? tmpYear+1900 : tmpYear;
		var tmpMonth=tmpDate.getMonth()+1;
		var tmpDay=tmpDate.getDate();
		var aryWeek=new Array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');
		var tmpWeek=aryWeek[tmpDate.getDay()];
		$('#timer [el=today]').html(tmpYear+'-'+tmpMonth+'-'+tmpDay);
		$('#timer [el=week]').html(tmpWeek);
		
	},
	inited:function(){
		var that=this;
		this.jheader=$('#header'),this.jfooter=$('#footer');
		this.jbody=$('#body > .inners');
		this.jmains=this.jbody.find('.mains');
		var _resize=function(){
			var body_h=$(window).height()-that.jheader.height()-that.jfooter.height();
			var body_hi=body_h-10;
			that.jbody.height(body_h);
			that.jbody.find('.menus').height(body_hi);
			that.jbody.find('.strips').height(body_hi);
			that.jmains.height(body_hi);
			that.jmains.find('.ifrm').height(body_hi);
			that.body_hi=body_hi;
		};
		_resize();
		$(window).resize(_resize);
		
		this.tabIniter();

		this.jmenus=$('#menus');
		this.jmenus.on('click','a',function(){
			that.tabOpen($(this).attr('href'),$(this).text());
			return false
		});
		
		var jnava=null;
		this.jnav=this.jheader.find('.menu');
		this.jnav.on('click','li a',function(){
			if(jnava) jnava.parent('li').removeClass('pop');
			var jthis=$(this);
			jthis.parent('li').addClass('pop');
			jnava=jthis;
			that.menuClick(jthis.attrd('channel'));
			return false;
		});
		$w.timeout(function(){
			var ja=that.jnav.find('li a:first');
			ja.click();
		},0.1);
	},
	
	reload:function(target,url){
		this.mainOpen(null,url?url:jfrm.attr('href'));
	},
	logout:function(obj){
		if(!obj) return false;
		ui.confirm('您确定要 退出本系统 嘛？',function(){$p.go($(obj).attr('href'))});
		return false;
	},
	

	tab_index:-1,tab_index_now:-1,
	tabIniter:function(){
		var that=this;
		this.jtabs=this.jbody.find('.tabs');
		this.jtabsul=this.jtabs.find('ul');
		this.tab_tpl=this.jtabs.find('xmp').html();
		this.jtabs.on('click','li a',function(){
			that.tabSwitch($(this).parents('li'));
			return false
		});
		this.jtabs.on('click','li i',function(){
			that.tabClose($(this).parents('li'));
			return false
		});
	},
	tabOpenA:function(ja,url,title){
		url=url||ja.attr('tab-url')||ja.attr('href');
		title=title||ja.attr('tab-title')||ja.attrd('title');
		this.tabOpen(url,title);
	},
	tabOpen:function(url,title,first){
		var that=this;
		if(!url){
			dbg.t('tabOpen','nourl');
			return;
		}
		if(first){
			//tab_index_now
		}

		var urlb=url;
		urlb=r(urlb,'"','\"');
		var jtabv=this.jtabsul.find('li[tab-url^="'+urlb+'"]');
		if(jtabv && jtabv.length>0){
			this.tabSwitch(jtabv);
			return;
		}

		if(this.tab_index_now>-1){
			this.tabIndexHide(this.tab_index_now);
		}
		this.tab_index++;
		var _index=this.tab_index;
		//dbg.t('index='+this.tab_index,url);
		if(this.jtabsul.find('li[tab-index='+_index+']').length<1){
			var jtab=$(this.tab_tpl);
			jtab.appendTo(this.jtabsul);
			jtab.attr('tab-index',_index);
			if(title) jtab.find('a:first span').text($codes.toHTML(title,1,20));
			var jfrm=$('<div class="ifrm"><iframe name="ifrm_'+_index+'" src="about:blank" noresize style="height:100%;"></iframe></div>');
			jfrm.appendTo(this.jmains);
			jfrm.attr('tab-index',_index);
			jfrm.height(this.body_hi);
			jfrm.find('iframe').load(function(){
				that.tagIframeLoad($(this));
			});
		}
		this.tabIndexShow(_index,url);
	},
	tagIframeLoad:function(jifrm){
		var contents=jifrm.contents()[0];
		if(contents){
			var url=contents.URL;
			url=contents.location.pathname+contents.location.search;
			if(url){
				var index=jifrm.parent().attr('tab-index');
				//dbg.t(index);
				this.tabIndexSetURL(index,url);
			}
			else{
				dbg.t('mframe.tagIframeLoad','contents.url bad.');
			}
		}
	},
	tabClose:function(jtab){
		if(!jtab || jtab.hasClass('main')) return;
		var jtab0=jtab.prev();
		if(jtab0.length<1) jtab0=jtab.next();
		if(jtab0.length<1) return;

		var index=jtab.attr('tab-index');
		//dbg.t(this.tab_index_now+','+index);
		if(this.tab_index_now<0 || this.tab_index_now==index){	// || (this.tab_index_now>-1 && this.tab_index_now!=index)
			this.tabIndexShow(jtab0.attr('tab-index'));
		}
		this.jmains.find('.ifrm[tab-index="'+index+'"]').remove();
		jtab.remove();
	},
	tabSwitch:function(jtab){
		if(!jtab) return;
		var index=jtab.attr('tab-index');
		if(this.tab_index_now==index) return;
		this.tabIndexHide(this.tab_index_now);
		this.tabIndexShow(index);
	},
	tabIndexSetURL:function(index,url){
		var jtab=this.jtabsul.find('li[tab-index="'+index+'"]');
		jtab.attr('tab-url',url);
	},
	tabIndexShow:function(index,url){
		//dbg.t('index.show='+index,url);
		var jtab=this.jtabsul.find('li[tab-index="'+index+'"]').addClass('pop');
		var jfrm=this.jmains.find('.ifrm[tab-index="'+index+'"]').show();
		if(url){
			jtab.attr('tab-url',url);
			jfrm.find('iframe').attr('src',url);
		}
		this.tab_index_now=index;
	},
	tabIndexHide:function(index){
		//dbg.t('index.hide='+index);
		this.jtabsul.find('li[tab-index="'+index+'"]').removeClass('pop');
		this.jmains.find('.ifrm[tab-index="'+index+'"]').hide();
	},
	

	mainOpen:function(channel,url,index){
		channel=channel?channel:this.jmenus.attr('channel');
		this.tabOpen(url,index);
	},
	

	items:[],
	menuClick:function(channel){
		if(this.menus._channel_==channel){
			return;
		}
		this.jmenus.html('<span class="loading"></span>');
		//alert(this.items[channel]);
		if(this.items[channel]){
			this.items[channel].doParse(this.jmenus);
			//this.menuInitURL();
		}
		else{
			this.menuLoad(channel);
		}
		this.menus._channel_=channel;
	},
	menuInitURL:function(){
		var jitema=this.jmenus.find('.items a:first');
		//jitema.click();
		this.tabOpen(jitema.attr('href'),jitema.text(),true);
	},
	menuLoad:function(channel){
		var that=this;
		var exts='.'+$c.EXT;
		var _url=window.location.toString().split(document.domain)[1];
		if(ins(_url,exts)<1) _url+='index'+exts;
		_url=_url.split(exts)[0]+exts+'/frame/menu.x?channel={$channel}';
		_url=rd(_url,'channel',channel);
		//dbg.t('url',_url);
		$ajax({url:_url,value:'xml',ready:function(xml){
			var map=$util.toMapByXML(xml);
			var treeVar=map.getItemTree('var');
			var status=treeVar.v('status');
			if(status=='succeed'){
				that.menuParse(map.getItemTable('item'),channel);
			}
			else{
				ui.popups('error','错误的频道：'+channel,true);
			}
		},error:true});
	},
	menuParse:function(tableItem,channel){
		var omenu=new mframe.libMenu();
		omenu.channel=channel;
		tableItem.begin();
		for(var t=1;t<=tableItem.row();t++){
			omenu.addItems(tableItem.iv('type'),tableItem.iv('name'),tableItem.iv('url'),'','');
			tableItem.move();
		}
		omenu.doParse(this.jmenus);
		this.menuInitURL();
		this.items[channel]=omenu;
	},
'':''};


//########################################
//########################################
mframe.menus={
	
};

mframe.libMenu=function(){
	this.channel='';
	this.items=[];
	this.addItems=function(_type,_name,_url,_icon,_script){
		this.items[this.items.length]={'type':_type,'name':_name,'url':_url,'icon':_icon,'script':_script};
	};
	this.doParse=function(jmenus){
		var __frame=$('#element-menu-frame').html();
		var __bars=[];
		__bars['bar']=$('#element-menu-bar').html();
		__bars['sub']=$('#element-menu-sub').html();
		var __item=$('#element-menu-item').html();
		var __item_sup=$('#element-menu-item-sup').html();
		var __icon=$('#element-menu-icon').html();
		var __space=$('#element-menu-space').html();
		jmenus.attr('channel',this.channel).html('');
		var _items='',bn=0,imn=0,ispace=false;
		for(var i=0;i<this.items.length;i++){
			var oitem=this.items[i];
			if(oitem['type']=='bar'||oitem['type']=='sub'){
				bn++;
				if(_items){
					ispace=true;
					_frames=((bn>2 && imn>0)?__space:'')+__frame;
					_frames=rd(_frames,'sn',bn-1);
					_frames=rd(_frames,'bar',_itembar);
					_frames=rd(_frames,'items',_items);
					jmenus.append(_frames);
					_items='';
				}
				_itembar=__bars[oitem['type']];
				_itembar=rd(_itembar,'sn',bn);
				_itembar=rd(_itembar,'name',oitem['name']);
				imn=0;
			}
			else{
				_item=oitem['type']=='sup'?__item_sup:__item;
				_item=rd(_item,'sn',bn);
				_item=rd(_item,'name',oitem['name']);
				_item=rd(_item,'url',oitem['url']);
				_item=rd(_item,'icon',oitem['icon']);
				_items+=_item;
				imn++;
			}
		}
		//dbg.t(imn);
		if(_items){
			_frames=((bn>1 && imn>0 && ispace)?__space:'')+__frame;
			_frames=rd(_frames,'sn',bn);
			_frames=rd(_frames,'bar',_itembar);
			_frames=rd(_frames,'items',_items);
			jmenus.append(_frames);
			_items='';
		}
	};
};
