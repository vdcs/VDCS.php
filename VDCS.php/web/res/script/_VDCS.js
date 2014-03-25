/*
Version:	VDCS Common & Instantiation Library v2.0
		in jquery 1.10.2
Support:	http://go.hpns.cn/vdcs/js
Uodated:	2014-01-23
*/

var VDCS={},dcs={ver:{bulid:'0.2.6.3',d:'20140100'}};
var d=document,dE=d.documentElement,dO=null,w=window;


/*###jquery###*/


// variable
var BR=NEWLINE="\n";

// object & define
Object.extend=function(){var tg=arguments[0]||{},i=1,al=arguments.length,deep=false,options;if(typeof(tg)==='boolean'){deep=tg;tg=arguments[1]||{};i=2}if(typeof(tg)!=='object'&&!isf(tg))tg={};if(al==i){tg=this;--i}for(;i<al;i++)if((options=arguments[i])!=null)for(var name in options){var src=tg[name],copy=options[name];if(tg===copy)continue;if(deep&&copy&&typeof(copy)==='object'&&!copy.nodeType)tg[name]=Object.extend(deep,src||(copy.al!=null?[]:{}),copy);else if(copy!==undefined)tg[name]=copy}return tg};
Object.clone=function(o){return Object.extend({},o)};
Object.bind=Object.bindEvent=Object.addEvent=function(o,event,func){$(o).bind(event,func)};
var Class={create:function(){return function(){this.initialize.apply(this,arguments)}}};
var Try={o:function(){var re,alen=arguments.length;for(var i=0;i<alen;i++){var func=arguments[i];try{re=func();break}catch(e){}};return re}};

extend=function(o,a,b,c,d,e){return $.extend({},o,a,b,c,d,e)};extendo=function(o,a,b,c,d,e){return Object.extend(o,a,b,c,d,e)};
$j=function(o,i){return iso(o)?$(o):$((i?'#':'')+o)};$jo=function(o){o=$j(o);if(!o.length)o=null;return o};


// prototype
Date.d=Date.now||function(){return new Date().getTime()};
extendo(Date.prototype,{Conversion:{w:604800000,d:86400000,h:3600000,i:60000,s:1000},
	toDate:function(s){var d=new Date();d.setTime(Date.parse(s.replace(/-/gi,"/")));return d},
	toFormat:function(tpl){tpl=tpl||'yyyy-mm-dd hh:ii:ss';['yyyy','mm','dd','hh','ii','ss'].each(function(v,idx){tpl=tpl.replace(new RegExp(v,'g'),'$'+(++idx))});return this.toJSON().replace(/^"(\d+)-(\d+)-(\d+) (\d+):(\d+):(\d+)"$/g,tpl).replace(/\{\d+\}/,function(idx){idx=idx.match(/\{(\d+)\}/)[1];idx=idx.indexOf('0')==0?idx.split('')[1]:idx;idx=parseInt(idx)-1;return $v['date.months'][idx]})},
	getWeekDay:function(){return $v['date.weekdays'][0]+$v['date.weekdays'][1].charAt(this.getDay())},
	add:function(interval,number){return new Date(this.getTime()+number*this.Conversion[interval])},
	diff:function(interval,d){return parseInt((d.getTime()-this.getTime())/this.Conversion[interval])},
	distance:function(d){var n=this.diff('s',d==0?new Date():$v.sDate),re=[0,'',-1];if(n>86400){re[0]=3;re[2]=parseInt(n/86400);if(re[2]<1)re[2]=1}else if(n>3600){re[0]=2;re[2]=parseInt(n/3600);if(re[2]<1)re[2]=1}else if(n>60){re[0]=1;re[2]=parseInt(n/60);if(re[2]<1)re[2]=1}else if(n>0){re[0]=0;re[2]=n;if(re[2]<1)re[2]=1}if(re[2]>0)re[1]=r($v['date.distances'][re[0]],'$1',re[2]);return re},
	toJSON:function(){return'"'+this.getFullYear()+'-'+(this.getMonth()+1).toPaddedString(2)+'-'+this.getDate().toPaddedString(2)+' '+this.getHours().toPaddedString(2)+':'+this.getMinutes().toPaddedString(2)+':'+this.getSeconds().toPaddedString(2)+'"'}
});
extendo(String.prototype,{
	trim:function(){return this.replace(/(^\s*)|(\s*$)/g,'')},
	strip:function(){return this.replace(/^\s+/,'').replace(/\s+$/,'')},
	ary:function(){return this.split('')}
});

// function
put=function(s){d.write(s)};
isn=function(s){return (s==null||s==undefined)?true:false};
isun=function(v){return (typeof(v)==undefined||typeof(v)=='undefined')?true:false};
ise=function(s){return (s==null||s.length<1)?true: false};
isb=function(s){return (s==true||s==1||s=='True'||s=='true'||s=='1')?true:false};
iss=function(s){return typeof(s)=='string'};
isa=function(o){var re=false;if(iso(o)){try{if(o.length>-1)re=true}catch(e){}} return re};
isf=function(o){return o instanceof Function||typeof(o)=='function'};
ish=function(o){return o instanceof Hash};
iso=function(o){return (typeof(o)=='object'&&o)?true:false};
tn=function(s){return typeof(s).toString()};

isint=isInt=function(s){return (parseInt(s)==s)};	isnum=isNum=function(s){return (parseFloat(s)==s)};	isInte=function(s){return /^[0-9]*[1-9][0-9]*$/.exec(s)?true:false};
isNume=isNumber=function(s,t,v){var vars=(t==1)?'0123456789.':'0123456789',ar=new Array(1);if(ise(v)){ar[0]=false;ar[1]=true}else{ar[0]=0;ar[1]=s}if(ise(s)) return ar[0];for(var i=0;i<s.length;i++){if(vars.indexOf(s.charAt(i))==-1) return ar[0]}return ar[1]};

too=function(o,t){return iso(o)?o:$o(o,t)};
tob=toBool=function(s){return (s==true||s==1||s=='True'||s=='true'||s=='1')};
toi=toInt=function(s,b){return isInt(s)?parseInt(s,b||10):0};
ton=toNum=function(s){return isNum(s,1)?parseFloat(s):0};

t=function(s){return typeof(s)=='string'?s.trim():s};	len=function(s){return s.replace(/[^\x00-\xff]/g,'aa').length};
r=function(s,s1,s2){if(!s)return s;if(!ise(s1)){while(s.indexOf(s1)>-1){s=s.replace(s1,s2)}};return s};	rv=function(s,s1,s2){return r(s,'{'+s1+'}',s2)};	rd=function(s,s1,s2){return r(s,'{$'+s1+'}',s2)};
rs=function(s,s1,s2,ic){if(!RegExp.prototype.isPrototypeOf(s1)){return s.replace(new RegExp(s1,(ic?'g': 'gi')),s2)}else{return s.replace(s1,s2)}};

sl=strl=left=function(s,n){return s.substr(0,n)};	sr=strr=right=function(s,n){return s.substr(s.length-n)};
ins=function(s,c){return s==undefined?-1:s.indexOf(c)};	inp=function(s,v,p){p=p||',';return s==undefined?-1:((p+s+p).indexOf(p+v+p)+1)};
inps=function(s,v,p){p=p||',';v=v.split(',');var re=0;for(var a=0;a<v.length;a++){re=(p+s+p).indexOf(p+v[a]+p)+1;if(re>0)break}return re};

o2s=function(o,p1,p2){p1=p1||';';p2=p2||'=';var re='';if(iso(o)){for(var k in o){if(iss(o[k])) re+=p1+k+p2+o[k]}if(re) re=re.substring(p1.length)} return re};
s2o=function(s,p1,p2){s=s||'';p1=p1||';';p2=p2||'=';var re={},ar,ars=s.split(p1),alen=ars.length;for(var i=0;i<alen;i++){ar=ars[i].split(p2);if(ar.length==2&&t(ar[0])!='') re[t(ar[0])]=t(ar[1])}return re};
ox=function(o,a,b,c,d,e){return $.extend({},o,a,b,c,d,e)};	//function(o,a){o=o||{};a=a||{};for(var k in a){o[k]=a[k]};return o};


// value
$v=dcs.v={sDate:new Date(),
	'date.months.sh'	:['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
	'date.months.en'	:['January','February','March','April','May','June','July','August','September','October','November','December'],
	unit:{coin:'元',price:'元',money:'元',emoney:'金币',points:'分',exp:'点',strength:'点',prestige:'点'}
};

$lng=$lang=dcs.lang={};$app=dcs.app={};$a={};
$v.v=$lng.v=function(k,v){return ise(v)?this[k]:this[k]=v};
$v.STime=function(s){this.sTime=s;this.sDate=new Date().toDate(s)};

var ua={rc:'',id:-1,name:''};

// config & common
$c=dcs.config=dcs.common={local:true,EXT:'php',
	_data:{'url':'/','dir':'/',	'dir.images':'images/',			'dir.emotes':'images/emotes/',		'dir.script':'images/script/',		'dir.editor':'images/script/editor/',
	'dir.common':'common/',		'dir.support':'support/',		'dir.account':'account/',		'dir.passport':'passport/',
	'dir.upload':'upload/',		'dir.themes.base':'themes/',		'dir.themes':'themes/default/'},
	channel:'',p:'',m:'',mi:'',x:'',action:'',params:'',
	CHARSET:'utf-8',CONTENT_TYPE:'text/html',CONTENT_TYPE_XML:'text/xml',NEWLINE:'\n',		//EXTS:function(){return '.'+this.EXT;},
	EMPTY_REPLACER:'NaN',EMPTY_REPLACERS:'<span class="gray">NaN</span>',UNKNOWN:'[unknown]',
	PATTERN_PRE:/\{\@([^{\@}]*)}/gi,PATTERN_VAR:/\{\$([^{\$}]*)}/gi,
	toReplaceRegex:function(re,values,pattern,func){	//values=values||[];
		func=func||(isa(values)?function(s,s1){return values[s1]}:function(s,s1){try{return values?values.getItem(s1):''}catch(ex){return values[s1]}});
		return re.replace(pattern,func)
	},
	toReplacePre:function(re,values){return this.toReplaceRegex(re,values,this.PATTERN_PRE)},toReplaceVar:function(re,values){return this.toReplaceRegex(re,values,this.PATTERN_VAR)},
	v:function(k,v){return v?$c.setValue(k,v):$c.getValue(k)},getValue:function(k){return this._data[k]},setValue:function(k,v){this._data[k]=v},
	vrt:function(t){var re=this.local?Date.d():dcs.ver.d;return t?('_t='+re):re},
	setv:function(tim,scriptd,urlmode,local){
		$v.STime(tim);if(scriptd)dcs.ver.d=scriptd;
		this.urlMode=urlmode;this.rewrite=(this.urlMode=='rewrite');
		this.local=(local=='l'||local=='local')?true:false;
		this.setExt()
	},
	setExt:function(ext){
		if(ext)this.EXT=ext;
		this.EXTS=this.rewrite?'html':this.EXT
	},
	setPath:function(d,u,t){if(!ise(d))this._data['dir']=d; if(!ise(u)) this._data['url']=u; if(!ise(t))this._data['dir.theme']=(t=='_base')?this._data['dir.themes.base']:t},
	setUnit:function(emoney,points,exp){extendo($v.unit,{emoney:emoney,points:points,exp:exp})},
	
	setr:function(channel,p,m,mi){this.channel=channel;this.p=p;this.m=m;this.mi=mi},
	router:function(){return {channel:this.channel,p:this.p,m:this.m,mi:this.mi,x:this.x,action:this.action,params:this.params}},

	setUA:function(rc,id,name){
		if(typeof ua == 'undefined') return;
		ua.rc=rc;ua.id=toi(id);if(name)ua.name=name;
		ua.IMG_PATH=this.url('images')+'ua/';
	},

	setDir:function(k,v){this._data['dir.'+k]=v},getDir:function(k){return this._data['dir.'+k]},setFile:function(k,v){this._data['url.file.'+k]=v},
	url:function(k,f,a){
		var re='';
		if(k) re=this._data['dir.'+k]?this._data['dir.'+k]:(this._data['url.'+k]?this._data['url.'+k]:'');
		if(re.substring(0,1)!='/'&&re.indexOf('://')==-1)re=this._data['url']+re;
		if(f) re+=this._data['url.file.'+f]?this._data['url.file.'+f]:'';
		if(a) re=$url.link(re,a);
		return re
	},getURL:function(k,f,a){return this.url(k,f,a)},
'':''};

// browser
$b=dcs.browser={nav:navigator,agent:navigator.userAgent.toLowerCase(),ver:navigator.appVersion,
	prober:function(){
		var re={agent:this.agent,version:this.ver.split(' ')[0],
			msie:/msie/.test(this.agent),chrome:/chrome/.test(this.agent),mozilla:/firefox/.test(this.agent),
			webkit:/webkit/.test(this.agent),safari:/safari/.test(this.agent),opera:!!w.opera,
			w3c:(d.compatMode=='CSS1Compat')};
		re.v=Math.floor(re.version);re.firefox=re.gecko=re.mozilla;
		re.chrome=isf(w.MessageEvent);re.safari=isf(w.openDatabase);
		if(re.msie){
			var vers=this.ver.match(/MSIE ([\d]+)\.([\d]+);/);
			re.ie=true;re.iev=re.MajorVer=vers[1];re.MinorVer=vers[2];
			re.ieo=re.iev<9;re.ie6=(re.iev==6);re.ie7=(re.iev==7);re.ie8=(re.iev==8);
		}
		return re
	},
	init:function(){
		/*
		$(function(){dbg.o(navigator);});
		alert(this.agent+'\n'+this.ver);
		alert(Math.floor(this.ver.split(' ')[0]));
		*/
		var b=$b.browser=$b.prober();
		for(var k in b){$b[k]=b[k]}		//ox($b,$b.browser);
		if(!$.browser) $.browser=$b.browser;
		//$(function(){dbg.o($b);});
	},
	initURL:function(){
		if(this._initURL) return; this._initURL=true; this._url={};
		this._url['title']=d.title; this._url['referrer']=d.referrer; this._url['uri']=w.location.toString();/*d.URL*/
		var arys=this._url['uri'].split('://'); this._url['protocol']=arys[0]; this._url['domain']=d.domain;
		this._url['channel']='';this._url['dirn']=0;for(var a=0;a<5;a++){this._url['dir'+(a+1)]=''}
		var ary=arys[1].split('/');
		if(ary.length>2){
			this._url['channel']=ary[1];
			this._url['dirn']=ary.length-2;
			for(var a=2;a<(ary.length-1);a++){this._url['dir'+(a-1)]=ary[a]}
			this._url['script']=ary[ary.length-1];
		}
		else{this._url['script']=ary[1]}
		if(this._url['script'])this._url['page']=this._url['script'].substr(0,this._url['script'].lastIndexOf('.'))
	},
	getURL:function(k){this.initURL();return this._url[k||'uri']},url:function(k){return this.getURL(k)},get:function(k){return this.getURL(k)},
	getXMLHttp:function(){if(!this.xmlHttp){this.xmlHttp=this.getXMLHttpObject()}return this.xmlHttp},
	getXMLHttpObject:function(){var re=null;if(w.ActiveXObject){var ar=new Array('Microsoft.XMLHTTP','Msxml2.XMLHTTP','Msxml.XMLHTTP');var alen=ar.length;for(var i=0;i<alen;i++){try{re=new ActiveXObject(ar[i])}catch(e){re=null}if(re!=null)break}}else if(w.XMLHttpRequest){re=new XMLHttpRequest()}return re}
};
$b.init();

// window
$w=dcs.window={w:-1,h:-1,ws:-1,hs:-1,wid:-1,wh:-1,
	init:function(){
		if(this.isinit)return;this.isinit=true;
		$(function(){
			$w.initer();
			$w.resize(function(){$w.init()})
		})
	},
	initer:function(){
		var od=$(d);
		this.w=od.width();this.h=od.height();this.wi=$(w).width();this.hi=$(w).height();
		//this.ws=this.w;this.hs=this.h;
		this.ws=w.screen.width-18;this.hs=d.body.scrollHeight;
		if(this.wid<1) this.wid=$('#confine').width();
		this.wid=this.wid||0;this.wh=(this.w-this.wid)/2
	},
	reset:function(){this.ws=w.screen.width-18;this.hs=d.body.scrollHeight},
	bindEvent:function(t,func,o){Object.bind(o?o:w,t,func)},
	load:function(func,o){this.bindEvent('load',func,o)},
	bindChange:function(func,o){
		$(o?o:w).bind('scroll',func);
		$(o?o:w).resize(func)
	},
	resize:function(f){
		this.wi=$(w).width();this.hi=$(w).height();
		$(w).resize(f)
	},
	scroll:function(f){$(w).scroll(f)},
	pos:function(){
		var _x=-1,_y=-1;
		if(w.innerWidth){_x=w.pageXOffset;}
		else if(dE && dE.scrollLeft){_x=dE.scrollLeft;}
		else if(d.body){_x=d.body.scrollLeft;}
		if(w.innerHeight){_y=w.pageYOffset;}
		else if(dE && dE.scrollTop){_y=dE.scrollTop;}
		else if(d.body){_y=d.body.scrollTop;}
		return {x:_x,y:_y}
	},
	timeout:function(s,p){return w.setTimeout(s,p*1000)},clearTimeout:function(s){return clearTimeout(s)},
	interval:function(s,p){return w.setInterval(s,p*1000)},clearInterval:function(s){return clearInterval(s)}
};
$w.init();


// page
$p=dcs.page={
	q:function(k){return $.query.get(k)},qi:function(k){return toi(this.q(k))},
	go:function(s){d.location.href=s},
	gotop:function(){w.scroll(0,0);d.body.scrollTop='0px'},
	goback:function(){w.history.back()},
	refresh:function(){return d.location.reload()},
	put:function(type,url,charset){this.include($url.link(url,'d='+dcs.ver.d),null,charset)},
	include:function(url,func,charset){$.include(url,func)},
	append:function(type,value,opt,func){
		if(!type) return false;
		opt=ox(opt);
		if(iso(type)){
			var or=d.body;if(iso(value))or=value; 
			or.appendChild(t);
			return true
		}
		switch(type){
			case 'o':		$('head').append(value);break;
			case 'e':		$('body').append(value);break;
			case 'js':		$.include(value,func);break;
			case 'css':		$.include(value,func);break;
			case 'cssText':		$('head').append('<style type="text/css">'+value+'</style>');break;
		}
	},
	addFav:function(s,u){
		if($b.ie) w.external.addFavorite(u,s);
		else if(w.sidebar) w.sidebar.addPanel(s,u,'');
		else alert($lng['pages']['fav.prompt'])
	},
	copy:function(s){$.copy(iso(s)?o.val():s)},
	open:function(u,n,wid,hei,scroll,content){var _Left=(screen.width)?(screen.width-wid)/2:0,_Top=(screen.height)?(screen.Height-hei)/2:0;var o=w.open(u,n,'width='+wid+',height='+hei+',left='+_Left+',top='+_Top+',toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars='+scroll+',resizable=no');if(content){o.document.write(content)}return o},
	isClickReturn:function(s,t){var re='';if(inp('1,2',t)<1)t=0;re=$lng['pages']['click'][t];return w.confirm(r(re,'$1',s))},
	linktop:function(id,opt){return $j(id?id:'#linktop').linktop(opt)},linkPlace:function(id,opt){return this.linktop(id,opt)},
'':''};

// form
$f=dcs.form={MSX:'[]',
	form:function(id,nuller){
		var oj=$('form[name="'+id+'"]');
		if(oj.length<1) oj=$('form#'+id);
		if(nuller && oj && oj.length<1) oj=null;
		return oj
	},
	obj:function(id,nuller){		//: target=parent,abc
		if(iso(id)) return id;
		var oj,_id=id;
		if(ins(id,'.')>0){
			var ar=id.split('.');
			_id=ar[1];
			var of=this.form(ar[0],true);
			if(of) oj=of.find(':input[name="'+_id+'"]')
		}
		else{
			oj=$(':input[name="'+_id+'"]')
		}
		if(oj && oj.length<1) oj=$('#'+_id+':input');
		if(nuller && oj && oj.length<1) oj=null;
		return oj
	},
	oo:function(id,nuller){return this.form(id,nuller)},
	o:function(id,nuller){return this.obj(id,nuller)},element:function(id,nuller){return this.obj(id,nuller)},
	or:function(o){
		if(!iso(o)) this._o=$f.o(o,true);
		if(o && o.jquery) o=o[0];
		return o
	},
	getValue:function(id){
		var re='',oj=this.obj(id,true);
		if(!oj) return re;
		var ar=oj.fieldValue();
		for(var a=0;a<ar.length;a++){
			if(len(ar[a])>0) re+=','+ar[a]
		}
		if(len(re)>1) re=re.substr(1);
		return re
	},
	setValue:function(id,value,mod){
		var oj=this.obj(id,true);if(!oj || value === undefined) return false;
		var _type=oj[0].type,values=','+value+',';
		switch(oj[0].type){
			case 'radio':
			case 'checkbox':
				oj.each(function(i){
					if(mod=='__all' || inp(values,$(this).val())>0 || inp(values,'_no'+i)>0 || inp(values,'__no'+i)>0) $(this).checked(true);
				});
				break;
			default:
				if(oj[0].tagName.toLowerCase()=='select'){
					var ojo=oj.find('option');
					ojo.each(function(i){		//oj.attr('value',value);
						if(mod=='__all' || inp(values,$(this).val())>0 || inp(values,'_no'+i)>0 || inp(values,'__no'+i)>0) $(this).attr('selected',true);
					})
				}
				else{
					oj.attr('value',mod=='append'?(oj.val()+value):value)		//oj.val(value);
				}
				break;
		}
		return true
	},
	v:function(o,v,m){return v?this.setValue(o,v,m):this.getValue(o)},
	focus:function(id){this.o(id).focus()},doFocus:function(id){this.focus(id)},
	submit:function(id){this.oo(id).submit()},doSubmit:function(id){this.submit(id)},
	noEnter:function(id){this.form(id).keypress(function(e){if(e.which==13) return false})},
	submitOnce:function(s){var o=s;if(!iso(o))o=$o(s);o=o||d.forms[s];if(iso(o)){if(o._smt) o._smt.disabled=true;if(o._sbt) o._sbt.disabled=true;if(o._rst) o._rst.disabled=true}},
	submitQuick:function(o,events){events=events||w.event;if((events.keyCode==13&&events.ctrlKey)||(events.keyCode==83&&events.altKey)){this.doSubmitOnce(o);this.submit(o)}},
	reset:function(s){var o=s;if(!iso(o))o=$o(s);o=o||d.forms[s];if(iso(o))o.reset()},
	toConvertInput:function(s,n){n=n||($f.SELECT_INPUT_NAME+$f.MSX);var re='',ar=s.split(',');for(var i=0;i<ar.length;i++){re+='<input type="hidden" name="'+n+'" value="'+ar[i]+'" />'} return re},
	isInputInt:function(s){var kc=w.event.keyCode;return ((kc>=48&&kc<=57)||(s==1&&!re&&kc==46))?true:false},
	bindi:function(jo){ui.form.bindi(jo)},
'':''};


// ajax
$ajax=dcs.ajax=function(){return new $ajax.ne(arguments[0])};
$ajax.toState=function(n){return $v['ajax.states'][n]};
$ajax.ne=function(){
	var _p=ox({value:'oxml',realtime:true,method:'',params:'',mime:'text/html;charset='+$c.CHARSET,async:true},arguments[0]);
	var _url=_p['url'];if(!_url)return;
	if(_p['send'])_p['params']=(_p['params']?_p['params']+'&':'')+(isa(_p['send'])||iso(_p['send'])?$url.querys(_p['send']):_p['send']);
	_p['method']=_p['method'].toUpperCase();
	if(!_p['method']) _p['method']=_p['params']?'POST':'GET';
	_p['load']=_p['load']||_p['onLoad'];_p['error']=_p['error']||_p['onError'];_p['ready']=_p['ready']||_p['onReady'];
	var _x=$b.getXMLHttpObject(),_v=null;
	if(!_x) return;
	if(_p['realtime'])_url=$url.link(_url,$c.vrt(1));
	if($c.debug || isdebug('ajax')) dbg.t('ajax',_url);
	_x.open(_p['method'],_url,_p['async']);
	if(_x.overrideMimeType) _x.overrideMimeType(_p['mime']);
	var _ready=function(){
		if(_x.readyState==4){
			if(_x.status==200||_x.status==304){
				if(_p['ready']) _p['ready']($ajax.value(_x,_p['value']))
			}
			else if(_x.status==0){
				//
			}
			else{
				if(_p['error']){
					if(isf(_p['error'])) _p['error'](_x.status,_x.responseText);
					else alert('URL: '+_url+'\nError: '+_x.status+'\nResult:\n'+_x.responseText+'');
				}
			}
		}
		else{
			if(_p['load']) _p['load'](_x.readyState)
		}
	};
	_x.onreadystatechange=_ready;
	if(_p['method']=='POST') _x.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	//_x.send(_p['method']=='POST'?_p['params']:null);
	_x.send(_p['params']);
	if(!$b.ie&&_p['async']==false){
		if(_p['ready']) _p['ready']($ajax.value(_x,_p['value']))
	}
};
$ajax.value=function(_x,t){
	var _v='';
	switch(t){
		case 1:case 'text':	_v=_x.responseText;break;
		case 2:case 'xml':	_v=$b.ie?_x.responseXML.xml:_x.responseText;break;
		case 3:case 'oxml':	_v=_x.responseXML;break;
		case 4:case 'tree':	_v=$util.toTreeByXML(_x.responseXML);break;
		case 5:case 'table':	_v=$util.toTableByXML(_x.responseXML);break;
		case 6:case 'map':	_v=$util.toMapByXML($b.ie?_x.responseXML.xml:_x.responseText);break;
		default:		_v=_x;break;
	};
	return _v
};


// VDCS Library
VDCS.Queues=function(s){
	var _d=[];
	this.is=function(s){var re=false;for(var i=0;i<_d.length;i++){if(_d[i]==s){re=true;break}} return re};
	this.count=function(){return _d.length};
	this.item=function(n){return _d[n-1]};
	this.add=function(s){if(s)_d[_d.length]=s};
	this.exec=function(){for(var i=0;i<_d.length;i++){if(_d[i])_d[i]()}};
};

VDCS.QueueAsync=function(){
	var _isa=false,_ary=new Array(),_dt='map',_func=null;
	var xml='',maps=null,treeVar=null,tableData=null;
	this.getXML=function(){return xml};
	this.set=function(dt,func){_dt=dt;_func=func};
	this.parse=function(opt){
		var that=this;
		if(!opt['value'])opt['value']='xml';
		if(!opt['ready'])opt['ready']=function(xml){that.async(xml)};
		$ajax(opt)
	};
	this.async=function(xml){
		if(inp(_dt,'map')>0){
			this.maps=$util.tmapsByXML(xml);
			this.treeVar=this.maps.getItemTree('var');
			if(inp(_dt,'table')>0) this.tableData=this.maps.getItemTable('item')
		}
		else if(inp(_dt,'table')>0){
			this.tableData=$util.toTableByXML(xml)
		}
		_isa=true;if(_func)_func();this.exec()
	};
	this.bind=function(func){
		if(!_isa) _ary[_ary.length]=func;
		else this.execItem(func)
	};
	this.exec=function(){
		if(!_isa) return;
		for(var i=0;i<_ary.length;i++){this.execItem(_ary[i])}
	};
	this.execItem=function(func){if(func)func()};
};

VDCS.QueuesLoader=function(){
	var _queue=[],_isload=false,_isexec=false;
	this.is=function(s){return this._isload};
	this.count=function(){return _queue.length};
	this.add=function(type,src){_queue.push([0,type,src])};
	this.exec=function(callback){
		if(this._isload) callback();
		if(this._isexec)return;this._isexec=true;
		var ars=[];
		for(var n=0;n<_queue.length;n++){if(_queue[n][0]<1) ars.push(_queue[n][2])}
		var that=this;
		$.include(ars,function(){
			that._isload=true;
			callback()
		})
	};
};

VDCS.Error=function(){
	var _n=0;_data=[];
	this.isCheck=function(){return (_n>0)?false:true};
	this.add=function(s){_n++;_data[_n]=s};this.addItem=function(s){this.add(s)};
	this.toString=function(){var re='';for(var i=1;i<=_n;i++){re+=_data[i]+"\n"};return re};
	this.toJS=function(){var re='';for(var i=1;i<=_n;i++){re+=_data[i]+"\n"};return re};
	this.doPop=function(){if(_n>0)alert(this.toJS())};
};

VDCS.Flash=function(id,u,w,h){
	var _d=[],_e=[],_var={};
	this.addParam=function(k,v){if($b.ie){_d.push('<param name="'+k+'" value="'+v+'">')}else{_d.push(' '+k+'="'+v+'"')};_e.push(' '+k+'="'+v+'"')};
	this.addVar=function(n,v){_var[n]=v};
	this.getVars=function(){var pairs=new Array(),k;for(k in _var){pairs[pairs.length]=k+"="+$url.toEncode(_var[k])}return pairs.join('&')};
	this.setURL=function(v){this.addParam($b.ie?'movie':'src',v)};
	this.setLinkURL=function(v){this.linkURL=v};
	if(u){this.setURL(u)};
	this.addParam('quality','high');
	this.toFlash=function(){var vars=this.getVars();if(vars.length>0){this.addParam('flashvars',vars)}if($b.ie){return '<object id="'+id+'" width="'+w+'" height="'+h+'" align="middle" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0">'+_d.join('')+'</object>'}else{return '<embed id="'+id+'" width="'+w+'" height="'+h+'" align="middle" '+_d.join('')+' type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>'}};
	this.toString=function(){var re='';if(!this.linkURL){re=this.toFlash()}else{re='<table width="'+w+'" height="'+h+'" border="0" cellpadding="0" cellspacing="0"><tr><td><div style="position:relative;overflow:hidden;"><embed style="position:absolute;z-index:0;" wmode="opaque" id="'+id+'" width="'+w+'" height="'+h+'" align="middle" '+_e.join('')+' type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed><div style="position:relative;'+(($b.ie)?'filter:alpha(opacity=0);':'')+'-moz-opacity:0;z-index:10;left:0;top:0;background:#cdeaf6;width:'+w+'px;height:'+h+'px;"><a href="'+this.linkURL+'" target="_blank" style="cursor:pointer;display:block;width:'+w+'px;height:'+h+'px;"></a></div></div></td></tr></table>'}return re};
	this.doShow=function(o){if(o){$j(o).html(this.toString())}else{put(this.toString())}};
};


// dbg debug
var dbg={
e:function(e){alert(this.eString(e))},
eString:function(e){var rea=[];for(var k in e){rea.push(k+'='+e[k])}return rea.join(BR)},
toString:function(o,l){if(inp('number,string,boolean',typeof(o))>0) return (l>0&&o.length>l)?o.substr(0,l)+' ...':o;else return '['+typeof(o)+']'},
obj:function(o,sMode,sRow){return this.o(o,sMode,sRow)},
o:function(o,sMode,sRow){if(isTree(o)){var rea=[];o.begin();for(var t=1;t<=o.count();t++){rea.push(o.ik()+'='+o.iv());o.move()}alert(rea.join(BR));return}var _k,_v,_n=1,re='';if(ise(sRow)||sRow==-1)sRow=30;try{for(_k in o){if(_n>=sRow){alert(re);re='';_n=1}if(sMode=="key")re+=_k+"\n";else re+=_k+"="+(this.toString(o[_k],100))+"\n";_n++}if(re!=''){alert(re);re=''}}catch(e){dbg.e(e)}},
oString:function(o,sMode,sRow){var _k,_v,_n=1,re=''; if(ise(sRow)||sRow==-1) sRow=20; try{ for(_k in o){ if(sMode=="key") re+=_k+"\n"; else re+=_k+"="+(this.toString(o[_k],100))+"\n"; _n++}}catch(e){ re=dbg.eString(e)} return re},
otree:function(o){alert(this.tree(o))},otable:function(o){this.o(this.table(o))},
list:function(o){return this.ary(o)},tree:function(o){if(isTree(o))return this.ary(o.getArray())},table:function(o){if(isTable(o))return this.ary(o.getArray())},
ary:function(ar,sTit){var rea=new Array();rea.push('<table class="test">');if(typeof(ar[0])=='object'){var tmpCol=ar[0].length,tmpRow=ar.length,tmpAry,_r;if(!ise(sTit))rea.push("<tr><td colspan=\""+(tmpCol+1)+"\">"+sTit+"</td></tr>");rea.push("<tr><td colspan=\""+(tmpCol+1)+"\">Col="+tmpCol+", Row="+tmpRow+"</td></tr>");for(var _a=0;_a<ar.length;_a++){tmpAry=ar[_a];rea.push("<tr>");rea.push("<td>"+(_a+1)+".</td>");for(_r=0;_r<tmpAry.length;_r++){rea.push("<td>"+tmpAry[_r]+"</td>")}rea.push("</tr>")}}else{if(!ise(sTit))rea.push("<tr><td colspan=\"2\">"+sTit+"</td></tr>");if(iso(ar)){var c=0;rea.push("<tr><td colspan=\"2\">Lnegth={$count}</td></tr>");for(var k in ar){if(typeof(ar[k])=="string"){rea.push("<tr><td>"+k+".</td><td>"+ar[k]+"</td></tr>");c++}}re=rd(re,"count",c)}else{rea.push("<tr><td colspan=\"2\">Lnegth="+ar.length+"</td></tr>");for(var _a=0;_a<ar.length;_a++){rea.push("<tr><td>"+(_a+1)+".</td><td>"+ar[_a]+"</td></tr>")}}}rea.push("</table>");return rea.join(BR)},
t:function(t,s,id){id=id||'test-box';o=$j('#'+id);
	if(isn(s)){s=t;t=''}else{
		var ta=t.split(':');
		if(inp('url,ajax,api',ta[0])>0) s='<a href="'+s+'" target="_blank">'+s+'</a>';
		s='<t>'+t+'</t>'+s
	}
	if(!o.length){o=$('<div></div>').attr('id',id).appendTo('body')}
	o.attri('init',function(){
		var css='#test-box in{display:block;padding-right:20px;max-height:'+($w.hs-200)+'px;overflow:hidden;overflow-y:auto;}';
		css+='#test-box t{color:#008080;font-weight:bold;margin-right:5px;}';
		css+='#test-box a{color:#000;}';
		$p.append('cssText',css);
		$('<in></in>').appendTo(o);
		o.css({padding:'5px 10px',backgroundColor:'#FFF',color:'#000','text-align':'left','line-height':1.5,'border-radius':5,opacity:0.5,position:'fixed',bottom:40,left:20,zIndex:1}).attr('init','true').show();
	});
	o.find('in').prepend('<p>'+s+'</p>')
}};
isdebug=function(v){var _v=$req.q('debug');if(iss(v) && v==_v) return true;else return false;if(_v) return true;return false};

if(!console) var console={log:function(){}};

//##append##


//request
/* ************************************* */
$req={q:function(k){return $.query.get(k)},qi:function(k){return toi(this.q(k))},get:function(k){return this.q(k)}};
query=function(k){return $.query.get(k)};queryi=function(k){return toi($.query.get(k))};queryn=function(k){return ton($.query.get(k))};


//codes
/* ************************************* */
$code=$codes={
	leni:function(str){return str.replace(/[^\u0000-\u00ff]/g,'aa').length},
	len:function(str){
		var len=str.length,tl=0;
		for(var i=0;i<len;i++){
			if(str.charCodeAt(i)<27 || str.charCodeAt(i)>126){tl+=2}else{tl++}	// 全角
		}
		return tl
	},
	cut:function(str,len){
		var l=str.length,rel=[],tl=0;
		for(var i=0;i<l && tl<len;i++){
			rel[i]=str[i];
			if(str.charCodeAt(i)<27 || str.charCodeAt(i)>126){tl+=2}else{tl++}
		}
		return rel.join('')
	},
	toCuted:function(str, len, syb){
		var re='';if(syb==null) syb='..';syb=syb||'';
		var pcn=/[^\x00-\xff]/g,slen=str.replace(pcn,'**').length,len2=0,strc='';
		for(var i=0;i<slen;i++){
			strc=str.charAt(i).toString();
			if(strc.match(pcn) != null) len2 += 2;
			else len2++;
			if(len2>len) break;
			re += strc
		}
		if(slen>len) re+=syb;
		return re
	},
	toHTML:function(re,t,c,syb){
		if(!re) return '';
		if(t==2){
			re=re.replace(/<(.*)>.*.<\/\1>/ig,'');
			re=re.replace(/<[\/]?([a-zA-Z0-9]+)[^>^<]*>/ig,'');
			re=re.replace(/\[[\/]?([a-zA-Z0-9]+)[^]^[]*]/ig,'');
			re=re.replace(/(\r\n)/ig,'');
			re=re.replace(/&lt;[\/]?([a-zA-Z0-9]+)[^&^;]*&gt;/ig,'');
		}
		//re=r(re,"&", "&amp;");
		re=r(re,"\"", "&quot;");re=r(re,"'", "&#39;");
		re=r(re,"<", "&lt;");re=r(re,">", "&gt;");
		//re=r(re,CHR(9),"&nbsp; &nbsp; &nbsp; &nbsp; ");	//制表符
		//re=r(re,CHR(10),"<br>"&vbcrlf);			//换行
		//re=r(re,CHR(13),"");					//回车
		if(c) re=this.toCuted(re,c,syb);
		return re
	},
	toTXT:function(s){return this.toHTML(s,t)},
	toXML:function(s){return s.replace(['&','"',"'",'<','>','’'],['&amp;','&quot;','&apos;','&lt;','&gt;','&apos;'])},
	toFilterText:function(re){return re=re.replace(/<[^>]*>/g,'')},
	toTemplat:function(s,tpl,k){
		if(!k) k='tpl';
		if(!tpl) tpl='{$'+k+'}';
		return rd(tpl,k,s)
	},
	toNumber:function(s,n){
		var re='',s=''+s+'',len=s.length,dotPos=s.indexOf('.',0);
		if(dotPos==-1){
			re=s+'.';
			for(i=0;i<n;i++){re=re+'0'}
		}
		else{
			if((len-dotPos-1)>=n){
				var len2=1;
				for(j=0;j<n;j++){len2=len2*10}
				re=Math.round(parseFloat(s)*len2)/len2
			}
			else{
				re=s;
				for(i=0;i<(n-len+dotPos+1);i++){re=re+'0'}
			}
		}
		return re
	},
	toPrice:function(s){return this.toNumber((isNum(s)?s:''),2)},
	toCommaK:function(str){
		var rgx=/(\d+)(\d{3})/,x=str.split('.'),x1=x[0],x2=x.length>1?'.'+x[1]:'';
		while(rgx.test(x1)){x1=x1.replace(rgx,'$1'+','+'$2')}
		return x1+x2
	},
	getRandNum:function(n){return Math.round(Math.random()*n)},
	getRandom:function(min,max){return Math.floor(Math.random()*(max-min+1)+min)}
};


//cookies
/* ************************************* */
$ck=$cookie={
	v:function(k,v,epr){return isn(v)?this.setValue(k,v,epr):getValue(k)},
	setValue:function(k,v,epr){
		var _days=0;
		switch(epr){
			case 'day':	_days=1;
			case 'week':	_days=7;
			case 'month':	_days=30;
			case 'year':	_days=365;
			case 'yes':	_days=3650;
		}
		if(_days>0){
			var _date=new Date();
			_date.setTime(_date.getTime()+(_days*24*60*60*1000));
			var tmpExpires='; expires='+_date.toGMTString();
			d.cookie=k+'='+v+tmpExpires+'; path='+$c._data['dir'];
		}
		return v
	},
	getValue:function(k){
		var re='',rRE=new RegExp(escape(k)+"=([^;]+)");
		if(rRE.test(d.cookie+';')){rRE.exec(d.cookie+';'); re=unescape(RegExp.$1)}
		return re
	},
	getValueGipId:function(name){
		var dc=d.cookie,prefix=name+'=',begin=dc.indexOf('; '+prefix);
		if(begin==-1){begin=dc.indexOf(prefix);if(begin!=0) return null}else{begin+=2}
		var end=d.cookie.indexOf(';',begin);
		if(end==-1) end=dc.length;
		return unescape(dc.substring(begin+prefix.length,end))
	}
};


//time
/* ************************************* */
$tim=$time=dcs.time={date:new Date(),_Data:new Array(),
	timer:function(){return Math.round(Date.d()/1000)},
	v:function(t,od){return this.toConvert(od||this.date,t);},
	getToday:function(){this._Data['today']=this._Data['today']||this.toConvert(this.date,'date'); return this._Data['today']},
	getNow:function(r){if(r) return this.toConvert(new Date(),'time');this._Data['now']=this._Data['now']||this.toConvert(this.date,'time'); return this._Data['now']},
	toDate:function(s){if(isInt(s))return new Date(s*1000);var d=new Date();d.setTime(Date.parse(s.replace(/-/gi,"/")));return d},
	toNumber:function(d){var re=Date.parse(d)/1000; if(re<1) re=0; return re},	//.replace(/-/gi,"/")
	v2:function(s){if(s.toString().length<2)s='0'+s;return s},
	toConvert:function(d,t,s){
		var re='';
		d=iso(d)?d:this.toDate(d);
		switch(t){	//Y-m-d H:i:s
			case 'date':	re='Y-M-D';break;
			case 'dates':	re='M-D H:I';break;
			case 'datey':	re='Y-M-D H:I';break;
			case 'timec':	re='y-M-D H:I';break;
			case 'times':	re=r(this.toConvert(d,'time'),' ','<br/>');if(s=='s') re='<span class="times">'+re+'</span>';return re;break;
			case 'time':	re='Y-M-D H:I:s';break;
			default:	re=t?t:'Y-M-D H:I:s';break;
		}
		re=r(re,'Y',d.getFullYear());		re=r(re,'y',d.getFullYear().toString().substr(2));
		re=r(re,'M',this.v2(d.getMonth()+1));	re=r(re,'m',d.getMonth()+1);
		re=r(re,'D',this.v2(d.getDate()));	re=r(re,'d',d.getDate());
		re=r(re,'H',this.v2(d.getHours()));	re=r(re,'h',d.getHours());
		re=r(re,'I',this.v2(d.getMinutes()));	re=r(re,'i',d.getMinutes());
		re=r(re,'S',this.v2(d.getSeconds()));	re=r(re,'s',d.getSeconds());
		return re
	},
	toDateAdd:function(v,n,p,t){
		var dn,millisecond=1,second=millisecond*1000,minute=second*60,hour=minute*60,day=hour*24,month=hour*24*30,year=day*365;
		switch(p){
			case 'ms':	dn=millisecond*n;break;
			case 's':	dn=second*n;break;
			case 'mi':	dn=minute*n;break;
			case 'h':	dn=hour*n;break;
			case 'm':	dn=month*n;break;
			case 'y':	dn=year*n;break;
			case 'd':
			default:	dn=day*n;break;
		}
		var od=new Date(new Date(new Date(v.replace('-',','))).valueOf()+dn);
		return t?this.toConvert(od,t):od
	},
	toDateDiff:function(t,d1,d2){
		var d_1=Date.parse(d1.replace(/-/gi,"/")),d_2=Date.parse(d2.replace(/-/gi,"/"));
		var re=d_1-d_2;
		switch(t){
			case 'h':	re=re/360000; break;
			case 'm':	re=re/60000; break;
			case 's':	re=re/1000; break;
		}
		return re
	},
	toMinuteString:function(n,unit){
		if(!unit) unit=[];
		if(!unit['day']) unit['day']=$v['date.unit'][3];
		if(!unit['hour']) unit['hour']=$v['date.unit'][2];
		if(!unit['minute']) unit['minute']=$v['date.unit'][1];
		var re='',d=h=m=0,nn=n;
		if(n>60){m=n%60;n=(n-m)/60;re=m+unit['minute']}
		if(n>24){h=n%24;n=(n-h)/24;re=h+unit['hour']+re}
		if(n>0){re=n+unit['day']+re}
		return re
	}
};


//url
/* ************************************* */
$url=dcs.url={
	is:function(s){return this.isValid(s)},isValid:function(s){return (s.indexOf('://')!=-1||s.substring(0,1)=='/')?true:false},
	isSafe:function(s){return regEx.exec(new RegExp("^(http(s?)|ftp)://","i"))},	//|file
	querys:function(ar){
		var re='';
		for(k in ar) re+='&'+k+'='+this.toEncode(ar[k]);
		if(re.length>0)re=re.substring(1);
		return re
	},
	link:function(re,apd){
		re=re||'';
		if(isa(apd))apd=this.querys(apd);
		if(apd){
			if(re.indexOf('?')==-1) re+='?';
			else if(re.substring(re.indexOf('?')+1).length>0) re+='&';
			re+=apd
		}
		return re
	},
	linkr:function(s,v,k){k=k||'_r';v=v||Math.random();return this.link(s,k+'='+v)},
	hlink:function(_url,tit,ps){	//toHyperLink
		ps=ox({txt:''},ps);
		var re=ps.txt;
		if(_url!=''&&_url!='http://'){
			re='<a href="'+_url+'"';
			if(ps.target) re+=' target="_blank"';
			if(ps.alt) re+=' alt="'+ps.alt+'"';
			if(ps.atts) re+=' '+ps.atts;
			re+='>'+tit+'</a>';
		}
		return re
	},
	en:function(s){return this.toEncode(s)},toEncode:function(s){return encodeURIComponent(s)},toEncodei:function(s){return encodeURI(s)},
	de:function(s){return this.toDecodei(s)},toDecode:function(s){return decodeURIComponent(s)},toDecodei:function(s){return decodeURI(s)},
'':''};


//images
/* ************************************* */
$lng['img']={};
$img={_total:0,
	load:function(url,func,callback){
		var img=new Image();img.src=url;
		if(func||callback){
			var _load=function(){
				if(func) func(img);
				if(callback) callback.call(img)		//将callback函数this指针切换为img。
			};
			if(img.complete){				//如果图片已经存在于浏览器缓存，直接调用回调函数
				_load();
				return					//直接返回，不用再处理onload事件
			}
			img.onload=function(){_load()}			//图片下载完毕时异步调用callback函数。
		}
	},
'':''};


//paging
/* ************************************* */
$lng['paging']={};
$pg=$paging={
	go:function(txt,field,page,diff){
		if(!txt)txt='paging_url';
		if(!field)field='paging_num';
		if(!page)page='{$page}';
		if(!diff) diff=0;
		var _url=$('input[name="'+txt+'"]').val(),_page=toi($('input[name="'+field+'"]').val())+diff;
		if(_page<1) _page=0;
		_url=r(_url,page,_page);
		$p.go(_url)
	},
	toStringQuick:function(url,total,pageNum,tpl){
		if(!isInt(pageNum)) pageNum=10;
		var re='',_url='',pages=Math.round(total/pageNum);
		if((pages*pageNum)<total) pages++;
		if(tpl&&pages>1){
			for(var i=2;i<4;i++){
				if(i>pages) break;
				_url=rd(url,'page',i);
				re+='<a href="'+_url+'" alt="Page '+i+'">'+i+'</a> ';
			}
			if(pages>3){
				if(pages>4) re+=' ..';
				i=pages;
				_url=rd(url,'page',i);
				re+='<a href="'+_url+'" alt="Page '+i+'">'+i+'</a> ';
			}
		}
		else{re=rd(tpl,'url',rd(url,"page",pages))}
		return re
	}
};


//util
/* ************************************* */
newTree=function(){return new VDCS.utilTree()};newTable=function(){return new VDCS.utilTable()};newMap=function(){return new VDCS.utilMap()};newXCML=function(){return new VDCS.utilXCML()};
isTree=function(o){var re=false;if(iso(o)){try{if(o.getObjectType()=="VDCS.utilTree") re=true}catch(e){}}return re};
isTable=function(o){var re=false;if(iso(o)){try{if(o.getObjectType()=="VDCS.utilTable") re=true}catch(e){}}return re};
isMap=function(o){var re=false;if(iso(o)){try{if(o.getObjectType()=="VDCS.utilMap") re=true}catch(e){}}return re};
isXCML=function(o){var re=false;if(iso(o)){try{if(o.getObjectType()=="VDCS.utilXCML") re=true}catch(e){}}return re};

extendo(VDCS,{
XCML_NODENAME:'xcml',
XCML_NODE_NAME_CONFIGURE:'configure',
XCML_NODE_NAMES:'__node',
XCML_NODE_EXIST:'__yes',
XCML_NODE_CONNECT_SYMBOL:'.',
NodeValue:function(o){var re='';if(w.ActiveXObject) re=o.text;else{try{re=o.childNodes[0].nodeValue}catch(ex){re=''}}return re}
});

$util=dcs.util={
	toTreeByString:function(s,p1,p2){
		if(!p1) p1=';';if(!p2) p2='=';
		var re=new VDCS.utilTree();if(!s) return re;
		var ars=s.split(p1),ar;
		for(var a=0;a<ars.length;a++){
			ar=ars[a].split(p2);
			if(ar[0].length>0) re.addItem(ar[0],ar[1])
		}
		return re
	},
	toTreeByXML:function(xml){
		var reTree=new VDCS.utilTree();
		if(xml){
			var xcml=new VDCS.utilXCML();
			if(iso(xml)) xcml.loadResponseXML(xml);
			else xcml.loadXML(xml);
			xcml.doParse();
			var tmpItemArray=xcml.getConfigure('node').split(',');
			for(var a=0;a<tmpItemArray.length;a++){reTree.doAppendTree(xcml.getNodeTree(tmpItemArray[a]),tmpItemArray[a]+'.')}
		}
		return reTree
	},
	toTableByXML:function(xml){
		var reTable=new VDCS.utilTable();
		if(xml){
			var xcml=new VDCS.utilXCML();
			if(iso(xml)) xcml.loadResponseXML(xml);
			else xcml.loadXML(xml);
			xcml.doParse();
			var fields=xcml.getConfigureField();
			if(fields!=''){
				reTable.setFields(fields);
				xcml.doParseItem();
				xcml.doItemBegin();
				for(var a=0;a<xcml.getItemCount();a++){reTable.addItem(xcml.getItemTree());xcml.doItemMove()}
			}
		}
		return reTable
	},
	toMapByXML:function(xml){
		var reMap=new VDCS.utilMap();
		if(xml){	//alert(xml);
			var xcml=new VDCS.utilXCML();
			if(iso(xml)) xcml.loadResponseXML(xml);
			else xcml.loadXML(xml);
			xcml.doParse();
			reMap.addItem('var',xcml.getNodeTree('var'));
			var oTable,a,i,fields=xcml.getConfigureField();
			if(fields!=''){
				oTable=new VDCS.utilTable();
				oTable.setFields(fields);
				xcml.doParseItem();
				xcml.doItemBegin();
				for(a=0;a<xcml.getItemCount();a++){
					oTable.addItem(xcml.getItemTree());
					xcml.doItemMove();
				}
				reMap.addItem(xcml.getConfigureNode(),oTable);
			}
			var tmpItemArray=xcml.getConfigure('nodes').split(',');
			for(a in tmpItemArray){
				var _node=tmpItemArray[a];
				fields=xcml.getConfigure('field.'+_node);
				if(_node && fields){
					oTable=new VDCS.utilTable();
					oTable.setFields(fields);
					xcml.doParseNode(_node);
					xcml.doItemBegin();
					for(i=0;i<xcml.getItemCount();i++){
						oTable.addItem(xcml.getItemTree());
						xcml.doItemMove();
					}
					reMap.addItem(_node,oTable)
				}
			}
		}
		return reMap
	},
	toMap:function(maps){return iso(maps)?maps:this.toMapByXML(maps)}
};

//dtml
/* ************************************* */
$dtml=dcs.DTML={
	BATCH_SYMBOL			:"$$$",
	SWAP_SYMBOL			:"~~~$$$~~~",
	PATTERN_FLAG			:"(.+?)",
	PATTERN_FLAG_VAR		:"([\w\-\_\.\:]*)",
	PATTERN_FLAG_LABEL		:"([^>\"]*)",
	PATTERN_FLAG_LABELS		:this.PATTERN_FLAG_LABEL+"(!([\w-\.][^!]+?))?",
	PATTERN_FLAG_PARAM		:"([^{\$\"]*)",
	PATTERN_FLAG_PARAMS		:"([\s\w-\.\:=;\'\(\)<>\[\]{\$}]*)",
	PATTERN_FLAG_PARAMQ		:"(,\"(.|[^>\"]*)\")?",
	PATTERN_FLAG_CONTENT		:"([\s\S.]*?)",		//((.|\n)*?)
	PATTERN_FLAG_CONTENT2		:"((.|\n)*?)",
	PATTERN_FLAG_OPTION		:"(!([\w-\.\:]+?))?",
	PATTERN_FLAG_STR		:"(.+?)",
	PATTERN_PRE			:/\{\@([^{\@}]*)}/gi,
	PATTERN_VAR			:/\{\$([^{\$}]*)}/gi,
	PATTERN_VAR_PX			:/\{\${\$px}([^{\$}]*)}/gi,
	PATTERN_LEBEL_VAR		:"<var:([^<>]*)>",
	PATTERN_LEBEL_VARS		:"<var:([^<>!]*)(!([\w-\.][^!]+?))?>",
	PATTERN_LEBEL_DAT		:"<dat:([^<>]*)>",
	PATTERN_LEBEL_DATS		:"<dat:([^<>!]*)(!([\w-\.][^!]+?))?>",
	PATTERN_LEBEL_DATA		:"<data:([^<>]*)>",
	PATTERN_LEBEL_DATAS		:"<data:([^<>!]*)(!([\w-\.][^!]+?))?>",
	PATTERN_LEBEL_ITEM		:/\[item:([\w-\.]*)\]/gi,
	PATTERN_LEBEL_ITEMS		:/\[item:([\w-\.]*)(!([\w-\.][^!]+?))?(!([\w-\.][^!]+?))?\]/gi,
	toReplaceRegex:function(re,values,pattern,func){return $c.toReplaceRegex(re,values,pattern,func)},
	toReplacePre:function(re,values){return this.toReplaceRegex(re,values,this.PATTERN_PRE)},toReplaceVar:function(re,values){return this.toReplaceRegex(re,values,this.PATTERN_VAR)},
	toReplaceEncodeFilter:function(re,values,pattern,func){if(!re)return'';return re.replace(pattern,function(s,s1,s2,s3,s4,s5){return $dtml.toEncodeFilterValue(values.getItem(s1),s3,s5,func)})},
	filterItem:function(re,treeItem,pattern){return this.toReplaceEncodeFilter(re,treeItem,pattern||this.PATTERN_LEBEL_ITEMS)},
	toEncodeFilterValue:function(re,fmt,p2,func){
		if(fmt){switch(fmt){
			case 'html':		re=$codes.toHTML(re,1,p2); break;
			case 'explain':		re=$codes.toHTML(re,2,p2); break;
			case 'date':		re=$time.toConvert(re,'date'); break;
			case 'dates':		re=$time.toConvert(re,'dates'); break;
			case 'datey':		re=$time.toConvert(re,'datey'); break;
			//case 'url':		re=$codes.toURL(re); break;
			//case 'explain.js':	re=$codes.toJS(toHTMLValue(re,2,0)); break;
			//case 'money':		re=$codes.toMoney(re); break;
			//case 'emoney':	re=$codes.toEmoney(re); break;
			case 'remark':		re=$codes.toHTML(re,0,p2); break;
			case 'text':		re=$codes.toHTML(re,1,p2); break;
			case 'xml':		re=$codes.toXML(re); break;
			//case 'js':		re=$codes.toJS(re); break;
			default:
				if(func) re=func(re,fmt,p2);
				else{
					var fname='to_'+fmt;
					if($codes[fname]) re=$codes[fname](re,p2);
				}
				break;
		}}
		return re
	},
	toXML:function(node,field,items){
		if(!node) node='item';
		var re=new array();
		re.push(this.xmlHeader());
		re.push('<configure>');
		re.push('	<node>'+node+'</node>');
		re.push('	<field>'+field+'</field>');
		re.push('</configure>');
		re.push(items);
		re.push(this.xmlFooter());
		return re.join(BR)
	},
	toXMLMap:function(node,field,items){
		if(!node) node='item';
		var re=new array();
		re.push(this.xmlHeader());
		re.push('<configure>');
		re.push('	<nodes>'+node+'</nodes>');
		re.push('	<field.'+node+'>'+field+'</field.'+node+'>');
		re.push('</configure>');
		re.push(items);
		re.push(this.xmlFooter());
		return re.join(BR)
	},
	xmlHeader:function(){return '<'+'?xml version="1.0"?'+'><xcml version="1.0" model="data">'},		// encoding="utf-8"
	xmlFooter:function(){return '</xcml>'}
};

VDCS.DTML={};


//struct
/* ************************************* */
VDCS.utilTree=function(){
	this._count=-1,this._i=0,this._data=new Array(),this._ObjectType='VDCS.utilTree';
	
	this.isData=this.is=function(){return isInt(this._count)};
	this.getObjectType=function(){return this._ObjectType};
	
	this.getCount=this.count=function(){return this._count+1};
	this.getLength=this.len=function(){return this._data.length};
	this.getI=this.i=function(){return this._i};
	
	this.getArray=function(){return this._data};
	
	this.doBegin=this.begin=function(){this._i=0};
	this.doEnd=function(){this._i=(this._count>-1?this._count:0)};
	this.doMove=this.move=function(n){
		if(!isInt(n)) n=1;
		this._i+=n;
		if(this._i>this._count) this._i=this._count;
		if(this._i<0) this._i=0
	};
	
	this.getItemKey=this.ik=function(){return(this._count>-1)?this._data[this._i][0]:''};
	this.getItemValue=this.iv=function(){return(this._count>-1)?this._data[this._i][1]:''};
	
	this.addItem=this.add=function(k,v){
		for(var i=0;i<=this._count;i++){
			if(this._data[i][0]==k){this._data[i][1]=v;return}
		}
		this._count++;
		this._data[this._count]=new Array(k,v)
	};
	
	this.setItem=function(k,v){
		for(var i=0;i<=this._count;i++){
			if(this._data[i][0]==k){this._data[i][1]=v;return}
		}
	};
	
	this.delItem=function(k){
		for(var i=0;i<=this._data.length;i++){
			if(this._data[i][0]==k){
				this._data=this._data.slice(0,i).concat(this._data.slice(i+1,this._data.length));
				this._count=this._data.length-1;
				this.doBegin();
				break
			}
		}
	};
	
	this.isItem=function(k){
		var re=false;
		for(var i=0;i<=this._count;i++){
			if(this._data[i][0]==k){re=true;break}
		}
		return re
	};
	
	this.getItem=this.v=function(k){
		var re='';
		for(var i=0;i<=this._count;i++){
			if(this._data[i][0]==k){re=this._data[i][1];break}
		}
		return re
	};
	this.getItemInt=this.vi=function(k){return toi(this.getItem(k))};
	this.getItemNum=this.vn=function(k){return ton(this.getItem(k))};
	
	this.getFields=function(){return this.getValues(0)};
	this.getFieldArray=function(){return this.getValueArray(0)};
	
	this.getValues=function(t){
		if(!isint(t)) t=1;
		var re='';
		for(var i=0;i<=this._count;i++){re+=','+this._data[i][1]}
		if(re.length>0) re=re.substr(1);
		return re
	};
	this.getValueArray=function(t){
		if(isint(t)) t=1;
		var rea=new Array();
		for(var i=0;i<=this._count;i++){rea[i]=this._data[i][t]}
		return rea
	};
	
	this.doAppendTree=function(treeo,px){
		if(isTree(treeo)){
			if(!px)px='';
			treeo.doBegin();
			for(var i=0;i<treeo.getCount();i++){
				this.addItem(px+treeo.getItemKey(),treeo.getItemValue());
				treeo.doMove()
			}
		}
	};
	
	this.extractJson=function(fields){
		var fielda=fields?fields.split(','):[];
		for(var a in fielda){
			var field=fielda[a];
			if(field) this.extractJsoni(JSON.parse(this.v(field)),field+'.');
		}
	};
	this.extractJsoni=function(jsono,px){
		for(var node in jsono){
			if(typeof(jsono[node])=='object') this.extractJsoni(jsono[node],px+node+'.');
			else this.add(px+node,jsono[node]);
		}
	};
};

VDCS.utilTable=function(){
	this._row=-1,this._col=-1,this._i=1,this._data=new Array(),this._ObjectType="VDCS.utilTable";
	
	this.isData=this.is=function(){return this._count>-1?true:false};
	this.getObjectType=function(){return this._ObjectType};
	
	this.getRow=this.row=function(){return this._row};
	this.getCol=this.col=function(){return this._col+1};
	this.getI=this.i=function(){return this._i};
	
	this.getArray=function(){return this._data};
	
	this.doItemBegin=this.begin=this.ibegin=function(){this._i=1};
	this.doItemEnd=this.end=this.iend=function(){this._i=(this._row>0?this._row:1)};
	this.doItemMove=this.move=this.imove=function(strer){
		if(!isInt(strer)) strer=1;
		this._i+=strer;
		if(this._i>this._row) this._i=this._row;
		if(this._i<1) this._i=1
	};
	
	this.addItem=this.add=function(treeo){
		if(isTree(treeo)){
			if(this._row<0 && treeo.getCount()>0){
				this._row=0;
				this._col=treeo.getCount()-1;
				this._data[this._row]=treeo.getFieldArray();
				//putArray(this._data[this._row]);
			}
			if(this._row<0) return;
			this._row++;
			this._data[this._row]=new Array(this._col+1);
			for(var c=0;c<=this._col;c++){
				this._data[this._row][c]=treeo.getItem(this._data[0][c])
			}
		}
	};
	
	this.setItem=function(treeo){
		if(isTree(treeo) && this._row>0){
			for(var c=0;c<=this._col;c++){
				this._data[this._i][c]=treeo.getItem(this._data[0][c])
			}
		}
	};
	
	this.setItemValue=function(k,v){
		if(this._row>0){
			for(var c=0;c<=this._col;c++){
				if(this._data[0][c]==k){this._data[this._i][c]=v;break}
			}
		}
	};
	
	this.delItem=function(s){
		if(this._row>0){
			var tmpRow=isInt(s)?s:this._i,tmpAry=this._data;
			this._row=-1;
			this._data=new Array();
			for(var a=0;a<tmpAry.length;a++){
				if(a!=tmpRow){
					this._row++;
					this._data[this._row]=tmpAry[a]
				}
			}
			this._i=1
		}
	};
	
	this.getItemTree=function(){
		var reTree=new VDCS.utilTree();
		if(this._row>0){
			for(var c=0;c<=this._col;c++) reTree.addItem(this._data[0][c],this._data[this._i][c])
		}
		return reTree
	};
	
	this.getItemValue=this.iv=function(k){
		var re='';
		if(this._row>0){
			for(var c=0;c<=this._col;c++){
				if(this._data[0][c]==k){re=this._data[this._i][c];break}
			}
		}
		return re
	};
	this.getItemValueInt=this.ivi=function(k){return toi(this.getItemValue(k))};
	this.getItemValueNum=this.ivn=function(k){return ton(this.getItemValue(k))};
	
	this.getFields=function(){
		var re='';
		if(this._row>0){
			for(var c=0;c<=this._col;c++) re+=','+this._data[0][c];
			if(re.length>0) re=re.substr(1);
		}
		return re
	};
	
	this.setFields=function(fields){
		var fielda=fields.split(',');
		if(fielda.length>0){
			this._row=0;
			this._col=fielda.length-1;
			this._data=new Array();
			this._data[this._row]=fielda
		}
	};
	
	this.doAppendTree=function(tree){
		if(iso(tree) && tree.getObjectType()==this._ObjectType){
			tree.doBegin();
			for(var i=0;i<tree.getCount();i++){
				this.addItem(tree.getItemKey(),tree.getItemValue());
				tree.doMove();
			}
		}
	};
};

VDCS.utilMap=function(){
	this._list={},this._ObjectType='VDCS.utilMap';
	
	this.isData=this.is=function(){return this._list.length>0?true:false};
	this.getObjectType=function(){return this._ObjectType};
	
	this.isItem=function(k){return typeof(this._list[k])!='undefined'};
	this.addItem=this.add=function(k,o){this._list[k]=o};
	this.setItem=function(k,o){this._list[k]=o};
	this.getItem=function(k,o){return this._list[k]};
	
	this.getItemString=function(k,o){return(typeof(this._list[k])=='string')?this._list[k]:''};
	
	this.getItemArray=function(k,o){
		var rea=new Array();
		if(isa(this._list[k])) rea=this._list[k];
		return rea
	};
	
	this.getItemTree=function(k,o){
		var reTree=new VDCS.utilTree();
		if(isTree(this._list[k])) reTree=this._list[k];
		return reTree
	};
	
	this.getItemTable=function(k,o){
		var reTable=new VDCS.utilTable();
		if(isTable(this._list[k])) reTable=this._list[k];
		return reTable
	};
};


//xcml
/* ************************************* */
VDCS.utilXCML=function(){
	this._i=0,this._Dom=null,this._isObject=false,this._NodeConfigure=null,this._NodeItem=null,this._NodeItemLength=0,this._NodeItemNow=0,this._NodeItemArray=[],this.ConfigureNode='',this.ConfigureField='',this._ObjectType="VDCS.utilXCML";
	
	this.getDomObject=function(){
		var o=null;
		if(w.ActiveXObject){
			var aryObj=['Microsoft.XMLDOM'];
			for(var i=0;o<aryObj.length;i++){
				try{o=new ActiveXObject(aryObj[i])}catch(e){o=null}
				if(o!=null) break
			}
		}
		else if(d.implementation && d.implementation.createDocument) o=d.implementation.createDocument('','',null);
		return o
	};
	
	this.isObject=function(strer){return this._isObject};
	this.getObjectType=function(){return this._ObjectType};
	
	this.doInit=function(s){ 
		this._Dom=this.getDomObject();
		if(this._Dom!=null){
			this._Dom.async=false;
			this._Dom.resolveExternals=false;
			this._isObject=true
		}
	};
	
	this.loadResponseXML=function(o){if(iso(o)){try{ this._Dom=o.documentElement; this._isObject=true}catch(e){ this._isObject=false} } };
	this.loadFile=function(strer){this._Dom=this.getDomObject(); try{ this._Dom.load(strer); this._isObject=true}catch(e){ this._isObject=false} };
	this.loadURL=function(strer){this._Dom=this.getDomObject(); try{ this._Dom.load(strer); this._isObject=true}catch(e){ this._isObject=false} };
	this.loadXML=function(strer){if(!$b.ie){this.loadResponseXML(new DOMParser().parseFromString(strer,'text/xml'))} else{this._Dom=this.getDomObject(); try{ this._Dom.async=false; this._Dom.resolveExternals=false; this._Dom.loadXML(strer); this._isObject=true}catch(e){ this._isObject=false} } };
	
	this.getXML=function(){return(this._isObject==true)?this._Dom.xml:''};
	
	//####################
	this.doParse=function(){
		if(!this._isObject||!this._Dom) return;
		try{
			this._NodeConfigure=this.getNodeObject('configure');
			this.ConfigureNode=this.getConfigure('node');
			this.ConfigureField=this.getConfigure('field');
		}catch(e){}
	};
	
	this.doParseNode=function(s){
		if(!this._isObject) return;
		if(s.length>0){
			this._NodeItem=this.getNodeObject(s);
			this._NodeItemLength=this._NodeItem.length;
			if(this.ConfigureField.length>0) this._NodeItemArray=this.ConfigureField.split(',');
		}
	};
	this.doParseItem=function(){this.doParseNode(this.ConfigureNode)};
	
	//####################
	this.getConfigure=function(k){return this.getNodeValue(this._NodeConfigure,0,k)};
	this.getConfigureNode=function(){return this.ConfigureNode};
	this.getConfigureField=function(){return this.ConfigureField};
	
	//####################
	this.getItemField=function(){return this.ConfigureField};
	this.getItemCount=function(){return this._NodeItemLength};
	this.getItemLength=function(){return this._NodeItemLength};
	this.getItemNow=function(){return this._NodeItemNow};
	
	this.doItemBegin=function(){return this._NodeItemNow=0};
	this.doItemMove=function(){
		this._NodeItemNow++;
		if(this._NodeItemNow>(this._NodeItemLength-2)) this._NodeItemNow=this._NodeItemLength-1;
	};
	
	this.getItem=function(k){
		var re='';
		if(this._isObject && this._NodeItemLength>0){re=this.getNodeValue(this._NodeItem,this._NodeItemNow,k)}
		return re
	};
	
	this.getItemTree=function(){
		var reTree=new VDCS.utilTree();
		if(this._isObject && this._NodeItemLength>0){reTree=this.getNodeTree(this._NodeItem,this._NodeItemNow)}
		return reTree
	};
	
	//####################
	this.getNodeObject=function(s) {
		var re=null;
		if(this._isObject==true){
			if($b.ie){re=this._Dom.getElementsByTagName(VDCS.XCML_NODENAME+'/'+s)}
			else{re=this._Dom.ownerDocument.getElementsByTagName(VDCS.XCML_NODENAME)[0].getElementsByTagName(s)}
		}
		return re
	};
	
	this.getNodeValue=function(node,num,key){
		var re='';
		try{
			if(node==null) node=this._Dom;
			re=node.item(num).getElementsByTagName(key).item(0).firstChild.nodeValue
		}catch(e){}
		return re
	};
	
	this.getNodeTree=function(node,num){
		var reTree=new VDCS.utilTree();
		try{
			if(!num)num=0;
			if(!iso(node)) node=this.getNodeObject(node);
			if(node&&node.length){
				var _nodes;
				if($b.ie){_nodes=node.item(num).childNodes}
				else{
					var n=0;
					for(var i=0;i<node.length;i++){
						if(node[i].parentNode.tagName==VDCS.XCML_NODENAME){
							if(num==n){_nodes=node.item(i).childNodes;break}
							n++
						}
					}
				}
				var _key,_value;
				for(var c=0;c<_nodes.length;c++){
					_key=_nodes.item(c).tagName;
					if(!ise(_key)){
						_value='';
						if(iso(_nodes.item(c).firstChild)) _value=_nodes.item(c).firstChild.nodeValue;
						reTree.addItem(_key,_value)
					}
				}
			}
		}catch(e){}
		return reTree
	};
};


//########################################
//########################################
//serve
/* ************************************* */
VDCS.serve=function(){
	this.filter='router';
	this.url='/p.'+$c.EXT+'/{$channel}/{$p}/{$m}/{$mi}.{$x}?action={$action}';
	this._var={channel:'',p:'',m:'',mi:'',x:'x',action:''};
	this.setVar=function(k,v){this._var[k]=v};
	this.filterURL=function(url,f){
		if(this.filter=='router'||f){url=r(url,'/.','.');url=r(url,'/.','.');url=r(url,'/.','.');}
		return url
	};
	this.getURL=function(opt){		//组装url
		opt=ox(this._var,opt);
		var _url=this.url;
		_url=rd(_url,'channel',opt.channel);_url=rd(_url,'p',opt.p);_url=rd(_url,'m',opt.m);_url=rd(_url,'mi',opt.mi);_url=rd(_url,'x',opt.x);_url=rd(_url,'action',opt.action);
		_url=this.filterURL(_url);
		var params=opt.params;
		if(params) _url=$url.link(_url,(isa(params)||iso(params)?$url.querys(params):params));
		return _url
	};
};

$form={
	formCheck:function(that){
		if(!that) that=this;
		var _ischeck=true,_vcheck=false,ardata={};
		that.err=new VDCS.Error(),that.jformitem=null;
		if(typeof VCheck != 'undefined'){
			VCheck.resete();
			_vcheck=true
		}
		//:text,input,select,textarea
		that.jforms.find('input,select,textarea').each(function(i){
			var jfield=$(this),_name=jfield.attr('name');
			var name=(_name && _name.substr(-2)=='[]')?_name.substr(0,_name.length-2):_name;
			//dbg.t(_name+','+name);
			if(name && typeof(ardata[name])=='undefined'){
				var _value=jfield.vals();
				//dbg.t(name+', '+jfield.attr('type')+' = '+_value);
				ardata[name]=_value;
				if(_vcheck && !VCheck.elementv(jfield)) _ischeck=false;
				if(!_vcheck){
					var _min=toi(jfield.attr('vmin')?jfield.attr('vmin'):(jfield.attr('min')?jfield.attr('min'):jfield.attr('minlength')));
					if(_min>0 && !jfield.val().length) _ischeck=false;
				}
				if(!_ischeck && !that.jformitem) that.jformitem=jfield;
			}
		});
		that.jforms.find('input[type=radio]:checked').each(function(){
			var name=$(this).attr('name');
			var value=$(this).val();
			if(name) ardata[name]=value;
		});
		if(that.opt && that.opt.encrypt){
			var _timer='';
			if(that.opt.encrypt_timer) _timer=$tim.timer();
			ardata['_encrypt_timer']=_timer;
			var afield=that.opt.encrypt_field.split(','),_field;
			for(var a in afield){
				_field=afield[a];
				if(ardata[_field]){
					ardata[_field]=$.md5(ardata[_field]);
					if(_timer) ardata[_field]=$.md5(ardata[_field]+','+_timer);
				}
			}
		}
		that.ardata=ardata;
		if(that.formCheckExtend) that.formCheckExtend();
		if($req.q('debug')=='data') dbg.o(that.ardata);
		if(_ischeck && that.err) _ischeck=that.err.isCheck();
		if(!_ischeck && that.tips && that.err) that.tips('error',that.err.toString(),true);
		//if(_ischeck && that.tips) that.tips('hide');
		//_ischeck=false;
		return _ischeck
	},
	formCheckExtend:function(){},

	isLogin:function(){return ua.isLogin(true)},
	send:function(){
		if(!this.isLogin())return;
		var that=this;
		if(!this.formCheck()){
			//alert(this.ardata['content']);
			return
		}
		if(this.issend) return;this.issend=true;
		var _url=this.getURL('send'),_send=this.ardata;
		if(!_url){
			app.tips('info','Missing Send URL!',true);
			return
		}
		$ajax({url:_url,send:_send,value:'xml',ready:function(o){that.sendAsync(o)},error:true})
	},
	sendAsync:function(xml){
		//alert(xml);
		this.maps=$util.toMapByXML(xml);
		this.treeVar=this.maps.getItemTree('var');
		var _status=this.treeVar.v('status');
		if(_status=='succeed'){
			var _msg=this.statusMessage(this.treeVar,'发布成功！');
			app.tips('succeed',_msg,true);
			if(this.reset) this.reset();
			if(this.refresh) this.refresh();
			this.sendSucceed()
		}
		else{
			this.statusParser(this.treeVar)
		}
		this.issend=false
	},
	sendSucceed:function(){},

	statusOpt:function(opt){opt=ox({tips:'tips',already:true},opt);return opt},
	isSucceed:function(val,opt){
		opt=this.statusOpt(opt);
		var re=false;
		var _status=iso(val)?val.v('status'):val;
		if(_status=='succeed') re=true;
		if(opt.already && _status=='already') re=true;
		return re
	},
	statusSucceed:function(treeVar,opt){
		if(this.isSucceed(treeVar,opt)) return true;
		this.statusParser(treeVar,opt);
	},
	statusMessage:function(treeVar,def,err){
		var _msg=treeVar.v('message')||treeVar.v('message.string')||'';
		if(!_msg && err) _msg=treeVar.v('error_msg')||treeVar.v('error_message')||'';
		if(!_msg && def) _msg=def;
		return _msg
	},
	statusParser:function(treeVar,opt){
		opt=this.statusOpt(opt);
		var ret={};
		var _status=opt.status?opt.status:treeVar.v('status');
		var _msg=this.statusMessage(treeVar,'',true);
		if(!_msg) _msg=opt['message_'+_status];
		if(!_msg){
			switch(_status){
				case 'init':		_msg='数据初始化！';break;
				case 'parser':		_msg='无效的数据解析！';break;
				case 'already':		_msg='数据已处理！';break;
				case 'params':		_msg='缺少必要的参数！';break;
				case 'data':		_msg='不正确的数据提交！';break;
				case 'nodata':		_msg='不正确的数据提交！';break;
				case 'noexist':		_msg='记录不存在！';break;
				case 'nopermission':	_msg='权限不足！';break;
				case 'succeed':		_msg='提交成功！';break;
				case 'failed':		
				default:		_msg='数据处理失败！';break;
			}
		}
		ret.status=_status;ret.message=_msg;
		if(!_msg) alert(xml);
		else{
			var tips_status=opt['tips_status_'+_status] || 'info';
			if(opt.tips){
				switch(opt.tips){
					case 'mini':		ui.mini.show(_msg);break;
					case 'tips':
					default:		app.tips(tips_status,_msg,true);break;
				}
			}
			if(opt.callback) opt.callback(_status,_msg);
		}
		return ret
	},
'':''};

//forms
/* ************************************* */
VDCS.forms=function(opt,selector){
	this.opt=ox({frm:'frm_post',
		names			: '服务',
		message_serve		: '未知的服务请求！',
		message_formcheck	: '请填写必要的信息！',
		message_error_unknown	: '未知错误',
		message_parser		: '处理中..',
		message_succeed		: '处理成功！',
		message_back		: '服务跳转中..',
		submit_status		: true,
		submit_ing		: '提交中..',
		submit_succeed		: '提交成功！',
		tips_speed		: 300,
		tips_timer		: 2,
		tips_cover		: true,
		tips_succeed		: true,
		encrypt			: false,
		encrypt_timer		: false,
		encrypt_field		: '',
		serv_method		: 'post',
		serv_vars		: [],
		servURL			: '',
		serveURL		: '',
		autoenter		: true,
		goback			: true},opt);
	this.selector=ox({
		submit			: '[el=submit]',
		tips			: '.tips',
		tips_type		: 'hint',	//tips
		tips_msg		: 'span',
	'':''},selector);
	
	this._opt=function(opt){if(opt) this.opt=ox(this.opt,opt)};
	this._selector=function(selector){if(selector) this.selector=ox(this.selector,selector)};
	
	this._initer=this.__initer=function(opt,selector){
		if(this.isiniter)return;this.isiniter=true;
		this._opt(opt);this._selector(selector);
		if(this.opt.body || this.jbody){
			this.jbody=this.jbody?$jo(this.jbody):$jo(this.opt.body);
			if(this.jbody) this.jform=this.jbody.find('form');
		}
		else if(this.opt.frm || this.jform){
			this.jform=this.jform?$jo(this.jform):$f.form(this.opt.frm);
			if(this.jform) this.jbody=this.jform.parent();
		}
		if(!this.jbody && !this.jform)return;
		this.jtips=this.selector.jtips?this.selector.jtips:this.jbody.finder(this.selector.tips);
		this.jsubmit=this.selector.jsubmit?this.selector.jsubmit:this.jbody.finder(this.selector.submit);
		this.isinit=true
	};
	
	this.servVar=function(k,v){this.opt.serv_vars[k]=v},
	this.serve=function(params){
		var re=this.opt.serveURL?this.opt.serveURL:this.opt.servURL;
		re=$c.toReplaceVar(re,this.opt.serv_vars);
		if(params) re=$url.link(re,params);
		return re
	},
	
	this.formCheck=function(){if(!this.jforms)this.jforms=this.jbody;return $form.formCheck(this)};
	this.formHide=function(){
		if(!this.isinit) return;
		this.jbody.hide()
	};
	
	this.submitInit=function(opt){
		var that=this;
		if(this.jform){
			this.jform.submit(function(){return false});
			if(this.opt.autoenter){
				this.jform.find('input').on('keypress',function(et){
					if(et.keyCode=='13') that.parser()
				})
			}
		}
		if(!this.jsubmit) return;
		this.jsubmit.click(function(){
			if($(this).attr('disabled'))return;
			that.parser();
			return false
		});
	};
	this.submitSet=function(status,title){
		if(!this.jsubmit)return;
		this.submit_status=status;
		var _value=title;
		if(!this.opt.submit_value) this.opt.submit_value=this.jsubmit.text();
		switch(status){
			case 'ing':
			case 'off':
				this.jsubmit.attr('disabled',true);
				if(!_value) _value=this.opt.submit_ing;
				break;
			case 'succeed':
				this.jsubmit.attr('disabled',true);
				if(!_value) _value=this.opt.submit_succeed;
				break;
			case 'on':
			default:
				this.jsubmit.attr('disabled',false);
				if(!_value) _value=this.opt.submit_value;
				break;
		}
		if(this.opt.submit_status && _value) this.jsubmit.find('span').text(_value)
	};
	this.submitLock=function(){return this.submit_status && this.submit_status!='on'};
	
	this.tips=function(status,message,callback,timer){
		timer=timer?timer:this.opt.tips_timer;
		if(status=='hide') app.hint(this.jtips,'hide');
		else app.hint(this.jtips,message,{status:status,callback:callback,tip:'.itip',tip_class:'itip',speed:this.opt.tips_speed,timer:timer,cover:this.opt.tips_cover});
	};
	
	this.getMessage=function(key){return this.opt['message_'+key] || '['+key+']'};
	this.urlBack=function(url){if(url) this.opt.url_back=url;return this.opt.backurl||this.opt.url_back||$c.url('root');};
	
	// xform
	this.initer=function(opt,selector){
		/*
		var backurl=$req.q('backurl')||$req.q('url');
		if(backurl) opt.backurl=backurl;
		*/
		this.__initer(opt,selector);if(!this.isinit)return;
		this.submitInit()
	};
	this.check=function(){
		if(!this.formCheck()){
			var msg=this.getMessage('formcheck');
			if(this.jformitem){
				var _name=this.jformitem.attr('names');
				if(!_name) _name=this.jformitem.attr('_placeholder');
				if(!_name) _name=this.jformitem.attr('placeholder');
				if(!_name) _name=this.jformitem.attr('name');
				msg='请输入 '+_name+'！';
				this.jformitem.addClass('error');
				this.jformitem.blur(function(){
					var jo=$(this);
					if(jo.val()) jo.removeClass('error')
				});
				this.jformitem.focus()
			}
			this.tips('error',msg);
			if(this.opt.onerror) this.opt.onerror(this.jformitem);
			return false
		}
		return true
	},
	this.parser=function(){
		if(this.submitLock())return;
		if(!this.check())return;
		this.tips('load',this.getMessage('parser'));
		this.submitSet('ing');
		var that=this;
		var _url=this.serve(),_send=null;
		if(!_url){
			this.tips('info',this.getMessage('serve'));
			this.submitSet('on');
			return;
		}
		if(this.opt.serv_method=='post') _send=this.ardata;
		else _url=$url.link(_url,$url.querys(this.ardata));
		//dbg.t(_url);
		//dbg.o(_send);
		$ajax({url:_url,send:_send,value:'xml',ready:function(o){that.parserAsync(o)},error:true});
		return false
	};
	this.parserAsync=function(xml){
		if(this.debugxml || isdebug('maps')){
			var jcont=$('#debug_xml');
			if(!jcont || !jcont.length) alert(xml);
			else jcont.html($code.toHTML(xml));
			this.submitSet('on');
			return
		}
		var that=this;
		this.maps=$util.toMapByXML(xml);
		this.treeVar=this.maps.getItemTree('var');
		var _status=this.treeVar.v('status');
		//dbg.t(_status);
		if(inp('succeed,already',_status)>0){
			if(this.treeVar.v('backurl')) this.urlBack(this.treeVar.v('backurl'));
			if(this.treeVar.v('url_back')) this.urlBack(this.treeVar.v('url_back'));
			this.submitSet('succeed');
			if(this.opt.tips_succeed) that.parserTips();
			if(this.opt.callback) this.opt.callback(_status,this.treeVar);
		}
		else{
			this.tips('error',this.treeVar.v('message')||this.treeVar.v('error_message')||this.getMessage('error_unknown')+'('+_status+')',true);
			this.submitSet('on');
			if(this.opt.callerror) this.opt.callerror(_status,this.treeVar);
		}
	};
	this.parserTips=function(opt){
		var that=this;
		opt=ox({message:this.getMessage('succeed')},opt);
		var _back=function(){
			if(!that.opt.goback) return;
			ui.mini.show(that.getMessage('back'));
			$p.go(that.urlBack())
		};
		if(this.opt.backurl || this.opt.url_back){
			ui.popup.show({status:'succeed',message:opt.message,close:_back});
		}
		this.tips('succeed',opt.message,_back,1)
	};
};

VDCS.list=function(opts){
	this._opt=function(opt,opt2){
		if(!opt2) return opt;
		if(opt2.serveVar) opt2.serveVar=ox(opt.serveVar,opt2.serveVar);
		opt=ox(opt,opt2);
		return opt
	};
	this.opt=this._opt({node_table:'item',serveVar:{x:'x'}},opts);
	
	this.setServeVar=function(k,v){this.opt.serveVar[k]=v};
	this.getServeURL=function(opt){						//组装url
		opt=ox(this.opt.serveVar,opt);
		return $url.link(ui.serve.getURL(opt),'page='+this.page)
	};
	this.getURL=function(){return this.opt.serveURL?$url.link(this.opt.serveURL,'page='+this.page):this.getServeURL()};

	this.init=function(opt){
		this.opt=this._opt(this.opt,opt);				//将init传入值并到this.opt
		if(this.opt.wrap) this.jwrap=$j(this.opt.wrap);			//wrap区
		if(this.opt.cont) this.jcont=$j(this.opt.cont);			//内容区
		if(this.opt.tpl) this.jtpl=$j(this.opt.tpl);			//模板
		if(this.opt.tple) this.jtple=$j(this.opt.tple);			//模板
		if(this.opt.paging) this.jpaging=$j(this.opt.paging);		//分页
		if(this.opt.loader) this.jloader=$j(this.opt.loader);		//加载
		if(this.opt.loading) this.jloading=$j(this.opt.loading);	//加载内容
		//##########
		if(this.jwrap&&!this.jwrap.length)this.jwrap=null;
		if(this.jcont&&!this.jcont.length)this.jcont=null;
		if(!this.jwrap && this.jcont) this.jwrap=this.jcont.parents('div');
		//##########
		if(this.jwrap){
			if(!this.jcont) this.jcont=this.jwrap.finde('cont');
			if(!this.jtpl) this.jtpl=this.jwrap.finde('tpl');
			if(!this.jtple) this.jtple=this.jwrap.finde('tple');
		}
		//##########
		if(this.jcont&&!this.jcont.length)this.jcont=null;
		if(this.jtpl&&!this.jtpl.length)this.jtpl=null;
		if(this.jtple&&!this.jtple.length)this.jtple=null;
		if(this.jpaging&&!this.jpaging.length)this.jpaging=null;
		if(this.jloader&&!this.jloader.length)this.jloader=null;
		if(this.jloading&&!this.jloading.length)this.jloading=null;
		//##########
		if(this.jcont&&this.jtpl)this.isinit=true;
	};
	
	this.refresh=function(){
		//this.reset();
		this.parse()
	};
	this.reset=function(){
		this.load()
	};
	
	
	this.clickPage=function(page){
		this.page=page;
		this.parse()
	},
	this.parse=function(){
		if(!this.isinit)return;
		if(this.isparse)return;this.isparse=true;
		this.loadon();
		var that=this;
		//this.action='query';
		var _url=this.getURL();
		//dbg.t(_url);
		$ajax({url:_url,value:'xml',ready:function(o){that.parseAsync(o)},error:false})
	};
	
	
	this.parseAsync=function(xml){
		var that=this;
		this.maps=iso(xml)?xml:$util.toMapByXML(xml);
		this.treeVar=this.maps.getItemTree('var');
		this.tableList=this.maps.getItemTable(this.opt.node_table);
		var _status=this.treeVar.v('status');
		this.loadoff();
		if(_status=='succeed'){
			var jsonFielda=this.opt.jsonFields?this.opt.jsonFields.split(','):[];
			this.jcont.html('');
			this.tableList.begin();
			for(var i=1;i<=this.tableList.row();i++){
				var treeItem=this.tableList.getItemTree();
				var oe=(i+1)%2+1;
				treeItem.addItem('_oe_',oe);
				treeItem.addItem('_sn_',i);
				treeItem.extractJson(this.opt.jsonFields);
				if(this.opt.user_avatar) treeItem.addItem('user_avatar',rd('/images/ua/avatar.gif','uid','[item:uuid]'));
				if(this.opt.avatar) treeItem.addItem('avatar.url',rd('/images/ua/avatar.gif','uid','[item:uuid]'));
				if(this.opt.filterItem) treeItem=this.opt.filterItem(treeItem);
				if(this.filterItem) treeItem=this.filterItem(treeItem);
				var _html=this.getItemString(treeItem);
				//htmla.push(_html);
				var jitem=$(_html).appendTo(this.jcont);
				
				if(this.opt.bind) this.opt.bind(jitem,treeItem);
				if(this.bind) this.bind(jitem,treeItem);
				this.tableList.move();
			}
			if(this.tableList.row()<1){
				var _html=this.jtple?this.jtple.html():'';
				if(!_html){
					//data-empty="暂无记录" data-empty-type="inline" data-empty-status="info"
					var _empty=this.jcont.attrd('empty');
					if(_empty) _html='<div class="iblank '+(this.jcont.attrd('empty-type')||'inline')+' '+(this.jcont.attrd('empty-status')||'info')+'"><p><em></em><i>'+_empty+'</i></p></div>';
				}
				if(_html){
					var jempty=$(_html);
					if(this.jcont.is('tbody')&&jempty.find('tr > td').length<1){
						_html='<tr><td colspan="'+this.jwrap.find('thead th').length+'">'+_html+'</td></tr>';
					}
					this.jcont.append(_html);
				}
			}
			if(this.opt.binds) this.opt.binds(this.jcont,this.treeVar,this.maps);
			if(this.binds) this.binds(this.jcont,this.treeVar,this.maps);
			this.uio(this.jcont);
			if(this.jpaging){
				ui.paging.parser(this.treeVar,this.jpaging,function(page){
					that.clickPage(page);
				},{limit:true,jump:true});
				this.uio(this.jpaging);
			}
			if(this.parsePaging) this.parsePaging(this.treeVar);
		}
		else{
			if(!_status) _status='!XCML';
			var _message=this.treeVar.v('message');
			if(!_message) _message='['+_status+']';
			ui.popups('info',_message,true);
			if(this.opt.fails) this.opt.fails(_status);
		}
		this.isparse=false
	};
	this.getItemString=function(treeItem){
		var re='';
		re=this.jtpl.html();
		re=this.refille($(re),treeItem).outerHTML();
		if(this.refill) re=this.refill($(re),treeItem).html();
		//if(this.opt.refill) re=this.opt.refill($(re),treeItem).outerHTML();
		re=$dtml.filterItem(re,treeItem);
		return re
	};
	
	this.refille=function(jitem,treeItem){
		var that=this;
		jitem.find('[data-refill]').each(function(){
			var jo=$(this);
			var selector=r(jo.attr('data-refill'),'{val}',treeItem.v(jo.attr('data-refill-field')));
			that.refillei(jitem,jo,selector);
		});
		//alert(jitem.outerHTML());
		return jitem
	};
	this.refillei=function(jitem,jo,selector){
		var _html=this.jwrap.find('xmp[data-refill="'+selector+'"]').html();
		if(_html) jo.append(_html);
	};
	
	this.uio=function(jo){
		if(app.uio) app.uio(jo);
		if(this.opt.uio) this.opt.uio(jo);
	};

	this.loadon=function(){
		if(this.jloader){
			if(!this.jloader.finder('.loading')){
				this.jloader.html(this.getLoadString());
			}
			this.jloader.show();
			this.jcont.html('');
		}
		else{
			this.jcont.html(this.getLoadString());
		}
	};
	this.loadoff=function(){
		this.jcont.html('');
		if(this.jloader) this.jloader.hide();
	};
	this.getLoadString=function(){
		var re='';
		if(this.jloading) re=this.jloading.html();
		if(!re) re='<span class="loading"><img src="/images/common/load/bar.gif" /></span>';
		return re
	};
};


//##define##
extendo($v,{
	'date.unit'		:['秒','分钟','小时','天'],
	'date.weekdays'		:['星期','天一二三四五六'],
	'date.months'		:['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
	'date.distances'	:['$1秒前','$1分钟前','$1小时前','$1天前'],
	'ajax.states'		:['正在初始化...','正在发送请求...','正在接收数据...','正在解析数据...','数据请求结束！']
});

extendo($lang,{
	'pages':{
		'fav.prompt'		:'请按快捷键 "Ctrl+D" 添加收藏！',
		'click'			:['$1','您确定$1吗？','您确定要$1吗？\n\n执行该操作后将不可恢复！']
	}
});
