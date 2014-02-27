
var searchbar={channel:"",unit:"",
	
	init:function(ps){
		this.ps=ox({isload:true},ps);
		this.ps=ps||{};
		this.initStyle();
		if(this.ps["unit"]) this.initUnit(this.ps["unit"]);
		
		if(ps.isload){
			var __othis=this;
			$(function(){
				__othis.load();
			});
		}
	},
	
	initStyle:function(){
		$p.append("cssText",this.getCSS(this.ps.style,this.ps.path));
	},
	initUnit:function(unit_){
		this.unit=unit_||this.unit;
		this.ounit=$("#"+this.unit);
		this.obar=this.ounit.find(".SearchBar");
		this.otab=this.ounit.find(".SearchTab");
		this.ounit.css("margin-left",(Math.abs(this.ounit.width()+(parseInt(this.ounit.css("margin-left"))||0)-this.obar.width())/2));
		if(this.otab){
			this.otaba=this.otab.find(".items").find("a");
			var __othis=this;
			this.otaba.click(function(){
				__othis.selectChannel($(this).attr("_channel"));
			});
		}
	},
	
	load:function(){
		if(!this.channel) this.selectChannel("_no1");
	},
	
	getChannel:function(){return this.channel},
	setChannel:function(chn_){
		this.channel=chn_;
		this.setForm("channel",this.channel);
	},
	
	getForm:function(k){return this.obar.find("input[name='"+k+"']").attr("value")||"";},
	setForm:function(k,v){this.obar.find("input[name='"+k+"']").attr("value",v);},
	
	selectChannel:function(chn_){
		var __othis=this;
		this.otaba.each(function(i){
			if($(this).attr("_channel")==chn_||chn_==("_no"+(i+1))){
				$(this).addClass("pop");
				__othis.setChannel($(this).attr("_channel"));
				if(__othis.selectCallback) __othis.selectCallback(chn_);
			}
			else $(this).removeClass("pop");
		});
	},
	selectBind:function(callback){
		this.selectCallback=callback;
	},
	
	getCSS:function(bstyle,bpath){
		var _style=bstyle?("_"+bstyle):"";
		if(!bpath) bpath=$c.getURL("images")+"common/struct/";
		var _css=".SearchBar,\
.SearchBar .keyword,\
.SearchBar .btn,\
.SearchBar .btns{background:url('"+bpath+"searchbar"+_style+".gif') no-repeat;}\
.SearchBar{clear:both;width:500px;height:36px;background-position:-85px -36px;padding-left:3px;overflow:hidden;}\
.SearchBar input{border:0;font-size:16px;}\
.SearchBar .keyword{float:left;width:402px;background-position:0 0;background-repeat:repeat-x;height:22px;line-height:22px;padding:7px 5px;}\
.SearchBar .btn,\
.SearchBar .btns{float:left;width:85px;height:36px;background-position:0 -72px;text-indent:-9999px;cursor:pointer;}\
.SearchBar .btn{background-position:0 -36px;text-indent:5px;color:#FFF;font-weight:bold;}\
.SearchTab{clear:both;width:490px;margin-left:10px;overflow:hidden;}\
.SearchTab .items{height:25px;overflow:hidden;}\
.SearchTab .items a,\
.SearchTab .items a span{float:left;display:block;height:25px;line-height:25px;background:url('"+bpath+"searchbar"+_style+".gif') 0 -110px no-repeat;}\
.SearchTab .items a{background-position:0 -110px;padding-left:3px;margin-right:5px;text-decoration:none;}\
.SearchTab .items a span{background-position:100% -140px;padding-left:5px;padding-right:8px;cursor:pointer;}\
.SearchTab .items a:hover{background-position:0 -170px;color:#FFF;text-decoration:none;}\
.SearchTab .items a:hover span{background-position:100% -200px;}\
.SearchTab .items a.pop{background-position:0 -230px;color:#FFF;}\
.SearchTab .items a.pop span{background-position:100% -260px;}\
.SearchTabb .items a{background-position:0 -295px;}\
.SearchTabb .items a span{background-position:100% -325px;}\
.SearchTabb .items a:hover{background-position:0 -355px;}\
.SearchTabb .items a:hover span{background-position:100% -385px;}\
.SearchTabb .items a.pop{background-position:0 -415px;}\
.SearchTabb .items a.pop span{background-position:100% -445px;}";
		return _css;
	},

"":""};

/*
<div id="SearchBar-unit" style="margin:15px 0 15px 100px;">
<div class="SearchTab SearchTabt">
<div class="items"><a 
	href="javascript:;" _channel="article"><span>文章</span></a><a 
	href="javascript:;" _channel="news"><span>新闻</span></a><a 
	href="javascript:;" _channel="shop"><span>商城</span></a><a 
	href="javascript:;" _channel="forum"><span>论坛</span></a></div>
</div>
<div class="SearchBar">
<form name="fs1" action="" method="get">
<input class="keyword" type="text" name="keyword" value="" size="40" maxlength="100" />
<input class="btns" type="submit" value="搜索" />
</form>
</div>
</div>
*/
