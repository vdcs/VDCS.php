/*
Version:	VDCS Post Library
Support:	http://uri.sx/vdcs.js
Uodated:	2014-04-00
*/


/* ************************************* */
$pf={
	id:function(s){return '_pages_form_'+s},
	o:function(s){return $o(this.id(s))},item:function(s){return this.o(s)},
	v:function(k,v){return $f.v(k,v)},		//frm_post,$formx.FORMNAME+"."+
	load:function(func){$(func);},
	submit:function(func){this.arq=this.arq||[];this.arq[this.arq.length]=func;},
	isSubmit:function(){
		var re=true;
		if(re&&this.arq){
			for(var q=0;q<this.arq.length;q++){
				//re=this.arq[q]();
				re=this.arq[q]?this.arq[q]():'';
				if(!re)break;
			}
		}
		return re;
	},
	chooseTime:function(_name){
		var _year=new Date($("input[name="+_name+"]").val()).getYear()+1900;
		return;
		$(function(){$("input[name="+_name+"]").datepicker({
			dateFormat:'yy-mm-dd',
			dayNamesMin:['日','一','二','三','四','五','六'],
			monthNamesShort:['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
			yearRange:(_year-20)+':'+(_year+50),changeYear:true,changeMonth:true
		});});
	},
	chooseColor:function(_name){
		$("input[name="+_name+"]").colorpicker({
			fillcolor:true,
			success:function(o,color){$(o).css("color",color)}
		});
	},
	chooseValue:function(_name,_value,_space){		//chooseValue('{@table.px}unit','件,个,只,箱,公斤,克,套');
		var re="";
		_space=_space||" ";
		var _arys=_value.split(",");
		for(var a=0;a<_arys.length;a++){
			if(ins(_arys[a],"=")<1) _arys[a]+="="+_arys[a];
			var _ary=_arys[a].split("=");
			re+="<a href=\"javascript:$pf.setValue('"+_name+"','"+_ary[0]+"');\">"+_ary[1]+"</a>";
			if((a+1)!=_arys.length) re+=_space;
		}
		put(re);
	},
	setValue:function(k,v){this.v(k,v)},
	sh:function(s){
		var _ar=s.split(",");
		for(var a=0;a<_ar.length;a++){
			var key_=_ar[a];
			var oj=$("#"+this.id(key_));	//this.o(name_);
			if(oj.is(":hidden")){
				oj.show();
			}
			else{
				oj.hide();
			}
		}
	},
'':''};


/* ************************************* */
$post={config:{"icon_total":9}};

extendo($post,{
	toUploadContent:function(url,opt){return dcs.editor.toElementContent(url,opt)},
	
	getIcon:function(sInput,v){
		if(ise(sInput)) sInput="icon";
		var re="";
		for(var i=0;i<=this.config["icon_total"];i++){
			re+="<img class=\"icon\" src=\""+$c.getURL("emotes")+"icon/"+(i==0?"no":i)+".gif\" />";
			re+="<input type=\"radio\" name=\""+sInput+"\" class=\"normal\" value=\""+i+"\""; if(v==i){ re+=" checked"; } re+=" /> &nbsp;";
		}
		return re;
	},
	getColorBox:function(sInput,sColor){
		var cbox="colorbox_"+sInput;
		this.doColorChoose(sInput,cbox);
		if(ise(sInput)) sColor="000000";
		var re="";
		re+="<input id="+cbox+" type=\"text\" class=\"normal hand\" onClick=\"javascript:$post.doColorChoose('"+sInput+"','"+cbox+"');\" style=\"width:18px;height:17px;background:"+(sColor?"#"+sColor+" ":"")+"url('"+$c.getURL("script")+"editor/images/colorbox.gif') no-repeat 0 0;\" readonly />";
		re+=" <span class=\"hand\" onClick=\"javascript:$post.doColorChoose('"+sInput+"','"+cbox+"');\">选取颜色</span>";
		return re;
	},
	doColorChoose:function(sInput,oid){
		$("input[name="+sInput+"]").colorpicker({
			fillcolor:true,
			success:function(o,color){
				$(o).css("color",color);
				$("#"+oid).css("background-color",color);
			}
		});
	},
	doTextareaResize:function(o,t,s){
		if(s==null) s=5;
		var r=s;
		if(t=="-") r=-s;
		o=$f.o(o);
		if(o && parseInt(o.attr("rows"))+r>=s) o.attr("rows",parseInt(o.attr("rows"))+r);
	},
	doTimeChoose:function(n,t){return $pf.chooseTime(n,t)},
	doCopyCode:function(o){
		var rng=d.body.createTextRange();
		rng.moveToElementText(o);
		rng.scrollIntoView();
		rng.select();
		rng.execCommand("Copy");
		rng.collapse(false);
	},
	doSaveAs:function(o){
		var v=$o(o).v();
		if(!ise(v)){
			var wn=w.open('','_blank','top=10000');
			wn.document.open('text/html','replace');
			wn.document.writeln(v);
			wn.document.execCommand('saveas','','saveas.html');
			wn.close();
		}
	},
	runEx:function(o){
		var v=$o(o).v();
		if(!ise(v)){
			var wn=w.open('','','');
			wn.opener=null;
			wn.document.write(v);
			wn.document.close();
		}
	},
	doTextareaKeyDown:function(e){
		if(e) $f.doSubmitQuick('frm_post',e);
	},
	setValue:function(strName,strValue,sMode){
		$f.setValue(strName,strValue,sMode);
	},
	doSubmit:function(name){
		name=name||"frm_post";
		$("form[name="+name+"]").submit();
	},
	setInterfaceValue:function(id,value,mode){
		this.setValue(id,value,mode);
		if(id.indexOf(".")!=-1) id=id.substring(id.indexOf(".")+1);
		dcs.editor.setValue(id,value,mode);
	}
});


/*
########################################
########################################
*/
$editor=dcs.editor={
	_queue:{},
	queueAdd:function(id,mode,sets){this._queue[id]={id:id,mode:mode,sets:sets,editor:null}},
	queueExec:function(){
		for(var id in this._queue) this.parser(this._queue[id].id,this._queue[id].mode,this._queue[id].sets);
	},
	queueClear:function(){this._queue={}},
	init:function(callback){
		if(this.isinit) return;
		if(dcs.xeditor){
			this.isinit=true;
			return;
		}
		var urlRes=[$c.url("editor")+"xeditor.min.js"];
		var that=this;
		$.include(urlRes,function(){
			that.isinit=true;
			that.queueExec();
		});
	},
	loader:function(id,mode,sets){
		this.init();
		this.queueAdd(id,mode,sets);
		if(this.isinit) this.parser(id,mode,sets);
	},
	parser:function(id,mode,sets){this._queue[id].editor=dcs.xeditor.parser(id,mode,sets);},
	getQueue:function(id){
		if(id.indexOf(".")!=-1) id=id.substring(id.indexOf(".")+1);
		return this._queue[id]
	},
	getEditor:function(id){return this.getQueue(id).editor},
	getMode:function(id){return this.getQueue(id).mode},
	getValue:function(id){
		var editor=this.getEditor(id);
		if(editor.html) return editor.html();
		if(editor.getData) return editor.getData();
		if(editor.getSource) return editor.getSource();		//xeditor
		return '';
	},
	setValue:function(id,value,mode){
		var editor=this.getEditor(id);
		switch(mode){
			case 'append':
				if(editor.appendHTML) editor.appendHTML(value);		//xeditor
				if(editor.insertHtml) editor.insertHtml(value);
				break;
			case 'insert':
				if(editor.pasteHTML) editor.pasteHTML(value);		//xeditor
				if(editor.insertHtml) editor.insertHtml(value);
				break;
			default:
				if(editor.setData) editor.setData(value);
				if(editor.setSource) editor.setSource(value);		//xeditor
				if(editor.html) editor.html(value);
				break;
		}
	},

	toElementContent:function(url,opt){
		var re='';
		if(inp('png,gif,jpg,jpeg,bmp',opt.ext)>0){
			re='<img src="'+url+'" data-upload="'+url+'" data-names="'+opt.realnames+'" />';
		}
		else{
			re='<a href="'+url+'" data-upload="'+url+'" data-names="'+opt.realnames+'">附件：'+opt.realnames+'</a>';
		}
		return re
	},
'':''};

dcs.UBBeditor={
	replaceTextarea:function(id,sets){dcs.editor.loader(id,"ubb",sets)},
	getEditor:function(id){return dcs.editor.getEditor(id)},
	getMode:function(id){return dcs.editor.getMode(id)},
	getValue:function(id){return dcs.editor.getValue(id)},
	setValue:function(id,value,mode){dcs.editor.setValue(id,value,mode)},
'':''};

dcs.HTMLeditor={
	replaceTextarea:function(id,sets){dcs.editor.loader(id,"html",sets);},
	getEditor:function(id){return dcs.editor.getEditor(id)},
	getMode:function(id){return dcs.editor.getMode(id)},
	getValue:function(id){return dcs.editor.getValue(id)},
	setValue:function(id,value,mode){dcs.editor.setValue(id,value,mode)},
'':''};
