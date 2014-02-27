
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
		$('[el=timenow] [el=today]').html(tmpYear+'-'+tmpMonth+'-'+tmpDay);
		$('[el=timenow] [el=week]').html(tmpWeek);
		
	},
	inited:function(){
		var that=this;
		this.jheader=$('#header'),this.jfooter=$('#footer');
		this.jinners=$('#body > .inners');
		var _resize=function(){
			var body_h=$(window).height()-that.jheader.height()-that.jfooter.height();
			var body_hi=body_h-10;
			that.jinners.height(body_h);
			that.jinners.find('.menus').height(body_hi);
			that.jinners.find('.strips').height(body_hi);
			that.jinners.find('.mains').height(body_hi);
			that.jinners.find('.mains iframe').height(body_hi);
		};
		_resize();
		$(window).resize(_resize);
		
		this.jmenus=$('#menus');
		this.jmenus.on('click','a',function(){
			$(this).attr('target','ifrm_main');	
		});
		
		var jnava=null;
		this.jnav=this.jheader.find('.menu .nav');
		this.jnav.find('li a').click(function(){
			if(jnava) jnava.parent('li').removeClass('pop');
			var jthis=$(this);
			jthis.parent('li').addClass('pop');
			jnava=jthis;
			mframe.menuClick(jthis.attrd('channel'));
			return false;
		});
		$w.timeout(function(){
			var jmenua=that.jnav.find('li a:first');
			jmenua.click();
			//$('iframe[name="ifrm_menu"]').attr('src',jmenua.attr('href'));
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
	
	
	items:[],
	menuClick:function(channel){
		if(this.menus._channel_==channel){
			return;
		}
		this.jmenus.html('<span class="loading"></span>');
		//alert(this.items[channel]);
		if(this.items[channel]){
			this.items[channel].doParse(this.jmenus);
			this.menuInitURL();
		}
		else{
			this.menuLoad(channel);
		}
		this.menus._channel_=channel;
	},
	menuInitURL:function(){
		var jitema=this.jmenus.find('.items a:first');
		jitema.click();
		this.mainOpen(null,jitema.attr('href'));
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
	
	mainOpen:function(channel,url){
		channel=channel?channel:this.jmenus.attr('channel');
		$('iframe[name="ifrm_main"]').attr('src',url);
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
