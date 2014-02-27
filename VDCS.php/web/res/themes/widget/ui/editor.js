
extendo(ui.editor,{isloader:2,
	toolbar:{
		initer:function(mode,opt){
			var that=this;
			this.parent=ui.editor,this.mode=mode;
			this.opt=ox({offsetY:-3},opt);
			//this.jev=jev;
			//this.jtxtbox=jtxtbox;
			//if(this.jem||this.jem.length>0) return;
			this.jtoolbar=this.opt.j||$("[el=toolbar]");
			if(!this.opt.sessionid) this.opt.sessionid=this.jtoolbar.attr('sessionid');
			this.jwinbox=$('[el=toolbar_box]');
			if(!this._init_ele&&this.jwinbox.length<1){
				$(document.body).append(this.getHTML());
				this.jwinbox=$('[el=toolbar_box]');
				this._init_ele=true;
			}
			this.jwinop=this.jwinbox.find('.winop');
			this.jwinop.find('.close').click(function(){that.close();});
			this.jtoolbar.find('.at').click(function(){that.setClick($(this));that.init_at();return false});
			this.jtoolbar.find('.em').click(function(){that.setClick($(this));that.init_em();return false});
			this.jtoolbar.find('.image').mouseenter(function(){that.setClick($(this));that.init_image();return false});
			this.jtoolbar.find('.imagec').mouseenter(function(){that.setClick($(this));that.init_imagec();return false});
			//this.jtoolbar.find('.image').click(function(){that.setClick($(this));that.init_image();return false});
			this.jtoolbar.find('.images').mouseenter(function(){that.setClick($(this));that.init_images();return false});
			//this.jtoolbar.find('.images').click(function(){that.setClick($(this));that.init_images();return false});
			this.jtoolbar.find('.imagem').click(function(){that.setClick($(this));that.init_imagem();return false});
			this.jtoolbar.find('.video').click(function(){that.setClick($(this));that.init_video();return false});
			this.jtoolbar.find('.music').click(function(){that.setClick($(this));that.init_music();return false});
			this.jtoolbar.find('.link').click(function(){that.setClick($(this));that.init_link();return false});
			this.jtoolbar.find('a[cmd]').click(function(){that.setClick($(this));that.parent.contentCommand($(this).attr("cmd"));});//或者通过cmd属性执行同样操作
		},
		close:function(){
			this.winboxClose();
		},
		
		
		winboxJ:function(sort){return this.jwinbox.find('.box_'+sort);},
		winboxShow:function(sort,showclose){
			var ispic=this.checkPic(sort);
			if(ispic) return;
			var _top=this.jclick.offset().top+this.jclick.height()+this.opt.offsetY-$(window).scrollTop();//fixed的top
			var _left=this.jtoolbar.offset().left;
			//_left=this.jtoolbar.find('.'+sort).offset().left;
			_left=this.jclick.offset().left;
			//dbg.t(_top+','+_left);
			_top=this.jclick.offset().top+this.jclick.height()+this.opt.offsetY;
			this.jwinbox.css('position','absolute').css('top',_top).css('left',_left);	
			this.jwinbox.show();
			if(this.winboxsort){
				this.winboxJ(this.winboxsort).hide();
			}
			if(!sort) return null;
			if(showclose) this.jwinop.find('.close').show();
			else this.jwinop.find('.close').hide();
			this.winboxsort=sort;
			var jsort;
			if(this.winboxsort){
				jsort=this.winboxJ(this.winboxsort);
				jsort.show();
			}
			return jsort;
		},
		winboxClose:function(sort){
			if(!this.jwinbox) return;
			this.jwinbox.hide();
			this.winboxJ(this.winboxsort).hide();
			if(this.juploadx) this.juploadx.hide();
		},
		checkPic:function(sort){		//检测是否是上传图片
			if(sort!='image' && sort!='images' && sort!='imagec') return;
			if(sort=='images'){
				this.juploadxs=$uploadx.btnElement('images',null,{top:this.jclick.offset().top,left:this.jclick.offset().left,width:25,height:26,display:'show',sessionid:this.opt.sessionid});
			}else if(sort=='image' || sort=='imagec'){
				//if(this.juploadx) this.juploadx.remove();
				this.juploadx=$uploadx.btnElement('image',null,{top:this.jclick.offset().top,left:this.jclick.offset().left,width:25,height:26,zindex:1011,display:'show',sessionid:this.opt.sessionid});
			}
			return true;
		},
		setClick:function(jthis){this.jclick=jthis;this.jtoolbarc=this.jclick.parents('.toolbar')},
		getTarget:function(){return this.jtoolbarc.attr('_target')||''},
		getFormat:function(){return this.jtoolbarc.attr('_format')||'bb'},
		setValue:function(mode,value,target){
			if(!target) target=this.getTarget();
			switch(this.getTarget()){
				case 'message':
					if(!this.jmessage){
						this.jmessage=this.jtoolbar.parents('[el=_main]').find('.message').find('textarea[name=message]');
					}
					if(this.jmessage.attr('prompts')=='empty'){
						if(this.jmessage.val()==this.jmessage.attr('promptvalue')) this.jmessage.val('');
					}
					this.jmessage.insertAtCaret(value);
					break;
				default:
					/*
					if(this.mode=='talk' || this.mode=='putmini'){
						if(this.opt.jcall.setValue) this.opt.jcall.setValue(this.opt.id,'insert',value);
					}
					else{
						if(this.opt.jcall.contentSet) this.opt.jcall.contentSet('insert',value);
					}
					*/
					if(this.opt.jcall.contentSet) this.opt.jcall.contentSet('insert',value);
					break;
			}
		},
		
		
		init_at:function(){
			var jsort=this.winboxShow("at",true);
			if(!jsort.attr("_init")){
				var that=this;
				appu.followBind(function(tableFollow){
					var jitems=jsort.find("[el=_items]");
					/*
					jitems.html('');
					tableFollow.begin();
					for(var t=0;t<tableFollow.row();t++){
						var treeFollow=tableFollow.getItemTree();
						var _html=that.toAtItemString(treeFollow);
						jitems.append(_html);
						tableFollow.move();
					}
					*/
					that.tableFollow=tableFollow;//好友table
					that.at_searcher();
					jitems.find("a").live('click',function(){
						that.at_click($(this));
					});
				});
				//放大镜的显示
				this.jwinbox.find('.at_search .search_icon').click(function(){
					$(this).hide();
					that.jwinbox.find('input[name=at_search]').focus();
				});
				this.jwinbox.find('input[name=at_search]').focus(function(){
					that.jwinbox.find('.at_search .search_icon').hide();
				}).blur(function(){
					if(!$(this).val()) that.jwinbox.find('.at_search .search_icon').show();
				});
				this.jwinbox.find('input[name=at_search]').keyup(function(){
					that.at_searcher($(this).val());
				});
				jsort.attr("_init","yes");
			}
		},
		at_searcher:function(keyword){
			var that=this;
			var jsort=this.winboxShow("at",true);
			var jitems=jsort.find("[el=_items]");
			jitems.html('');
			if(!keyword){
				this.tableFollow.doItemBegin();
				for(var t=0;t<this.tableFollow.getRow();t++){
					var treeItem=this.tableFollow.getItemTree();
					var _html=this.toAtItemString(treeItem);
					jitems.append(_html);
					this.tableFollow.doItemMove();
				}
				return;
			}
			keyword=keyword.toLowerCase();
			this.tableFollow.doItemBegin();
			//alert(this.tableFollow);
			for(var t=0;t<this.tableFollow.getRow();t++){
				if(this.tableFollow.iv("unames").toLowerCase().indexOf(keyword)>-1){
					//dbg.t(keyword+"= "+this.tableItems.iv("name").indexOf(keyword)+" , "+this.tableItems.iv("name"));
					var treeItem=this.tableFollow.getItemTree();
					var _html=this.toAtItemString(treeItem);
					jitems.append(_html);
				}
				this.tableFollow.doItemMove();
			}
		},
		at_click:function(jthis){
			this.jclick=jthis;
			var uid=jthis.attr('uid');uname=jthis.text();
			var value=' @'+uname+' ';
			this.setValue("",value);
			this.close();
		},
		toAtItemString:function(treeItem){
			var re='';
			re+='<a uid="[item:uuid]"><span>[item:unames]</span></a>';
			re=dcs.DTML.toReplaceEncodeFilter(re,treeItem,dcs.DTML.PATTERN_LEBEL_ITEMS);
			return re;
		},
		
		init_em:function(){
			var that=this;
			var jsort=this.winboxShow('em',true);
			jsort.initer(function(jthis){
				jthis.find('td a').click(function(){
					that.em_click($(this));
				});
			});
		},
		em_click:function(jthis){
			this.jclick=jthis;
			var filename=jthis.attr('_sn');
			if(!filename) filename=jthis.attr('class').substring(1);
			var value='[em'+filename+']';
			if(this.getFormat()=='code'){
				var url='/images/emotes/em/'+filename+'.gif';
				value='<img src="'+url+'" />';
			}
			this.setValue('',value);
			this.close();
		},
		init_color:function(){
			var jsort=this.winboxShow('color',true);
			if(!jsort.attr('_init')){
				var that=this;
				jsort.find('td a').click(function(){
					that.color_click($(this));
				});
				jsort.attr('_init','yes');
			}
		},
		color_click:function(jthis){
			this.jclick=jthis;
			this.parent.contentCommand('color',this.jclick.attr("_color"));		
			this.close();
		},
		
		init_image:function(puttype){
			var that=this;
			var jsort=this.winboxShow("image",true);
			$uploadx.init_image("image",{},{complete:function(vars){
				var treeVar=vars.treeVar;
				var url=treeVar.v("file.urls");
				switch(puttype){
					case 'contact':			appt.putcontact.mediaAdd('pic',url);break;
					default:			appt.putmini.mediaAdd('pic',url);break;
				}
				that.close();
			}});
		},
		init_imagec:function(){this.init_image('contact')},
		init_images:function(){
			var that=this;
			this.winboxShow("images",true);
			$uploadx.init_images("images",{queue_limit:10},{complete:function(vars){
				that.plusImage(null,vars);
			}});
		},
		init_imagem:function(){
			var jsort=this.winboxShow("uploadm",true);
			if(!jsort.attr("_init")){
				var that=this;
				$uploadm.set('upload',{upload_channel:'t',upload_sorts:'image'});
				$uploadm.set('plus',function(file,vars){that.plusImage(file,vars)});
				$uploadm.init();
				jsort.attr("_init","yes");
			}
		},
		
		plusImage:function(file,vars){
			if(!file){
				//alert(vars.uploadx.vars["xml"]);
				var treeVar=vars.treeVar;
				//alert(treeVar.v("status"));
				file={id:treeVar.v("file.id"),name:treeVar.v("file.name"),
					url:treeVar.v("file.urls"),ext:treeVar.v("file.ext"),
					size:treeVar.v("file.size"),sizes:treeVar.v("file.sizes"),
					thumbid:treeVar.v("thumb.id"),thumburl:treeVar.v("thumb.urls"),
				'':''};
			}
			var putbox=appt.putbox;
			if(!putbox) putbox=appt.master.putbox;
			putbox.af.plus(file);
			putbox.toolbar.close();
		},
		
		init_video:function(){
			var jsort=this.winboxShow("video",false);
			if(!jsort.attr("_init")){
				var that=this;
				jsort.find("[el=_cancel]").click(function(){that.close()});
				jsort.find("[el=_submit]").click(function(){
					that.video_trans($(this));
				});
				jsort.attr("_init","yes");
			}
		},
		video_trans:function(jthis){
			this.jclick=jthis;
			var jsort=this.winboxJ("video");
			var jtips=jsort.find('[el=_tips]');
			var jurl=jsort.find("input[name=url]");
			var url=jurl.val();
			if(!url){
				this.sortTips(jtips,'请输入视频播放地址',2);
				return;
			}
			var that=this;
			appt.parserVideo(url,function(treeInfo){
				if(!treeInfo){
					that.sortTips(jtips,'暂不支持您输入的视频播放地址！',2);
					return;
				}
				var value="";
				if(that.getFormat()=="code"){
					value='<p style="text-align:center;"><a class="video_holder" href="'+url+'"><em>video</em></a></p>';
					var src='/images/space.gif';
					src=treeInfo.v('img');
					value='<p style="text-align:center;"><img class="video_holder" src="'+src+'" url="'+url+'" title="'+treeInfo.v('title')+'" img="'+treeInfo.v('img')+'" /></a></p>';
					that.setValue("",value);
					jurl.val('');
				}
				else{
					var src='/images/space.gif';
					src=treeInfo.v('img');
					value='<img class="video_holder" src="'+src+'" url="'+url+'" title="'+treeInfo.v('title')+'" img="'+treeInfo.v('img')+'" /></a>';
					appt.putmini.mediaAdd('video',value);
				}
				that.close();
			});
		},
		
		init_music:function(){
			var jsort=this.winboxShow("music",false);
			if(!jsort.attr("_init")){
				var that=this;
				jsort.find("[el=_cancel]").click(function(){that.close()});
				jsort.find("[el=_submit]").click(function(){
					that.music_trans($(this));
				});
				jsort.attr("_init","yes");
			}
		},
		music_trans:function(jthis){
			this.jclick=jthis;
			var jsort=this.winboxJ("music");
			var jurl=jsort.find("input[name=url]");
			var url=jurl.val();
			if(!url){
				this.sortTips(jtips,'请输入音乐地址',2);
				return;
			}
			var value="";
			value='<p style="text-align:center;"><a class="music_holder" href="'+url+'"><em>music</em></a></p>';
			value='<p style="text-align:center;"><img class="music_holder" src="/images/space.gif" url="'+url+'" /></a></p>';
			//alert(value);
			this.setValue("",value);
			jurl.val('');
			this.close();
		},
		
		init_vote:function(){
			//alert("vote");
		},
		
		init_link:function(){
			var jsort=this.winboxShow("link",false);
			if(!jsort.attr("_init")){
				var that=this;
				jsort.find("[el=_cancel]").click(function(){that.close()});
				jsort.find("[el=_submit]").click(function(){
					that.link_click($(this));
				});
				jsort.attr("_init","yes");
			}
		},
		link_click:function(jthis){
			this.jclick=jthis;
			var jsort=this.winboxJ("link");
			var jtips=jsort.find('[el=_tips]');
			var jurl=jsort.find("input[name=url]");
			var url=jurl.val();
			if(!url){
				this.sortTips(jtips,'请输入链接URL',2);
				return;
			}
			
			var editor=this.parent.contentO();
			editor.focus();
			var selection=editor.getSelection(),nativeSel=selection.getNative();
			//var text=CKEDITOR.env.ie && CKEDITOR.env.version<9 ? nativeSel.createRange().toString() : nativeSel.toString();
			var text=nativeSel.createRange ? nativeSel.createRange().toString() : nativeSel.toString();
			if(!text){
				this.sortTips(jtips,'请先选择要添加链接的文字内容',2);
				return;
			}
			text='<a href="'+url+'" target="_blank">'+text+'</a>';
			editor.insertHtml(text);
			
			jurl.val('');
			this.close();
		},
		
		sortTips:function(jtips,message,timer){
			if(this.tipsTimer) $w.clearTimeout(this.tipsTimer);
			if(message){
				jtips.hide();
				jtips.find("span").html(message);
				jtips.slideDown();
			}
			if(timer){
				this.tipsTimer=$w.timeout(function(){jtips.slideUp()},timer);
			}
		},
		
		
		getHTML:function(){
			var htmla=[];
			htmla.push('<div class="winbox" el="toolbar_box"><div class="inner">');
			htmla.push('<div class="winop"><a class="close"><span></span></a></div>');
			/*htmla.push('<div class="box_at hide">');
			htmla.push('	<div class="at_search"><input type="text" name="at_search" /><span class="search_icon"></span></div><div class="at_items" el="_items">load..</div>');
			htmla.push('</div>');*/
			htmla.push('<div class="box_em hide">');
			htmla.push('	<div class="sort"></div>');
			htmla.push('	<div class="items">');
			htmla.push('		<div class="previews"><img src="/images/emotes/icon.gif" /></div>');
			htmla.push('		<div class="emotes_em">');
			htmla.push('		<table>');
			htmla.push('		<tr><td><a class="n1"><span></span></a></td><td><a class="n2"><span></span></a></td><td><a class="n3"><span></span></a></td><td><a class="n4"><span></span></a></td><td><a class="n5"><span></span></a></td><td><a class="n6"><span></span></a></td><td><a class="n7"><span></span></a></td><td><a class="n8"><span></span></a></td><td><a class="n9"><span></span></a></td><td><a class="n10"><span></span></a></td></tr>');
			htmla.push('		<tr><td><a class="n11"><span></span></a></td><td><a class="n12"><span></span></a></td><td><a class="n13"><span></span></a></td><td><a class="n14"><span></span></a></td><td><a class="n15"><span></span></a></td><td><a class="n16"><span></span></a></td><td><a class="n17"><span></span></a></td><td><a class="n18"><span></span></a></td><td><a class="n19"><span></span></a></td><td><a class="n20"><span></span></a></td></tr>');
			htmla.push('		<tr><td><a class="n21"><span></span></a></td><td><a class="n22"><span></span></a></td><td><a class="n23"><span></span></a></td><td><a class="n24"><span></span></a></td><td><a class="n25"><span></span></a></td><td><a class="n26"><span></span></a></td><td><a class="n27"><span></span></a></td><td><a class="n28"><span></span></a></td><td><a class="n29"><span></span></a></td><td><a class="n30"><span></span></a></td></tr>');
			htmla.push('		</table>');
			htmla.push('		</div>');
			htmla.push('	</div>');
			htmla.push('	<div class="paging"></div>');
			htmla.push('</div>');
			htmla.push('<div class="box_video hide">');
			htmla.push('	<div class="iinput"><b>视频播放地址</b><p><input type="text" name="url" value="" minlength="3" maxlength="200" /></p></div>');
			htmla.push('	<p class="ac">目前支持大部分视频网站播放地址直接粘贴</p>');
			htmla.push('	<p class="tips ac" el="_tips"><span></span></p>');
			htmla.push('	<div class="action"><a class="sbtnb" el="_submit"><span>插入</span></a><i></i><a class="sbtn" el="_cancel"><span>取消</span></a></div>');
			htmla.push('</div>');
			htmla.push('<div class="box_music hide">');
			htmla.push('	<div class="iinput"><b>音乐地址</b><p><input type="text" name="url" value="" minlength="3" maxlength="200" /></p></div>');
			htmla.push('	<p class="tips ac" el="_tips"><span></span></p>');
			htmla.push('	<div class="action"><a class="sbtnb" el="_submit"><span>插入</span></a><i></i><a class="sbtn" el="_cancel"><span>取消</span></a></div>');
			htmla.push('</div>');
			htmla.push('<div class="box_link hide">');
			htmla.push('	<div class="iinput"><b>URL</b><p><input type="text" name="url" value="" minlength="3" maxlength="200" /></p></div>');
			htmla.push('	<p class="tips ac" el="_tips"><span></span></p>');
			htmla.push('	<div class="action"><a class="sbtnb" el="_submit"><span>添加</span></a><i></i><a class="sbtn" el="_cancel"><span>取消</span></a></div>');
			htmla.push('</div>');
			htmla.push('<div class="box_uploadm hide">');
			htmla.push('	<div class="uploadm" sessionid="">');
			htmla.push('	<div class="bars">');
			htmla.push('		<a class="upload"><span>本地图片</span></a>');
			htmla.push('		<<aclass="album hide"><span>我的相册</span></a>');
			htmla.push('		<a class="web"><span>网络图片</span></a>');
			htmla.push('	</div>');
			htmla.push('	<div class="cons con_upload">');
			htmla.push('		<h3><a class="upload rc5" sessionid="<dcs:session.id>"><span>上传本地图片</span></a></h3>');
			htmla.push('		<p>可按住Ctrl键多选图片，最多可选择10张。</p>');
			htmla.push('	</div>');
			htmla.push('	<div class="cons con_web">');
			htmla.push('		<h3>图片：<input class="url" type="text" value="http://" /><a class="sbtnb add"><span>添加</span></a></h3>');
			htmla.push('		<p>请输入图片网址，支持JPG、PNG、GIF图片文件。</p>');
			htmla.push('		<p class="tips ac" hint="yes" id="hint1"><span data-value="请输入一张图片的有效网址！"></span></p>');
			htmla.push('	</div>');
			/*
			htmla.push('	<div class="items">');
			htmla.push('		<ul></ul>');
			htmla.push('	</div>');
			*/
			htmla.push('	</div>');
			htmla.push('</div>');
			htmla.push('</div></div>');
			var re=htmla.join(BR);
			re=r(re,'{sessionid}',this.opt.sessionid);
			//alert(re);
			return re;
		},
		
	'':''},
});
