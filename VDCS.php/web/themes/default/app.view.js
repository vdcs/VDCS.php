
var view={opt:{},pageNum:-1,
	init:function(chn,id){var that=this;$(function(){that.initer();that.inited();})},
	initer:function(){
		if(this._init)return;this._init=true;
		this.jwrap=$('.e_view');
		this.module=this.channel=this.jwrap.attrd('channel');
		this.rootid=this.id=toi(this.jwrap.attrd('id'));
		var res='';
		if(this.jwrap.finder('.digg_bar')) res+=',digg';
		if(this.jwrap.finder('.relates')) res+=',relate';
		if(res) view.syncInit(res);
		if(this.jwrap.finder('pic')) view.picInit();
	},
	inited:function(){}
};
view.init();

//########################################
//########################################
extendo(view,{
	picInit:function(){
		return;
		if(this.pageNum<2){
			var img=$es($o("div-view-pic"),"img");
			if(img.length>0){
				img=$o(img[0]);
				if(inPart(img.att("_src"),"no_pic.gif","/")<1){
					img.cssStyle("display:inline;");
					img.src=common.toUploadPic(img.att("_src"),null,null,1,-1);
					img.onload=function(){
						//if(this.width>600) this.width=600;
						//if(this.height>600) this.height=600;
						var image=new Image(); 
						var iwidth = 600;		//定义允许图片宽度 
						var iheight = 600;		//定义允许图片高度 
						image.src=img.src; 
						if(image.width>0 && image.height>0){ 
							if(image.width/image.height>= iwidth/iheight){ 
								if(image.width>iwidth){   
									this.width=iwidth; 
									this.height=(image.height*iwidth)/image.width; 
								}else{ 
									this.width=image.width;   
									this.height=image.height; 
								} 
								this.alt=image.width+"×"+image.height; 
							} 
							else{ 
  								if(image.height>iheight){   
									this.height=iheight; 
									this.width=(image.width*iheight)/image.height;   
								}else{ 
									this.width=image.width;   
									this.height=image.height; 
								} 
								this.alt=image.width+"×"+image.height; 
							} 
						} 
					};
				}
			}
		}
		$o("div-view-summary").style.display=(this.pageNum<2)?"":"none";
	},
	doContentSize:function(o,t){
		return;
		o=too(o);
		if(o){
			if(!o.att("_class")){
				var cname=" "+o.className;
				cname=r(cname," big","");
				cname=r(cname," normal","");
				cname=r(cname," small","");
				cname=cname.trim();
				o.att("_class",cname);
			}
			o.className=o.att("_class")+" "+t;
		}
	},
	doContentResize:function(o,size){
		return;
		var o=$o("div-view-remark-content");
		if(o){//scroll-y
			if(!isInt(size))size=200;
			o.h=($b.opera)?o.scrollHeight:o.clientHeight;
			if(o.h>size){o.cssStyle("height:"+size+"px;");}
		}
	}
});

//########################################
//########################################
extendo(view,{
	toURL:function(m,apd){
		var _url=ui.serve.getURL({channel:'common',p:'widget',action:m,params:'module='+this.channel+'&rootid='+this.rootid+'&'+apd});
		_url=rd(_url,'module',this.opt['module']);_url=rd(_url,'channel',this.opt['channel']);
		_url=rd(_url,'rootid',this.opt['rootid']);_url=rd(_url,'id',this.opt['id']);
		return _url;
	},
	syncInit:function(res){
		if(!res) res=this.opt['res']; if(!res)return; this.opt['_res']=res;
		//if(inPart(this.opt['_res'],'digg')>0)appDigg.initUI();
		var that=this;
		$ajax({url:this.toURL('syns','res='+res),value:'xml',ready:function(o){that.syncAsync(o)}});
	},
	syncAsync:function(xml){
		this.maps=$util.toMapByXML(xml);
		this.treeVar=this.maps.getItemTree('var');
		var _status=this.treeVar.v('status');
		if(!this.digg.parent) this.digg.parent=this;
		if(!this.relate.parent) this.relate.parent=this;
		if(inp(this.opt['_res'],'digg')>0) this.digg.parser(this.treeVar);
		if(inp(this.opt['_res'],'relate')>0) this.relate.parser(this.treeVar);
	}
});


//########################################
//########################################
view.digg={opt:{},
	doLoad:function(opt,act){
		if(this.isAsync)return;this.isAsync=true;
		this.opt=oapd(opt,this.opt);
		if(!act) act='';
		var that=this;
		$ajax({url:view.toURL("digg","action="+act),value:'xml',ready:function(o){that.doAsync(o)}});
	},
	doAsync:function(xml){
		this.maps=$util.toMapByXML(xml);
		this.treeVar=this.maps.getItemTree('var');
		var _status=this.treeVar.v('status');
		//this.tableItem=this.oMap.getItemTable("item");
		this.parser(this.treeVar);
		this.isAsync=false;
	},
	parser:function(treeVar){
		var _status=treeVar.vi("digg.status");
		this.initUI(_status);
	},
	doClick:function(_action){
		if(this._action)return;this._action=_action;
		this.doLoad({},_action);
		var o=$o("syns-digg");
		if(o){
			var oc=$es(o,"a",_action);
			if(oc.length>0){
				oc=$es(oc[0],"em");
				if(oc.length>0){
					var oem=$o(oc[0]);
					oem.html(toInt(oem.html())+1);
				}
			}
		}
		return false;
	},
	initUI:function(t){
		this.jbox=this.jbox||this.parent.jwrap.finder('.digg_bar');
		if(!this.jbox) return;
		return;
		var oc=$es(o,"a","agree,oppose");
		if(t==1){
			o.cssClass(o.cssClass()+"Ready","append");
			for(var i=0;i<oc.length;i++){
				oc[i]=$o(oc[i]);
				oc[i].onclick=function(){return false};
			}
		}
		else{
			for(var i=0;i<oc.length;i++){
				oc[i]=$o(oc[i]);
				var _action=oc[i].att("_action")||oc[i].className;
				var that=this;
				if(!oc[i].onclick) oc[i].onclick=function(){
					var _o=$o(this);
					var _action=_o.att("_action")||_o.className;
					return that.doClick(_action);
				};
			}
		}
		//this.initStyle();
	},
	initStyle:function(t){
		if(!this._initStyle){
			if($b.ff){
				var css='';
				css+='.DiggBar a cite{padding-left:30px;}';
				$p.append("cssStyle",css);
			}
			this._initStyle=true;
		}
	}
};

view.relate={opt:{'nodata':'暂无'},
	init:function(chn,id){view.init(chn,id);},
	initer:function(opt){
		this.opt=oapd(opt,this.opt);
		var that=this;
		$ajax({url:view.toURL('relate'),value:'xml',ready:function(o){that.parseAsync(o)}});
	},
	parseAsync:function(xml){
		this.maps=$util.toMapByXML(xml);
		this.treeVar=this.maps.getItemTree('var');
		var _status=this.treeVar.v('status');
		this.parse(this.treeVar);
	},
	parser:function(treeVar){
		this.jbox=this.jbox||this.parent.jwrap.finder('.relates');
		if(!this.jbox) return;
		var _id=treeVar.vi('previous.id'),_html='';
		//alert(this.jbox.find('.previous').find('t').outerHTML());
		_html=(_id>0)?('<a href="'+treeVar.v('previous.linkurl')+'">'+treeVar.v('previous.topic')+'</a>'):this.opt['nodata'];
		this.jbox.find('.previous').find('t').replaceWith('<t>'+_html+'</t>');		//ie8
		_id=treeVar.vi('next.id');
		_html=(_id>0)?('<a href="'+treeVar.v('next.linkurl')+'">'+treeVar.v('next.topic')+'</a>'):this.opt['nodata'];
		this.jbox.find('.next').find('t').replaceWith('<t>'+_html+'</t>');		//ie8
	}
};