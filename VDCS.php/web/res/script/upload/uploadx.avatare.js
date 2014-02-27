
var $uploadx_avatar={};

$uploadxModel['avatare']={
	
	initParams:function(){
		this.opt=ox(this.opt,{
			//'serveURL':app.serve('a/settings/avatar'),
			es_img_avatar_small		: '.avatar_show [el="small"] img',
			es_img_avatar_middle		: '.avatar_show [el="middle"] img',
			es_img_avatar_big		: '.avatar_show [el="big"] img',
			es_img_avatar_origin		: '.avatar_show [el="origin"] img',
			'message_abdata':'服务数据异常！',
			'message_save_ing':'正在保存您的头像设置 ..',
			'message_save_succeed':'保存头像设置成功！',
		'':''});
		$uploadx_avatar=this;
	},
	
	initCallback:function(){this.initCallbackAvatar()},
	initModel:function(){this.initModelAvatar()},
	
	//UploadComplete,EditorSaved,EditorError
	initCallbackAvatar:function(){
		var that=this;
		if(!this.isCallback('UploadComplete')){
			this.setCallback('UploadComplete',function(vars){
				that.hideUpload();
				//alert(that.vars['xml']);
				var urls=vars.treeVar.v('file.urls');
				if(!urls){
					ui.popups('error',that.getMessage('abdata'),true);
					return
				}
				that.showImg('img_avatar_origin',urls);
				urls=$url.link(urls,'_t='+Math.random());
				//dbg.t(urls);
				that.hideGuide();
				editorAvatar('photo',vars.treeVar.v('filename'),urls)
			})
		}
		if(!this.isCallback('EditorSaved')){
			this.setCallback('EditorSaved',function(vars){
				alert('头像设置成功！')
			})
		}
	},
	initModelAvatar:function(){
		var that=this;
		var jupload=this.je('upload');if(jupload.length<1)jupload=null;
		var juploadx=$uploadx.btnElement(null,this.btnPic(),{jwrap:jupload,force_place:true,display:'show'});
		this.btnPicClick(function(){juploadx.show();that.btnPicClicked()});
		this.btnCameraClick(function(){juploadx.hide();that.btnCameraClicked()});
	},
	
	
	/*
	########################################
	########################################
	*/
	serveShow:function(){
		var that=this;
		that.hideUpload();that.hideEditor();
		that.showGuide();
		if(isdebug('ajax')) dbg.t('url:serveShow',$url.link(that.c('serveURL'),'mode='));
		$ajax({url:$url.link(that.c('serveURL'),'mode='),value:'map',ready:function(maps){
			var isdata=false;
			if(maps){
				var treeVar=maps.getItemTree('var');
				if(treeVar.v('status')=='succeed'){
					isdata=true;
					that.showImg('img_avatar_small',treeVar.v('avatar.small'));
					that.showImg('img_avatar_middle',treeVar.v('avatar.middle'));
					that.showImg('img_avatar_big',treeVar.v('avatar.big'));
					that.showImg('img_avatar_origin',treeVar.v('res.origin.url'));
				}
			}
		},error:true})
	},
	serveSave:function(vars){
		var that=this;
		that.showTips(that.getMessage('save_ing'));
		if(isdebug('ajax')) dbg.t('url:serveSave',$url.link(that.c('serveURL'),'mode=save'));
		$ajax({url:$url.link(that.c('serveURL'),'mode=save'),value:'xml',ready:function(maps){
			//alert(maps);
			if(!iso(maps)) maps=$util.toMapByXML(maps);
			that.hideTips();
			var isdata=false,issave=false;
			if(maps){
				var treeVar=maps.getItemTree('var');
				if(treeVar.v('status')=='succeed'){
					isdata=true;
					if(treeVar.v('save.status')=='succeed'){
						issave=true;
						ui.popups('succeed',that.getMessage('save_succeed'),true);
						that.serveShow();
					}
				}
			}
			if(!isdata || !issave){
				ui.popups('error',that.getMessage('abdata'),true);
				that.showTips(that.getMessage('abdata'));
			}
		},error:true});
	},
	
	initPage:function(){
		var that=this;
		that.setCallback('EditorSaved',function(vars){
			that.serveSave(vars)
		});
		$('#btn-avatar-origin').click(function(){
			that.btnEditorClicked(that.je('img_avatar_origin').attr('src'));
		});
		that.serveShow()
	},
	
	
	/*
	########################################
	########################################
	*/
	btnPicClicked:function(){
		this.btnPicShape('pop');
		this.btnCameraShape();
		this.showGuide();
		//this.hideGuide();
		this.showUpload();
		this.hideEditor();
		this.SWFUpload=initSWFUpload(this);
	},
	btnCameraClicked:function(){
		this.btnPicShape();
		this.btnCameraShape('pop');
		this.hideGuide();
		this.hideUpload();
		this.showEditor();
		cameraAvatar();
	},
	btnEditorClicked:function(url,filename){
		if(!url) return;
		if(!filename){
			var ar=url.split('/');
			filename=ar[ar.length-1];
			if(filename.indexOf('?')>0) filename=filename.substr(0,filename.indexOf('?'));
		}
		this.btnPicShape();
		this.btnCameraShape();
		this.hideGuide();
		this.hideUpload();
		editorAvatar('photo',filename,url);
	},
	
'':''};


/*
########################################
########################################
*/
cameraAvatar=function(){
	var postURL=$uploadx_avatar.URLUpload({m:'avatare',x:'j'});
	if(isdebug('ajax')) dbg.t('url:cameraAvatar',postURL);
	var saveURL=postURL;
	var content='<embed height="464" width="524" ';
	content +='flashvars="action=camera';
	content +='&postURL='+$url.toEncode(postURL)+'';
	content +='&saveURL='+$url.toEncode(saveURL)+'';
	content +='" ';
	content+='src="'+$uploadx_avatar.PATH_BASE+'uploadx/avatare.swf" ';
	content+='type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" ';
	content+='allowscriptaccess="always" quality="high" wmode="opaque" />';
	$uploadx_avatar.showEditor(content);
};
editorAvatar=function(action,photoid,photoURL){
	var postURL=$uploadx_avatar.URLUpload({m:'avatare',x:'j'});
	if(isdebug('ajax')) dbg.t('url:cameraAvatar',postURL);
	var saveURL=postURL;
	var content='<embed height="464" width="524" '; 
	content+='flashvars="action='+action;		//photo
	//content+='&bgColor=F7F7F7';
	//content+='&bgBar=EDEDED';
	content+='&photoid='+photoid;
	content+='&photoURL='+photoURL;
	content+='&postURL='+$url.toEncode(postURL)+'';
	content+='&saveURL='+$url.toEncode(saveURL)+'';
	content +='" ';
	content+='src="'+$uploadx_avatar.PATH_BASE+'uploadx/avatare.swf" ';
	content+='type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" ';
	content+='allowscriptaccess="always" quality="high" wmode="opaque" />';
	$uploadx_avatar.showEditor(content);
};

// FLASH的接口 ： 没有摄像头时的回调方法
function noCamera(){
	if($uploadx_avatar.isCallback('noCamera')){
		$uploadx_avatar.doCallback('noCamera');
	}
	else{
		alert('没有找到摄像头！');
	}
}

// FLASH的接口：编辑头像保存成功后的回调方法
function editorAvatarSaved(){
	if($uploadx_avatar.isCallback('EditorSaved')){
		$uploadx_avatar.doCallback('EditorSaved');
	}
	else{
		alert('保存成功！');
	}
}

// FLASH的接口：编辑头像保存失败的回调方法, msg 是失败信息，可以不返回给用户, 仅作调试使用.
function editorAvatarError(msg){
	if($uploadx_avatar.isCallback('EditorError')){
		$uploadx_avatar.doCallback('EditorError',msg);
	}
	else{
		alert('保存失败了：'+msg);
	}
}
