/*
VDCS Page effect package
*/

var $pp={};

$pp.Steps={
	_name:"",_v:{w:500,path:"./"},_items:Array(),_total:0,_active:0,
	init:function(name,v){
		this._name=name;
		this._v=ox(this._v,v);
		this._v.path=this._v.path||$c.getURL("common")+"struct/";
	},
	addItem:function(tit){
		this._items[this._items.length]=tit;
	},
	doParse:function(o){
		this._total=this._items.length;
		put('<style type="text/css">');
		put(this.getCSS());
		put('</style>');
		var html_=this.getHTML();
		o=$o(o);
		if(o) o.html(html_);
		else put(html_);
	},
	active:function(a){
		if(a<1 || a>this._total) a=1;
		if(this._active>0) $o('box'+this._name+'-step-'+this._active).cssClass("actived");
		$o('box'+this._name+'-step-'+a).cssClass("active");
		this._active=a;
	},
	getHTML:function(){
		var re='<div class="BoxSteps">';
		re+='<ul>';
		re+='';
		for(var a=0;a<this._total;a++){
			var a1=a+1;
			var ra=(a==0?' class="first"':(a1==this._total?' class="last"':''));
			re+='<li'+ra+'><span id="box'+this._name+'-step-'+a1+'"><span><span>'+this._items[a]+'</span></span></span></li>';
		}
		re+='</ul>';
		re+='</div>';
		return re;
	},
	getCSS:function(){
		var re='';
		var wids=this._v.w;
		var wid=Math.floor(wids/this._total)+16;
		wid1=wid-8;
		wid9=wid+(wids-Math.floor(wids/this._total)*this._total)-8;
		re+='.BoxSteps{clear:both;width:'+wids+'px;height:25px;overflow:hidden;}';
		re+='.BoxSteps ul {width:'+wids+'px;}';
		re+='.BoxSteps li{float:left;width:'+wid+'px;height:25px;line-height:25px;font-weight:bold;font-size:13px;margin-right:-16px;overflow:hidden;text-align:center;/**/}';
		re+='.BoxSteps li span{display:block;padding-left:16px;background:url('+this._v.path+'steps.png) no-repeat 0 -150px;}';
		re+='.BoxSteps li span span{padding-left:0;padding-right:16px;background:url('+this._v.path+'steps.png) no-repeat 100% -150px;}';
		re+='.BoxSteps li span span span{height:25px;padding:0 16px;background:url('+this._v.path+'steps.png) repeat-x 0 -75px;}';
		re+='';
		re+='.BoxSteps li.first{width:'+wid1+'px;}';
		re+='.BoxSteps li.first span{background-position:0 -50px;}';
		re+='.BoxSteps li.first span span{background-position:100% -150px;}';
		re+='.BoxSteps li.first span span span{background-position:0 -75px;padding-left:4px;}';
		re+='.BoxSteps li.last{width:'+wid9+'px;}';
		re+='.BoxSteps li.last span span{background-position:100% -200px;}';
		re+='.BoxSteps li.last span span span{background-position:0 -75px;padding-right:4px;}';
		re+='';
		re+='.BoxSteps li span.active{position:relative;color:#fff;background-position:0 -125px;}';
		re+='.BoxSteps li span.active span{background-position:100% -100px;}';
		re+='.BoxSteps li span.active span span{background-position:0 -25px;}';
		re+='';
		re+='.BoxSteps li.first span.active{background-position:0 0;}';
		re+='.BoxSteps li.first span.active span{background-position:100% -100px;}';
		re+='.BoxSteps li.first span.active span span{background-position:0 -25px;}';
		re+='.BoxSteps li.last span.active span{background-position:100% -175px;}';
		re+='.BoxSteps li.last span.active span span{background-position:0 -25px;}';
		return re;
	}
	
};
