/*
Version:	App T for Theme
Uodated:	2013-11-00
*/


var appt={
	serveEntry		: 't',
	row_start		: 10,	//第一次加载加载数量
	row_more		: 10,
	newtim			: 10,
	notice_newtim		: 10,
	t_link_view		: '/t/{$id}',
	tags_link		: '/tags/k/{$name}',
'':''};
appt.init=function(){var that=this;$(function(){that.initer()})};
appt.initer=function(){
	app.serveEntry('t',this.serveEntry);
	this.headbar&&this.headbar.initer();
	//this.headmenu&&this.headmenu.initer();
	//this.hots&&this.hots.initer();
	//this.ucard&&this.ucard.initer();
	this.talki&&this.talki.initer();
	
	$('#tabs_nav').find('search em').click(function(){
		var jform=$(this).next('form');
		if(!jform.length)return;
		if(jform.is(':visible')) jform.hide();
		else{
			jform.show();
			jform.attri('init',function(jthis){
				app.placeholder(jthis)
			});
		}
	});
	//alert(appt.serve('action=abc'));
};
appt.init();

extendo(appt,{
	serve:function(p,params){return app.serve('t/'+p,params)},
	tips:function(type,msg,cover,timeer){app.tips(type,msg,cover,timeer)},
	hint:function(jo,msg,timer){app.hint(jo,msg,timer)},
	
	isLogin:function(){return ua.isLogin(true)},
	
	toFilterText:function(re){return $code.toFilterText(re)},
	toUploadThumb:function(filepath,type){return comm.toUploadThumb(filepath,type)},
	parserVideo:function(url,callback){return comm.parserVideo(url,callback)},
	
	jo:function(id,p){return $('body').finde((p=='opt'?'mopt':'mlog')+'-'+id)},
	jop:function(jo){return jo.parents('.mlog')},
	joid:function(jo){return this.jop(jo).attrd('id')},
'':''});

extendo(appt,{
	followLock:function(){return ui.lock.is("follow")},
	followAction:function(uid,action,callback){
		if(ui.lock.is("follow"))return;ui.lock.en("follow");
		//dbg.t(follow);
		var that=this;
		var _action=isInt(action)?(action==1?"cancel":"create"):action;
		var _url=app.serve('a/contacts/follow','action='+_action+'&uid='+uid);
		//dbg.t(_url);return;
		$ajax({url:_url,value:"xml",ready:function(o){that.followParser(o,uid,_action,callback)},error:true});
	},
	followParser:function(xml,uid,action,callback){	
		//alert(xml);
		var maps=$util.toMapByXML(xml);
		var treeVar=maps.getItemTree("var");
		switch(treeVar.v("status")){
			case "succeed":
				var follow=action=="cancel"?0:1;
				callback(uid,follow);
				break;
			case "already":
				app.popup("info","已经关注过了！",true);
				break;
			case "not":
				app.popup("info","还没关注过哦！",true);
				break;
			case "failed":
				app.popup("error",_names+"失败 :(",true);
				break;
			default:
				var message=treeVar.v("message.string")||treeVar.v("error_message")||treeVar.v("message");
				if(!message) message="[unknown]";
				app.popup("fail",message,true);
				break;
		}
		ui.lock.un("follow");
	},
'':''});
