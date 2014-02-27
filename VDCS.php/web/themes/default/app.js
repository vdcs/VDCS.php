
extendo(app,{
	nav:{
		initer:function(){
			this.bar();
			this.menu();
		},
		urlto:function(p,m,mi){
			if(!this.uri) this.uri=$c.url('url')+'{channel}/{p}/{m}/{mi}.html';
			if(!p)p='';if(!m)m='';if(!mi)mi='';
			var url=this.uri;
			url=rv(url,'channel',$c.channel);url=rv(url,'p',p);url=rv(url,'m',m);url=rv(url,'mi',mi);
			url=r(url,'/.','.');url=r(url,'/.','.');
			return url
		},
		bar:function(){
			var that=this;
			var jbar=$('#navbar');
			if(jbar.length<1) return;
			var jbarm=jbar.find('h3'),jbari=jbarm.find('a:first'),jbarnow=jbarm.find('a[el="now"]');
			var _item=jbar.finde('tpl').html();
			//if(!_item) return;
			var treeTitle=$util.toTreeByString(jbari.attrd('titles'),';','='),placea=(jbari.attrd('place')||'p,m').split(',');
			this.uri=jbari.attrd('uri');
			//dbg.otree(treeTitle);
			var _title=treeTitle.v('title'),_tit='';
			var _urlto=function(p,m,mi){return that.urlto(p,m,mi)};
			var _append=function(_tit,_url){
				var jitem=$(_item);
				jitem.attr('href',_url);
				jitem.finder('t')?jitem.find('t').html(_tit):jitem.html(_tit);
				jbarnow.before(jitem)
			};
			var _parse=function(k,p,m,mi){
				_tit=treeTitle.v('title.'+k);if(_tit==_title) _tit='';
				if(_tit){
					var _url=_urlto(p,m,mi);
					_append(_tit,_url);
				}
			};
			_parse('p',$c.p);
			_parse('m',$c.p,$c.m);
			_parse('mi',$c.p,$c.m,$c.mi);
		},
		menu:function(){
			var jmenu=$('.sets_menu');
			jmenu.find('li.'+$c.p+'').addClass('pop');
			var jnav=$('.sets_nav .links');
			jnav.finde($c.m).addClass('pop');
			jnav.finde('_'+$c.m).addClass('pop');
			var _url=this.urlto($c.p,$c.m,$c.mi);
			jnav.find('li a[href="'+_url+'"]').parents('li').addClass('pop');
			//!jnav.find('li').length || 
			if(jnav.find('[el=module]').length==2){
				jnav.hide();
				$('.conts:first').append('<div class="gray" style="font-size:20px;font-weight:bold;padding:20px;">建设中</div>');
			}
		},
	'':''},
	
'':''});
