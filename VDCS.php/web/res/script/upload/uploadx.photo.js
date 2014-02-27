
$uploadxModel['photo']={
	
	initParams:function(){
		this.ps=ox(this.ps,{
			"id_img_photo":"img-photo",
			"id_save_form":"save-form",
			"id_save_submit":"save-submit",
			"message_abdata":"服务数据异常！",
			"message_apply_ing":"正在为您保存图片 ..",
			"message_save_complete":"图片保存成功！",
		'':''});
		$uploadx_avatar=this;
	},
	
	initCallback:function(){this.initCallbackAuth()},
	initModel:function(){this.initModelAuth()},
	
	//UploadComplete,EditorSaved,EditorError
	initCallbackAuth:function(){
		var __othis=this;
		if(!this.isCallback("UploadComplete")){
			this.setCallback("UploadComplete",function(vars){
				__othis.hideUpload();
				//alert(__othis.vars["xml"]);
				__othis.serveShow({tips:false});
				__othis.hideTips();
				__othis.serveSaveForm(true);
			});
		}
	},
	initModelAuth:function(){
		var __othis=this;
		__othis.hideBar();
		__othis.showUpload();
		this.SWFUpload=initSWFUpload(__othis);
		__othis.serveShow();
	},
	
	
	/*
	########################################
	########################################
	*/
	serveSaveForm:function(status){
		var __othis=this;
		if(status){
			$("#"+__othis.c("id_save_form")).show();
			if(!__othis._save_submit){
				$("#"+__othis.c("id_save_submit")).click(function(){
					__othis.serveSave()
				});
				__othis._save_submit=true;
			}
		}
		else{
			$("#"+__othis.c("id_save_form")).hide();
		}
	},
	
	serveShow:function(ps){
		ps=ox({tips:true},ps);
		var __othis=this;
		//dbg.t($url.link(__othis.c("serveURL"),"mode="));
		$ajax({url:$url.link(__othis.c("serveURL"),"mode="),value:"map",ready:function(maps){
			var isdata=false;
			if(maps){
				var treeVar=maps.getItemTree("var");
				if(treeVar.v("status")=="succeed"){
					isdata=true;
					__othis.showLayout();
					__othis.imgSrc( __othis.c("id_img_photo"),treeVar.v("res.origin.url"));
					
					var issaveform=false;
					if(treeVar.v("save.is")){
						if(ps.tips){
							
						}
					}
					else{
						issaveform=true;
					}
					if(issaveform) __othis.serveSaveForm(true);
				}
			}
		},error:true});
	},
	
	serveSave:function(){
		var __othis=this;
		var sendData={};
		__othis.hideUpload();
		__othis.serveSaveForm(false);
		__othis.showTips(__othis.serveMessage("save_ing"));
		//dbg.t($url.link(__othis.c("serveURL"),"mode=save"));
		$ajax({url:$url.link(__othis.c("serveURL"),"mode=save"),send:sendData,value:"map",ready:function(maps){
			__othis.hideTips();
			var isdata=false,isapply=false;
			if(maps){
				var treeVar=maps.getItemTree("var");
				if(treeVar.v("status")=="succeed"){
					isdata=true;
					if(treeVar.v("save.status")=="succeed"){
						isapply=true;
						$xtip.popups('succeed',__othis.serveMessage("save_complete"),true);
						__othis.showTips(__othis.serveMessage("save_complete"));
					}
				}
			}
			if(!isdata || !isapply){
				$xtip.popups('error',__othis.serveMessage("abdata"),true);
				__othis.showTips(__othis.serveMessage("abdata"));
			}
		},error:true});
	},
	
'':''};
