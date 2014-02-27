
/* ************************************* */
dcs.TMenu={
	initStyle:function(){
		if(this.isInitStyle){return this.isInitStyle;} this.isInitStyle=true;
		var imgbase=$c.getURL("images")+"common/struct/";
		var _posy=$b.ie?1:2;
		var css="";
		//css+="<style type=\"text/css\">";
		css+=".TMenu li{overflow:hidden;padding:0;margin:0;padding-top:0!important;padding-bottom:0!important;}";
		css+=".TMenu li a{display:inline-block;overflow:hidden;height:20px;line-height:20px;text-decoration:none;background-repeat:no-repeat;background-position:0 "+_posy+"px;padding-left:18px;margin:0;}";
		css+=".TMenu li a{background-image:url(\""+imgbase+"tmenus.gif\");}";
		if($b.ie||$b.opera||$b.chrome)css+=".TMenu li a{line-height:22px;margin:2px 0;}";
		css+=".TMenu li.pop > a{font-weight:bold;background-position:0 "+(-30+_posy)+"px;}";
		css+=".TMenu li dd{padding-left:13px;}";
		css+=".TMenu li dd a{background-position:0 "+(-60+_posy)+"px;}";
		css+=".TMenu li dd.pop > a{font-weight:bold;color:#468847;background-position:0 "+(-60+_posy)+"px;}";
		//css+="</style>";
		$p.append('cssText',css);
	}
};

VDCS.TMenu=function(opt){
	this.opt=ox({},opt);
	if(!isa(this.opt["data"]))this.opt["data"]=new Array();
	this.jm=null;
	this.mClass="TMenu";this.popClass="pop",this.lClass="level";
	
	this.init=function(opt){
		this.opt=ox(this.opt,opt);
		if(!this.opt["cont"])return;
		if(!this.opt["m"]){
			var jcont=$(this.opt["cont"]);
			if(jcont.length){
				jcont.html(this.toMString(this.opt["data"]));
				this.jm=jcont.find("ul[type=tmenu]:first");
			}
		}
		else{
			this.jm=$(this.opt["m"]);
		}
		//alert(this.jm.html());
		if(!this.jm.length)return;
		
		dcs.TMenu.initStyle();
		this.jm.addClass(this.mClass);
		var that=this;
		this.jm.find("li[data-level=1]").each(function(){
			var jdl=$(this).find("dl:first");
			if(jdl.length){
				var _id=$(this).attr("data-id");
				$(this).find("a:first").attr("href","javascript:;")
					.click(function(){
						return that.doClick(_id)
					});
				jdl.addClass(that.lClass).hide();
			}
		});
	};
	
	this.doClick=function(id){
		var idr=this.getValueID(id,true);
		if(!id || !idr) return;
		if(this.oid) this.jm.find("dd[data-id="+this.oid+"]").removeClass("pop");
		this.jm.find("dd[data-id="+id+"]").addClass("pop");
		this.oid=id;
		if(this.oidr) this.jm.find("dd[data-id="+this.oidr+"]").removeClass("pop");
		this.jm.find("dd[data-id="+idr+"]").addClass("pop");
		if(this.oidr==idr) idr="";
		this.oidr=idr;
		var that=this;
		this.jm.find("li[data-level=1]").each(function(){
			var jdl=$(this).find("dl:first");
			if(jdl.length>0){
				var _id=$(this).attr("data-id");
				if(_id==idr){
					$(this).addClass(that.popClass);
					jdl.show();
				}
				else{
					$(this).removeClass(that.popClass);
					jdl.hide();
				}
			}
		});
		return false
	}
	this.getValueID=function(id,root,ary){
		var re="";
		ary=ary||this.opt["data"];
		if(isa(ary) && id){
			var _rootid,_level;
			for(var a=0;a<ary.length;a++){
				if(!ary[a]) continue;
				if(ary[a]["id"]==id){
					re=ary[a]["id"];
					_rootid=ary[a]["rootid"];
					_level=ary[a]["level"];
					break;
				}
			}
			if(root && _level!="1"){
				re="";
				for(var a=0;a<ary.length;a++){
					if(!ary[a]) continue;
					if(ary[a]["rootid"]==_rootid && ary[a]["level"]=="1"){
						re=ary[a]["id"];
						break;
					}
				}
			}
		}
		return re;
	}
	
	this.toMString=function(ary){
		var re="";
		ary=ary||this.opt["data"];
		if(isa(ary)){
			re+="\n<ul type=\"tmenu\">";
			var _level,_level0=-1;
			for(var a=0;a<ary.length;a++){
				if(!ary[a]) continue;
				var oi=ary[a];
				_level=toInt(oi["level"]);
				var _pretab=this.toTabString(_level-1);
				if(!oi["url"])oi["url"]="javascript:;";
				if(_level0>-1){
					if(_level<_level0){
						re+="\n"+this.toTabString(_level0-1)+"</dl>";
						re+="\n</li>";
					}
					//re+="</li>";
					if(_level>_level0){
						re+="\n"+_pretab+"<dl data-id=\""+oi["id"]+"\" data-level=\""+_level+"\" style=\"display:none;\">";
					}
				}
				if(_level>1){
					re+="\n"+_pretab+"<dd class=\"level"+_level+"\" data-id=\""+oi["id"]+"\" data-level=\""+_level+"\"><a href=\""+oi["url"]+"\"><span>"+oi["title"]+"</span></a></dd>";
				}
				else{
					re+="\n"+_pretab+"<li data-id=\""+oi["id"]+"\" data-level=\""+_level+"\"><a href=\""+oi["url"]+"\"><span>"+oi["title"]+"</span></a>";
				}
				_level0=_level;
			}
			if(_level0>-1){
				for(var l=1;l<_level0;l++){
					re+="\n"+this.toTabString(l)+"</dl>";
					re+="</li>";
				}
				//re+="\n</li>";
			}
			re+="\n</ul>";
		}
		//alert(re)
		return re;
	};
	this.toTabString=function(n){var re="";for(var l=0;l<n;l++){re+="	";};return re;};
};
