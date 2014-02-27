appt.comm={
	module:'follow',
	initer:function(selector,ps){
		var __othis=this;
		selector=selector||'.fribody';//获取选择器名
		this.jbox=$(selector);
		this.ugcard_tpl=$('.gclayer');//gccard的模板
		//this.jfollowsul=this.jbox.find('ul');//获取ul
		this.friendmenu=this.jbox.find('.friend_menu');
		this.search=this.friendmenu.find('[el=search_person]');//搜索
		this.friendmenuPop();
		this.jbody=this.jbox.find('.fri_main');//从导航开始
		this.friendsnav=this.jbody.find('.tags_nav');//导航栏
		this.jitems=this.jbody.find('.fri_items');//好友信息部分
		
		this.checkall_btn=this.friendsnav.find('.checkall_btn');//全选按钮
		this.doact=this.friendsnav.find('.do_act');//操作按钮
		
		this.loader(oapd({page:1},ps));//加载
		this.checkall_btn.click(function(){
			__othis.chosenAll();
		});
		this.doact.click(function(){
			__othis.doActAll();
		});
		this.search.submit(function(event){event.preventDefault();__othis.searchPerson(__othis.module,ps);});
		
		appt.quicknav.searchAction();
	},
	loader:function(ps){
		this.checkall_btn.attr('_is','');
		this.chosenIds=[];
		if(this.isloader) return;this.isloader=true;
		this.dataLoad();//加载loading...
		var __othis=this;
		
		var _url=$url.link(appt.ASrvURL,"p=contacts&m="+this.module+"&x=x&action=list&page="+ps.page+'&listnum=30');
		//dbg.t(_url);
		$ajax({url:_url,value:"xml",ready:function(o){__othis.parser(o,ps)},error:true});//获取值
	},
	dataLoad:function(){
		this.dataReset();
		$('<span class="loading">loading..</span>').appendTo(this.jfollowsul);//显示正在加载
	},
	dataReset:function(){//清空jfollows中的所有数据
		this.jitems.html('');
	},
	parser:function(xml,ps){
		//dbg.t(this.module);
		var __othis=this;
		var maps=$util.toMapByXML(xml);
		//alert(maps);
		var treeVar=maps.getItemTree('var');
								
		var tableItem=maps.getItemTable('item');
		if(tableItem.row()<1){//如果没有数据则显示暂无数据，并结束
			this.dataNo();
			$('.ipaging').empty();
			
			this.isloader=false;
			return;
		}
		this.dataReset();//清空一切，重新显示相关内容
		
		//开始遍历
		tableItem.begin();
		this.total_data=tableItem.row();
		for(var i=1;i<=tableItem.row();i++){
			var treeItem=tableItem.getItemTree();//转为tree格式，进行遍历
			treeItem.addItem("sn",i);
			treeItem.addItem("oe",(i-1)%3+1);
			var each1=treeItem.v("eacho")==0?1:0;//加关注按钮
			treeItem.addItem('each1',each1);
			//dbg.t(treeItem.v('uusign2'));
			var uusingn2=treeItem.v('uusign2');
			if(uusingn2){
				uusingn2="签名："+uusingn2;
				treeItem.add('nuusign2',uusingn2);
				//alert(123);
			}
			var uusingn=treeItem.v('uusign');
			if(uusingn){
				uusingn="签名："+uusingn;
				treeItem.add('nuusign',uusingn);
			}
			//dbg.t(treeItem.getArray());
			if(ps.type=='result'){
				var _html=appt.recentf.toItemString(treeItem);
			}else{
				var _html=this.toItemString(treeItem);//生成html
			}
			//dbg.t(_html);
			this.jitems.append(_html);
			//$('[el=result]').append(_html);
			tableItem.move();
		}
		
		
		this.jitemsmain=this.jitems.find('.fri_block');
		this.juinfo=this.jitemsmain.find('.fri_info h4 em');
		this.juinfo.click(function(){
			__othis.showUserGcard($(this).parents('.fri_block').attr('_uid'));//显示用户的gc
		});
		
		this.jitemsmain.find('cite').click(function(){
			var uid=$(this).parents('.fri_block').attr('_uid');
			__othis.chosenUser(uid);
		});
		
		this.parserBind();
		
		appt.pagingParser(treeVar,".ipaging",function(page){//分页
			__othis.loader({page:page});
		});
		this.isloader=false;
		ps={};
	},
	parserBind:function(){
		var __othis=this;
		this.followbtn=this.jitems.find('.add_friend');
		//alert(this.followbtn);
		this.followbtn.click(function(){
			var uid=$(this).parents('.fri_block').attr('_uid');
			//alert(uid);
			appt.followAction(uid,'create',function(uid,follow){
				var jlinow=__othis.jitems.find("[_uid="+uid+"]");
				jlinow.find(".add_friend").hide();
				jlinow.find(".cancel_friend:last").css('display','inline-block');
			});//关注
		});
		
		this.cancelfollow=this.jitems.find('.cancel_friend');//取消关注
		this.cancelfollow.click(function(){
			var uid=$(this).parents('.fri_block').attr('_uid');
			//alert(uid);
			appt.followAction(uid,'cancel',function(uid,follow){
				var jlinow=__othis.jitems.find("[_uid="+uid+"]");
				jlinow.find(".add_friend").css('display','inline-block');
				jlinow.find(".cancel_friend").hide();
			});//取消关注
		});
	},
	friendmenuPop:function(){
		this.friendmenu.find('.fri_mem').addClass('fcur');
		this.friendmenu.find('.fri_mem a').addClass('pop');
	},
	dataNo:function(){
		this.dataReset();//清空
		$('<span class="nodata">暂无</span>').appendTo(this.jfollowsul);
	},
	searchPerson:function(type,ps){
		this.friendsnav.find('.pop').removeClass('pop');
		if(this.isloader) return;this.isloader=true;
		this.dataLoad();//加载loading...
		var __othis=this;
		var uname=this.search.find('input[name=keywords]').val();
		var _url=$url.link(appt.ASrvURL,"p=contacts&m=find&x=x&action=searchPerson&listnum=30&uname="+uname);
		var ps={};
		ps.type='result';
		$ajax({url:_url,value:"xml",ready:function(o){__othis.parser(o,ps)},error:true});//获取值
	},
	showUserGcard:function(uid){
		if(this.ugcard_tpl && this.ugcard_tpl.attr('uid')==uid){
			this.ugcard_tpl.attr('uid','');
			this.ugcard_tpl.hide();
			return;
		}
		this.gcard_item=this.jitems.find('[_uid='+uid+']');
		var wei=parseInt(this.gcard_item.attr('_sn'));
		wei=Math.ceil(wei/3)*3;
		if(wei>this.total_data) wei=this.total_data;
		var oe=parseInt(this.gcard_item.attr('_oe'));
		var lbtn_wei=((oe-1)*30+20)+'%';
		var u_name=this.gcard_item.find('[el=name]').text();
		var u_total_post=this.gcard_item.find('[el=total_post]').text();
		var u_total_follow=this.gcard_item.find('[el=total_follow]').text();
		var u_total_fans=this.gcard_item.find('[el=total_fans]').text();
		var u_sign=this.gcard_item.find('[el=u_sign]').text();
		this.ugcard_tpl.attr('uid',uid);
		var _ulink=rd(appt.uu_link,"uid",uid);//"/u/{$id}"  uid  [item:uuid]======/u/10002  怎么个解析法？
		var _uavatar=rd(appt.uu_avatarb,"uid",uid);
		this.ugcard_tpl.find('.username em').text(u_name);
		this.ugcard_tpl.find('.username').next('p').text(u_sign);
		this.ugcard_tpl.find('dt a').attr('href',_ulink);
		this.ugcard_tpl.find('dt a img').attr('src',_uavatar);
		this.ugcard_tpl.find('.icon_gz').parent('span').find('em').text(u_total_follow);
		this.ugcard_tpl.find('.icon_fs').parent('span').find('em').text(u_total_fans);
		this.ugcard_tpl.find('.icon_wz').parent('span').find('em').text(u_total_fans);
		this.ugcard_tpl.css('display','block');
		this.ugcard_tpl.find('.gc_arror').css({
			left:lbtn_wei
		});
		this.jitems.find('[_sn='+wei+']').after(this.ugcard_tpl);
		appt.gcard.initer(this.ugcard_tpl);
	},
	chosenAll:function(){
		var __othis=this;
		if(this.checkall_btn.attr('_is')){
			this.jitemsmain.each(function(){
				$(this).attr('_ischosen','');
				$(this).find('cite i').css('display','none');
				$(this).removeClass('block_cur');
			});
			this.chosenIds=[];
			this.checkall_btn.attr('_is','');
		}else{
			this.chosenIds=[];
			this.jitemsmain.each(function(){
				__othis.chosenIds.push($(this).attr('_uid'));
				$(this).attr('_ischosen','yes');
				$(this).find('cite i').css('display','inline-block');
				$(this).addClass('block_cur');
			});
			this.checkall_btn.attr('_is','yes');
		}
	},
	doActAll:function(){
		if(this.chosenIds.length<1){
			$xtip.popups('info','请选择需要取消关注的用户',0,2);
			return;
		}
		var __othis=this;
		var ps={};
		ps.title='系统提示';
		ps.body='您确定要取消关注吗？';
		ps.btn_submit='确定';
		ps.btn_close='取消';
		ps.callback=function(){
			//alert(__othis.chosenIds);
			var _url=$url.link(appt.ASrvURL,"p=contacts&m="+__othis.module+"&x=x&action=cancels&uids="+__othis.chosenIds);
			//dbg.t(_url);
			$ajax({url:_url,value:"xml",ready:function(o){__othis.delUsers(o)},error:true});
			$xbox.dialogClose();
		}
		//$xbox.load(ps);
		appt.sysTipsBox.init(ps);	
	},
	chosenUser:function(uid){
		var chosenUserItem=this.jitems.find('[_uid='+uid+']');
		if(!chosenUserItem.attr('_ischosen')){
			chosenUserItem.attr('_ischosen','yes');	
			chosenUserItem.addClass('block_cur');
			chosenUserItem.find('cite i').css('display','inline-block');
			this.chosenIds.push(uid);
		}else{
			chosenUserItem.attr('_ischosen','');
			chosenUserItem.removeClass('block_cur');
			chosenUserItem.find('cite i').css('display','none');
			var ids=[];
			for(var i=0;i<this.chosenIds.length;i++){
				if(this.chosenIds[i]!=uid){
					ids.push(this.chosenIds[i]);
				}
			}
			this.chosenIds=ids;
		}
	},
	delUsers:function(xml){
		var map=$util.toMapByXML(xml);
		var treeVar=map.getItemTree('var');
		if(treeVar.v('status')=='succeed'){
			$xtip.popups('succeed','取消关注成功！',0,2);
			this.loader(oapd({page:1}));
		}else{
			$xtip.popups('error','取消关注错误！',0,2);
			this.chosenIds=[];
		}
	},
	
	followUsers:function(xml){
		var map=$util.toMapByXML(xml);
		var treeVar=map.getItemTree('var');
		if(treeVar.v('status')=='succeed'){
			$xtip.popups('succeed','关注成功！',0,2);
			this.checkall_btn.attr('_is','');
			for(var i=0;i<this.chosenIds.length;i++){
				var uid=this.chosenIds[i];
				var jlinow=this.jitems.find("[_uid="+uid+"]");
				jlinow.attr('_ischosen','');
				jlinow.removeClass('block_cur');
				jlinow.find('cite:first i').css('display','none');
				jlinow.find(".add_friend").hide();
				jlinow.find(".cancel_friend:last").css('display','inline-block');
			}
			this.chosenIds=[];
			this.isSendMsg=0;
		}else{
			$xtip.popups('error','关注错误！',0,2);
			this.chosenIds=[];
		}
	},
'':''};

/*关注*/
appt.follow=$.extend({},appt.comm,{	
	module:'follow',
	toItemString:function(treeItem){
		var _ulink=rd(appt.uu_link,"uid","[item:uuid2]");//"/u/{$id}"  uid  [item:uuid]======/u/10002  怎么个解析法？
		var _uavatar=rd(appt.uu_avatar,"uid","[item:uuid2]");
		var follow_status='';				
		if(!this._item_tpl){
			this._item_tpl=this.jbox.find('[el=_item_tpl]').html();
			if(!this._item_tpl){
				this._item_tpl='<div class="fri_block" _uid="[item:uuid2]" _sn=[item:sn] _oe=[item:oe] _ischosen=""><cite><i></i></cite>\
				<div class="logo"><a href="'+_ulink+'" target="_blank"><img src="'+_uavatar+'" /><span class="avatarbg"></span></a></div>\
				<div class="fri_info">\
					<h4><a href="'+_ulink+'" target="_blank"><span el="name">[item:uuname2]</span></a><em></em></h4>\
					<span class="fbtn cancel_friend hide[item:eacho]"><a><i></i><em>相互关注</em></a></span>\
					<span class="fbtn cancel_friend followed hide[item:each1]"><a><i></i><em>已关注</em></a></span>\
					<span class="fbtn add_friend hide0"><a><i></i><em>加为好友</em></a></span>\
					<span el="total_post" class="hide">[item:total_post2]</span><span el="total_follow" class="hide">[item:total_follow2]</span><span el="total_fans" class="hide">[item:total_fans2]</span><span el="u_sign" class="hide">[item:uusign2]</span>\
				</div></div>';
			}
		}
		var re=this._item_tpl;
		re=dcs.DTML.toReplaceEncodeFilter(re,treeItem,dcs.DTML.PATTERN_LEBEL_ITEMS);      
		return re;
	},
'':''});

/*粉丝*/
appt.fans=$.extend({},appt.comm,{	
	module:'fans',
	toItemString:function(treeItem){
		var _ulink=rd(appt.uu_link,"uid","[item:uuid]");//"/u/{$id}"  uid  [item:uuid]======/u/10002  怎么个解析法？
		var _uavatar=rd(appt.uu_avatar,"uid","[item:uuid]");
		var follow_status='';
		if(!this._item_tpl){
			this._item_tpl=this.jbox.find('[el=_item_tpl]').html();
			if(!this._item_tpl){
				this._item_tpl='<div class="fri_block" _uid="[item:uuid]" _sn=[item:sn] _oe=[item:oe] _ischosen=""><cite><i></i></cite>\
				<div class="logo"><a href="'+_ulink+'" target="_blank"><img src="'+_uavatar+'" /><span class="avatarbg"></span></a></div>\
				<div class="fri_info">\
					<h4><a href="'+_ulink+'" target="_blank"><span el="name">[item:uuname]</span></a><em></em></h4>\
					<span class="fbtn cancel_friend hide[item:eacho]" el="_eacho"><a><i></i><em>相互关注</em></a></span>\
					<span class="fbtn add_friend hide[item:each1]"><a><i></i><em>加为好友</em></a></span>\
					<span el="total_post" class="hide">[item:total_post]</span><span el="total_follow" class="hide">[item:total_follow]</span><span el="total_fans" class="hide">[item:total_fans]</span><span el="u_sign" class="hide">[item:uusign]</span>\
				</div></div>';
			}
		}
		var re=this._item_tpl;
		re=dcs.DTML.toReplaceEncodeFilter(re,treeItem,dcs.DTML.PATTERN_LEBEL_ITEMS);      
		return re;
	},
        
	doActAll:function(){
		var __othis=this;
		if(this.chosenIds.length<1){
			$xtip.popups('info','请选择需要关注的用户',0,2);
			return;
		}
		var _url=$url.link(appt.ASrvURL,"p=contacts&m=follow&x=x&action=creates&uids="+__othis.chosenIds);
		//dbg.t(_url);return;
		if(this.isSendMsg) return;
		this.isSendMsg=1;
		$ajax({url:_url,value:"xml",ready:function(o){__othis.followUsers(o)},error:true});
	},
'':''});

//最近联系
appt.recentf=$.extend({},appt.comm,{	
	module:'recent',
	toItemString:function(treeItem){
		var _ulink=rd(appt.uu_link,"uid","[item:userid]");//"/u/{$id}"  uid  [item:uuid]======/u/10002  怎么个解析法？
		var _uavatar=rd(appt.uu_avatar,"uid","[item:userid]");
		var follow_status='';
		var _act='';
		var is_follow=treeItem.v('follow');
		var is_followby=treeItem.v('follow_by');
		if(is_follow==1 && is_followby==1){
			_act='<span class="fbtn cancel_friend"><a><i></i><em>相互关注</em></a></span><span class="fbtn add_friend hide0"><a><i></i><em>加为好友</em></a></span>';
		}else if(is_follow==1){
			_act='<span class="fbtn followed cancel_friend"><a><i></i><em>已关注</em></a></span><span class="fbtn add_friend hide0"><a><i></i><em>加为好友</em></a></span>';
		}else if(is_follow==0){
			_act='<span class="fbtn add_friend hide[item:each1]"><a><i></i><em>加为好友</em></a></span><span class="fbtn followed cancel_friend" style="display:none;"><a><i></i><em>已关注</em></a></span>';
		}
		var _item_tpl='<div class="fri_block" _uid="[item:userid]" _sn=[item:sn] _oe=[item:oe] _ischosen=""><cite><i></i></cite>\
				<div class="logo"><a href="'+_ulink+'" target="_blank"><img src="'+_uavatar+'" /><span class="avatarbg"></span></a></div>\
				<div class="fri_info">\
					<h4><a href="'+_ulink+'" target="_blank"><span el="name">[item:u_names]</span></a><em></em></h4>\
					'+_act+'\
					<span el="total_post" class="hide">[item:u_total_post]</span><span el="total_follow" class="hide">[item:u_total_follow]</span><span el="total_fans" class="hide">[item:u_total_fans]</span><span el="u_sign" class="hide">[item:u_sign]</span>\
				</div></div>'
		var re=_item_tpl;
		re=dcs.DTML.toReplaceEncodeFilter(re,treeItem,dcs.DTML.PATTERN_LEBEL_ITEMS);      
		return re;
	},//加关注系列操作
	doActAll:function(){
		var __othis=this;
		if(this.chosenIds.length<1){
			$xtip.popups('info','请选择需要关注的用户',0,2);
			return;
		}
		var _url=$url.link(appt.ASrvURL,"p=contacts&m=follow&x=x&action=creates&uids="+__othis.chosenIds);
		//dbg.t(_url);return;
		if(this.isSendMsg) return;
		this.isSendMsg=1;
		$ajax({url:_url,value:"xml",ready:function(o){__othis.followUsers(o)},error:true});
	},
	friendmenuPop:function(){
		this.friendmenu.find('.fri_find').addClass('fcur');
		this.friendmenu.find('.fri_find a').addClass('pop');
	},
'':''});

//草根
appt.findfri=$.extend({},appt.recentf,{	
	module:'find',
'':''});
//趣味相投
appt.relatedfri=$.extend({},appt.recentf,{	
	module:'related',
'':''});
appt.freelookfri=$.extend({},appt.recentf,{	
	module:'freelook',
'':''});
