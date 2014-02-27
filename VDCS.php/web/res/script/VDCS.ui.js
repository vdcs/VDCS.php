/*
Version:	VDCS UI Library
Support:	http://go.hpns.cn/vdcs/js
Uodated:	2014-01-00
*/

var ui={speed:500,effect:{},
	show:function(jo,real){return jo.ishow(real)},hide:function(jo,real){return jo.ihide(real)},
	float:function(jo,opt){return jo.floato(opt)},
	floatBox:function(jo,opt){if(!iso(jo)) jo=$(jo).appendTo('body');return jo.floatBox(opt)},
	
	//lock
	lock:{items:{},
		k:function(k){return k||'_def_'},
		is:function(k,set){
			var re=this.items[this.k(k)];
			if(set) this.en(k);
			return re
		},
		en:function(k){this.items[this.k(k)]=true},
		un:function(k){this.items[this.k(k)]=false},
	'':''},
'':''};

extendo(ui,{		//oparent:ui,
	//cover
	cover:{id_cover:'__xtip_cover',
		show:function(opt){
			opt=ox({real:false},opt);
			if(this._show&&!opt.real)return;this._show=true;
			this.real=opt.real;
			var that=this;
			var _resize=function(){
				if(!that._show)return;
				that.jo.width($w.wi).height($w.hi)
			};
			if(!this._init){
				this.jo=$('<div></div>').attr('id',this.id_cover).addClass('xtip_cover')
						.css({zIndex:99,position:'fixed',top:0,left:0,backgroundColor:'#000',opacity:0.8,display:'none'})
						.appendTo('body');
				//this.jo.fixIE6();
				this._init=true
			}
			this.jo.fadeIn(ui.speed);
			_resize();$w.resize(function(){_resize()});
			$w.reset();
			return this.jo
		},
		hide:function(){
			if(!this._show)return;
			this.jo.fadeOut(ui.speed);
			this._show=false;this.real=false
		},
		hidei:function(){if(!this.real) this.hide()},
	'':''},
	
	//mini
	mini:{id_mini:'__xtip_mini',
		show:function(message,opt){
			opt=ox({timer:2},opt);
			if(!this.jo){
				this.jo=$('<div></div>').attr('id',this.id_mini).addClass('xtip_mini')
						.css({zIndex:901,position:'fixed',top:45,right:15,		//.css(width,150)
							color:'#FFF',backgroundColor:'#68AF02',textAlign:'center',padding:'3px 10px'})
						.appendTo('body');
				//this.jo.fixIE6();
				this.jo.html('loading..');
			}
			if(opt.color) this.jo.css('color',opt.color);
			if(opt.bgcolor) this.jo.css('background-color',opt.bgcolor);
			if(opt.css) this.jo.css(opt.css);
			if(message) this.jo.html(message);
			ui.show(this.jo,'downi');
			if(opt.timer) this.hide(opt.timer);
			return this.jo
		},
		message:function(message){
			if(this.jo) this.jo.html(message)
		},
		hide:function(timer){
			var that=this;
			$w.timeout(function(){ui.hide(that.jo,'upi')},timer)
		},
	'':''},
	
	//popup
	popups:function(opt,message,cover,timer,close){
		if(!iso(opt)){
			opt={status:opt,message:message};
			if(!isn(cover)) opt.cover=cover;
			if(!isn(timer)) opt.timer=timer;
			if(!isn(close)) opt.close=close;
		}
		return this.popup.show(opt)
	},
	popup:{id_:'__xtip_popup',_classname:'ui-popup',speed:500,timer:2,
		init:function(){
			if(this.isinit)return;this.isinit=true;
		},
		bar:function(opt){
			
		},
		show:function(opt){
			this.init();
			if(this._show)return;this._show=true;
			opt=ox({status:'',message:'hello',cover:true,timer:this.timer,classname:this._classname},opt);
			this._opt=opt;
			var that=this;
			var _resize=function(){
				if(!that._show)return;
				that.jo.css({top:($w.hi-that.jo.height()-100)/2,left:($w.wi-that.jo.width())/2});
			};
			if(!this.init_){
				this.jo=$('<div></div>').attr('id',this.id_).addClass(this._opt.classname)
						.css({zIndex:10002,position:'fixed',top:0,left:0})
						.appendTo('body');
				//this.jo.fixIE6();
				$('<div></div>').addClass('pinner').append(
					$('<div></div>').addClass('pbody').append($('<p><em></em><span></span></p>'))
				).appendTo(this.jo);
				this.jcont=this.jo.finder('p:first span');
				if(!this.jcont) return;
				this.jo.click(function(){that.click()});
				this.init_=true;
			}
			this.jo.removeClass().addClass(this._opt.classname);
			if(this._opt.status) this.jo.addClass(this._opt.status);
			ui.show(this.jo,'up');
			if(this._opt.cover) ui.cover.show({real:true});
			if($b.ie6) this.jo.width(this._opt.message.length*10+120);		// ie6 hack
			this.message(this._opt.message);
			_resize();$w.resize(function(){_resize()});
			clearTimeout(this.timer_o);
			this.timer_v=this._opt.timer;
			if(this.timer_v) this.timer_o=$w.timeout(function(){that.hide()},this.timer_v);
			return this.jo
		},
		message:function(message){
			if(this._show&&this.jcont) this.jcont.html(message)
		},
		click:function(){if(this.timer_v) this.hide()},
		hide:function(){
			if(!this._show)return;
			ui.hide(this.jo,'up');
			if(this._opt.cover) ui.cover.hide();
			if(this._opt.close) this._opt.close();
			this._show=false
		},
	'':''},
	
	hint:function(jo,msg,opt){
		opt=ox({status:'',callback:null,tip:'.itip',tip_class:'itip',timer:2,speed:350,cover:true},opt);
		if(!jo||!jo.length){
			if(msg=='hide') ui.popup.hide(true)
			else ui.popups(opt.status,msg,opt.cover,opt.timer)
			return
		}
		if(msg=='hide' && this.hintTimer){
			$w.clearTimeout(this.hintTimer);this.hintTimer=null;
			//this.hintj&&this.hintj.slideUp()
		}
		if(msg=='hide') return;
		if(!msg || msg=='data') msg=jo.find('span').attr('data-value');
		if(!msg) return;
		msg=r(msg,'\n','<br/>');msg=r(msg,';','<br/>');msg=r(msg,'$$$','<br/>');
		if(isdebug('hint')) dbg.t(opt.status+' = '+msg);
		if(opt.tip) jo.find(opt.tip).removeClass().addClass(opt.tip_class).addClass(opt.status);
		//jo.hide();
		jo.find('span').html(msg);
		jo.slideDown(opt.speed);
		if(opt.timer!=null){
			if(isn(opt.timer)) opt.timer=2;
			var that=this;
			this.hintj=jo;
			this.hintTimer=$w.timeout(function(){
				//jo.stop().slideUp(opt.speed);
				jo.stop().slideUp(100);
				if(isf(opt.callback)) opt.callback();
				that.hintj=null
			},opt.timer);
		}
	},
	
	tipload:function(jo,action){
		var cn_cont='tipload-container';
		if(jo.hasClass(cn_cont)){
			if(action=='remove'){
				jo.find('.tipload-shade').remove();
				jo.find('.tipload').remove();
				jo.removeClass(cn_cont)
			}
			return
		}
		jo.addClass(cn_cont);
		jo.append('<span class="tipload-shade"></span><span class="tipload"><em></em></span></a>')
	},
	
	confirm:function(msg,callback,opt){
		if(msg && callback){
			opt=ox({message:msg,callback:callback},opt);
		}
		else{
			opt=msg;
		}
		opt=ox({mode:0,status:'info',title:'操作确认',submit_name:'确定',close_name:'取消'},opt);
		if(opt.mode>0) opt.message='您确定 '+opt.message+' 嘛？';
		if(opt.mode>1) opt.message+='<br/>操作将可能无法恢复！';
		//dbg.o(opt);
		var htmla=[];
		htmla.push('<div>');
		htmla.push('<div class="iconfirm">');
		if(opt.status) htmla.push('<p class="itip m info ac"><em></em><span>');
		htmla.push(opt.message);
		if(opt.status) htmla.push('</span></p>');
		htmla.push('</div>');
		htmla.push('</div>');
		var opti={title:opt.title,nobar:opt.nobar,nobtn:opt.nobtn,onsubmit:opt.callback,submit_name:opt.submit_name,onclose:opt.onclose,close_off:opt.close_off,close_name:opt.close_name,cover:opt.cover};
		this.jconfirm=$(htmla.join(NEWLINE)).ibox(opti)
	},
	
	mtip:function(opt){
		opt=ox({wrap:null,status:'info',title:'系统提示',message:'提示详细信息'},opt);
		opt.jwrap=opt.wrap?$j(opt.wrap):$('form!last').parent();
		this.jmtip=opt.jwrap.finder('.mtip');
		if(!this.jmtip){
			var htmla=[];
			htmla.push('<div class="mtip">');
			htmla.push('	<cite class="info"><em></em></cite>');
			htmla.push('	<h3>提示标题</h3>');
			htmla.push('	<h4>提示内容</h4>');
			htmla.push('	<h5>');
			htmla.push('		<a class="btn" el="index" href="'+$c.url('root')+'"><span>首页</span></a>');
			htmla.push('		<a class="btn" el="back" href="javascript:;"><span>返回</span></a>');
			htmla.push('	</h5>');
			htmla.push('</div>');
			this.jmtip=$(htmla.join(NEWLINE)).appendTo(opt.jwrap);
		}
		this.jmtip.show();
		this.jmtip.find('cite').removeClass().addClass(opt.status);
		this.jmtip.find('h3').html(opt.title);
		if(isa(opt.message)) opt.message='<p>'+opt.message.join('</p><p>')+'</p>';
		this.jmtip.find('h4').html(opt.message);
		var jbtn=this.jmtip.find('h5');
		if(!opt.onback && !opt.back_link) opt.onback=function(){$p.goback()};
		ui.setbtn('index',jbtn,opt);ui.setbtn('back',jbtn,opt);
		this.jmtip.opt=opt;
		return this.jmtip
	},
	
	setbtn:function(el,jbtn,opt){
		var jbtni=jbtn.find('[el="'+el+'"]');
		if(opt[el+'_off']) jbtni.hide();
		if(opt[el+'_name']) jbtni.find('span').html(opt[el+'_name']);
		if(opt[el+'_link']) jbtni.attr('href',opt[el+'_link']);
		if(opt['on'+el]) jbtni.click(opt['on'+el]);
	},
'':''});


/* ################ box ################ */
extendo(ui,{		//oparent:ui,
	box:function(jo,opt){$j(jo).ibox(opt)},
'':''});
extendo(ui.box,{
	createWrapper:function(jo,opt){
		opt=ox({cover:true},opt);
		var content=iso(jo)?jo.html():jo;
		//alert(content);
		var htmla=[];
		htmla.push('<div class="ui-box rc5">');
		htmla.push('<h3><t></t><a class="close iclose"><span>close</span></a></h3>');
		htmla.push('<div class="conts">'+content+'</div>');
		htmla.push('<h5><a class="btn m submit" el="submit"><span>确定</span></a><a class="btn close" el="close"><span>取消</span></a></h5>');
		htmla.push('</div>');
		return ui.floatBox(htmla.join(NEWLINE),opt)
	},
	setWrapper:function(jbox,opt){
		var jbtn=jbox.find('h5');
		if(opt.nobar) jbox.find('h3').hide();
		if(opt.nobtn) jbtn.hide();
		if(opt.title) jbox.find('h3 t').html(opt.title);
		if(opt.btn_align) jbtn.css('text-align',opt.btn_align);
		ui.setbtn('submit',jbtn,ox(opt,{onsubmit:null}));
		ui.setbtn('close',jbtn,ox(opt,{onclose:null}));
		var _submit=function(){
			var issubmit=true;
			if(opt.onsubmit) issubmit=opt.onsubmit(jbox);
			if(typeof issubmit =='undefined') issubmit=true;
			if(issubmit) _close();
		};
		var _close=function(){
			var isclose=true;
			if(opt.onclose) isclose=opt.onclose(jbox);
			if(typeof isclose =='undefined') isclose=true;
			if(isclose){
				ui.hide(jbox,opt.effect);
				if(opt.cover) ui.cover.hidei();
				jbox.remove()
			}
		};
		jbox.find('h3 > .close, h5 > .close').click(_close);
		jbox.jsubmit=jbox.find('h5 > .submit').click(_submit);
	},
	submit:function(jbox){jbox.find('h5 > .submit').click()},
	close:function(jbox){jbox.find('h3 > .close').click()},
'':''});

(function($){
$.fn.extend({
	ibox:function(opt){
		if(opt && !iso(opt)){
			switch(opt){
				case 'submit':		ui.box.submit(ui.jibox);break;
				case 'close':		ui.box.close(ui.jibox);break;
			}
			return
		}
		var jthis=$(this);
		opt=ox({effect:'left',cover:true},opt);
		opt=ox(opt,jthis.data());
		//if(ui.jibox) ui.jibox.stop(true,true).remove();
		var jbox=ui.box.createWrapper(jthis,opt);
		ui.box.setWrapper(jbox,opt);
		ui.jibox=jbox;
		if(opt.bindEvent) opt.bindEvent(jbox);
		return jbox
	},
'':''});
})(jQuery);


/* ############### effect ############## */
extendo(ui.effect,{
	remove:function(jo,callback){
		jo.fadeOut(300,function(){
			jo.remove();
			if(callback) callback();
		});
	},
	expandTalk:function(jmsg,jact,opt){
		opt=ox({style:'def',speed:100,speedh:200,timer:0.5,high_base:32,high_multiple:2,collapse:false},opt);
		var jmsgp=jmsg.parent(),msgp_h=jmsgp.height()||opt.high_base,msgp_origh=msgp_h*opt.high_multiple,timer_pack;
		jmsgp.attr('height_orig',msgp_origh);
		jmsg.autoresizer({speed:opt.speed,speedh:opt.speedh});
		jmsg.click(function(){
			if(opt.style=='high' && !jmsg.val()){
				jmsgp.animate({height:msgp_origh},{speed:opt.speed});
			}
			if(!jact.is(':visible')) jact.slideDown(opt.speed)
		});
		if(opt.collapse){jmsg.blur(function(){
			timer_pack=$w.timeout(function(){
				if(jmsg.val())return;
				if(opt.style=='high') jmsgp.animate({height:msgp_h},opt.speedh);
				if(jact.is(':visible')) jact.slideUp(opt.speedh);
			},opt.timer);
		});}
		if(jmsg.val()) jmsg.focusEnd().click();
		jact.click(function(){$w.clearTimeout(timer_pack)});
	},
	counts:function(jbox,opt){
		opt=ox({max:140},opt);
		var jbox=jbox;
		var jmsg=jbox.find('textarea[name="message"]');
		var keyupBox=function(){
			var re=false;
			var _count=opt.max-jmsg.val().length;
			jbox.finde('letters').text(_count);
			jbox.find('.counts i').text(_count);
		};
		jmsg.keyup(keyupBox);
		keyupBox()
	},
'':''});


/* ################ form ############### */
ui.form={
	class_pop:'gray',
	parser:function(obj,opt){
		if(typeof opt !='object'){
			this.type=opt;
		}else{
			this.type=opt.type;
		} 
		if(!this.type) this.type='checkbox';
		this.obj=obj;
		this.opt=opt;
		if(this.obj.find('input').length>6) return;
		var opn_html=this.getOpnHtml();
		this.obj.after(opn_html);
		this.bindAction();
		this.obj.hide();
	},
	getOpnHtml:function(){
		var that=this;
		var htmla=[];
		htmla.push('<div class="opn-group" data-toggle="buttons-'+that.type+'">');
		this.obj.find('input').each(function(){
			var jthis=$(this);
			var id=jthis.attr('id');
			var text=jthis.parent('label').text();
			if(!text) text=jthis.next('span').text();
			if(!text) text=jthis.attr('value');
			var ischecked='';
			if(jthis.checked()) ischecked='opn-'+that.class_pop+'';
			htmla.push('<a type="button" class="opn '+ischecked+'">'+text+'</a>');
		});
		htmla.push('</div>');
		//$('.myval').after(htmla.join(''));
		return htmla.join('')
	},
	bindAction:function(){
		var that=this;
		this.opns=this.obj.next('.opn-group');
		this.opns.find('.opn').each(function(i){
			if($(this).attr('isinit')) return;
			if(that.type=='checkbox'){
				$(this).bind('click.check',function(){
					if($(this).hasClass('opn-'+that.class_pop+'')){
						that.getOrigWrap($(this)).find('input:eq('+i+')').checked(false);
					}else{
						that.getOrigWrap($(this)).find('input:eq('+i+')').checked(true);
					}
				}).attr('isinit','yes');
			}else if(that.type=='radio'){
				//that.getOrigWrap($(this)).find('input').checked(false)
				$(this).bind('click.check',function(){
					var jinput=that.getOrigWrap($(this)).find('input');
					jinput.checked(false);
					jinput.end().find('input:eq('+i+')').checked(true);
					//alert(i);
				}).attr('isinit','yes');
			}
			
		});
	},
	getOrigWrap:function(jthis){
		var jparent=jthis.parent('.opn-group');
		var jre=jparent.prev('[data-ui-form]');
		if(jre.length<1) jre.prev('div');
		return jre
	},

	bindi:function(jo){
		var selector='input[type="checkbox"][data-bind="check"]';		//,.iradio,.iselect
		jo=jo?jo.find(selector):$(selector);
		this.bindii(jo)
	},
	bindii:function(jo){
		var that=this;
		jo.each(function(){
			var jthis=$(this),type=jthis.attr('type');
			switch(type){
				case 'checkbox':that.bindicheck(jthis);break;
				//case 'iradio':that.bindiradio(jthis);break;
				//case 'iselect':that.bindiselect(jthis);break;
			}
		});
	},
	bindicheck:function(jo){
		if(!jo.is('input') || !jo.attr('type')=='checkbox') return;
		if(jo.attr('_init'))return;jo.attr('_init','yes');
		var jlabel=jo.parent();
		if(!jlabel.is('label')){
			jlabel=$('<label>'+jo.outerHTML()+'</label>');
			jo.replaceWith(jlabel);
			jo=jlabel.find('input')
		}
		var jem=jlabel.finder('em');
		if(!jem){
			jem=$('<em class="icheck"></em>');
			jem.addClass(jo.attrd('style'));
			jo.after(jem)
		}
		jo.hide();
		if(isdebug('data')) dbg.t('icheck','value='+jo.val());
		if(jo.checked()) jem.addClass('checked');
		var _parse=function(){
			if(jo.checked()){
				jo.checked(false);
				jem.removeClass('checked')
			}
			else{
				jo.checked(true);
				jem.addClass('checked')
			}
			return false
		};
		jlabel.click(_parse)
	},
	bindiradio:function(jo){},
	bindiselect:function(jo){},

	init:function(jwrap){
		var that=this;
		if(jwrap){
			jwrap.find('[data-ui-form]').each(function(){
				var jthis=$(this);
				if(jthis.attr('data-ui-form')) that.parser(jthis,jthis.attr('data-ui-form'));
			});
		}
		this.initOpnAction()
	},
	initOpnAction:function(){
		var that=this;
		$(document).on('click.button.data-api', '[data-toggle^=button]', function (e) {
			var $opn = $(e.target);
			if (!$opn.hasClass('opn')) $opn = $opn.closest('.opn');
			that.switchopn($opn,'toggle');
		})	
	},
	switchopn:function(obj,option){
		var that=this;
		return obj.each(function(){that.toggleopn(obj)})
	},
	toggleopn:function(obj){
		var that=this;
		var $parent = obj.closest('[data-toggle="buttons-radio"]')
		$parent && $parent.find('.opn-'+that.class_pop+'').removeClass('opn-'+that.class_pop+'');
		obj.toggleClass('opn-'+that.class_pop+'')
	},
'':''};


/* ############### serve ############### */
ui.serve=new VDCS.serve();


/* ################ list ############### */
ui.list=new VDCS.list();

ui.paging={
	parser:function(treeVar,jpaging,click,opt){
		opt=ox({},opt);
		/*
		var opt_str={};
		if(typeof opt.limit !='undefined') opt_str.limit=opt.limit;
		*/
		jpaging.html('');
		if(treeVar.vi('paging.total')<1) return;
		jpaging.html(this.toString(opt,treeVar));
		jpaging.find('a[page]').click(function(){
			var jthis=$(this);
			if(ins(jthis.parent('li').attr('class'),'disabled')<0){
				var page=$(this).attr('page');
				click && click(page)
			}
			return false
		});
		jpaging.find('.jump_btn').click(function(){
			var page=toi(jpaging.find('.jump_page').val());
			if(page) click && click(page)
			return false
		});
	},
	filterOpt:function(opt){
		var numhalf=Math.floor(opt.pagenum/2);
		opt.pagebegin=opt.page-numhalf;
		if(opt.pagebegin<1) opt.pagebegin=1;
		opt.pageend=opt.pagebegin+opt.pagenum-1;
		if(opt.pageend>opt.pagetotal) opt.pageend=opt.pagetotal;
		return opt
	},
	toString:function(opt,treeVar){
		var _opt={page:1,listnum:10,total:0,pagenum:5,pagetotal:0,pagebase:0,
			classname:'pagination',
			href:'javascript:;',
			limit:false,around:false,total:true,jump:false,jump_txt:'GO'};
		if(treeVar && treeVar.vi){
			_opt=ox(_opt,{
				page:treeVar.vi('paging.page'),
				listnum:treeVar.vi('paging.listnum'),
				total:treeVar.vi('paging.total'),
				pagenum:treeVar.vi('paging.pagenum'),
				pagetotal:treeVar.vi('paging.pagetotal'),
				pagebase:treeVar.vi('paging.pagebase')
			});
		}
		opt=ox(_opt,opt);
		opt=this.filterOpt(opt);
		var htmla=[];
		htmla.push('<div class="'+opt.classname+'" data-total="'+opt.total+'" data-pagetotal="'+opt.pagetotal+'">');		// pagination-centered
		htmla.push('<ul>');
		if(opt.limit) htmla.push('<li class="first'+(opt.page<2?' disabled':'')+'"><a href="'+opt.href+'" page="1">&laquo;</a></li>');
		if(opt.around || opt.page>1) htmla.push('<li'+(opt.page<2?' class="disabled"':'')+'><a href="'+opt.href+'" page="'+(opt.page-1)+'"><</a></li>');
		for(var i=opt.pagebegin;i<=opt.pageend;i++){
			var _active='';
			if(opt.page==i) _active=' class="active"';
			htmla.push('<li'+_active+'><a href="'+opt.href+'" page="'+i+'">'+i+'</a></li>');
		};
		if(opt.around || (opt.page<opt.pagetotal && opt.page>0)) htmla.push('<li'+(opt.page>=opt.pagetotal?' class="disabled"':'')+'><a href="'+opt.href+'" page="'+(opt.page+1)+'">></a></li>');
		if(opt.limit) htmla.push('<li class="last'+(opt.page>=opt.pagetotal?' disabled':'')+'"><a href="'+opt.href+'" page="'+opt.pagetotal+'">&raquo;</a></li>');
		htmla.push('</ul>');
		if(opt.total) htmla.push('<cite><i>'+opt.total+'</i></cite>');
		if(opt.jump){
			htmla.push('<div class="jump">');
			htmla.push('<i><input type="text" class="jump_page" name="jump_page" value="'+opt.page+'" size="3" /></i>');
			htmla.push('<a class="btn jump_btn"><span>'+opt.jump_txt+'</span></a>');
			htmla.push('</div>');
		}
		htmla.push('</div>');
		return htmla.join(BR)
	},
'':''};


/* ################ pages ############## */
ui.pages={channel:'',
	ex:function(jbtn,opt){
		var that=this;
		jbtn.click(function(){
			that.recordClick($(this),opt);
			return false
		})
	},
	exClick:function(jbtn,opt){
		opt=ox({names:'标题',name:'操作'},opt);
		var that=this;
		var serveURLE=ui.serve.getURL(ox({channel:this.channel,x:'e'},opt.serveE));
		var serveURLX=opt.serveXurl?opt.serveXurl:ui.serve.getURL(ox({channel:this.channel,x:'x'},opt.serveX));
		//dbg.t('url',serveURLE);
		//dbg.t('url',serveURLX);
		if(!opt.callbacke){		//模板加载完之后调用的函数
			opt.callbacke=function(html){
				that._bindParseX(html,serveURLX,opt);		
			};
		}
		this._bindParseE(serveURLE,opt)
	},
	_bindParseE:function(_url,opt){
		ui.mini.show('正在加载..');
		$ajax({url:_url,value:"text",ready:function(html){if(opt.callbacke) opt.callbacke(html)},error:true})
	},
	_bindParseX:function(html,_url,opt){
		ui.mini.hide();
		
		var xaction=opt.xaction;
		var _submit=function(){
			xaction.parser();
			return false
		};

		var opt_box={onsubmit:function(){return _submit()}};
		opt_box=ox({},opt,opt_box);
		var htmla=html.split('<!--script-->'),_script=htmla[1];
		var jhtml=$(htmla[0]);
		if(!jhtml.finder('.tips')) jhtml.append('<div class="tips hide"><p class="itip"><em></em><span>提示信息</span></p></div>');
		var jbox=jhtml.ibox(opt_box);
		if(_script) $('body').append(_script);
		if(opt.boxInit) opt.boxInit(jbox);
		opt.callbackx=function(status,treeVar){
			jbox.ibox('close');
			if(opt.succeed) opt.succeed(treeVar);
		};

		if(opt.serveXaction) xaction=opt.serveXaction();
		if(!xaction){
			xaction=extend(new VDCS.forms(),{	//app.forms
				initer:function(ps,selector){
					ps=ox({frm:'',
						names			: opt.names,
						message_formcheck	: '请填写必要的信息！',
						message_parser		: opt.name+'中..',
						message_succeed		: opt.name+'成功！',
						submit_ing		: opt.name+'中..',
						submit_succeed		: opt.name+'成功！',
						servURL			: _url},ps);
					this._initer(ps,selector);if(!this.isinit)return;
					this.submitInit();
				},
			'':''});
		}
		xaction.jbody=jbox;
		xaction.initer(ox({goback:false,callback:opt.callbackx},opt.xopt))
	},
	
	record:function(jbtn,opt){return this.ex(jbtn,opt)},
	recordClick:function(jbtn,opt){return this.exClick(jbtn,opt)},
	del:function(jbtn,opt){
		if(!opt.confirm) opt.confirm='您确定要删除一条信息嘛？';
		var that=this;
		jbtn.click(function(){
			ui.confirm(opt.confirm,function(){
				that.delParser($(this),opt);
			})
			return false
		});
	},
	delParser:function(jbtn,opt){
		var _url=ui.serve.getURL(ox({channel:this.channel,x:'x'},opt.serveX));
		//dbg.t(_url);return;
		$ajax({url:_url,value:"xml",ready:function(xml){
			var map=$util.toMapByXML(xml);
			var treeVar=map.getItemTree('var');
			var status=treeVar.v('status');
			if(status=='succeed'){
				if(opt.succeed) opt.succeed();
			}else{
				alert(treeVar.v('error_message'));
			}
		},error:true})
	},	
'':''};


ui.fbm={_relates:{}};
extendo(ui.fbm,{px_pane:'fbm-body-',
	relates:function(v){this._relates=v},
	
	init:function(jwrap){var that=this;$(function(){that.initer(jwrap)})},
	initer:function(jwrap){
		if(!jwrap) jwrap=$('#fbm-wrap');
		if(!jwrap.length){
			$('#fbm-tab').hide();
			return
		}
		this.jwrap=jwrap;
		this.jtarget=this.jwrap.attrd('target')?$(this.jwrap.attrd('target')):this.jwrap;
		var Ddef=this.jwrap.attr('data-define')||this.jwrap.find('[el="define"]').html();
		var arDdef=Ddef.split("$$$");
		var iDef=s2o(arDdef[0],";","="),iVar=s2o(arDdef[1],";","="),iNow;
		var ifirst='';
		var htmla=[];
		htmla.push('<ul class="tab-nav">');
		for(var key in iVar){
			var tit=iVar[key];this.tabi=this.tabi||key;
			if(tit){
				iDef["default"]=iDef["default"]||key;
				htmla.push('<li><a href="#'+this.px_pane+key+'" fbm-key="'+key+'">'+tit+'</a></li>');	// data-toggle="tab"
			}
		}
		htmla.push('</ul>');
		this.jtarget.html(htmla.join(BR));
		
		var _default=$f.getValue('frm_post._multibar');
		if(!_default) _default=iDef['default'];
		if(!_default) _default=this.tabi;
		this.jtabs=this.jtarget.find('.tab-nav');
		var jtab=this.jtabs.finder('a[fbm-key="'+_default+'"]');
		if(!jtab){
			jtab=this.jtabs.finder('a[fbm-key]:first');
			_default=jtab?jtab.attrd('key'):'';
		}
		if(jtab){
			jtab.parent('li').addClass('active');
			$('#'+this.px_pane+_default).addClass('active');
		}
		this.jtabs.on('click.tab.data-api','[fbm-key]',function(e){
			e.preventDefault()
			$(this).tab('show')
		});
	},
	click:function(key){
		$f.v('frm_post._multibar',key);
		this.jtabs.find('a[fbm-key="'+key+'"]').trigger('click');
	},
	
	item:function(key){
		$f.v('frm_post._multibar',key);
		for(ik in this._relates){
			if(inp(this._relates[ik],key)>0) $j(this.px_pane+ik).show();
			else $j(this.px_pane+ik).hide();
		}
	},
	load:function(){
		$f.v('frm_post._multibar',this.k1);
		this.jwrap.find('[el="loading"]').hide();this.jbar.show();
	},
	loading:function(def_,defs_){
		this.jwrap.html(this.toString(def_,defs_));
		this.init();
	},
	toString:function(def_,defs_){
		var htmla=[];
		htmla.push('<div el="bar" class="bar" style="display:none;"><table class="multibar">');
		htmla.push('<tr class="multibar"><td colspan="2"><div class="multibar-items" el="items"></div></td></tr>');
		htmla.push('</table></div>');
		htmla.push('<div el="loading" class="loading multibar-loaddata"><span>数据加载中...</span></div>');
		htmla.push('<xmp el="tpl"><a id="fbm-bar-{key}" _key="{key}" href="javascript:;" onclick="javascript:ui.fbm.click(\'{key}\');"><span>{title}</span></a></xmp>');
		htmla.push('<xmp el="define">default=' + def_ + '$$$' + defs_ + '</xmp>');
		return htmla.join(BR)
	},
'':''});

ui.editor={
	loader:function(callback){
		if(this.isloader){
			if(this.isloader>1) callback&&callback();
			return;
		}this.isloader=1;
		var that=this;
		var urlRes=[];
		if(!window.CKEDITOR){
			//urlRes.push($c.url('script')+'ckeditor/xeditor.js');
			//urlRes.push($c.url('script')+'ckeditor/ckeditor.js');
		}
		//urlRes.push($c.url('images')+'common/upload/uploadm.js'');
		//urlRes.push($c.url('images')+'common/upload/uploadm.css');
		urlRes.push($c.url('images')+'themes/widget/ui/editor.js');
		urlRes.push($c.url('images')+'themes/widget/ui/editor.css');
		//dbg.o(urlRes);
		$.include(urlRes,function(){
			that.isloader=2;
			callback&&callback();
		});
	},
'':''};

