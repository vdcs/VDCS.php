/*
Version:	Public for Stage
Uodated:	2014-01-00
*/

$c.v('url.login',			$c.url('root')+'login'+$c.EXTS);
$c.v('url.register',			$c.url('root')+'register'+$c.EXTS);

$c.v('url.report',			$c.url('common')+'report'+$c.EXTS);
$c.v('url.commend',			$c.url('common')+'commend'+$c.EXTS);
$c.v('url.comment',			$c.url('common')+'comment'+$c.EXTS);

$c.v('url.search',			$c.url('common')+'search'+$c.EXTS);
$c.v('url.upload',			$c.url('common')+'upload'+$c.EXTS);


//########################################
//########################################
var page={},appi={},appc={},widget={};
var app={
	init:function(){$(function(){app.initer()})},
	initer:function(){
		
	},
	
	uu_link			: '/u/{$uid}',
	uu_avatar		: '/avatar/{$uid}_small.gif',
	uu_avatarb		: '/avatar/{$uid}_big.gif',

	serve_entry:{_:'/p.php/{$router}?',c:'common',a:'account',p:'passport'},
	serveEntry:function(k,v){if(v)this.serve_entry[k]=v;return this.serve_entry[k]},
	serve:function(p,params){
		var pi=p.indexOf('/'),chn=p.substring(0,pi),route=p.substring(pi);
		if(this.serve_entry[chn])chn=this.serve_entry[chn];
		if(route.indexOf('.')<1)route+='.x';
		var re=rd(this.serve_entry['_'],'router',chn+route);
		re=ui.serve.filterURL(re,true);
		return $url.link(re,params)
	},
	
	uio:function(jo){
		app.placeholder(jo);
		app.tooltip(jo);
		ui.form.bindi(jo);
	},
	
	placeholder:function(jo){
		var selector='input[placeholder]';
		jo=jo?jo.find(selector):$(selector);
		jo.placeholder()
	},
	tooltip:function(jo){
		jo=jo?jo:$(document);
		if($.fn.tooltip){
			var opt={animation:true,delay:{show:100,hide:300},container:'body',html:true};
			//$(d).tooltip(opt);
			jo.find('[rel="tooltip"],[title]').tooltip(opt);
		}
		$.fn.popover && jo.find('[rel="popover"]').popover();
	},
	linktop:function(selector){
		$(selector||'#linktop').linktop();
	},
	
	tips:function(status,msg,cover,timer){return this.popup(status,msg,cover,timer)},popups:function(status,msg,cover,timer,close){return this.popup(status,msg,cover,timer,close)},
	popup:function(status,msg,cover,timer,close){
		timer=timer||3;
		if(!msg) msg='[unknown]';
		return ui.popups(status,msg,cover,timer,close)
	},
	hint:function(jo,msg,opt){return ui.hint(jo,msg,opt)},
'':''};
app.init();


if(typeof noload === 'undefined'){
$(document).ready(function(){
	app.uio();
	app.linktop();
})
}


//########################################
//########################################
app.forms=new VDCS.forms(),app.cform=extend(app.forms,{}),app.xform=extend(app.forms,{});


//########################################
//########################################
var comm=common={};
extendo(comm,{
	timei:function(time){
		var timeDT=$time.toDate(time).distance();
		var timei=(timeDT[0]<3)?timeDT[1]:$time.toConvert(time,'m月d日 h:i');
		if(!timei) timei='刚刚';
		return timei;
	},
	
	toUploadThumb:function(filepath,type){
		if(!filepath) return '';
		var re=filepath;
		if(left(filepath,8)=='/upload/'){
			re='/up'+type+'/'+filepath.substring(8);
		}
		else if(ins(filepath,'://')>0){
			var domain=window.location.hostname;
			if(domain.substring(0,4)=='www.') domain=domain.substring(4);
			if(ins(filepath,domain+'/')<1){
				re='/up'+type+'/'+$url.toEncode(filepath);
			}
		}
		if(isdebug('up')) dbg.t('url',re);
		return re
	},
'':''});


/* ################ tpl ################ */
var tpl={
	replaceItem:function(re,treeItem){return $dtml.filterItem(re,treeItem)},
	
	uLink:function(uid){return rd(app.uu_link,'uid','[item:uuid]')},
	uAvatarSrc:function(uid){return rd(app.uu_avatar,'uid','[item:uuid]')},
	uAvatar:function(opt){
		opt=ox({size:'m',frame:false},opt);
		var _uavatar=rd(app.uu_avatar,'uid','[item:uuid]');
		var re='',hta=[];
		if(opt.frame) hta.push('<div class="avatar" _uuname="[item:uuname]" _uuid="[item:uuid]">');
		hta.push('<a href="'+this.uLink()+'" target="_blank"><span class="avatar'+opt.size+'"><img class="icon" src="'+this.uAvatarSrc()+'" /></span></a>');
		if(opt.frame) hta.push('</div>');
		re=hta.join(BR);
		if(opt.uid && opt.uname){
			var treeItem=newTree();
			treeItem.add('uuid',opt.uid);
			treeItem.add('uuname',opt.uname);
			treeItem.add('uid',opt.uid);
			treeItem.add('uname',opt.uname);
			re=this.replaceItem(re,treeItem);
		}
		return re
	},
'':''};


//########################################
//########################################
extendo(ua,{
	isLogin:function(popup,quick,opt){
		var islogin=(this.id>0);
		if(!islogin){
			if(popup) ui.popups('info','您还未登录！',true);
			if(quick) this.loginQuick(opt);
		}
		return islogin
	},
	
	loginQuick:function(opt,jo){
		opt=ox({
			serveE:{channel:'passport',p:'login',action:'quick',params:''},
			boxInit:function(jbox){
				ui.form.bindi()
			},
			xopt:{
				message_succeed:'登录成功！',
			'':''},
			serveXaction:function(){
				return app.xlogin
			},
			nobtn:true,
			title:'快捷登录',
			succeed:function(){
				$w.timeout(function(){$p.refresh()},1)
			},
		},opt);
		ui.pages.exClick(jo,opt)
	},
'':''});
