
extendo(pages,{
	
	initForms:function(jwrap){
		jwrap=jwrap?jwrap:$('.forms').parents('.xform');
		var datav=jwrap.data();
		//alert(jwrap.outerHTML());
		//this.params=datav.params');
		this.jform=jwrap;
		//##########
		ui.fbm.initer($('#fbm-wrap'));
		ui.form.init(jwrap);
		ui.uploadx.initer(jwrap);
		//##########
		var parama=[];
		if(this.params) parama.push(this.params);
		var _url_vars={channel:this.channel,p:this.p,m:this.m,action:this.action};	//x:this.x,,params:this.params
		if(datav.channel){
			var _params=datav.params;
			if(_params) parama.push(_params);
			_url_vars=ox(_url_vars,{channel:datav.channel,p:datav.p,m:datav.m,action:datav.action});		//,params:parama.join('&')
		}
		if(this.action=='edit') parama.push('id='+$req.q('id'));
		_url_vars=ox(_url_vars,{params:parama.join('&')});
		var opt={names:this.names,name:this.name};
		if(datav.title || datav.names){
			opt=ox(opt,{title:datav.title,names:datav.names,name:datav.action_name||datav.name});
		}
		//dbg.o(_url_vars);
		var _url_serve=ui.serve.getURL(_url_vars);
		//alert(_url_serve);
		var xaction=extend(app.xform,{
			initer:function(ps,selector){
				ps=ox({frm:'',
					title			: opt.title,
					names			: opt.names,
					name			: opt.name,
					message_formcheck	: '请填写必要的信息！',
					message_parser		: opt.name+'中..',
					message_succeed		: opt.name+'成功！',
					submit_ing		: opt.name+'中..',
					submit_succeed		: opt.name+'成功！',
					servURL			: _url_serve},ps);
				this._initer(ps,selector);if(!this.isinit)return;
				this.submitInit();
			},
		'':''});
		var _callback=function(status, treeVar){
			if($req.q('_repeat')){
				xaction.submitSet('on');
				ui.popups('succeed','提交成功！请继续..');
				return;
			}
			$('#fbm-wrap').hide();
			xaction.formHide();
			var jwraptip=jwrap;
			var jbox=jwrap.finder('div.box');
			if(jbox){
				$('#fbm-tab').hide();
				jwraptip=jwrap.finder('div.box > .con:first');
			}
			var title=xaction.opt.title,names=xaction.opt.names,name=xaction.opt.name;
			if(!name) name=xaction.jform.finde('action').finde('submit').attrd('title');
			if(!name) name='操作';
			var msga=[];
			msga.push('您已成功 '+name+' 了 '+names+' 信息！');
			var _onback=null;
			if($req.q('backurl')){
				_onback=function(){
					switch($req.q('backurl')){
						case 'close':
							window.close();
							break;
					}
				};
			}
			if(_onback==null){
				_onback=function(){
					$p.go(treeVar.getItem('backurl'));
				};
			}
			ui.mtip({wrap:jwraptip,status:'succeed',title:title,message:msga,index_off:true,onback:_onback});
		};
		xaction.jbody=jwrap.find('.forms');
		xaction.initer({goback:false,callback:_callback,tips_succeed:false,onerror:function(jitem){
			var jfbm=jitem.parents('[data-fbm]');
			if(jfbm.length && ui.fbm){
				ui.fbm.click(jfbm.attr('data-fbm'));
				jitem.focus();
			}
		}});
		this.xaction=xaction;
	},
	
'':''});
