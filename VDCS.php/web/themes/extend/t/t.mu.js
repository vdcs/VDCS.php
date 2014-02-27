
//########################################
//########################################
appt.mu={
	follower:function(){
		this.jwrap=$(".icardu");
		//alert(this.jwrap.attr("uid"));
		this.uid=toi(this.jwrap.attr("uid"));
		this.follow=this.jwrap.attrd("follow");
		this.jfollow=this.jwrap.find('[data-follow]');
		if(this.uid==ua.id){
			this.jfollow.hide();
			return
		}
		this.followShower();
		var that=this;
		this.jfollow.click(function(){
			that.followClick(that.uid,that.follow);
		});
	},
	followShower:function(){
		//alert(this.follow);
		this.jwrap.attrd('follow',this.follow);
		this.jfollow.attrd('follow',this.follow);
		var that=this;
		this.jfollow.initer(function(jo){
			if(!jo.attrd('title')) jo.attrd('title',jo.text());
		});
		var _pop=this.jfollow.attrd('class-pop')||'pop';
		if(this.follow==1){
			this.jfollow.find('span').html(this.jfollow.attrd('titled'));
			this.jfollow.addClass(_pop);
		}
		else{
			this.jfollow.find('span').html(this.jfollow.attrd('title'));
			this.jfollow.removeClass(_pop);
		}
	},
	followClick:function(uid,action){
		if(appt.followLock()) return;
		var that=this;
		appt.followAction(uid,action,function(uid,follow){that.followParser(uid,follow)});
	},
	followParser:function(uid,follow){
		this.follow=follow;
		this.followShower();
	},
	
"":""};
/*

	followTags:function(tagid,action,cnext){
		if(this.follow_tagid) return;this.follow_tagid=tagid;
		//alert(action);
		var that=this;
		var _url=$url.link(appt.SrvURL,"p=tags&m=follow&x=x&action="+action+"&tagid="+tagid);
		$ajax({url:_url,value:"xml",ready:function(xml){that.followTagsAsync(xml,tagid,action,cnext)}});
	},
	followTagsAsync:function(xml,tagid,action,cnext){
		//alert(xml);
		var _names=action=="cancel"?"取消订阅":"订阅";
		this.maps=$util.toMapByXML(xml);
		this.treeVar=this.maps.getItemTree("var");
		//alert(this.treeVar.v("status"));
		switch(this.treeVar.v("status")){
			case "succeed":
				this.follow=action=="cancel"?0:1;
				if(cnext) cnext();
				break;
			case "already":
				$xtip.popups("info","已经关注过了！",true);
				break;
			case "not":
				$xtip.popups("info","还没关注过哦！",true);
				break;
			case "failed":
				$xtip.popups("error",_names+"失败 :(",true);
				break;
			default:
				var message=this.treeVar.v("message.string");
				if(!message) alert(xml);//message="[unknown]";
				else $xtip.popups("fail",message,true);
				break;
		}
		this.follow_tagid=null;
	},
*/
