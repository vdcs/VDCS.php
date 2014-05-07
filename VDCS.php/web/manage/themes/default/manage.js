/*
Version:	VDCS 1.0 for Manage App
*/

//$formx.FORMNAME="frm_post";
//$formx.init();
//$i.loadRes("tmenu");


//########################################
//########################################
if(typeof(app)=="undefined") var app={};
app.Help=function(){w.open('http://go.hpns.cn/vdcs/php')}


//########################################
//########################################
var ma=manager={id:0,name:''};


//########################################
//########################################
var appi={};
var manage={
	init:function(){$(function(){manage.initer()})},
	initer:function(){
		$('[href="#goback"]').click(function(){
			$p.goback();
			return false
		});
		this.initForm();
	},
	initForm:function(){
		
	},
	
	getURL:function(opt){return ui.serve.baseurl+opt},
	
	chooseTime:function(_name){$pf.chooseTime(_name);},
	chooseValue:function(_name,_value,_space){$pf.chooseValue(_name,_value,_space);},		//chooseValue('{@table.px}unit','件,个,只,箱,公斤,克,套');
	
'':''};

appi.widget={
	init:function(){var that=this;$(function(){that.initer()})},
	initer:function(){
		var that=this;
		this.jwrap=$('#widget');
		var page_width=parseInt($('#wrapper').width());
		var margin_left=parseInt(this.jwrap.css('margin-left'));
		//margin_left=0;
		if(page_width>0){
			this.jwrap.css({'left':page_width+margin_left});
		}
		else{
			this.jwrap.css({'left':'auto','right':'15px'});
		}
		this.jwrap.show();
		this.jwrap.on('click','a[class]',function(){
			return that.click($(this));
		});
	},
	click:function(ja){
		var re;
		var _action=ja.attr('class');
		var _func='on_'+_action;
		if(this[_func]){
			re=this[_func](ja,_action);
			if(!re) re=false;
		}
		return re
	},
	on_refresh:function(ja){
		$p.refresh();
	},
	on_newin:function(ja){
		ja.attri(function(){
			//dbg.o(window.location)
			ja.attr('href',window.location.pathname+window.location.search);
			ja.attr('target','_blank');
		});
		return true
	},
	on_go_back:function(ja){
		window.history.go(-1);
	},
	on_go_forward:function(ja){
		window.history.go(1);
	},
'':''};
appi.widget.init();


appi.chooseUa=function(jbody,callback){
	var jforms=jbody.parents('form')||$('body');
	jforms.find('[name="unames"]').attr('readonly','true');
	var url_add=manage.getURL('account/'+ua.rc+'?action=add&backurl=close');
	jforms.finde('add').attr('href',url_add).attr('target','_blank');
	jbody.finde('choose').click(function(){
		var jbox=jbody.finde('box').ibox(),jselect=jbox.find('select');
		jbox.finde('add').attr('href',url_add).attr('target','_blank');
		jbox.find('input[key="keyword"]').bind('input propertychange', function(){
			var keyword=$(this).val();
			if (!keyword)return;
			keyword=$url.en(keyword);
			var _url=ui.serve.getURL({channel:'account',p:ua.rc,action:'searchi',params:'keyword='+keyword});
			//dbg.t('url',_url);
			$ajax({url:_url,value:'xml',ready:function(xml){
				var maps=$util.toMapByXML(xml);
				var tableUa=maps.getItemTable('item');
				jselect.empty();
				tableUa.begin();
				for(var t=1;t<=tableUa.row();t++){
					var treeItem=tableUa.getItemTree();
					jselect.append('<option value="'+treeItem.v('uid')+'">'+treeItem.v('unames')+'</option>');
					tableUa.move();
				}
			},error:true});
		});
		var _submit=function(){
			var _value=jselect.val(),_title=jselect.find("option:selected").text();
			jforms.find('[name="uuid"]').val(_value);
			jforms.find('[name="unames"]').val(_title);
			if(callback) callback({value:_value,title:_title,tableUa:tableUa,jbox:jbox,jselect:jselect,jforms:jforms});
			if(appi.choseUaExtend) appi.choseUaExtend(obj);
			jbox.ibox('close');
		};
		jbox.find('[el=submit]').on('click',function(){_submit()});
		jselect.on('dblclick', function(){_submit()});
	});
}

appi.dicts={selector:'[data-dict]',
	setString:function(s){this._string=s},
	initer:function(jwrap){
		var that=this;
		this.jwrap=jwrap;
		//##########
		var parama=[];
		parama.push('dicts='+this.getItems());
		//##########
		var _url=ui.serve.getURL({channel:'common',p:'query',action:'dicts',params:parama.join('&')});
		//dbg.t('url',_url);
		$ajax({url:_url,value:'xml',ready:function(xml){
			var maps=$util.toMapByXML(xml);
			var treeVar=maps.getItemTree('var');
			if(treeVar.v('status')=='succeed'){
				that._string=treeVar.v('dicts.string');
				that.parser(that.jwrap);
			}
			islock=false;
		},error:true});
	},
	parser:function(jwrap){
		if(!jwrap) jwrap=this.jwrap;
		var that=this;
		if(!that.otable) that.otable=that.toTable(this._string);
		jwrap.find(this.selector).each(function(){
			var jdict=$(this);
			jdict.html(that.toTitle(that.otable[jdict.attrd('dict')],jdict.attrd('value')));
		});
	},
	
	getItems:function(jwrap){
		if(!jwrap) jwrap=this.jwrap;
		var jdict=jwrap.find(this.selector),dicta=new Array();
		jdict.each(function(){
			var _dict=$(this).attrd('dict');
			if(_dict) dicta.push(_dict);
		});
		return dicta.join(',')
	},
	
	toTitle:function(tableDict,value){
		var re='['+value+']';
		if(!tableDict) return re;
		tableDict.begin();
		for(var t=1;t<=tableDict.row();t++){
			if(tableDict.iv('value')==value){
				re=tableDict.iv('title');
				re=r(re,'&lt;','<');
				re=r(re,'&gt;','>');
				break;
			}
			tableDict.move()
		}
		return re
	},
	toTable:function(dictstring){
		var dicto=new Array();
		var treeDicts=$util.toTreeByString(dictstring,'$$$','===');
		//alert(dbg.tree(treeDicts));
		treeDicts.begin();
		for(var t=1;t<=treeDicts.count();t++){
			//alert(treeDicts.ik());
			var tableDict=newTable();tableDict.setFields('value,name,title');
			var itema=treeDicts.iv().split('|||');
			for(var n in itema){
				var valuea=itema[n].split('###');
				//dbg.o(valuea);
				var treeItem=newTree();
				treeItem.add('value',valuea[0]);
				treeItem.add('name',valuea[1]);
				treeItem.add('title',valuea[2]);
				tableDict.add(treeItem);
			}
			dicto[treeDicts.ik()]=tableDict;
			treeDicts.move();
		}
		return dicto
	},
'':''};


appi.binder={
	_serve:{
		'account':{'channel':'account','p':'account','action':'viewi','params':'uid={$value}'},
		'staff':{'channel':'common','p':'em','m':'staff','action':'viewi','params':'uid={$value}'},
		'audit':{'channel':'common','p':'audit','action':'viewi','params':'rootid={$value}'},
	'':''},
	addServe:function(serve,value){this._serve[serve]=value},
	getServeURL:function(serve,key,value,params){
		var url=ui.serve.getURL(this._serve[serve]);
		url=rd(url,'key',key);
		url=rd(url,'value',value);
		url=$url.link(url,params);
		return url;
	},
	toExtend:function(){
		
	},
	callback:function(jcont){
		
	},
	initer:function(){
		var that=this;
		that.toExtend();
		$('[data-binder]').each(function(){
			var jthis=$(this);
			var serve=jthis.attrd('binder');
			//var type=$(this).data('binder-type');
			var target=jthis.data('binder-target');
			var key=jthis.data('binder-key');
			var value=jthis.data('binder-value');
			var params=jthis.data('binder-params');
			var pred=jthis.attrd('binder-pred');//x的前缀
			var by=jthis.attrd('binder-by');//显示内容标识
			var _url=that.getServeURL(serve,key,value,params);
			var jcont=$(target);//显示信息的obj
			if(jcont) that.parser(_url,jcont,by,pred);
		});
	},

	parser:function(url,jcont,by,pred){
		var that=this;
		if(!by) by='eli';
		if(!pred) pred='info';//x的前缀
		$ajax({url:url,value:'xml',ready:function(xml){
			var maps=$util.toMapByXML(xml);
			var treeVar=maps.getItemTree('var');
			if(treeVar.v('status')=='succeed'){
				jcont.find('['+by+']').each(function(){
					var key=$(this).attr(by);
					var value=treeVar.v(pred+'.'+key);
					//if(value) $(this).text(value);
					$(this).text(value);
				});
				that.callback(jcont);
			}else{
				//dbg.t('信息获取失败，请联系管理员:'+url);
				//ui.popups('info','信息获取失败，请联系管理员');
			}
		},error:true});
	},
'':''};
$(function(){
	appi.binder.initer();
});


//########################################
//########################################
var modClass={Datas:{},tmenu:null};
modClass.Datas={};

modClass.toDataAry=function(){
	var ary=new Array();
	for(var k in this.Datas){
		var ok=this.Datas[k];
		if(ok&&ok["name"]){
			var ak={};
			ak["id"]=ok["id"]||k;
			ak["rootid"]=ok["rootid"];
			ak["fatherid"]=ok["fatherid"];;
			ak["level"]=ok["levelid"];
			ak["title"]=ok["name"];
			ak["url"]=ok["url"];
			ary[ary.length]=ak;
		}
	}
	return ary;
}

modClass.doShow=function(){
	if(!VDCS.TMenu){
		alert('VDCS.TMenu required');
		return;
	}
	this.tmenu=new VDCS.TMenu({cont:"#class_menu_body",data:this.toDataAry()});
	this.tmenu.init();
	
	this.classid=queryi("classid");
	this.id=this.classid>0?"c"+this.classid:"";
	if(this.id) this.tmenu.doClick(this.id);
}

appi.modClass={
	initer:function(){
		var that=this;
		this.jwrap=$('#class_menu');
		this.jwrap.finda('#all').attr('href',this.getURL(''));
		this.jwrap.finda('#no').attr('href',this.getURL('-1'));
		///manage/index.php/system/model/class/use.x?action=list&channel=article
		var _url=ui.serve.getURL({channel:'system',p:'model',m:'class',mi:'use',action:'list',params:'channel='+pages.channel});
		//dbg.t('url',_url);
		$ajax({url:_url,value:'xml',ready:function(o){that.parseAsync(o)},error:true});
	},
	parseAsync:function(xml){
		//alert(xml);
		var maps=iso(xml)?xml:$util.toMapByXML(xml);
		var treeVar=maps.getItemTree('var');
		var tableItem=maps.getItemTable('item');
		if(treeVar.v('status')=='succeed'){
			//this.parser(that.jwrap);
		}
		tableItem.begin();
		for(var t=1;t<=tableItem.row();t++){
			//tableItem.iv('classid');
			var _id=tableItem.iv('classid');
			modClass.Datas["c"+_id]={
				classid:_id,
				name:tableItem.iv('name'),
				rootid:tableItem.iv('rootid'),
				fatherid:tableItem.iv('fatherid'),
				levelid:tableItem.iv('levelid'),
				url:this.getURL(_id)
			};
			tableItem.move();
		}
		modClass.doShow();
	},
	getURL:function(classid){return ui.serve.baseurl+pages.channel+'?action=list&classid='+(classid||'')},
'':''};

/*
modClass.Datas["c76"]={"name":"应用编程类","rootid":"76","fatherid":"0","levelid":"1","url":"?portals=article&action=list&classid=76"};
modClass.doShow();
*/
