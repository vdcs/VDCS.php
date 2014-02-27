/*
Version:	VDCS.vform
Uodated:	2013-12-00
*/

/* ************************************* */
var VCheck=VDCS.VCheck={
	MSG_INPUT		:"请输入",
	MSG_NAME		:"名称的格式不对",
	MSG_PASSWORD		:"密码的格式不对",
	MSG_PASSWORD2		:"两次输入的密码不一致",
	MSG_MOBILE		:"手机号码的格式不对(支持11位数字的手机号码)",
	MSG_EMAIL		:"请输入有效的邮箱地址",
	MSG_MAX20		:"信息过长，请输入不超过50个字符",
	MSG_MAX50		:"信息过长，请输入不超过50个字符",
	MSG_MAX250		:"信息过长，请输入不超过250个字符",
	MSG_AGREEMENT		:"您没有同意服务协议",
	
	trim:function(str){
		var rule = /^\s+|\s+$/g;
		return str.replace(rule, "");
	},
	
	//检查是否字母
	isAlpha:function(str){
		var rule = /^[A-z]+$/;
		return rule.test(str);
	},
	
	//检查是否字母或数字
	isAlphaNumeric:function(str){
		var rule = /^[0123456789A-z]+$/;
		return rule.test(str);
	},
	
	//检查是否字母或数字或中划线下划线
	isAlphaDash:function(str){
		var rule = /^[-_0123456789A-z]+$/;
		return rule.test(str);
	},
	
	//检查是否帐号名
	isAccount:function(str){
		if(str.indexOf(" ")>0||str.indexOf("	")>0)return false;
		var rule = /^[A-z]+$/;
		if(!rule.test(str.substring(0,1)))return false;
		rule = /^[-_0123456789A-z]+$/;
		return rule.test(str);
	},
	
	//检查是否用户名
	isName:function(str){
		if(str.indexOf(" ")>0||str.indexOf("	")>0)return false;
		var rule = /^[-_.]+$/;
		if(rule.test(str.substring(0,1)))return false;
		rule = /^[\?$%#\*@&=\'\"<>()\[\]{}~^/,;\!|]+$/;
		return !rule.test(str);
	},
	
	//检查EMAIL
	isEmail:function(str){
		if(str.indexOf(" ")>0||str.indexOf("	")>0)return false;
		var rule = /\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
		return rule.test(str);
	},
	
	//检查是否手机号码
	isMobile:function(str){
		var rule = /^1\d{10}$/;
		return rule.test(str);
	},
	
	//检查是否密码
	isPassword:function(str){
		var rule = /^[.-_0123456789A-z]+$/;
		return rule.test(str);
	},
	
	//检查是否数字
	isInt:function(str){
		var rule = /^[0-9]+$/;
		return rule.test(str);
	},
	//检查是否数值
	isNum:function(str){
		var rule = /^-?[0-9]+\.?[0-9]*$/;
		return rule.test(str);
	},
	
	// 检查是否小于
	isLess:function(str, num){
		return str.length < num;
	},
	// 检查是否大于
	isMore:function(str, num){
		return str.length > num;
	},
	
	is:function(type,str,v1){
		if(!str)return false;
		var re=true;
		switch(type){
			case "account":		re=this.isAccount(str);break;
			case "name":		re=this.isName(str);break;
			case "email":		re=this.isEmail(str);break;
			case "mobile":		re=this.isMobile(str);break;
			case "password":	re=this.isPassword(str);break;
			case "int":		re=this.isInt(str);break;
			case "num":		re=this.isNum(str);break;
		}
		return re;
	},
	
	resete:function(){
		this.elefocus=false;
	},
	elementv:function(jfield,opt){
		opt=ox({mode:'ihint'},opt);	//before
		var re=true,jih=null;
		var _name=jfield.attr("name");
		var _value=jfield.val();
		var _vmin=toInt(jfield.attr("vmin")?jfield.attr("vmin"):(jfield.attr("min")?jfield.attr("min"):jfield.attr("minlength")));
		var _vmax=toInt(jfield.attr("vmax")?jfield.attr("vmax"):(jfield.attr("max")?jfield.attr("max"):jfield.attr("maxlength")));
		var _vcheck=jfield.attr("vcheck")?jfield.attr("vcheck"):jfield.attr("type");
		//dbg.t(_name+" = "+_vmin+"-"+_vmax+", "+_vcheck);
		if(_vmin || _vmax){
			switch(opt.mode){
				case 'before':
					if(!jfield.attr("_bgcolor_")) jfield.attr("_bgcolor_",jfield.css("background-color"));
					if(!jfield.attr("_bdcolor_")) jfield.attr("_bdcolor_",jfield.css("border-color"));
					break;
				case 'ihint':
					jih=jfield.parent().find('.ih');
					break;
			}
			if((_vmin && _value.length<1) || (_vmax && _value.length>_vmax))re=false;
			if(re&&_vmin&&_vcheck)re=this.is(_vcheck,_value);
			if(!re){
				if(!this.elefocus){
					jfield.focus();
					this.elefocus=true;
				}
				if(!jfield.attr("_e_blur_")){
					jfield.blur(function(){
						VCheck.elementv(jfield);
					});
					jfield.attr("_e_blur_","yes");
				}
				switch(opt.mode){
					case 'before':
						jfield.css("background-color","#F0FFFF");
						jfield.css("border-color","#FF6633");
						break;
					case 'ihint':
						jih.removeClass().addClass('ih').addClass('error');
						break;
				}
			}
			else{
				switch(opt.mode){
					case 'before':
						jfield.css("background-color",jfield.attr("_bgcolor_"));
						jfield.css("border-color",jfield.attr("_bdcolor_"));
						break;
					case 'ihint':
						jih.removeClass().addClass('ih');
						break;
				}
			}
		}
		return re;
	},
"":""};


VCheck.pwd={putCSS:false,
	snid:function(){
		this._snid=this._snid||0;
		this._snid++;
	},
	isPassword:function(s){
		if(s.length<this.lenMin) return false;
		for(var i=0;i<s.length;i++){
			if(s.charCodeAt(i)>255) return false;
		}
		return true;
	},
	toCheckStrong:function(s){			//返回密码的强度级别
		var m=0;
		for (i=0;i<s.length;i++){
			m|=this.toCharMode(s.charCodeAt(i));	//测试每一个字符的类别并统计一共有多少种模式.
		}
		return this.toBitTotal(m);
	},
	toCharMode:function(s){
		if(s>=48 && s<=57) return 1;		//数字
		if(s>=65 && s<=90) return 2;		//大写字母
		if(s>=97 && s<=122) return 4;		//小写
		else return 8;				//特殊字符
	},
	toBitTotal:function(s){				//计算出当前密码当中一共有多少种模式
		var m=0;
		for(var i=0;i<4;i++){
			if(s & 1) m++;
			s>>>=1;
		}
		return m;
	}
}


/* ************************************* */
$v.PWD={
	promptTitle:"密码安全度:",
	promptValue:["为空","太短","较弱","一般","很强"]		//["还没填哪","太短了呢","比较弱哦","一般般啦","很强了耶"]
};

VDCS.libPWD=function(){
	this._elPrefix="__pwd_"+VCheck.pwd.snid();
	this.lenMin=4;
	this.promptTitle=$v.PWD.promptTitle;this.promptValue=$v.PWD.promptValue;
	this.setLenMin=function(s){
		if(isInt(s)) this.lenMin=s;
	};
	this.initDynamic=function(o,ot){
		o=too(o);
		var that=this;
		//if(!o.onkeyup) Object.bindEvent(o,"onkeyup",this._name+".doDynamicCheck($o(o.id))");
		if(!o.onkeyup) o.onkeyup=function(){
			that.doDynamicCheck($o(o.id));
		};
		var s="";
		if(!VCheck.pwd.putCSS){
			var _css="";
			//_css+="<style type=\"text/css\">";	
			_css+=".pwd_div{width:200px;color:#888888;}";
			_css+=".pwd_div .pwd_depict{line-height:180%;}";
			_css+=".pwd_div .pwd_depict span{padding-left:10px;}";
			_css+=".pwd_div .pwd_bar{height:10px;background-color:#E0E0E0;}";
			_css+=".pwd_div .pwd_barInner{float:left;height:10px;}";
			_css+=".pwd_div0 .pwd_depict span{display:none;}";
			_css+=".pwd_div1 .pwd_depict span{display:inline;color:#F00;}";
			_css+=".pwd_div2 .pwd_depict span{display:inline;color:#C48002;}";
			_css+=".pwd_div3 .pwd_depict span{display:inline;color:#2CA4DE;}";
			_css+=".pwd_div4 .pwd_depict span{display:inline;color:#009933;}";
			_css+=".pwd_div0 .pwd_barInner{width:0;}";
			_css+=".pwd_div1 .pwd_barInner{width:25%;background-color:#F00;}";
			_css+=".pwd_div2 .pwd_barInner{width:50%;background-color:#F90;}";
			_css+=".pwd_div3 .pwd_barInner{width:75%;background-color:#2CA4DE;}";
			_css+=".pwd_div4 .pwd_barInner{width:100%;background-color:#009933;}";
			//_css+="</style>";
			$p.append("cssText",_css);
			VCheck.pwd.putCSS=true;
		}
		s+="<div class=\"pwd_div pwd_div0\" id=\""+this._elPrefix+"_check\">";
		s+="<div class=\"pwd_depict\">"+this.promptTitle+"<span id=\""+this._elPrefix+"_checkvalue\"></span></div>";
		s+="<div class=\"pwd_bar\"><div class=\"pwd_barInner\"></div></div>";
		s+="</div>";
		if(iso(ot)) ot.html(s);
		else put(s);
	};
	this.doDynamicCheck=function(o){
		var s=iso(o)?o.value:o;
		if(!s){
			$o(this._elPrefix+"_check").className="pwd_div pwd_div0";
			$o(this._elPrefix+"_checkvalue").innerHTML=this.promptValue[0];
		}
		else if(s.length<this.lenMin){
			$o(this._elPrefix+"_check").className="pwd_div pwd_div1";
			$o(this._elPrefix+"_checkvalue").innerHTML=this.promptValue[1];
		}
		else if(!VCheck.pwd.isPassword(s) || !/^[^%&]*$/.test(s)){
			$o(this._elPrefix+"_check").className="pwd_div pwd_div0";
			$o(this._elPrefix+"_checkvalue").innerHTML=this.promptValue[0];
		}
		else{
			var sInt=VCheck.pwd.toCheckStrong(s);
			switch(sInt){
				case 1:
					$o(this._elPrefix+"_checkvalue").innerHTML=this.promptValue[2];
					$o(this._elPrefix+"_check").className="pwd_div pwd_div"+(sInt+1);
					break;
				case 2:
					$o(this._elPrefix+"_checkvalue").innerHTML=this.promptValue[3];
					$o(this._elPrefix+"_check").className="pwd_div pwd_div"+(sInt+1);
					break;
				case 3:
					$o(this._elPrefix+"_checkvalue").innerHTML=this.promptValue[4];
					$o(this._elPrefix+"_check").className="pwd_div pwd_div"+(sInt+1);
					break;
			}
		}
	};
}




/* ************************************* */
$lang["vcode"]={};
$lang["vcode"]["title"]="点击输入框显示随机验证码图案";
$lang["vcode"]["explain"]="请输入随机验证码 <a href=\"javascript:;\" onclick=\"javascript:return $vcode.doRefresh();\">点击刷新</a>",
$lang["vcode"]["img-alt"]="点击刷新验证码";

$vcode=dcs.vcode=$app.vcode=$app.verifycode={
	_module:"",
	_mode:"auto",
	_id:"_vcode_img",
	_field:"_vcode_img",
	_file_default:"xvcodei.{ext}",
	_file:"",
	getImagesName:function(){ return "img_"+this._field; },
	getInputName:function(){ return this._field; },
	isReal:function(){ return $f.v($o(this._id))?true:false; },
	doFocus:function(){ return $f.o($o(this._id)).focus(); },
	setModule:function(strer){ this._module=strer; },
	setMode:function(strer){ this._mode=strer; },
	setFile:function(strer){ this._file=strer; },
	getFile:function(res){
		var re=this._file;
		if(!res) res="png";
		if(res=='audio') res='mp3';
		if(re==""){
			re=$c.v("url.vcodei")||$c.getURL("common")+this._file_default;
			this._file=re;
		}
		re=rv(re,'ext',res);
		if(ins(re,"{$module}")<1) re=$url.link(re,"module={$module}");
		return re;
	},
	getURL:function(module,res){
		var re=this.getFile(res);
		module=module||this._module;
		re=rd(re,"module",module)
		return re;
	},
	bind:function(input,support){
		var that=this;
		if(input){
			var _click=function(){
				that.doImagesShow()
			};
			this.jinput=$j(input);
			this.jinput.attr('name',this._field);
			this.jinput.focus(_click).click(_click);
		}
		if(support){
			this.jsupport=$j(support);
			this.jsupport.html(this.getImages(null,'auto'));
			this.jsupport.find('#_vcode_clew').html('');
		}
	},
	getImages:function(module,t){
		if(!isn(module)) this._module=module;
		var re="";
		if(t=="auto"){
			re="<span id=\"_vcode_clew\" _status=\"\">"+$lang["vcode"]["title"]+"</span>";
			re+="<span id=\"_vcode_img\" style=\"display:none;\"><img class=\"icon hand\" id=\""+this.getImagesName()+"\" src=\""+$c.getURL("images")+"common/load/load.gif\" onclick=\"javascript:$vcode.doRefresh()\" /> "+this.getExplain()+"</span>";
		}
		else{
			var url=this.getURL(this._module);
			re="<img class=\"icon hand\" id=\""+this.getImagesName()+"\" src=\""+url+"\" onclick=\"javascript:$vcode.doRefresh()\" title=\""+$lang["vcode"]["img-title"]+"\" />";
		}
		return re;
	},
	getExplain:function(){
		var re=$lang["vcode"]["explain"];
		if($c.EXT=="php"){
			var urlAudio=this.getURL(this._module,"audio");
			var urlPlay=$c.getURL("images")+'common/struct/vcode/audio.swf?audio='+$url.toEncode(urlAudio)+'&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#000';	//&amp;roundedCorner=5&amp;borderWidth=1&amp;borderColor=#000
			re="";
			re+='<span style="width:50px;height:20px;display:inline-block;margin-left:6px;overflow:hidden;">';
			re+='<span style="float:left;display:inline-block;height:20px;">';
			re+='<object id="si_player" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="20" height="20">';
			re+='<param name="allowScriptAccess" value="sameDomain" />';
			re+='<param name="allowFullScreen" value="false" />';
			re+='<param name="movie" value="'+urlPlay+'" />';
			re+='<param name="quality" value="high" />';
			re+='<param name="bgcolor" value="#ffffff" />';
			re+='<embed src="'+urlPlay+'" bgcolor="#ffffff" quality="high" width="20" height="20" name="si_player" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />';
			re+='</object>';
			re+='</span>';
			re+='<a tabindex="-1" href="javascript:;" onclick="javascript:this.blur();return $vcode.doRefresh();"';
			re+=' style="float:left;margin-left:6px;margin-top:0!important; align:top; display:inline-block;width:22px;height:20px;background:url(\''+$c.getURL("images")+'common/struct/vcode/refresh.gif\') 0 0 no-repeat;"></a>';//<img class="icon" src="'+$c.getURL("images")+'common/securimg/refresh.gif" title="'+$lang["vcode"]["img-alt"]+'" onclick="this.blur()" align="top" border="0" /></a>';
			re+='</span>';
		}
		return re;
	},
	getInputs:function(strField,strStyle){
		if(ise(strField)) strField=this._field;
		if(ise(strStyle)) strStyle="";
		return "<input type=\"text\" class=\"txt\" id=\""+this._id+"\" name=\""+strField+"\" size=\"8\" maxlength=\"8\" "+strStyle+" onclick=\"$vcode.doImagesShow()\" />";
	},
	getInput:function(strField,strStyle){
		if(ise(strField)) strField=this._field;
		if(ise(strStyle)) strStyle="";
		var re="";
		if(strStyle=="hidden") re="<input type=\"hidden\" name=\""+strField+"\" />";
		else re="<input type=\"text\" class=\"txt\" name=\""+strField+"\" size=\"8\" maxlength=\"8\" "+strStyle+" />";
		return re;
	},
	doImagesShow:function(){
		if($j("#_vcode_img").is(":visible")) return;
		if(this.jsupport) this.jsupport.show();
		$j("#_vcode_clew").hide();
		$j("#_vcode_img").show();
		$j('#'+this.getImagesName()).attr('src',this.getURL(this._module));
	},
	doRefresh:function(){
		var jimg=$j('#'+this.getImagesName());
		var url=jimg.attr('src');
		if(url.indexOf("&r=")>0) url=url.split("&r=")[0];
		url+="&r="+Math.random();
		jimg.attr('src',url);
		return false;
	}
};
